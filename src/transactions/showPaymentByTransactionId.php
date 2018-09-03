<?php
header('Content-Type: application/json; charset=utf-8');

require '../../vendor/autoload.php';
require '../util/helperFunctions.php';
require '../config.php';

$paymentMethod = htmlspecialchars($_POST['paymentMethod']);
$transactionId = htmlspecialchars($_POST['transactionId']);

$service = createTransactionService($paymentMethod);
try {
    // get a transaction by passing transactionId and paymentMethod to getTransactionByTransactionId method.
    $transaction = $service->getTransactionByTransactionId($transactionId, $paymentMethod);
    $json = json_encode(['result' => $transaction], JSON_PRETTY_PRINT);
    $json = str_replace("\\", "", $json);
    printf("%s", $json);
} catch (Exception $e) {
    if (!isset($transactionId) || $transactionId === "") {
        echo "No transactionId id found! Please specify a valid transactionId!";
    } elseif (!isset($paymentMethod) || $paymentMethod === "") {
        echo "No payment method found! Please specify a valid payment method!";
    } else {
        echo "No transaction found for transactionId ", $transactionId, " and paymentMethod ", $paymentMethod ."!".
            "\nPlease check your input data and try again.";
    }
}
