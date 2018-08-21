<?php
require_once('base.php');
require_once('../util/general-functions.php');

$creditcard = CCARD;
$payload = createPayload($creditcard, 1);
$payload['options']['frame-ancestor'] = 'http://localhost:8180';
$payload['options']['mode'] = 'seamless';
if (retrievePaymentRedirectUrl($payload, $creditcard)) {
    redirect('../payment/seamless.php');
}
