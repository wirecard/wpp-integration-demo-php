<?php
require_once('base.php');
require_once('../util/general-functions.php');

$paymentMethod = $_GET['method'];
$creditcard = $GLOBALS[$paymentMethod];
 $isEmbedded = 0;
$payload = createPayloadStandalone($creditcard, $isEmbedded);
if (retrievePaymentRedirectUrl($payload, $creditcard)) {
    redirect('../payment/standalone.php');
}
