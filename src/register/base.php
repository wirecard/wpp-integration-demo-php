<?php

require '../util/globals.php';

/**
 * Functions which are used for registering a payment by all 3 types of integration.
 */

/**
 * Creates a payload based on the example request JSON file,
 * which can be used as the body of a register payment POST request.
 *
 * @return array An array containing all the required parameters of the POST body.
 */
function createPayload($paymentMethod, $isEmbedded)
{
    if ($paymentMethod === $GLOBALS['ccard']) {
        $payloadText = file_get_contents("../../example-requests-standalone/creditcard_payment.json");
    } elseif ($paymentMethod === $GLOBALS['paypal'] && !$isEmbedded) {
        $payloadText = file_get_contents("../../example-requests-standalone/paypal_payment.json");
    } elseif ($paymentMethod === $GLOBALS['ideal'] && !$isEmbedded) {
        $payloadText = file_get_contents("../../example-requests-standalone/ideal_payment.json");
    } elseif ($paymentMethod === $GLOBALS['sepa_dd'] && !$isEmbedded) {
        $payloadText = file_get_contents("../../example-requests-standalone/sepa_dd_payment.json");
    } elseif ($paymentMethod === $GLOBALS['sofort'] && !$isEmbedded) {
        $payloadText = file_get_contents("../../example-requests-standalone/sofortbanking_payment.json");
    } else if ($paymentMethod === $GLOBALS['paypal'] && $isEmbedded) {
        $payloadText = file_get_contents("../../example-requests-embedded/paypal_payment.json");
    } else if ($paymentMethod === $GLOBALS['ideal'] && $isEmbedded) {
        $payloadText = file_get_contents("../../example-requests-embedded/ideal_payment.json");
    } else if ($paymentMethod === $GLOBALS['sepa_dd'] && $isEmbedded) {
        $payloadText = file_get_contents("../../example-requests-embedded/sepa_dd_payment.json");
    } else if ($paymentMethod === $GLOBALS['sofort'] && $isEmbedded) {
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
    $ch = curl_init("https://wpp-test.wirecard.com/api/payment/register");

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    if ($paymentMethod === $GLOBALS['ccard']) {
        $credentials = "70000-APIDEMO-CARD:ohysS0-dvfMx";
    } elseif ($paymentMethod === $GLOBALS['paypal']) {
        $credentials = "70000-APITEST-AP:qD2wzQ_hrc!8";
    } elseif ($paymentMethod === $GLOBALS['ideal']) {
        $credentials = "16390-testing:3!3013=D3fD8X7";
    } elseif ($paymentMethod === $GLOBALS['sepa_dd']) {
        $credentials = "16390-testing:3!3013=D3fD8X7";
    } elseif ($paymentMethod === $GLOBALS['sofort']) {
        $credentials = "16390-testing:3!3013=D3fD8X7";
    }

    $headers = array(
        "Content-type: application/json",
        "Authorization: Basic " . base64_encode($credentials)
    );

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if ($response === false) {
        $result = [
            "errors" => [
                [
                    "code" => curl_errno($ch),
                    "description" => curl_error($ch),

                ]
            ]
        ];
        curl_close($ch);
        return $result;
    }

    curl_close($ch);

    return json_decode($response, $assoc = true);
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
