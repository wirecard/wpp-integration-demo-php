<?php
require '../../vendor/autoload.php' ;
if (!isset($_SESSION)) {
    session_start();
}
?>
<html>
<head>
    <title>Wirecard Payment Page Integration Demo</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <script type=text/javascript src="https://wpp-test.wirecard.com/loader/paymentPage.js"></script>
</head>
<body>
<div class="container">
    <h1>Wirecard Payment Page Integration Demo - Embedded Payment</h1>

    <script type='text/javascript'>
        WPP.embeddedPayUrl(
            <?php
            echo '"' . $_SESSION['payment-redirect-url'] . '"';
            ?>
        );
    </script>

    <div class="well">
        Your consumer will see the content of your checkout page,<br>
        but it will be overlaid by the embedded payment form.
    </div>

</div>
</body>
</html>
