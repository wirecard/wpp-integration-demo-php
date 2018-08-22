<?php

require 'globals.php';
/**
 * General functions which are not specific for the WPP domain.
 */

/**
 * Redirect to a provided URL.
 *
 * @param $url
 */
function redirect($url)
{
    ob_start();
    header('Location: ' . $url);
    ob_end_flush();
    exit();
}

/**
 * Returns protocol, server name and port for the current page.
 *
 * @return string Base url of the application
 *
 */
function getBaseUrl()
{
    $baseUrl = $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . '/';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
        $baseUrl = "https://" . $baseUrl;
    } else {
        $baseUrl = "http://" . $baseUrl;
    }
    return $baseUrl;
}

/**
 * Shows content of response message depending on passed over attribute.
 *
 * @param $attr
 * @param $hasToBeEncoded
 * @return string Returns either the content of the response message or a default info message.
 */
function showResponseData($attr, $hasToBeEncoded = false)
{
    if ($hasToBeEncoded) {
        return isset($_SESSION['response'][$attr]) ? base64_decode($_SESSION['response'][$attr]) : DEFAULT_RES_MSG;
    }
    return isset($_SESSION['response'][$attr]) ? $_SESSION['response'][$attr] : DEFAULT_RES_MSG;
}

/**
 * Checks whether the signature of a payment response is valid.
 *
 * @param string $responseBase64
 * @param string $signatureBase64
 * @param string $merchantSecretKey
 * @return bool
 */
function isValidSignature($responseBase64, $signatureBase64, $merchantSecretKey)
{
    $signature = hash_hmac('sha256', $responseBase64, $merchantSecretKey, $raw_output = true);
    return hash_equals($signature, base64_decode($signatureBase64));
}
