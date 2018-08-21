<?php
require_once('base.php');
require_once('../util/helperFunctions.php');

$paymentMethod = $_GET['method'];
$payload = createPayload($paymentMethod, 0);
$payload['options']['frame-ancestor'] = getBaseUrl();
if (retrievePaymentRedirectUrl($payload, $paymentMethod)) {
    redirect('../payment/embedded.php');
}
