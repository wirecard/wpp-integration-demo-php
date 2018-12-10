<?php
require '../../vendor/autoload.php';



session_start();
$_SESSION['msg'] = 'The payment was successful.';
$_SESSION['response'] = $_POST;

/* For iDEAL a get request has to be sent otherwise notifications will not be triggered. The get request must be send
 * after the settlement and the payment transaction state must be successful.
 */
$idealMAID = '4aeccf39-0d47-47f6-a399-c05c1f2fc819';
$get_url = BASE_URL . '/engine/rest/merchants/' . $idealMAID . '/payments/search?payment.request-id=' .
    $_SESSION['uuid'];

$username = MERCHANT[IDEAL]['username'];
$password = MERCHANT[IDEAL]['password'];

$client = new GuzzleHttp\Client();
$headers = [
    'Content-type' => 'application/json; charset=utf-8',
    'Accept' => 'application/json',
    'Authorization' => 'Basic ' . base64_encode($username . ':' . $password),
];

try {
    $response = $client->request('GET', $get_url, [
        'headers' => $headers
    ]);
} catch (\GuzzleHttp\Exception\GuzzleException $e) {
    printf('%s', $e->getResponse()->getBody()->getContents());
}

redirect('show.php');
