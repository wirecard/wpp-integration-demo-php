<?php

const CCARD = 'ccard';
const PAYPAL = 'paypal';
const IDEAL = 'ideal';
const SEPA_DIRECTDEBIT = 'sepadirectdebit';
const SEPA_CREDIT = 'sepacredit';
const SOFORT = 'sofortbanking';
const PAYSAFECARD = 'paysafecard';

const PATH_TO_STANDALONE = '../../example-requests-standalone/';
const PATH_TO_EMBEDDED = '../../example-requests-embedded/';

const PATHS_STANDALONE = [
    CCARD => PATH_TO_STANDALONE . 'creditcard_payment_3DS.json',
    PAYPAL => PATH_TO_STANDALONE . 'paypal_payment.json',
    IDEAL => PATH_TO_STANDALONE . 'ideal_payment.json',
    SEPA_DIRECTDEBIT => PATH_TO_STANDALONE . 'sepa_dd_payment.json',
    SOFORT => PATH_TO_STANDALONE . 'sofortbanking_payment.json',
    PAYSAFECARD => PATH_TO_STANDALONE . 'paysafecard_payment.json'
];

const PATHS_EMBEDDED = [
    CCARD => PATH_TO_EMBEDDED . 'creditcard_payment_3DS.json',
    PAYPAL => PATH_TO_EMBEDDED . 'paypal_payment.json',
    IDEAL => PATH_TO_EMBEDDED . 'ideal_payment.json',
    SEPA_DIRECTDEBIT => PATH_TO_EMBEDDED . 'sepa_dd_payment.json',
    SOFORT => PATH_TO_EMBEDDED . 'sofortbanking_payment.json',
    PAYSAFECARD => PATH_TO_EMBEDDED . 'paysafecard_payment.json'
];
