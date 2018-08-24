<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>

    <title>Notify Page</title>
</head>
<body>
</body>
</html>

<?php
require_once('../util/helperFunctions.php');

// # PayPal notification

// Wirecard sends a server-to-server request regarding any changes in the transaction status.

// ## Required objects

// To include the necessary files, we use the composer for PSR-4 autoloading.
require '../../vendor/autoload.php';

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Wirecard\PaymentSdk\Config;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;
use Wirecard\PaymentSdk\TransactionService;

$baseUrl = 'https://wpp-test.wirecard.com';
$httpUser = MERCHANT_CONFIG_A["username"];
$httpPass = MERCHANT_CONFIG_A["password"];

$config = new Config\Config($baseUrl, $httpUser, $httpPass, 'EUR');
// ### Validation

// We use Monolog as logger. Set up a logger for the notifications.
$log = new Logger('Wirecard notifications');
$log->pushHandler(new StreamHandler('../logs/notify.log', Logger::INFO));

// Set a public key for certificate pinning used for response signature validation, this certificate needs to be always
// up to date
$config->setPublicKey(file_get_contents('../../certificate/api-test.wirecard.com.crt'));

// ## Transaction

// ### Transaction Service

// The `TransactionService` is used to determine the response from the service provider.
$service = new TransactionService($config);

// ## Notification status

// The notification are transmitted as _POST_ request and is handled via the `handleNotification` method.
$notification = $service->handleNotification(file_get_contents('php://input'));
// Log the notification for a successful transaction.

if ($notification instanceof SuccessResponse) {
    $log->info(sprintf(
        'Transaction with id %s was successful and validation status is %s.',
        $notification->getTransactionId(),
        $notification->isValidSignature() ? 'true' : 'false'
    ));
// Log the notification for a failed transaction.
} elseif ($notification instanceof FailureResponse) {
    $log->info(sprintf(
        'Transaction with id %s was failure and validation status is %s.',
        $notification->getTransactionId(),
        $notification->isValidSignature() ? 'true' : 'false'
    ));

    // In our example we iterate over all errors and echo them out.
    // You should display them as error, warning or information based on the given severity.
    foreach ($notification->getStatusCollection() as $status) {
        /**
         * @var $status \Wirecard\PaymentSdk\Entity\Status
         */
        $severity = ucfirst($status->getSeverity());
        $code = $status->getCode();
        $description = $status->getDescription();
        $log->warning(sprintf('%s with code %s and message "%s" occurred.<br>', $severity, $code, $description));
    }
}
