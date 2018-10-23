<?php
require '../../vendor/autoload.php';

session_start();
$_SESSION['msg'] = 'The payment failed.';
$_SESSION['response'] = $_POST;

redirect('show.php');
