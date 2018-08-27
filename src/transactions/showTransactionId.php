<?php

require_once('../util/helperFunctions.php');

// # PayPal notification

// Wirecard sends a server-to-server request regarding any changes in the transaction status.

// ## Required objects

// To include the necessary files, we use the composer for PSR-4 autoloading.
require '../../vendor/autoload.php';
require_once('../config.php');

use Wirecard\PaymentSdk\Config;
use Wirecard\PaymentSdk\TransactionService;

$paymentMethod = htmlspecialchars($_POST['paymentMethod']);
$transactionId = htmlspecialchars($_POST['transactionId']);

echo $paymentMethod . " : " . $transactionId . "<br>";

$baseUrl = 'https://wpp-test.wirecard.com';
$httpUser = MERCHANT_CONFIG_A["username"];
$httpPass = MERCHANT_CONFIG_A["password"];

$config = new Config\Config($baseUrl, $httpUser, $httpPass, 'EUR');

// Set a public key for certificate pinning used for response signature validation, this certificate needs to be always
// up to date
$config->setPublicKey(file_get_contents('../../certificate/api-test.wirecard.com.crt'));

// ## Transaction

// ### Transaction Service

// The `TransactionService` is used to determine the response from the service provider.
$service = new TransactionService($config);

// ## Get Transaction

// get a transaction by passing the arguments transactionId and paymentMethod to getTransactionByTransactionId method.
try {
    $response = $service->getTransactionByTransactionId($transactionId, $paymentMethod);
    $xml = $response->getRawData();
    echo $xml;
} catch (Exception $e) {
    echo "No transaction id found for transactionId: ", $transactionId, " and paymentMethod: ", $paymentMethod, "<br>";
}
