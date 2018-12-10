<?php

require '../../../vendor/autoload.php';

use Wirecard\PaymentSdk\Exception\MandatoryFieldMissingException;
use Wirecard\PaymentSdk\Exception\UnsupportedOperationException;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;
use Wirecard\PaymentSdk\Transaction\PayPalTransaction;

$transactionId = $_POST['transactionId'];

$transaction = new PayPalTransaction();
$transaction->setParentTransactionId($transactionId);

$service = createTransactionService(PAYPAL);

$response = null;
try {
    $response = $service->cancel($transaction);
} catch (MandatoryFieldMissingException $e) {
    echo 'No transaction id found for cancellation. Please check your input data and enter a valid transaction id. ';
} catch (UnsupportedOperationException $e) {
    echo 'The transaction can not be canceled. ';
    echo 'Probably <i>credit</i> was used as payment method which can not be canceled.';
} catch (Exception $e) {
    echo get_class($e), ': ', $e->getMessage(), '<br>';
}

if ($response instanceof SuccessResponse) {
    echo 'Payment successfully cancelled.<br>';
    echo 'TransactionID: ' . $response->getTransactionId();
} elseif ($response instanceof FailureResponse) {
    echoFailureResponse($response);
}
