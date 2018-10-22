<?php

require '../../../vendor/autoload.php';



use Wirecard\PaymentSdk\Entity\AccountHolder;
use Wirecard\PaymentSdk\Entity\Mandate;
use Wirecard\PaymentSdk\Exception\MalformedResponseException;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;
use Wirecard\PaymentSdk\Transaction\SepaCreditTransferTransaction;

$transactionId = htmlspecialchars($_POST['transactionId']);

$accountHolder = new AccountHolder();
$accountHolder->setLastName('Doe');
$accountHolder->setFirstName('Jane');

$mandate = new Mandate('12345678');

$transaction = new SepaCreditTransferTransaction();
$transaction->setAccountHolder($accountHolder);
$transaction->setMandate($mandate);
$transaction->setParentTransactionId($transactionId);
$transaction->setIban(DEMO_IBAN);

$service = createTransactionService(SOFORT);

try {
    $response = $service->credit($transaction);
    if ($response instanceof SuccessResponse) {
        echo 'Refund via SEPA Credit Transfer successfully completed.<br>';
        echo 'TransactionID: ' . $response->getTransactionId();
        require '../showButton.php';
    } elseif ($response instanceof FailureResponse) {
        echoFailureResponse($response);
    }
} catch (MalformedResponseException $e) {
    echo $e->getTraceAsString() . '<br>';
    echo $e->getMessage();
}
