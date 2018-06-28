<?php
require_once('../util/general-functions.php');

session_start();
$_SESSION['msg'] = 'The payment was successful.';
$_SESSION['response'] = $_POST;

redirect('show.php');
