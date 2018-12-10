<?php

require '../../../vendor/autoload.php';



use Wirecard\PaymentSdk\Entity\AccountHolder;
use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Entity\Redirect;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\InteractionResponse;
use Wirecard\PaymentSdk\Transaction\PaysafecardTransaction;

$successUrl = '../../../src/result/success.php?status=success';
$errorUrl = '../../../src/result/cancel.php?status=cancel';
// $notificationUrl = getBaseUrl() . 'wpp-integration-demo-php/src/result/notify.php';
$notificationUrl = 'http://4b74d815.ngrok.io/wpp-integration-demo-php/src/result/notify.php';

$paymentAmount = htmlspecialchars($_POST['paymentAmount']);
$parentTransactionId = htmlspecialchars($_POST['parentTransactionId']);
$accountHolderLastName = htmlspecialchars($_POST['accountHolderLastName']);

$amount = new Amount((float)$paymentAmount, 'EUR');

// If there was a previous transaction, use the ID of this parent transaction as reference.
$parentTransactionId = !isNullOrEmptyString($parentTransactionId) ? $parentTransactionId : null;

// The redirect URLs determine where the consumer should be redirected by Paysafecard after the reserve.
$redirectUrls = new Redirect(getUrl($successUrl), getUrl($errorUrl));

// As soon as the transaction status changes, a server-to-server notification will get delivered to this URL.
$notificationUrl = getUrl($notificationUrl);

//Account holder with last name and the crm id of your customer
$accountHolder = new AccountHolder();
$accountHolder->setCrmId(20);

//last name is a optional field
if (!isNullOrEmptyString($accountHolderLastName)) {
    $accountHolder->setLastName($accountHolderLastName);
}

// The Paysafecard transaction holds all transaction relevant data for the reserve process.
$transaction = new PaysafecardTransaction();
$transaction->setNotificationUrl($notificationUrl);
$transaction->setRedirect($redirectUrls);
$transaction->setAmount($amount);
$transaction->setParentTransactionId($parentTransactionId);
$transaction->setAccountHolder($accountHolder);

// The service is used to execute the reserve operation itself. A response object is returned.
$service = createTransactionService(PAYSAFECARD);
$response = $service->reserve($transaction);

// The response of the service must be handled depending on it's class
// In case of an `InteractionResponse`, a browser interaction by the consumer is required
// in order to continue the reserve process. In this example we proceed with a header redirect
// to the given _redirectUrl_. IFrame integration using this URL is also possible.
if ($response instanceof InteractionResponse) {
    die("<meta http-equiv='refresh' content='0;url={$response->getRedirectUrl()}'>");
} elseif ($response instanceof FailureResponse) {
    echoFailureResponse($response);
}
