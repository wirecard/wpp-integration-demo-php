<?php
require_once('base.php');
require_once('../util/helperFunctions.php');

$creditcard = CCARD;
$payload = createPayload($creditcard, 1);
$payload['options']['frame-ancestor'] = getBaseUrl();
$payload['options']['mode'] = 'seamless';
if (retrievePaymentRedirectUrl($payload, $creditcard)) {
    redirect('../payment/seamless.php');
}
