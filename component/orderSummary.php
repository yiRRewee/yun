<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php"); 
    exit;
}

$_SESSION['selected_items'] = $selectedItem;

?>
<link rel="stylesheet" href="../assets/css/orderSummary.css">


<div class="product-selected">
    <div class="order-summary">
        <h3>🧾 Order Summary</h3>

        <table>
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($selectedItem as $item): 
                        $images = getProductImageById($item['product_id']);
                        $image = $images[0] ;
                        $price= $item['price']-($item['price'] * $item['discount']/100);
                        $totalPrice = $price * $item['quantity'];
                        $subtotal += $totalPrice;
                ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($image) ?>" alt="Product Image" class="productImage"></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>RM<?=number_format($price,2)?></td>
                        <td>RM<?= number_format($totalPrice, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php $_SESSION['subtotal'] = $subtotal;
                ?>
            </tbody>
        </table>

        <hr>

        <div class="total-count">
            <div class="subtotal">
                <span>Subtotal</span>
                <strong>RM<?= number_format($subtotal, 2) ?></strong>
            </div>
            <div class="shipping-fee">
                <span>Shipping Fees </span>
                <strong>RM10.00</strong>
            </div>
            <hr>
            <div class="grand-total">
                <span><strong>Grand Total</strong></span>
                <strong>RM<?= number_format($subtotal + 10 , 2) ?></strong>
            </div>
        </div>
    </div>

    </div>