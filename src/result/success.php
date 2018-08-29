<?php
require '../util/helperFunctions.php';
require '../config.php';

session_start();
$_SESSION['msg'] = 'The payment was successful.';
$_SESSION['response'] = $_POST;

$idealMAID = "4aeccf39-0d47-47f6-a399-c05c1f2fc819";

echo "session stored uuid : " . $_SESSION["uuid"];
$get_url = BASE_URL . "/merchants/" . $idealMAID . "/payments/search?payment.request-id=" . $_SESSION["uuid"];

echo "<br> <br> get url: \n " . $get_url;

redirect('show.php');
