<?php

const DEFAULT_RES_MSG = 'Response data are not sent from the merchant acquirer!';
use Wirecard\PaymentSdk\Config;
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
