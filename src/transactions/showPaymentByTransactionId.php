<?php
require '../../vendor/autoload.php';

$paymentMethod = htmlspecialchars($_POST['paymentMethod']);
$transactionId = htmlspecialchars($_POST['transactionId']);

if (isNullOrEmptyString($paymentMethod) || isNullOrEmptyString($transactionId)) {
    echo "No payment method or transaction id found. Please enter a valid payment method and transaction id.";
    return;
}

$service = createTransactionService($paymentMethod);
try {
    // get a transaction by passing transactionId and paymentMethod to getTransactionByTransactionId method.
    $transaction = $service->getTransactionByTransactionId($transactionId, $paymentMethod);
    require 'showPayment.php';
} catch (Exception $e) {
    echo get_class($e), ': ', $e->getMessage(), '<br>';
}
