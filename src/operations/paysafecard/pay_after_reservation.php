<?php

require '../../../vendor/autoload.php';


use Wirecard\PaymentSdk\Entity\AccountHolder;
use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;
use Wirecard\PaymentSdk\Transaction\PaysafecardTransaction;

$transactionId = htmlspecialchars($_POST['transactionId']);

$amount = new Amount(9.99, 'EUR');
$accountHolder = new AccountHolder();
$accountHolder->setCrmId('A123456789');

$transaction = new PaysafecardTransaction();
$transaction->setAmount($amount);
$transaction->setParentTransactionId($transactionId);
$transaction->setAccountHolder($accountHolder);

$service = initTransactionService(PAYSAFECARD);

try {
    $response = $service->pay($transaction);
} catch (\Http\Client\Exception $e) {
    echo 'Transaction failed: ', $e->getMessage(), '\n';
}

if ($response instanceof SuccessResponse) {
    echo 'The payment was successfully.<br>';
    echo 'TransactionID: ' . $response->getTransactionId();
    require '../showButton.php';
} elseif ($response instanceof FailureResponse) {
    echoFailureResponse($response);
}
