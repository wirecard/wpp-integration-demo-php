<?php

require '../../vendor/autoload.php';
require '../util/globals.php';
require '../util/helperFunctions.php';
require '../config.php';

/**
 * Functions which are used for registering a payment by all 3 types of integration.
 */

/**
 * Creates a payload for standalone payment page based on the example request JSON file,
 * which can be used as the body of a register payment POST request.
 *
 * @return array An array containing all the required parameters of the POST body.
 */
function createPayload($paymentMethod, $isStandalone)
{
    if ($isStandalone === true) {
        $payloadText = file_get_contents(PATHS_STANDALONE[$paymentMethod]);
    } else {
        $payloadText = file_get_contents(PATHS_EMBEDDED[$paymentMethod]);
    }
    return modifyPayload($payloadText);
}

function modifyPayload($payloadText)
{
    $payload = json_decode($payloadText, $assoc = true);
    $uuid = uniqid('payment_request_', true);
    $payload["payment"]["request-id"] = $uuid;
    $payload["payment"]["success-redirect-url"] = getBaseUrl() . $payload["payment"]["success-redirect-url"];
    $payload["payment"]["fail-redirect-url"] = getBaseUrl() . $payload["payment"]["fail-redirect-url"];
    $payload["payment"]["cancel-redirect-url"] = getBaseUrl() . $payload["payment"]["cancel-redirect-url"];
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
    $username = MERCHANT[$paymentMethod]["username"];
    $password = MERCHANT[$paymentMethod]["password"];

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
