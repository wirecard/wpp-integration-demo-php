<?php

require '../../../vendor/autoload.php';

use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Exception\MandatoryFieldMissingException;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;
use Wirecard\PaymentSdk\Transaction\CreditCardTransaction;

$parentTransactionId = htmlspecialchars($_POST['parentTransactionId']);
$tokenId = htmlspecialchars($_POST['tokenId']);
$amountNumber = htmlspecialchars($_POST['amountNumber']);
$currency = htmlspecialchars($_POST['amountCurrency']);

$amount = new Amount((float)$amountNumber, $currency);

$transaction = new CreditCardTransaction();
if (!empty($parentTransactionId)) {
    $transaction->setParentTransactionId($parentTransactionId);
} elseif (!empty($tokenId)) {
    $transaction->setTokenId($tokenId);
}

$transaction->setAmount($amount);

$service = createTransactionService(CCARD);

$response = null;
try {
    $response = $service->reserve($transaction);
} catch (MandatoryFieldMissingException $e) {
    echo 'No transaction id or token id found for cancellation. ';
    echo 'Please check your input data and enter a valid transaction id or token id.';
} catch (Exception $e) {
    echo get_class($e), ': ', $e->getMessage(), '<br>';
}

if ($response instanceof SuccessResponse) {
    echo 'Successful reservation.<br>';
    echo 'TransactionID: ' . $response->getTransactionId();
    require '../showButton.php';
} elseif ($response instanceof FailureResponse) {
    echoFailureResponse($response);
}
