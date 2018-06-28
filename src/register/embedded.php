<?php
require_once('base.php');
require_once('../util/general-functions.php');

$payload = createPayload();
$payload['options']['frame-ancestor'] = 'http://localhost';
if (retrievePaymentRedirectUrl($payload)) {
    redirect('../payment/embedded.php');
}
