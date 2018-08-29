<?php
header('Content-Type: application/json; charset=utf-8');

require '../util/helperFunctions.php';
require '../../vendor/autoload.php';
require '../config.php';

use Wirecard\PaymentSdk\Config;
use Wirecard\PaymentSdk\Config\CreditCardConfig;
use Wirecard\PaymentSdk\Config\PaymentMethodConfig;
use Wirecard\PaymentSdk\Config\SepaConfig;
use Wirecard\PaymentSdk\Transaction\IdealTransaction;
use Wirecard\PaymentSdk\Transaction\PayPalTransaction;
use Wirecard\PaymentSdk\Transaction\SepaDirectDebitTransaction;
use Wirecard\PaymentSdk\Transaction\SofortTransaction;
use Wirecard\PaymentSdk\TransactionService;

$selectTransactionMethod = $_GET['transactionMethod'];

if (!empty($_POST['paymentMethod'])) {
    $paymentMethod = htmlspecialchars($_POST['paymentMethod']);
}
if (!empty($_POST['transactionId'])) {
    $transactionId = htmlspecialchars($_POST['transactionId']);
} elseif (!empty($_POST['requestId'])) {
    $requestId = htmlspecialchars($_POST['requestId']);
}

if (!isset($paymentMethod) || $paymentMethod === '') {
    echo 'No payment method found! Please specify a valid payment method!';
    return;
}
if ((!isset($transactionId) || $transactionId === '') && (!isset($requestId) || $requestId === '')) {
    echo 'No transactionId or requestId found! Please specify a valid id!';
    return;
}

// ## Connection
// The basic configuration requires the base URL for Wirecard and the username and password for the HTTP requests.

$baseUrl = 'https://api-test.wirecard.com';
$httpUser = '';
$httpPass = '';

if ($paymentMethod === 'creditcard' || $paymentMethod === 'paypal') {
    $httpUser = MERCHANT_CONFIG_A['username'];
    $httpPass = MERCHANT_CONFIG_A['password'];
} elseif ($paymentMethod === 'sepadirectdebit' || $paymentMethod === 'ideal' || $paymentMethod === 'sofortbanking') {
    $httpUser = MERCHANT_CONFIG_B['username'];
    $httpPass = MERCHANT_CONFIG_B['password'];
}

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

$merchant_account_id_cc = '53f2895a-e4de-4e82-a813-0d87a10e55e6';
$merchant_account_secret_key_cc = 'dbc5a498-9a66-43b9-bf1d-a618dd399684';

// ### Credit Card Non-3-D
$creditcardConfig = new CreditCardConfig();

### Credit Card Non-3-D
$creditcardConfig->setNonThreeDCredentials($merchant_account_id_cc, $merchant_account_secret_key_cc);

// ### Credit Card 3-D
$creditcardConfig->setThreeDCredentials($merchant_account_id_cc, $merchant_account_secret_key_cc);

$config->add($creditcardConfig);

// ### PayPal
$paypalMAID = '2a0e9351-24ed-4110-9a1b-fd0fee6bec26';
$paypalKey = 'dbc5a498-9a66-43b9-bf1d-a618dd399684';
$paypalConfig = new PaymentMethodConfig(PayPalTransaction::NAME, $paypalMAID, $paypalKey);
$config->add($paypalConfig);

// ### iDEAL
$IdealMAID = '4aeccf39-0d47-47f6-a399-c05c1f2fc819';
$IdealSecretKey = 'dbc5a498-9a66-43b9-bf1d-a618dd399684';
$IdealConfig = new PaymentMethodConfig(IdealTransaction::NAME, $IdealMAID, $IdealSecretKey);
$config->add($IdealConfig);

// ### Sofortbanking
$sofortMAID = '6c0e7efd-ee58-40f7-9bbd-5e7337a052cd';
$sofortSecretKey = 'dbc5a498-9a66-43b9-bf1d-a618dd399684';
$sofortConfig = new PaymentMethodConfig(SofortTransaction::NAME, $sofortMAID, $sofortSecretKey);
$config->add($sofortConfig);

// ### SEPA Direct Debit
$sepaDirectDebitMAID = '933ad170-88f0-4c3d-a862-cff315ecfbc0';
$sepaDirectDebitKey = 'ecdf5990-0372-47cd-a55d-037dccfe9d25';
// SEPA requires the creditor ID, therefore a different config object is used.
$sepaDirectDebitConfig = new SepaConfig(SepaDirectDebitTransaction::NAME, $sepaDirectDebitMAID, $sepaDirectDebitKey);
$sepaDirectDebitConfig->setCreditorId('DE98ZZZ09999999999');
$config->add($sepaDirectDebitConfig);

// ## Transaction

// ### Transaction Service

// The `TransactionService` is used to determine the response from the service provider.
$service = new TransactionService($config);

try {
    if ($selectTransactionMethod === 'requestId') {
        //
        $transaction_details = $service->getTransactionByRequestId($requestId, $paymentMethod);
    } elseif ($selectTransactionMethod === 'transactionId') {
        $transaction_details = $service->getTransactionByTransactionId($transactionId, $paymentMethod);
    } elseif ($selectTransactionMethod === 'group') {
        $transaction_details = $service->getGroupOfTransactions($transactionId, $paymentMethod);
    }
    $json = json_encode(['result' => $transaction_details], JSON_PRETTY_PRINT);
    $json = str_replace('\\', '', $json);
    printf('%s', $json);
} catch (Exception $e) {
    if (isset($transactionId)) {
        echo 'No transaction found for transactionId ', $transactionId, ' and paymentMethod ', $paymentMethod . '!'
            . '\nPlease check your input data and try again.';
    } else {
        echo 'No transaction found for requestId ', $requestId, ' and paymentMethod ', $paymentMethod . '!'
            . '\nPlease check your input data and try again.';
    }
}
