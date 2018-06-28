<?php
require_once('base.php');
require_once('../util/general-functions.php');

$payload = createPayload();
$payload['options']['frame-ancestor'] = 'http://localhost';
$payload['options']['mode'] = 'seamless';
if (retrievePaymentRedirectUrl($payload)) {
    redirect('../payment/seamless.php');
}
