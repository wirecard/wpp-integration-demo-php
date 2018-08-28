<?php

require_once('../util/helperFunctions.php');

// # PayPal notification

// Wirecard sends a server-to-server request regarding any changes in the transaction status.

// ## Required objects

// To include the necessary files, we use the composer for PSR-4 autoloading.
require '../../vendor/autoload.php';
require_once('../config.php');

use Wirecard\PaymentSdk\Config;
use Wirecard\PaymentSdk\Config\CreditCardConfig;
use Wirecard\PaymentSdk\TransactionService;

$paymentMethod = htmlspecialchars($_POST['paymentMethod']);
$requestId = htmlspecialchars($_POST['requestId']);

echo $paymentMethod . " : " . $requestId . "<br>";

// ## Connection
// The basic configuration requires the base URL for Wirecard and the username and password for the HTTP requests.

$baseUrl = 'https://api-test.wirecard.com';
$httpUser = MERCHANT_CONFIG_A["username"];
$httpPass = MERCHANT_CONFIG_A["password"];

$merchant_account_id_cc = '53f2895a-e4de-4e82-a813-0d87a10e55e6';
$merchant_account_secret_key_cc = 'dbc5a498-9a66-43b9-bf1d-a618dd399684';

// CreditCardTransaction::NAME

// The configuration is stored in an object containing the connection settings set above.
// A default currency can also be provided.
$config = new Config\Config($baseUrl, $httpUser, $httpPass, 'EUR');

// Set a public key for certificate pinning used for response signature validation, this certificate needs to be always
// up to date
$config->setPublicKey(file_get_contents('../../certificate/api-test.wirecard.com.crt'));

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
// ## Transaction

// ### Transaction Service

// The `TransactionService` is used to determine the response from the service provider.
$service = new TransactionService($config);


try {
    // get a transaction by passing transactionId and paymentMethod to getTransactionByTransactionId method.
    $transaction_details = $service->getTransactionByRequestId($requestId, $paymentMethod);
    print_r($transaction_details);
    echo "<br>";
    var_dump($transaction_details);
    // echo "Output: " . $transaction_details['errors'][0]['code'];


    //if($transaction_details[])
} catch (Exception $e) {
    echo "No transaction id found for transactionId: ", $requestId, " and paymentMethod: ", $paymentMethod, "<br>";
}
