<?php

require_once 'util/globals.php';

//const SECRET_KEY = 'a8c3fce6-8df7-4fd6-a1fd-62fa229c5e55';
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
    CREDITCARD => MERCHANT_CONFIG_A,
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

const SECRET_KEY = [
    CREDITCARD => 'dbc5a498-9a66-43b9-bf1d-a618dd399684',
    PAYPAL => 'dbc5a498-9a66-43b9-bf1d-a618dd399684',
    PAYSAFECARD => 'dbc5a498-9a66-43b9-bf1d-a618dd399684',
    IDEAL => '7a353766-23b5-4992-ae96-cb4232998954',
    SEPA_DIRECTDEBIT => '5caf2ed9-5f79-4e65-98cb-0b70d6f569aa',
    SEPA_CREDIT => 'ecdf5990-0372-47cd-a55d-037dccfe9d25',
    SOFORT => '58764ab3-5c56-450e-b747-7237a24e92a7',
    P24 => 'fdd54ea1-cef1-449a-945c-55abc631cfdc',
    EPS => '20c6a95c-e39b-4e6a-971f-52cfb347d359',
    ALIPAY_XBORDER => '94fe4f40-16c5-4019-9c6c-bc33ec858b1d'
];
