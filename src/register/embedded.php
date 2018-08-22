<?php
require_once('../config.php');
require_once('base.php');
require_once('../util/helperFunctions.php');

$paymentMethod = $_GET['method'];
$payload = createPayload($paymentMethod, false);
$payload['options']['frame-ancestor'] = getBaseUrl();
if (retrievePaymentRedirectUrl($payload, $paymentMethod)) {
    redirect('../payment/embedded.php');
}
