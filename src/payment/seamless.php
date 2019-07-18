<?php
require '../../vendor/autoload.php';
if (!isset($_SESSION)) {
    session_start();
}
?>
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
    <script type=text/javascript src="https://wpp-test.wirecard.com/loader/paymentPage.js"></script>

    <title>Wirecard Payment Page Integration Demo</title>
</head>
<body>

<div class="container">
    <div class="page-header">
        <h1>Wirecard Payment Page Integration Demo - Seamless Payment</h1>
    </div>

    <!--
    The seamless form is currently 345px high.
    This includes a top margin of 120px.
    Take this into account for the styling of your page.
    -->
    <div id='seamless-form-target' style="height: 345px;">
    </div>

    <button id="submit" type="button" class=" btn btn-primary center-block" onclick="submitPayment();"
            style="margin-bottom: 2em;">
        Submit payment
    </button>

    <div class="well">
        You can display any content to your consumer on your checkout page.<br><br>
        Make sure that the page contains
        <ul>
            <li>a div for the seamless form (minimum 345px high)</li>
            <li>a submit button</li>
        </ul>
    </div>

    <div class="panel panel-default" id="paymentResult">
        <div class="panel-heading">
            <h4 id="paymentResultTitle">Payment result</h4>
        </div>

        <div class="panel-body">
            <pre>
                <code id="paymentResponse">

                </code>
            </pre>
        </div>

    </div>
</div>

<script type='text/javascript'>
    function renderSeamlessForm(paymentRedirectUrl) {
        WPP.seamlessRender({
            wrappingDivId: 'seamless-form-target',
            url: paymentRedirectUrl,
            onSuccess: function () {
                console.log('Seamless form rendered.')
            },
            onError: function (err) {
                console.error(err)
            }
        });
    }

    function submitPayment() {
        WPP.seamlessSubmit({
            onSuccess: function (response) {
                displaySuccessfulPayment(response);
            },
            onError: function (response) {
                displayError(response);
            }
        });

        function displaySuccessfulPayment(response) {
            var paymentResult = document.getElementById("paymentResult");
            paymentResult.classList.remove("panel-default");
            paymentResult.classList.add("panel-success");
            document.getElementById("paymentResultTitle").innerText = "Your payment was successful.";
            document.getElementById("paymentResponse").innerHTML = atob(response['response-base64']);
            console.log("The payment has been executed successfully.");
        }

        function displayError(response) {
            var paymentResult = document.getElementById("paymentResult");
            paymentResult.classList.remove("panel-default");
            paymentResult.classList.add("panel-danger");
            document.getElementById("paymentResultTitle").innerText = "Your payment failed.";
            document.getElementById("paymentResponse").innerHTML = atob(response['response-base64']);
            console.error(response);
        }
    }

    renderSeamlessForm(
        <?php
        echo '"' . $_SESSION['payment-redirect-url'] . '"';
        ?>
    );
</script>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <a class="btn btn-primary center-block text-center" role="button"
               style="text-align: right;float: right; margin: 30px 0 30px 0"
               href="../../index.html">Back</a>
        </div>
    </div>
</div>
</body>
</html>
