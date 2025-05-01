<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php"); 
    exit;
}

?>
<div class="quantity-container">
        <button type="submit" class="minus-btn" name="minus-btn" value="<?= $product['cart_id'] ?>">-</button>
        <input type="text" class="quantity" name="quantity" value="<?= $product['quantity'] ?>" readonly data-stock="<?= $product['available_stock'] ?>">
        <button type="submit" class="plus-btn" name="plus-btn" value="<?= $product['cart_id'] ?>">+</button>
</div>


<link rel="stylesheet" href="../assets/css/quantityBox.css">
