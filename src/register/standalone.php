<?php
require_once('base.php');
require_once('../util/general-functions.php');

$paymentMethod = $_GET['method'];
$payload = createPayloadStandalone($paymentMethod);
if (retrievePaymentRedirectUrl($payload, $paymentMethod)) {
    redirect('../payment/standalone.php');
}
