<?php
if (getPaymentMethod() === 'creditcard') {
?>
    <form action="../operations/creditcard/cancel.php" method="post">
        <input type="hidden" name="transactionId" value="<?= getTransactionId() ?>"/>
        <button type="submit" class="btn btn-primary">Cancel the payment</button>
    </form>
    <br>
    <form action="../operations/creditcard/recurring_default.php" method="post">
        <input type="hidden" name="tokenId" value="<?= getTokenId() ?>"/>
        <button type="submit" class="btn btn-primary">Create a recurring payment</button>
    </form>
    <br>
<?php
} elseif (getPaymentMethod() === 'paypal') {
?>
    <form action="../operations/paypal/credit_default.php" method="post">
        <input type="hidden" name="transactionId" value=""/>
        <button type="submit" class="btn btn-primary">Transfer fund the payment</button>
    </form>
    <br>
<?php
} elseif (getPaymentMethod() === 'sofortbanking') {
?>
    <form action="../operations/sofort/credit_default.php" method="post">
        <input type="hidden" name="transactionId" value="<?= getTransactionId() ?>"/>
        <button type="submit" class="btn btn-primary">Refund via SEPA credit transfer</button>
    </form>
    <br>
<?php
}
