<?php
require_once('base.php');
require_once('../util/helperFunctions.php');

$paymentMethod = $_GET['method'];
$payload = createPayload($paymentMethod, 1);
if (retrievePaymentRedirectUrl($payload, $paymentMethod)) {
    redirect('../payment/standalone.php');
}
