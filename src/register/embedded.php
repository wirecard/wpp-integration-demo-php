<?php
require_once('base.php');
require_once('../util/general-functions.php');

$creditcard = $GLOBALS['ccard'];
$payload = createPayload($creditcard);
$payload['options']['frame-ancestor'] = 'http://localhost:8180';
if (retrievePaymentRedirectUrl($payload, $creditcard)) {
    redirect('../payment/embedded.php');
}
