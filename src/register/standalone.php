<?php
require_once('../config.php');
require_once('base.php');
require_once('../util/helperFunctions.php');

$paymentMethod = $_GET['method'];
$payload = createPayload($paymentMethod, true);
if (retrievePaymentRedirectUrl($payload, $paymentMethod)) {
    redirect('../payment/standalone.php');
}
