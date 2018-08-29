<?php

require '../../../vendor/autoload.php';
require '../../util/helperFunctions.php';
require '../../config.php';

use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Entity\AccountHolder;
use Wirecard\PaymentSdk\Transaction\PayPalTransaction;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;

$accountHolderEmail = htmlspecialchars($_POST['accountHolderEmail']);
$amountNumber = htmlspecialchars($_POST['amountNumber']);
$currency = htmlspecialchars($_POST['amountCurrency']);
$notificationUrl = htmlspecialchars($_POST['notificationUrl']);

$amount = new Amount((float)$amountNumber, $currency);

$transaction = new PayPalTransaction();
$transaction->setAmount($amount);
$transaction->setNotificationUrl($notificationUrl);

$accountHolder = new AccountHolder();
$accountHolder->setEmail($accountHolderEmail);
$transaction->setAccountHolder($accountHolder);

$service = createTransactionServiceForPayPal(MERCHANT_CONFIG_A);

$response = $service->credit($transaction);
    
if ($response instanceof SuccessResponse) {
    echo 'Funds successfully transferred.<br>';
} elseif ($response instanceof FailureResponse) {
    echoFailureResponse($response);
}
