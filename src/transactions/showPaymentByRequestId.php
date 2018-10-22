<?php


// # PayPal notification

// Wirecard sends a server-to-server request regarding any changes in the transaction status.

// ## Required objects

// To include the necessary files, we use the composer for PSR-4 autoloading.
require '../../vendor/autoload.php';


$paymentMethod = htmlspecialchars($_POST['paymentMethod']);
$requestId = htmlspecialchars($_POST['requestId']);

if (isNullOrEmptyString($paymentMethod) || isNullOrEmptyString($requestId)) {
    echo "No payment method or requestId id found. Please enter a valid payment method and requestId id.";
    return;
}

$service = createTransactionService($paymentMethod);
try {
    // get a transaction by passing transactionId and paymentMethod to getTransactionByTransactionId method.
    $transaction = $service->getTransactionByRequestId($requestId, $paymentMethod);
    require 'showPayment.php';
} catch (Exception $e) {
    echo get_class($e), ': ', $e->getMessage(), '<br>';
}
