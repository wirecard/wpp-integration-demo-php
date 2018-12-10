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
<div class="container" style="margin-top: 2%">
    <a class="btn btn-primary center-block text-center" role="button"
       style="text-align: right;float: right; margin: 30px 0 30px 0"
       href="../../index.html">Back</a>
    <?php
    require '../../vendor/autoload.php';
    
    session_start();
    ?>

    <h1>Wirecard Payment Page Integration Demo</h1>

    <div class="row">
        <div class="col-12">
            <h3 style="text-align: center"><strong>
                    <?php echo $_SESSION['msg']; ?>
                </strong></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h4><strong>responseSignatureBase64:</strong></h4>
            <pre><code><?php echo showResponseData('response-signature-base64'); ?></code></pre>
            <h4><strong>responseSignatureAlgorithm:</strong></h4>
            <pre><code><?php echo showResponseData('response-signature-algorithm'); ?></code></pre>
            <h4><strong>responseBase64:</strong></h4>
            <pre><code><?php echo showResponseData('response-base64'); ?></code></pre>
            <h4><strong>decodedResponseBase64:</strong></h4>
            <pre><code><?php echo showResponseData('response-base64', true); ?></code></pre>
            <h4><strong>validSignature:</strong></h4>
            <pre><code><?php showValidSignature(); ?></code></pre>
        </div>
    </div>
    <br>

    <?php
    if (getTransactionState() === 'success') {
        require 'followup_operations.php';
    }
    ?>


</div>
</body>
</html>
