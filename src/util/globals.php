<?php

const CREDITCARD ='creditcard';
const CCARD = 'ccard';
const PAYPAL = 'paypal';
const IDEAL = 'ideal';
const SEPA_DIRECTDEBIT = 'sepadirectdebit';
const SEPA_CREDIT = 'sepacredit';
const SOFORT = 'sofortbanking';
const PAYSAFECARD = 'paysafecard';
const P24 = 'p24';
const EPS = 'eps';
const ALIPAY_XBORDER = 'alipay-xborder';

const PATH_TO_STANDALONE = '../../example-requests-standalone/';
const PATH_TO_EMBEDDED = '../../example-requests-embedded/';

const PATHS_STANDALONE = [
    CCARD => PATH_TO_STANDALONE . 'creditcard_payment_3DS.json',
    PAYPAL => PATH_TO_STANDALONE . 'paypal_payment.json',
    IDEAL => PATH_TO_STANDALONE . 'ideal_payment.json',
    SEPA_DIRECTDEBIT => PATH_TO_STANDALONE . 'sepa_dd_payment.json',
    SOFORT => PATH_TO_STANDALONE . 'sofortbanking_payment.json',
    PAYSAFECARD => PATH_TO_STANDALONE . 'paysafecard_payment.json',
    P24 => PATH_TO_STANDALONE . 'przelewy24_payment.json',
    EPS => PATH_TO_STANDALONE . 'eps_payment.json',
    ALIPAY_XBORDER => PATH_TO_STANDALONE . 'alipay_cross_border_payment.json'
];

const PATHS_EMBEDDED = [
    CCARD => PATH_TO_EMBEDDED . 'creditcard_payment_3DS.json',
    PAYPAL => PATH_TO_EMBEDDED . 'paypal_payment.json',
    IDEAL => PATH_TO_EMBEDDED . 'ideal_payment.json',
    SEPA_DIRECTDEBIT => PATH_TO_EMBEDDED . 'sepa_dd_payment.json',
    SOFORT => PATH_TO_EMBEDDED . 'sofortbanking_payment.json',
    PAYSAFECARD => PATH_TO_EMBEDDED . 'paysafecard_payment.json',
    P24 => PATH_TO_EMBEDDED . 'przelewy24_payment.json',
    EPS => PATH_TO_EMBEDDED . 'eps_payment.json',
    ALIPAY_XBORDER => PATH_TO_EMBEDDED . 'alipay_cross_border_payment.json'
];
