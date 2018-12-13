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
    <h1 class="text-center">Find Registered Payments</h1>
    <div class="panel panel-default" style="margin-top: 1.5em;">
        <div class="panel-heading">
            Select one of the three variants to get transaction data.
        </div>

        <div class="panel-body">
            <div class="row">

                <div class="col-md-4 col-xs-12">
                    <form action="../transactions/showPaymentByTransactionId.php" method="post">
                        <div class="form-group">
                            <label for="transactionID">Use the transaction id to get a transaction</label>
                            <input type="text" name="transactionId" class="form-control" id="transactionID"
                                   value="<?php echo isset($_GET['tid']) ? $_GET['tid'] : ''; ?>"
                                   aria-describedby="transactionIDHelp" placeholder="Enter transaction id">
                            <small id="transactionIDHelp" class="form-text text-muted">Define a transaction id *</small>
                            <input type="text" name="paymentMethod" class="form-control" id="paymentMethod"
                                   value="<?php echo isset($_GET['paymentMethod']) ? $_GET['paymentMethod'] : ''; ?>"
                                   aria-describedby="paymentHelp" placeholder="Enter payment method">
                            <small id="paymentHelp" class="form-text text-muted">Define a payment method</br>
                                * For Alipay Cross-border payments use alipay-xborder</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <div class="col-md-4 col-xs-12">
                    <form action="../transactions/showPaymentByRequestId.php" method="post">
                        <div class="form-group">
                            <label for="requestId">Use the request id to get a transaction</label>
                            <input type="text" name="requestId" class="form-control" id="requestId"
                                   value="<?php echo isset($_GET['rid']) ? $_GET['rid'] : ''; ?>"
                                   aria-describedby="requestIdHelp" placeholder="Enter request id">
                            <small id="requestIdHelp" class="form-text text-muted">Define a request id
                            </small>
                            <input type="text" name="paymentMethod" class="form-control" id="paymentMethod_form2"
                                   value="<?php echo isset($_GET['paymentMethod']) ? $_GET['paymentMethod'] : ''; ?>"
                                   aria-describedby="paymentHelp_form2" placeholder="Enter payment method">
                            <small id="paymentHelp_form2" class="form-text text-muted">Define a payment method</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <div class="col-md-4 col-xs-12">
                    <form action="../transactions/showPaymentByGroupTransactions.php" method="post">
                        <div class="form-group">
                            <label for="groupTransactionID">Get a group of transactions</label>
                            <input type="text" name="transactionId" class="form-control" id="groupTransactionID"
                                   value="<?php echo isset($_GET['tid']) ? $_GET['tid'] : ''; ?>"
                                   aria-describedby="transactionIDHelp_form3" placeholder="Enter transaction id">
                            <small id="transactionIDHelp_form3" class="form-text text-muted">Define a transaction id
                            </small>
                            <input type="text" name="paymentMethod" class="form-control" id="paymentMethod_form3"
                                   value="<?php echo isset($_GET['paymentMethod']) ? $_GET['paymentMethod'] : ''; ?>"
                                   aria-describedby="paymentHelp_from3" placeholder="Enter payment method">
                            <small id="paymentHelp_from3" class="form-text text-muted">Define a payment method</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
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

