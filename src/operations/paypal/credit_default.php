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

$service = initTransactionService(PAYPAL);

try {
    $response = $service->credit($transaction);
} catch (\Http\Client\Exception $e) {
    echo 'Transaction failed: ', $e->getMessage(), '\n';
}

if ($response instanceof SuccessResponse) {
    echo 'Funds successfully transferred.<br>';
    echo 'TransactionID: ' . $response->getTransactionId();
    require '../showButton.php';
} elseif ($response instanceof FailureResponse) {
    echoFailureResponse($response);
}
