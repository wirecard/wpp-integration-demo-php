<?php
require_once '../../vendor/autoload.php';

require_once('base.php');


$paymentMethod = $_GET['method'];
$payload = createPayloadStandalone($paymentMethod);
if (retrievePaymentRedirectUrl($payload, $paymentMethod)) {
    redirect('../payment/standalone.php');
}
