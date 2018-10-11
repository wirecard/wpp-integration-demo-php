<?php

require '../../../vendor/autoload.php';
require '../../util/helperFunctions.php';
require '../../config.php';

use Wirecard\PaymentSdk\Entity\AccountHolder;
use Wirecard\PaymentSdk\Entity\Amount;
use Wirecard\PaymentSdk\Entity\Redirect;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\InteractionResponse;
use Wirecard\PaymentSdk\Transaction\PaysafecardTransaction;

$successUrl = '../../../src/result/success.php?status=success';
$errorUrl = '../../../src/result/cancel.php?status=cancel';
$notificationUrl = getBaseUrl() . 'wpp-integration-demo-php/src/result/notify.php';

$paymentAmount = htmlspecialchars($_POST['paymentAmount']);

$amount = new Amount((float)$paymentAmount, 'EUR');
$redirectUrls = new Redirect(getUrl($successUrl), getUrl($errorUrl));


//Account holder with last name and the crm id of your customer
$accountHolder = new AccountHolder();
$accountHolder->setCrmId('A123456789');

$transaction = new PaysafecardTransaction();
$transaction->setNotificationUrl($notificationUrl);
$transaction->setRedirect($redirectUrls);
$transaction->setAmount($amount);
$transaction->setAccountHolder($accountHolder);

$service = createTransactionService('paysafecard');

$response = $service->pay($transaction);

// ## Response handling

// The response of the service must be handled depending on it's class
// In case of an `InteractionResponse`, a browser interaction by the consumer is required
// in order to continue the payment process. In this example we proceed with a header redirect
// to the given _redirectUrl_. IFrame integration using this URL is also possible.
if ($response instanceof InteractionResponse) {
    die("<meta http-equiv='refresh' content='0;url={$response->getRedirectUrl()}'>");
} elseif ($response instanceof FailureResponse) {
    echoFailureResponse($response);
}
