<?php

const DEFAULT_RES_MSG = 'Response data are not sent from the merchant acquirer!';
use Wirecard\PaymentSdk\Config;
use Wirecard\PaymentSdk\Config\CreditCardConfig;
use Wirecard\PaymentSdk\Config\PaymentMethodConfig;
use Wirecard\PaymentSdk\TransactionService;

use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Entity\Status;

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
    $baseUrl = $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . '/';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
        $baseUrl = "https://" . $baseUrl;
    } else {
        $baseUrl = "http://" . $baseUrl;
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
        $signatureVerification = (isValidSignature($responseBase64, $signatureBase64, SECRET_KEY));
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
 * @param array $httpUser Contains the keys username, password.
 * @return Wirecard\PaymentSdk\TransactionService
    Returns a Wirecard\PaymentSdk\TransactionService with a test configuration.
 */
function createTransactionService($httpUser)
{
    $baseUrl = 'https://api-test.wirecard.com';
    $httpUsername = $httpUser["username"];
    $httpPass = $httpUser["password"];

    $merchant_account_id_cc = '53f2895a-e4de-4e82-a813-0d87a10e55e6';
    $merchant_account_secret_key_cc = 'dbc5a498-9a66-43b9-bf1d-a618dd399684';

    // The configuration is stored in an object containing the connection settings set above.
    // A default currency can also be provided.
    $config = new Config\Config($baseUrl, $httpUsername, $httpPass, 'EUR');

    // Set a public key for certificate pinning used for response signature validation.
    // This certificate needs to be always up to date.
    $certPath = $_SERVER['DOCUMENT_ROOT'] . '/wpp-integration-demo-php/certificate/api-test.wirecard.com.crt';
    $publicKey = file_get_contents($certPath);
    $config->setPublicKey($publicKey);

    // ## Payment methods
    // Each payment method can be configured with an individual merchant account ID and the corresponding key.
    // The configuration object for Credit Card is a little different than other payment methods and can be
    // instantiated without any parameters. If you wish to omit non-3-D transactions you can just leave out the
    // maid and secret in the default CreditCardConfig. However if you want to use non-3-D transactions you have two
    // ways of setting the credentials. First via setting the parameters maid and secret -

    // ### Credit Card Non-3-D

    $creditcardConfig = new CreditCardConfig();

### Credit Card Non-3-D
    $creditcardConfig->setNonThreeDCredentials($merchant_account_id_cc, $merchant_account_secret_key_cc);

// ### Credit Card 3-D
    $creditcardConfig->setThreeDCredentials($merchant_account_id_cc, $merchant_account_secret_key_cc);

    $config->add($creditcardConfig);

    return new TransactionService($config);
}

/**
 * Creates an instance of Wirecard\PaymentSdk\TransactionService
 *
 * @param array $configData Contains some payment method specific config.
 * @return Wirecard\PaymentSdk\TransactionService
    Returns a Wirecard\PaymentSdk\TransactionService with a test configuration.
 */
function createTransactionServiceFor($configData)
{

    $baseUrl = 'https://api-test.wirecard.com';
    $httpUsername = $configData["httpUsername"];
    $httpPass = $configData["httpPassword"];

    // The configuration is stored in an object containing the connection settings set above.
    // A default currency can also be provided.
    $config = new Config\Config($baseUrl, $httpUsername, $httpPass, 'EUR');

    // Set a public key for certificate pinning used for response signature validation.
    // This certificate needs to be always up to date.
    $certPath = $_SERVER['DOCUMENT_ROOT'] . '/wpp-integration-demo-php/certificate/api-test.wirecard.com.crt';
    $publicKey = file_get_contents($certPath);
    $config->setPublicKey($publicKey);

    $paypalConfig = new PaymentMethodConfig($configData['name'], $configData['maid'], $configData['secret']);
    $config->add($paypalConfig);

    return new TransactionService($config);
}


/**
 * Echoes an output containing the code and message of the failure response.
 *
 * @param Wirecard\PaymentSdk\Response\FailureResponse $response
 */
function echoFailureResponse($response)
{
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
