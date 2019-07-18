<?php
require '../../vendor/autoload.php';
if (!isset($_SESSION)) {
    session_start();
}
redirect($_SESSION['payment-redirect-url']);
?>
<html>
<head>
    <title>Wirecard Payment Page Integration Demo</title>
</head>
<body>
<h1>Wirecard Payment Page Integration Demo - Hosted / Standalone Payment</h1>



<p>
    Your consumer will see this page only for a short time, if the redirection happens relatively slowly.<br>
    You can display a text like:
</p>

<p>
    Please wait, You'll be redirected to the payment page.
</p>

</body>
</html>
