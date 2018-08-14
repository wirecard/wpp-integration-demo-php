<?php
require_once('base.php');
require_once('../util/general-functions.php');

$creditcard = $GLOBALS['ccard'];
$payload = createPayload($creditcard);
$payload['options']['frame-ancestor'] = 'http://localhost:8180';
$payload['options']['mode'] = 'seamless';
if (retrievePaymentRedirectUrl($payload, $creditcard)) {
    redirect('../payment/seamless.php');
}
