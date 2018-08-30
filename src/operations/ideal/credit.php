<?php

require '../../../vendor/autoload.php';
require '../../util/helperFunctions.php';
require '../../config.php';

use Wirecard\PaymentSdk\Entity\AccountHolder;
use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Entity\Mandate;
use Wirecard\PaymentSdk\Exception\MalformedResponseException;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;
use Wirecard\PaymentSdk\Transaction\SepaCreditTransferTransaction;

$amountNumber = htmlspecialchars($_POST['amountNumber']);
$accountHolderLastName = htmlspecialchars($_POST['accountHolderLastName']);
$accountHolderFirstName = htmlspecialchars($_POST['accountHolderFirstName']);
$mandateId = htmlspecialchars($_POST['mandateId']);
$currency = htmlspecialchars($_POST['amountCurrency']);

$transaction = new SepaCreditTransferTransaction();

$amount = new Amount((float)$amountNumber, $currency);

$accountHolder = new AccountHolder();
$accountHolder->setLastName($accountHolderLastName);
$accountHolder->setFirstName($accountHolderFirstName);

$mandate = new Mandate($mandateId);

$transaction->setAmount($amount);
$transaction->setAccountHolder($accountHolder);
$transaction->setMandate($mandate);
// use the IBAN you will receive by notification response
$transaction->setIban(IBAN);


if (array_key_exists('parentTransactionId', $_POST)) {
    $transaction->setParentTransactionId($_POST['parentTransactionId']);
}

$service = createTransactionService('ideal');

try {
    $response = $service->credit($transaction);
    if ($response instanceof SuccessResponse) {
        echo 'Refund via SEPA Credit Transfer successfully completed.<br>';
        echo getTransactionLink(BASE_URL, $response);
    } elseif ($response instanceof FailureResponse) {
        echoFailureResponse($response);
    }
} catch (MalformedResponseException $e) {
    echo $e->getTraceAsString() . '<br>';
    echo $e->getMessage();
}
