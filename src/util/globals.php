<?php

const CREDITCARD ='creditcard';
const PAYPAL = 'paypal';
const IDEAL = 'ideal';
const SEPA_DIRECTDEBIT = 'sepadirectdebit';
const SEPA_CREDIT = 'sepacredit';
const SOFORT = 'sofortbanking';
const PAYSAFECARD = 'paysafecard';
const P24 = 'p24';
const EPS = 'eps';
const ALIPAY_XBORDER = 'alipay-xborder';

const PATHS_TO_SAMPLE_REQUESTS = [
    CREDITCARD => '../../example-requests/creditcard_payment_3DS.json',
    PAYPAL => '../../example-requests/paypal_payment.json',
    IDEAL => '../../example-requests/ideal_payment.json',
    SEPA_DIRECTDEBIT => '../../example-requests/sepa_dd_payment.json',
    SOFORT => '../../example-requests/sofortbanking_payment.json',
    PAYSAFECARD => '../../example-requests/paysafecard_payment.json',
    P24 => '../../example-requests/przelewy24_payment.json',
    EPS => '../../example-requests/eps_payment.json',
    ALIPAY_XBORDER => '../../example-requests/alipay_cross_border_payment.json'
];
