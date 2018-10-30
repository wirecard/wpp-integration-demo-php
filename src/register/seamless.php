<?php
require_once '../../vendor/autoload.php';

require_once('base.php');


$creditcard = CCARD;
$payload = createPayloadStandalone($creditcard);
$payload['options']['frame-ancestor'] = getBaseUrl();
$payload['options']['mode'] = 'seamless';
if (retrievePaymentRedirectUrl($payload, $creditcard)) {
    redirect('../payment/seamless.php');
}
