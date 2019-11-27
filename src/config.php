<?php

use Wirecard\PaymentSdk\Config;
use Wirecard\PaymentSdk\Config\CreditCardConfig;
use Wirecard\PaymentSdk\Config\PaymentMethodConfig;
use Wirecard\PaymentSdk\Config\SepaConfig;
use Wirecard\PaymentSdk\Transaction\AlipayCrossborderTransaction;
use Wirecard\PaymentSdk\Transaction\IdealTransaction;
use Wirecard\PaymentSdk\Transaction\PayPalTransaction;
use Wirecard\PaymentSdk\Transaction\PaysafecardTransaction;
use Wirecard\PaymentSdk\Transaction\PtwentyfourTransaction;
use Wirecard\PaymentSdk\Transaction\SepaCreditTransferTransaction;
use Wirecard\PaymentSdk\Transaction\SepaDirectDebitTransaction;
use Wirecard\PaymentSdk\Transaction\SofortTransaction;

require_once 'util/globals.php';

const BASE_URL = 'https://api-test.wirecard.com';
const DEMO_IBAN = 'DE42512308000000060004';
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

/**
 * @param Config\Config $config
 */
function addAlipaycrossborderConfig(Config\Config $config): void
{
    $alipaycrossborderMAID = '47cd4edf-b13c-4298-9344-53119ab8b9df';
    $alipaycrossborderSecretKey = '94fe4f40-16c5-4019-9c6c-bc33ec858b1d';
    $alipaycrossborderConfig = new PaymentMethodConfig(
        AlipayCrossborderTransaction::NAME,
        $alipaycrossborderMAID,
        $alipaycrossborderSecretKey
    );
    $config->add($alipaycrossborderConfig);
}

/**
 * @param Config\Config $config
 */
function addP24Config(Config\Config $config): void
{
    $p24MAID = '86451785-3ed0-4aa1-99b2-cc32cf54ce9a';
    $p24SecretKey = 'fdd54ea1-cef1-449a-945c-55abc631cfdc';
    $p24Config = new PaymentMethodConfig(PtwentyfourTransaction::NAME, $p24MAID, $p24SecretKey);
    $config->add($p24Config);
}

/**
 * @param Config\Config $config
 */
function addPaysafecardConfig(Config\Config $config): void
{
    $paysafecardMAID = '28d4938b-d0d6-4c4a-b591-fb63175de53e';
    $paysafecardKey = 'dbc5a498-9a66-43b9-bf1d-a618dd399684';
    $paysafecardConfig = new PaymentMethodConfig(PaysafecardTransaction::NAME, $paysafecardMAID, $paysafecardKey);
    $config->add($paysafecardConfig);
}

/**
 * @param Config\Config $config
 */
function addSepaCreditTransferConfig(Config\Config $config): void
{
    $sepaCreditTransferMAID = '59a01668-693b-49f0-8a1f-f3c1ba025d45';
    $sepaCreditTransferKey = 'ecdf5990-0372-47cd-a55d-037dccfe9d25';
    // SEPA requires the creditor ID, therefore a different config object is used.
    $sepaCreditTransferConfig = new SepaConfig(
        SepaCreditTransferTransaction::NAME,
        $sepaCreditTransferMAID,
        $sepaCreditTransferKey
    );
    $sepaCreditTransferConfig->setCreditorId('DE98ZZZ09999999999');
    $config->add($sepaCreditTransferConfig);
}

/**
 * @param Config\Config $config
 */
function addSepaDirectDebitConfig(Config\Config $config): void
{
    $sepaDirectDebitMAID = '933ad170-88f0-4c3d-a862-cff315ecfbc0';
    $sepaDirectDebitKey = 'ecdf5990-0372-47cd-a55d-037dccfe9d25';
    // SEPA requires the creditor ID, therefore a different config object is used.
    $sepaDirectDebitConfig = new SepaConfig(
        SepaDirectDebitTransaction::NAME,
        $sepaDirectDebitMAID,
        $sepaDirectDebitKey
    );
    $sepaDirectDebitConfig->setCreditorId('DE98ZZZ09999999999');
    $config->add($sepaDirectDebitConfig);
}

/**
 * @param Config\Config $config
 */
function addSofortConfig(Config\Config $config): void
{
    $sofortMAID = '6c0e7efd-ee58-40f7-9bbd-5e7337a052cd';
    $sofortSecretKey = 'dbc5a498-9a66-43b9-bf1d-a618dd399684';
    $sofortConfig = new PaymentMethodConfig(SofortTransaction::NAME, $sofortMAID, $sofortSecretKey);
    $config->add($sofortConfig);
}

/**
 * @param Config\Config $config
 */
function addIdealConfig(Config\Config $config): void
{
    $IdealMAID = '4aeccf39-0d47-47f6-a399-c05c1f2fc819';
    $IdealSecretKey = 'dbc5a498-9a66-43b9-bf1d-a618dd399684';
    $IdealConfig = new PaymentMethodConfig(IdealTransaction::NAME, $IdealMAID, $IdealSecretKey);
    $config->add($IdealConfig);
}

/**
 * @param Config\Config $config
 */
function addPaypalConfig(Config\Config $config): void
{
    $paypalMAID = '2a0e9351-24ed-4110-9a1b-fd0fee6bec26';
    $paypalKey = 'dbc5a498-9a66-43b9-bf1d-a618dd399684';
    $paypalConfig = new PaymentMethodConfig(PayPalTransaction::NAME, $paypalMAID, $paypalKey);
    $config->add($paypalConfig);
}

/**
 * @param Config\Config $config
 *
 *  The configuration object for Credit Card is a little different than other payment methods and can be
 *  instantiated without any parameters. If you wish to omit non-3-D transactions you can just leave out the
 *  maid and secret in the default CreditCardConfig.
 */
function addCreditCardConfig(Config\Config $config): void
{
    $merchant_account_id_cc = '53f2895a-e4de-4e82-a813-0d87a10e55e6';
    $merchant_account_secret_key_cc = 'dbc5a498-9a66-43b9-bf1d-a618dd399684';

    // ### Credit Card Non-3-D
    $creditCardConfig = new CreditCardConfig();

    ### Credit Card Non-3-D
    $creditCardConfig->setNonThreeDCredentials($merchant_account_id_cc, $merchant_account_secret_key_cc);

    // ### Credit Card 3-D
    $creditCardConfig->setThreeDCredentials($merchant_account_id_cc, $merchant_account_secret_key_cc);

    $config->add($creditCardConfig);
}
