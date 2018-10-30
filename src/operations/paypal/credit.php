<?php

require '../../../vendor/autoload.php';



use Wirecard\PaymentSdk\Entity\AccountHolder;
use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;
use Wirecard\PaymentSdk\Transaction\PayPalTransaction;

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

$service = createTransactionService(PAYPAL);

$response = $service->credit($transaction);

if ($response instanceof SuccessResponse) {
    echo 'Funds successfully transferred.<br>';
    echo 'TransactionID: ' . $response->getTransactionId();
    require '../showButton.php';
} elseif ($response instanceof FailureResponse) {
    echoFailureResponse($response);
}
