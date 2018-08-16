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

    <title>Wirecard Payment Page Integration Demo</title>
</head>
<body>
<div class="container">
    <h1 class="text-center">Wirecard Payment Page Integration Demo - Standalone Creditcard and Alternative Payment
        Methods</h1>
    <div class="panel panel-default" style="margin-top: 1.5em;">
        <div class="panel-heading">
            Choose standalone creditcard or one of the currently supported alternative payment methods
        </div>

        <div class="panel-body">
            <div class="row">

                <div class="col-md-4 col-xs-12">
                    <div style="padding: 0.5em 2.5em;" class="text-center">
                        <h2>Creditcard</h2>
                        <img src="../../images/ccard.png"/>
                        <p style="margin: 1em 0;">
                            A standalone payment page hosted independently of your checkout page.
                        </p>
                        <div>
                            <a href="https://document-center.wirecard.com/display/PTD/Credit+Card+with+WPP">
                                Read more
                            </a>
                        </div>
                        <div style="padding: 1em 3em;">
                            <a class="btn btn-primary center-block text-center" role="button"
                               style="display: inline; padding: 0.55em;" href="../register/standalone.php?method=ccard">Register
                                payment</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-xs-12">
                    <div style="padding: 0.5em 2.5em;" class="text-center">
                        <h2>PayPal</h2>
                        <img src="../../images/paypal.png" alt="payment method PayPal" class="center-block"/>
                        <p style="margin: 1em 0;">
                            Payment service PayPal hosted independently of your checkout page.
                        </p>
                        <div>
                            <a href="https://document-center.wirecard.com/display/PTD/PayPal+with+WPP">Read
                                more</a>
                        </div>
                        <div style="padding: 1em 3em;">
                            <a class="btn btn-primary center-block text-center" role="button"
                               style="display: inline; padding: 0.55em;"
                               href="../register/standalone.php?method=paypal">Register
                                payment</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-xs-12">
                    <div style="padding: 0.5em 2.5em;" class="text-center">
                        <h2>Ideal</h2>
                        <img src="../../images/ideal.png" alt="payment method Ideal" class="center-block"/>
                        <p style="margin: 1em 0;">
                            Online payment service Ideal hosted independently of your checkout page.
                        </p>
                        <div>
                            <a href="https://document-center.wirecard.com/display/PTD/iDEAL+with+WPP">
                                Read more
                            </a>
                        </div>
                        <div style="padding: 1em 3em;">
                            <a class="btn btn-primary center-block text-center" role="button"
                               style="display: inline; padding: 0.55em;" href="../register/standalone.php?method=ideal">Register
                                payment</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-xs-12">
                    <div style="padding: 0.5em 2.5em;" class="text-center">
                        <h2>Sofort</h2>
                        <img src="../../images/sofort.png" alt="payment method Sofort" class="center-block"/>
                        <p style="margin: 1em 0;">
                            Online payment service Sofort hosted independently of your checkout page.
                        </p>
                        <div>
                            <a href="https://document-center.wirecard.com/display/PTD/Sofort.+with+WPP">
                                Read more
                            </a>
                        </div>
                        <div style="padding: 1em 3em;">
                            <a class="btn btn-primary center-block text-center" role="button"
                               style="display: inline; padding: 0.55em;"
                               href="../register/standalone.php?method=sofort">Register
                                payment</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-xs-12">
                    <div style="padding: 0.5em 2.5em;" class="text-center">
                        <h3>Sepa Direct Debit</h3>
                        <img src="../../images/sepa.png" alt="payment method Sepa-DD" class="center-block"/>
                        <p style="margin: 1em 0;">
                            Sepa Direct Debit hosted independently of your checkout page.
                        </p>
                        <div>
                            <a href="https://document-center.wirecard.com/display/PTD/SEPA+Direct+Debit+with+WPP">
                                Read more
                            </a>
                        </div>
                        <div style="padding: 1em 3em;">
                            <a class="btn btn-primary center-block text-center" role="button"
                               style="display: inline; padding: 0.55em;"
                               href="../register/standalone.php?method=sepa-dd">Register
                                payment</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

</body>
</html>
