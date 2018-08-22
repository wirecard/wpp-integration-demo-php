<?php
const CCARD = 'ccard';
const PAYPAL = 'paypal';
const IDEAL = 'ideal';
const SEPA = 'sepa_dd';
const SOFORT = 'sofort';

const PATH_TO_STANDALONE = '../../example-requests-standalone/';
const PATH_TO_EMBEDDED = '../../example-requests-embedded/';

const DEFAULT_RES_MSG = 'Response data are not sent from the merchant acquirer!';

const PATHS_STANDALONE = [
    CCARD => PATH_TO_STANDALONE . 'creditcard_payment.json',
    PAYPAL => PATH_TO_STANDALONE . 'paypal_payment.json',
    IDEAL => PATH_TO_STANDALONE . 'ideal_payment.json',
    SEPA => PATH_TO_STANDALONE . 'sepa_dd_payment.json',
    SOFORT => PATH_TO_STANDALONE . 'sofortbanking_payment.json',
];

const PATHS_EMBEDDED = [
    CCARD => PATH_TO_STANDALONE . 'creditcard_payment.json',
    PAYPAL => PATH_TO_EMBEDDED . 'paypal_payment.json',
    IDEAL => PATH_TO_EMBEDDED . 'ideal_payment.json',
    SEPA => PATH_TO_EMBEDDED . 'sepa_dd_payment.json',
    SOFORT => PATH_TO_EMBEDDED . 'sofortbanking_payment.json',
];
