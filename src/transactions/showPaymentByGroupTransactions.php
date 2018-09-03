<?php
header('Content-Type: application/json; charset=utf-8');

require_once('../util/helperFunctions.php');

// # PayPal notification

// Wirecard sends a server-to-server request regarding any changes in the transaction status.

// ## Required objects

// To include the necessary files, we use the composer for PSR-4 autoloading.
require '../../vendor/autoload.php';
require_once('../config.php');

$paymentMethod = htmlspecialchars($_POST['paymentMethod']);
$transactionId = htmlspecialchars($_POST['transactionId']);

$service = createTransactionService($paymentMethod);
try {
    // get a transaction by passing transactionId and paymentMethod to getTransactionByTransactionId method.
    $transaction_details = $service->getGroupOfTransactions($transactionId, $paymentMethod);
    $json = json_encode(['result' => $transaction_details], JSON_PRETTY_PRINT);
    $json = str_replace("\\", "", $json);
    printf("%s", $json);

    //if($transaction_details[])
} catch (Exception $e) {
    if (!isset($transactionId) || $transactionId === "") {
        echo "No transactionId id found! Please specify a valid transactionId id!";
    } elseif (!isset($paymentMethod) || $paymentMethod === "") {
        echo "No payment method found! Please specify a valid payment method!";
    } else {
        echo "No transaction found for transactionId ", $transactionId, " and paymentMethod ", $paymentMethod . "!" .
            "\nPlease check your input data and try again.";
    }
}
