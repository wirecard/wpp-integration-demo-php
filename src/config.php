<?php

require_once 'util/globals.php';

const SECRET_KEY = 'a8c3fce6-8df7-4fd6-a1fd-62fa229c5e55';
const BASE_URL = 'https://api-test.wirecard.com';
const DEMO_IBAN = "DE42512308000000060004";
const BIC = "WIREDEMMXXX";
const WPP_URL = 'https://wpp-test.wirecard.com';

const MERCHANT_CONFIG_A = [
    'username' => '70000-APITEST-AP',
    'password' => 'qD2wzQ_hrc!8'
];

const MERCHANT_CONFIG_B = [
    'username' => '16390-testing',
    'password' => '3!3013=D3fD8X7'
];

const MERCHANT = [
    CCARD => MERCHANT_CONFIG_A,
    PAYPAL => MERCHANT_CONFIG_A,
    PAYSAFECARD => MERCHANT_CONFIG_A,
    IDEAL => MERCHANT_CONFIG_B,
    SEPA_DIRECTDEBIT => MERCHANT_CONFIG_B,
    SEPA_CREDIT => MERCHANT_CONFIG_B,
    SOFORT => MERCHANT_CONFIG_B,
    P24 => MERCHANT_CONFIG_B,
    EPS => MERCHANT_CONFIG_B,
    ALIPAY_XBORDER => MERCHANT_CONFIG_B
];
