<?php

require '../../../vendor/autoload.php';
require '../../util/helperFunctions.php';
require '../../config.php';

use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Entity\AccountHolder;
use Wirecard\PaymentSdk\Transaction\PayPalTransaction;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;

$amount = new Amount(5.85, 'EUR');
$accountHolder = new AccountHolder();
$accountHolder->setEmail('paypal.buyer2@wirecard.com');

$transaction = new PayPalTransaction();
$transaction->setAmount($amount);
$transaction->setAccountHolder($accountHolder);

$service = createTransactionService('paypal');

$response = $service->credit($transaction);

if ($response instanceof SuccessResponse) {
    echo 'Funds successfully transferred.<br>';
    echo getTransactionLink(BASE_URL, $response);
} elseif ($response instanceof FailureResponse) {
    echoFailureResponse($response);
}
