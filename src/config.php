<?php
require_once 'util/globals.php';

const SECRET_KEY = 'a8c3fce6-8df7-4fd6-a1fd-62fa229c5e55';

const MERCHANT_CONFIG_A = [
    'username' => '70000-APIDEMO-CARD',
    'password' => 'ohysS0-dvfMx'
];

const MERCHANT_CONFIG_B = [
    'username' => '70000-APITEST-AP',
    'password' => 'qD2wzQ_hrc!8'
];

const MERCHANT_CONFIG_C = [
    'username' => '16390-testing',
    'password' => '3!3013=D3fD8X7'
];

const MERCHANT = [
    CCARD => MERCHANT_CONFIG_B,
    PAYPAL => MERCHANT_CONFIG_B,
    IDEAL => MERCHANT_CONFIG_B,
    SEPA => MERCHANT_CONFIG_C,
    SOFORT => MERCHANT_CONFIG_C,
];
