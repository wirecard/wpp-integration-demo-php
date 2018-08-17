<?php
require_once('base.php');
require_once('../util/general-functions.php');

$paymentMethod = $_GET['method'];
$creditcard = $GLOBALS[$paymentMethod];
$payload = createPayloadEmbedded($paymentMethod);
$payload['options']['frame-ancestor'] = 'http://localhost:8180';
if (retrievePaymentRedirectUrl($payload, $paymentMethod)) {
    redirect('../payment/embedded.php');
}
