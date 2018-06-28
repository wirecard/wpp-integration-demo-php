<?php
require_once('base.php');
require_once('../util/general-functions.php');

$payload = createPayload();
if (retrievePaymentRedirectUrl($payload)) {
    redirect('../payment/standalone.php');
}
