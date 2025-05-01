<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php"); 
    exit;
}

?>
<div class="quantity-container">
    <button type="button" class="qty-btn">-</button>
    <input type="number" class="quantity" name="quantity" value="1" min="1" readonly>
    <button type="button" class="qty-btn">+</button>
</div>

<link rel="stylesheet" href="../assets/css/quantityBox.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/js/quantityBox.js"></script>
