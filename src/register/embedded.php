<?php
require_once '../../vendor/autoload.php';

require_once('base.php');


$paymentMethod = $_GET['method'];
$payload = createPayloadEmbedded($paymentMethod);
$payload['options']['frame-ancestor'] = getBaseUrl();
if (retrievePaymentRedirectUrl($payload, $paymentMethod)) {
    redirect('../payment/embedded.php');
}
