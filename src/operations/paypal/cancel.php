<?php

require '../../../vendor/autoload.php';
require '../../util/helperFunctions.php';
require '../../config.php';

use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;
use Wirecard\PaymentSdk\Transaction\PayPalTransaction;

$transactionId = $_POST['transactionId'];

$transaction = new PayPalTransaction();
$transaction->setParentTransactionId($transactionId);

$service = createTransactionService('paypal');

$response = null;
try {
    $response = $service->cancel($transaction);
} catch (Exception $e) {
    echo get_class($e), ': ', $e->getMessage(),
    ' Probably credit was used as payment method which can not be canceled.<br>';
}


if ($response instanceof SuccessResponse) {
    echo 'Payment successfully cancelled.<br>';
    echo 'TransactionID: ' . $response->getTransactionId();
} elseif ($response instanceof FailureResponse) {
    echoFailureResponse($response);
}
