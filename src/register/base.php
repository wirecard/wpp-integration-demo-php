<?php

/**
 * Functions which are used for registering a payment by all 3 types of integration.
 */

/**
 * Creates a payload based on the example request JSON file,
 * which can be used as the body of a register payment POST request.
 *
 * @return array An array containing all the required parameters of the POST body.
 */
function createPayload()
{
    $payloadText = file_get_contents("../../example-request/payment.json");
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
function postRegisterRequest($payload)
{
    $ch = curl_init("https://wpp-test.wirecard.com/api/payment/register");

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $credentials = "70000-APIDEMO-CARD:ohysS0-dvfMx";
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
                    "code" => curl_errno(),
                    "description" => curl_error(),

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
function retrievePaymentRedirectUrl($payload)
{
    $responseContent = postRegisterRequest($payload);

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
