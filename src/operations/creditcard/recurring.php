<?php

require '../../../vendor/autoload.php';
require '../../util/helperFunctions.php';
require '../../config.php';

use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Transaction\CreditCardTransaction;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;

$tokenId = htmlspecialchars($_POST['tokenId']);
$amountNumber = htmlspecialchars($_POST['amountNumber']);
$currency = htmlspecialchars($_POST['amountCurrency']);
$successUrl = htmlspecialchars($_POST['successUrl']);

$amount = new Amount((float)$amountNumber, $currency);

$transaction = new CreditCardTransaction();
$transaction->setAmount($amount);
$transaction->setTokenId($tokenId);
$transaction->setTermUrl($successUrl);

$service = createTransactionService(MERCHANT_CONFIG_A);

$response = $service->pay($transaction);
    
if ($response instanceof SuccessResponse) {
    echo 'Successful payment.<br>';
} elseif ($response instanceof FailureResponse) {
    echoFailureResponse($response);
}
