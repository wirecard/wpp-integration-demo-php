<?php

require '../../../vendor/autoload.php';
require '../../util/helperFunctions.php';
require '../../config.php';

use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;
use Wirecard\PaymentSdk\Transaction\CreditCardTransaction;

$transactionId = $_POST['transactionId'];

$transaction = new CreditCardTransaction();
$transaction->setParentTransactionId($transactionId);

$service = createTransactionService('creditcard');

$response = null;
try {
    $response = $service->cancel($transaction);
} catch (Exception $e) {
    echo get_class($e), ': ', $e->getMessage(), '<br>';
}

if ($response instanceof SuccessResponse) {
    echo 'Payment successfully cancelled.<br>';
    echo 'TransactionID: ' . $response->getTransactionId();
} elseif ($response instanceof FailureResponse) {
    echoFailureResponse($response);
}
