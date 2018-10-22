<?php

require '../../../vendor/autoload.php';



use Wirecard\PaymentSdk\Entity\AccountHolder;
use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;
use Wirecard\PaymentSdk\Transaction\PayPalTransaction;

$amount = new Amount(5.85, 'EUR');
$accountHolder = new AccountHolder();
$accountHolder->setEmail('paypal.buyer2@wirecard.com');

$transaction = new PayPalTransaction();
$transaction->setAmount($amount);
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
