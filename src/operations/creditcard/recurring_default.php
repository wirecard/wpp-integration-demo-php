<?php

require '../../../vendor/autoload.php';

use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;
use Wirecard\PaymentSdk\Transaction\CreditCardTransaction;

$tokenId = htmlspecialchars($_POST['tokenId']);

$amount = new Amount(5.85, 'EUR');

$transaction = new CreditCardTransaction();
$transaction->setAmount($amount);
$transaction->setTokenId($tokenId);

$service = createTransactionService(CCARD);

$response = $service->pay($transaction);

if ($response instanceof SuccessResponse) {
    echo 'Successful payment.<br>';
    echo 'TransactionID: ' . $response->getTransactionId();
    require '../showButton.php';
} elseif ($response instanceof FailureResponse) {
    echoFailureResponse($response);
}
