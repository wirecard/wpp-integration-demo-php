<?php
require_once '../../vendor/autoload.php';
require_once('base.php');


$payload = createPayload(CREDITCARD);
$payload['options']['frame-ancestor'] = getBaseUrl();
$payload['options']['mode'] = 'seamless';
if (retrievePaymentRedirectUrl($payload, CREDITCARD)) {
    redirect('../payment/seamless.php');
}
