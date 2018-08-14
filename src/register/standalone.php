<?php
require_once('base.php');
require_once('../util/general-functions.php');

$creditcard = $GLOBALS['ccard'];
$payload = createPayload($creditcard);
if (retrievePaymentRedirectUrl($payload, $creditcard)) {
    redirect('../payment/standalone.php');
}
