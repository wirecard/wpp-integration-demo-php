<?php
header('Content-Type: application/json; charset=utf-8');

require '../util/helperFunctions.php';
require '../../vendor/autoload.php';
require '../config.php';

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

$service = createTransactionService($paymentMethod);

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
