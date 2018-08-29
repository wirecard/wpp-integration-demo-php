<?php
require_once 'util/globals.php';

use Wirecard\PaymentSdk\Transaction\PayPalTransaction;
use Wirecard\PaymentSdk\Transaction\SepaCreditTransferTransaction;

const SECRET_KEY = 'a8c3fce6-8df7-4fd6-a1fd-62fa229c5e55';

const MERCHANT_CONFIG_A = [
    'username' => '70000-APITEST-AP',
    'password' => 'qD2wzQ_hrc!8'
];

const MERCHANT_CONFIG_B = [
    'username' => '16390-testing',
    'password' => '16390-testing'
];

const MERCHANT = [
    CCARD => MERCHANT_CONFIG_A,
    PAYPAL => MERCHANT_CONFIG_A,
    IDEAL => MERCHANT_CONFIG_B,
    SEPA => MERCHANT_CONFIG_B,
    SOFORT => MERCHANT_CONFIG_B,
];

const CONFIGS = [
	'paypal' => [
		'name' => PayPalTransaction::NAME,
		'maid' => '2a0e9351-24ed-4110-9a1b-fd0fee6bec26',
		'secret' => 'dbc5a498-9a66-43b9-bf1d-a618dd399684',
		'httpUsername' => '70000-APITEST-AP',
    	'httpPassword' => 'qD2wzQ_hrc!8',
	],
	'sepa-ct' => [
		'name' => SepaCreditTransferTransaction::NAME,
		'maid' => '59a01668-693b-49f0-8a1f-f3c1ba025d45',
		'secret' => 'ecdf5990-0372-47cd-a55d-037dccfe9d25',
		'httpUsername' => '16390-testing',
    	'httpPassword' => '16390-testing',
    	'creditorId' => 'DE98ZZZ09999999999',
	]
];
