<?php

const DEFAULT_RES_MSG = 'Response data are not sent from the merchant acquirer!';
use Wirecard\PaymentSdk\Config;
use Wirecard\PaymentSdk\Config\CreditCardConfig;
use Wirecard\PaymentSdk\Config\PaymentMethodConfig;
use Wirecard\PaymentSdk\Config\SepaConfig;
use Wirecard\PaymentSdk\Transaction\AlipayCrossborderTransaction;
use Wirecard\PaymentSdk\Transaction\IdealTransaction;
use Wirecard\PaymentSdk\Transaction\PayPalTransaction;
use Wirecard\PaymentSdk\Transaction\PaysafecardTransaction;
use Wirecard\PaymentSdk\Transaction\PtwentyfourTransaction;
use Wirecard\PaymentSdk\Transaction\SepaCreditTransferTransaction;
use Wirecard\PaymentSdk\Transaction\SepaDirectDebitTransaction;
use Wirecard\PaymentSdk\Transaction\SofortTransaction;
use Wirecard\PaymentSdk\TransactionService;

/**
 * General functions which are not specific for the WPP domain.
 */

/**
 * Redirect to a provided URL.
 *
 * @param $url
 */
function redirect($url)
{
    ob_start();
    header('Location: ' . $url);
    ob_end_flush();
    exit();
}

/**
 * Returns protocol, server name and port for the current page.
 *
 * @return string Base url of the application
 *
 */
function getBaseUrl()
{
    $baseUrl = $_SERVER['HTTP_HOST'] . '/';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $baseUrl = 'https://' . $baseUrl;
    } else {
        $baseUrl = 'http://' . $baseUrl;
    }
    return $baseUrl;
}

/**
 * Shows content of response message depending on passed over attribute.
 *
 * @param $attr
 * @param $hasToBeEncoded
 * @return string Returns either the content of the response message or a default info message.
 */
function showResponseData($attr, $hasToBeEncoded = false)
{
    if ($hasToBeEncoded) {
        return isset($_SESSION['response'][$attr]) ? base64_decode($_SESSION['response'][$attr]) : DEFAULT_RES_MSG;
    }
    return isset($_SESSION['response'][$attr]) ? $_SESSION['response'][$attr] : DEFAULT_RES_MSG;
}

/**
 * Checks whether the signature of a payment response is valid.
 *
 * @param string $responseBase64
 * @param string $signatureBase64
 * @param string $merchantSecretKey
 * @return bool
 */
function isValidSignature($responseBase64, $signatureBase64, $merchantSecretKey)
{
    $signature = hash_hmac('sha256', $responseBase64, $merchantSecretKey, $raw_output = true);
    return hash_equals($signature, base64_decode($signatureBase64));
}

/**
 * Shows message if signature is valid.
 *
 */
function showValidSignature()
{
    require_once('../config.php');

    $isResponseBase64 = isset($_SESSION['response']['response-base64']);
    $isResponseSignatureBase64 = isset($_SESSION['response']['response-signature-base64']);
    $signatureVerification = false;

    if ($isResponseBase64 && $isResponseSignatureBase64) {
        $responseBase64 = trim($_SESSION['response']['response-base64']);
        $signatureBase64 = trim($_SESSION['response']['response-signature-base64']);
        $paymentMethod = getPaymentMethod();
        $signatureVerification = (isValidSignature($responseBase64, $signatureBase64, SECRET_KEY[$paymentMethod]));
    }

    $isResponseSet = isset($_SESSION['response']['response-signature-base64']);
    if ($isResponseSet) {
        echo $signatureVerification ? 'True' : 'False';
    } else {
        echo DEFAULT_RES_MSG;
    }
}

/**
 * Creates an instance of Wirecard\PaymentSdk\TransactionService
 *
 * @param string $paymentMethod
 * @return Wirecard\PaymentSdk\TransactionService
 * Returns a Wirecard\PaymentSdk\TransactionService with a test configuration.
 */
function initTransactionService($paymentMethod)
{
    $paymentConfig = createConfig($paymentMethod);
    addCertificate($paymentConfig);

    // Each payment method can be configured with an individual merchant account ID and the corresponding key.
    switch ($paymentMethod) {
        case CREDITCARD:
            addCreditCardConfig($paymentConfig);
            break;
        case PAYPAL:
            addPaypalConfig($paymentConfig);
            break;
        case IDEAL:
            addIdealConfig($paymentConfig);
            break;
        case SOFORT:
            addSofortConfig($paymentConfig);
            break;
        case SEPA_DIRECTDEBIT:
            addSepaDirectDebitConfig($paymentConfig);
            break;
        case SEPA_CREDIT:
            addSepaCreditTransferConfig($paymentConfig);
            break;
        case PAYSAFECARD:
            addPaysafecardConfig($paymentConfig);
            break;
        case P24:
            addP24Config($paymentConfig);
            break;
        case ALIPAY_XBORDER:
            addAlipaycrossborderConfig($paymentConfig);
            break;
        default:
            echo '$paymentMethod is currently not supported';
    }
    return new TransactionService($paymentConfig);
}

/**
 * @param Config\Config $config
 */
function addCertificate(Config\Config $config): void
{
    // Set a public key for certificate pinning used for response signature validation.
    // This certificate needs to be always up to date.
    $certPath = $_SERVER['DOCUMENT_ROOT'] . '/wpp-integration-demo-php/certificate/api-test.wirecard.com.crt';
    $publicKey = file_get_contents($certPath);
    $config->setPublicKey($publicKey);
}

/**
 * @param $paymentMethod
 * @return Config\Config
 */
function createConfig($paymentMethod): Config\Config
{
    $baseUrl = 'https://api-test.wirecard.com';
    $username = MERCHANT[$paymentMethod]['username'];
    $password = MERCHANT[$paymentMethod]['password'];

    // The configuration is stored in an object containing the connection settings set above.
    // A default currency can also be provided.
    return new Config\Config($baseUrl, $username, $password, 'EUR');
}

/**
 * Echoes an output containing the code and message of the failure response.
 *
 * @param Wirecard\PaymentSdk\Response\FailureResponse $response
 */
function echoFailureResponse($response)
{
    // In our example we iterate over all errors and echo them out.
    // You should display them as error, warning or information based on the given severity.
    foreach ($response->getStatusCollection() as $status) {
        /**
         * @var $status \Wirecard\PaymentSdk\Entity\Status
         */
        $severity = ucfirst($status->getSeverity());
        $code = $status->getCode();
        $description = $status->getDescription();
        echo sprintf('%s with code %s and message "%s" occurred.<br>', $severity, $code, $description);
    }
}

/**
 *  Reads the transaction id from the response
 * @return string
 */
function getTransactionId()
{
    $decodedResponse = base64_decode($_SESSION['response']['response-base64']);
    $obj = json_decode($decodedResponse, false);
    $transactionId = $obj->payment->{'transaction-id'};
    return $transactionId;
}

/**
 *  Reads the token id from the response
 * @return string
 */
function getTokenId()
{
    $decodedResponse = base64_decode($_SESSION['response']['response-base64']);
    $obj = json_decode($decodedResponse, false);
    $tokenId = $obj->payment->{'card-token'}->{'token-id'};
    return $tokenId;
}

/**
 *  Reads the payment token from the response
 * @return string
 */
function getPaymentMethod()
{
    $paymentMethod = '';
    if (!empty($_SESSION['response']['response-base64'])) {
        $decodedResponse = base64_decode($_SESSION['response']['response-base64']);
        $obj = json_decode($decodedResponse, false);
        $paymentMethod = $obj->payment->{'payment-methods'}->{'payment-method'}[0]->name;
    }
    return $paymentMethod;
}

/**
 *  Reads the transaction state from the response
 * @return string
 */
function getTransactionState()
{
    if (isset($_SESSION['response']['response-base64'])) {
        $decodedResponse = base64_decode($_SESSION['response']['response-base64']);
        $obj = json_decode($decodedResponse, false);
        return $obj->payment->{'transaction-state'};
    }
    return '';
}

/**
 * For requests which includes an URL for e.g. notifications it is easier to get the URL from the server variables.
 * @param $path
 * @return string
 */
function getUrl($path)
{
    $protocol = 'http';

    if ($_SERVER['SERVER_PORT'] === 443 || (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) === 'on')) {
        $protocol .= 's';
    }

    $host = $_SERVER['HTTP_HOST'];
    $request = $_SERVER['PHP_SELF'];
    return dirname(sprintf('%s://%s%s', $protocol, $host, $request)) . '/' . $path;
}

/**
 * Checks if a string is neither null nor empty
 * @param $str
 * @return bool
 */
function isNullOrEmptyString($str)
{
    return (!isset($str) || trim($str) === '');
}

/**
 * @param Config\Config $config
 */
function addAlipaycrossborderConfig(Config\Config $config): void
{
    $alipaycrossborderMAID = '47cd4edf-b13c-4298-9344-53119ab8b9df';
    $alipaycrossborderSecretKey = '94fe4f40-16c5-4019-9c6c-bc33ec858b1d';
    $alipaycrossborderConfig = new PaymentMethodConfig(
        AlipayCrossborderTransaction::NAME,
        $alipaycrossborderMAID,
        $alipaycrossborderSecretKey
    );
    $config->add($alipaycrossborderConfig);
}

/**
 * @param Config\Config $config
 */
function addP24Config(Config\Config $config): void
{
    $p24MAID = '86451785-3ed0-4aa1-99b2-cc32cf54ce9a';
    $p24SecretKey = 'fdd54ea1-cef1-449a-945c-55abc631cfdc';
    $p24Config = new PaymentMethodConfig(PtwentyfourTransaction::NAME, $p24MAID, $p24SecretKey);
    $config->add($p24Config);
}

/**
 * @param Config\Config $config
 */
function addPaysafecardConfig(Config\Config $config): void
{
    $paysafecardMAID = '28d4938b-d0d6-4c4a-b591-fb63175de53e';
    $paysafecardKey = 'dbc5a498-9a66-43b9-bf1d-a618dd399684';
    $paysafecardConfig = new PaymentMethodConfig(PaysafecardTransaction::NAME, $paysafecardMAID, $paysafecardKey);
    $config->add($paysafecardConfig);
}

/**
 * @param Config\Config $config
 */
function addSepaCreditTransferConfig(Config\Config $config): void
{
    $sepaCreditTransferMAID = '59a01668-693b-49f0-8a1f-f3c1ba025d45';
    $sepaCreditTransferKey = 'ecdf5990-0372-47cd-a55d-037dccfe9d25';
    // SEPA requires the creditor ID, therefore a different config object is used.
    $sepaCreditTransferConfig = new SepaConfig(
        SepaCreditTransferTransaction::NAME,
        $sepaCreditTransferMAID,
        $sepaCreditTransferKey
    );
    $sepaCreditTransferConfig->setCreditorId('DE98ZZZ09999999999');
    $config->add($sepaCreditTransferConfig);
}

/**
 * @param Config\Config $config
 */
function addSepaDirectDebitConfig(Config\Config $config): void
{
    $sepaDirectDebitMAID = '933ad170-88f0-4c3d-a862-cff315ecfbc0';
    $sepaDirectDebitKey = 'ecdf5990-0372-47cd-a55d-037dccfe9d25';
    // SEPA requires the creditor ID, therefore a different config object is used.
    $sepaDirectDebitConfig = new SepaConfig(
        SepaDirectDebitTransaction::NAME,
        $sepaDirectDebitMAID,
        $sepaDirectDebitKey
    );
    $sepaDirectDebitConfig->setCreditorId('DE98ZZZ09999999999');
    $config->add($sepaDirectDebitConfig);
}

/**
 * @param Config\Config $config
 */
function addSofortConfig(Config\Config $config): void
{
    $sofortMAID = '6c0e7efd-ee58-40f7-9bbd-5e7337a052cd';
    $sofortSecretKey = 'dbc5a498-9a66-43b9-bf1d-a618dd399684';
    $sofortConfig = new PaymentMethodConfig(SofortTransaction::NAME, $sofortMAID, $sofortSecretKey);
    $config->add($sofortConfig);
}

/**
 * @param Config\Config $config
 */
function addIdealConfig(Config\Config $config): void
{
    $IdealMAID = '4aeccf39-0d47-47f6-a399-c05c1f2fc819';
    $IdealSecretKey = 'dbc5a498-9a66-43b9-bf1d-a618dd399684';
    $IdealConfig = new PaymentMethodConfig(IdealTransaction::NAME, $IdealMAID, $IdealSecretKey);
    $config->add($IdealConfig);
}

/**
 * @param Config\Config $config
 */
function addPaypalConfig(Config\Config $config): void
{
    $paypalMAID = '2a0e9351-24ed-4110-9a1b-fd0fee6bec26';
    $paypalKey = 'dbc5a498-9a66-43b9-bf1d-a618dd399684';
    $paypalConfig = new PaymentMethodConfig(PayPalTransaction::NAME, $paypalMAID, $paypalKey);
    $config->add($paypalConfig);
}

/**
 * @param Config\Config $config
 *
 *  The configuration object for Credit Card is a little different than other payment methods and can be
 *  instantiated without any parameters. If you wish to omit non-3-D transactions you can just leave out the
 *  maid and secret in the default CreditCardConfig.
 */
function addCreditCardConfig(Config\Config $config): void
{
    $merchant_account_id_cc = '53f2895a-e4de-4e82-a813-0d87a10e55e6';
    $merchant_account_secret_key_cc = 'dbc5a498-9a66-43b9-bf1d-a618dd399684';

    // ### Credit Card Non-3-D
    $creditCardConfig = new CreditCardConfig();

    ### Credit Card Non-3-D
    $creditCardConfig->setNonThreeDCredentials($merchant_account_id_cc, $merchant_account_secret_key_cc);

    // ### Credit Card 3-D
    $creditCardConfig->setThreeDCredentials($merchant_account_id_cc, $merchant_account_secret_key_cc);

    $config->add($creditCardConfig);
}
