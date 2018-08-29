<?php

require '../../vendor/autoload.php';
require '../util/helperFunctions.php';
require '../config.php';

use Wirecard\PaymentSdk\Transaction\CreditCardTransaction;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;

$parentTransactionId = htmlspecialchars($_POST['parentTransactionId']);
$amountNumber = htmlspecialchars($_POST['amountNumber']);
$currency = htmlspecialchars($_POST['amountCurrency']);

$transaction = new CreditCardTransaction();
$transaction->setParentTransactionId($parentTransactionId);

if (!empty($amountNumber)) {
    $amount = new Amount((float)$amountNumber, $currency);
    $transaction->setAmount($amount);
}

$service = createTransactionService(MERCHANT_CONFIG_A);
$response = $service->pay($transaction);

if ($response instanceof SuccessResponse) {
    echo 'Payment based on reservation successfully executed.<br>';
} elseif ($response instanceof FailureResponse) {
    echo 'Failure during the payment based on reservation.<br>';
}
