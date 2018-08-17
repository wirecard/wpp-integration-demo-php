<?php

require '../../vendor/autoload.php';
require '../util/globals.php';

/**
 * Functions which are used for registering a payment by all 3 types of integration.
 */

/**
 * Creates a payload for standalone payment page based on the example request JSON file,
 * which can be used as the body of a register payment POST request.
 *
 * @return array An array containing all the required parameters of the POST body.
 */
function createPayloadStandalone($paymentMethod)
{
    $payloadText = "";
    if ($paymentMethod === CCARD) {
        $payloadText = file_get_contents("../../example-requests-standalone/creditcard_payment.json");
    } elseif ($paymentMethod === PAYPAL) {
        $payloadText = file_get_contents("../../example-requests-standalone/paypal_payment.json");
    } elseif ($paymentMethod === IDEAL) {
        $payloadText = file_get_contents("../../example-requests-standalone/ideal_payment.json");
    } elseif ($paymentMethod === SEPA) {
        $payloadText = file_get_contents("../../example-requests-standalone/sepa_dd_payment.json");
    } elseif ($paymentMethod === SOFORT) {
        $payloadText = file_get_contents("../../example-requests-standalone/sofortbanking_payment.json");
    }

    $payload = json_decode($payloadText, $assoc = true);
    $uuid = uniqid('payment_request_', true);
    $payload["payment"]["request-id"] = $uuid;

    return $payload;
}

/**
 * Functions which are used for registering a payment by all 3 types of integration.
 */

/**
 * Creates a payload for embedded payment page based on the example request JSON file,
 * which can be used as the body of a register payment POST request.
 *
 * @return array An array containing all the required parameters of the POST body.
 */
function createPayloadEmbedded($paymentMethod)
{
    if ($paymentMethod === CCARD) {
        $payloadText = file_get_contents("../../example-requests-standalone/creditcard_payment.json");
    } else if ($paymentMethod === PAYPAL) {
        $payloadText = file_get_contents("../../example-requests-embedded/paypal_payment.json");
    } else if ($paymentMethod === IDEAL) {
        $payloadText = file_get_contents("../../example-requests-embedded/ideal_payment.json");
    } else if ($paymentMethod === SEPA) {
        $payloadText = file_get_contents("../../example-requests-embedded/sepa_dd_payment.json");
    } else if ($paymentMethod === SOFORT) {
        $payloadText = file_get_contents("../../example-requests-embedded/sofortbanking_payment.json");
    }

    $payload = json_decode($payloadText, $assoc = true);
    $uuid = uniqid('payment_request_', true);
    $payload["payment"]["request-id"] = $uuid;

    return $payload;
}

/**
 * Sends a POST request to the WPP register payment endpoint.
 *
 * @param $payload
 * @return array|mixed The response from WPP as an array if the request was successful.
 *      An array with the number and the description of the curl error if the request was not successful.
 */
function postRegisterRequest($payload, $paymentMethod)
{
    if ($paymentMethod === CCARD) {
        $username = "70000-APIDEMO-CARD";
        $password = "ohysS0-dvfMx";
    } elseif ($paymentMethod === PAYPAL) {
        $username = "70000-APITEST-AP";
        $password = "qD2wzQ_hrc!8";
    } elseif ($paymentMethod === IDEAL) {
        $username = "70000-APITEST-AP";
        $password = "qD2wzQ_hrc!8";
    } elseif ($paymentMethod === SEPA) {
        $username = "16390-testing";
        $password = "3!3013=D3fD8X7";
    } elseif ($paymentMethod === SOFORT) {
        $username = "16390-testing";
        $password = "3!3013=D3fD8X7";
    }

    $client = new GuzzleHttp\Client();
    $headers = [
        'Content-type' => 'application/json; charset=utf-8',
        'Accept' => 'application/json',
        'Authorization' => 'Basic ' . base64_encode($username . ':' . $password),
    ];

    try {
        $response = $client->request('POST', 'https://wpp-test.wirecard.com/api/payment/register', [
            'headers' => $headers,
            'body' => json_encode($payload),
        ]);
    } catch (\GuzzleHttp\Exception\GuzzleException $exception) {
        return $exception->getResponse()->getBody(true);
    }

    $contents = $response->getBody()->getContents();
    return json_decode($contents, $assoc = true);
}

/**
 * Retrieves a payment-redirect-url from the WPP register payment endpoint and writes this URL into the session.
 *
 * @param $payload
 * @return bool TRUE if the request was successful and the response contained a redirect URL. FALSE otherwise.
 */
function retrievePaymentRedirectUrl($payload, $paymentMethod)
{
    $responseContent = postRegisterRequest($payload, $paymentMethod);
echo "123";
    // An error response looks like this:
    // { "errors" : [
    //      {
    //          "code" : "E7001",
    //          "description" : "Violation of field payment.requestedAmount.currency: size must be between 3 and 3"
    //      },
    //      {
    //          "code" : "E7001",
    //          "description" : "Requested payment method is not supported."
    //      }
    // ] }
    if (array_key_exists("errors", $responseContent)) {
        echo "The registration of the payment failed: <br>";
        foreach ($responseContent["errors"] as $error) {
            echo "code: " . $error["code"] . " description: " . $error["description"] . "<br>";
        }

        return false;
    }

    // A successful response looks like this:
    // { "payment-redirect-url" : "https://wpp-test.wirecard.com/?wPaymentToken=eQloDaTU-QvoB-whatever" }
    session_start();
    $paymentRedirectUrl = $responseContent["payment-redirect-url"];
    $_SESSION["payment-redirect-url"] = $paymentRedirectUrl;

    return true;
}
