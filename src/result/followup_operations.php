<?php
if (getPaymentMethod() === 'creditcard') {
    ?>
    <form action="../operations/creditcard/recurring_default.php" method="post">
        <input type="hidden" name="tokenId" value="<?= getTokenId() ?>"/>
        <button type="submit" class="btn btn-primary">Create a recurring payment</button>
    </form>
    <br>
    <form action="../operations/creditcard/cancel.php" method="post">
        <input type="hidden" name="transactionId" value="<?= getTransactionId() ?>"/>
        <button type="submit" class="btn btn-primary">Cancel the payment</button>
    </form>
    <?php
} elseif (getPaymentMethod() === PAYPAL) {
    ?>
    <form action="../operations/paypal/credit_default.php" method="post">
        <input type="hidden" name="transactionId" value=""/>
        <button type="submit" class="btn btn-primary">Transfer fund the payment</button>
    </form>
    <br>
    <form action="../operations/paypal/cancel.php" method="post">
        <input type="hidden" name="transactionId" value="<?= getTransactionId() ?>"/>
        <button type="submit" class="btn btn-primary">Cancel the payment</button>
    </form>
    <br>
    <?php
} elseif (getPaymentMethod() === SOFORT) {
    ?>
    <form action="../operations/sofort/credit_default.php" method="post">
        <input type="hidden" name="transactionId" value="<?= getTransactionId() ?>"/>
        <button type="submit" class="btn btn-primary">Refund via SEPA credit transfer</button>
    </form>
    <br>
    <?php
} elseif (getPaymentMethod() === PAYSAFECARD) {
    ?>
    <form action="../operations/paysafecard/pay_after_reservation.php" method="post">
        <input type="hidden" name="transactionId" value="<?= getTransactionId() ?>"/>
        <button type="submit" class="btn btn-primary">Pay</button>
    </form>
    <br>
    <form action="../operations/paysafecard/cancel.php" method="post">
        <input type="hidden" name="transactionId" value="<?= getTransactionId() ?>"/>
        <button type="submit" class="btn btn-primary">Cancel the payment</button>
    </form>
    <br>
    <?php
} elseif (getPaymentMethod() === P24) {
    ?>
    <form action="../operations/przelewy24/cancel.php" method="post">
        <input type="hidden" name="transactionId" value="<?= getTransactionId() ?>"/>
        <button type="submit" class="btn btn-primary">Refund the payment</button>
    </form>
    <br>
    <?php
} elseif (getPaymentMethod() === ALIPAY_XBORDER) {
    ?>
    <form action="../operations/alipay_crossborder/cancel.php" method="post">
        <input type="hidden" name="transactionId" value="<?= getTransactionId() ?>"/>
        <button type="submit" class="btn btn-primary">Refund the payment</button>
    </form>
    <br>
    <?php
}
