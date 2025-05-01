<?php
require "../public/header.php";
include "../models/addressModels.php";
include "../models/orderModels.php";
include "../models/productModels.php";
include "../models/cartModels.php";
include "../config/userAuth.php";

$userId = $_SESSION['user_id']; 
$selectedAddressId = $_SESSION['selected_address_id'] ?? null;
$buyNow=$_SESSION['order_data']??null;
$selectedCartIds = $_SESSION['selected_products'] ?? [];
$selectedItem = [];
$subtotal = 0;

if ($selectedAddressId) {
    $userAddress = getUserAddressesById($selectedAddressId);
} else {
    $userAddress = getDefaultAddress($userId);
}


if (!empty($_SESSION['selected_products'])) {
    $selectedItem = getAllItemByCartId($selectedCartIds);
} elseif (!empty($_SESSION['order_data'])) {
    $selectedItem = [[
        'product_id' => $buyNow['product_id'],
        'name' => $buyNow['name'],
        'quantity' => $buyNow['quantity'],
        'price' => $buyNow['price'],
        'discount' => $buyNow['discount'],
     ]];
}


if(is_post()){
    $cardNumber = trim($_POST['card_number']);
    $cardName = trim($_POST['card_name']);
    $expiryDate = trim($_POST['expiry_date']);
    $cvv = trim($_POST['cvv']);

    if (!$userAddress) {
        temp('info', '❌ Please select your shipping address.');
        redirect();
    }
    
    if (empty($cardNumber) || empty($cardName) || empty($expiryDate) || empty($cvv)) {
        temp('info', '❌ Please fill in all card info.');
        redirect();
    }
    
    // Validate card number (format: 16 digits with spaces)
    if (!preg_match('/^\d{4} \d{4} \d{4} \d{4}$/', $cardNumber)) {
        temp('info', '❌ Invalid card number format.');
        redirect();
    }
    
    // Validate cardholder name (letters and spaces, min 3 chars)
    if (!preg_match('/^[A-Za-z ]{3,}$/', $cardName)) {
        temp('info', '❌ Invalid cardholder name.');
        redirect();
    }
    
    // Validate expiry date (MM/YY)
    if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiryDate)) {
        temp('info', '❌ Invalid expiry date format.');
        redirect();
    }
    
    // Validate CVV (3 digits)
    if (!preg_match('/^\d{3}$/', $cvv)) {
        temp('info', '❌ Invalid CVV.');
        redirect();
    }
    
    // Optional: Check if card expired
    $expParts = explode('/', $expiryDate);
    $expMonth = (int)$expParts[0];
    $expYear = (int)('20' . $expParts[1]);
    
    $currentMonth = (int)date('m');
    $currentYear = (int)date('Y');
    
    if ($expYear < $currentYear || ($expYear == $currentYear && $expMonth < $currentMonth)) {
        temp('info', '❌ Card has expired.');
        redirect();
    }
    

    try {
        $selectedCartIds = $_SESSION['selected_products'];
        $subtotal = $_SESSION['subtotal'];
        $selectedItems = $_SESSION['selected_items'] ;

        $_db->beginTransaction();

        $orderNumber = generateOrderNumber();

        $address = getAddressById($userAddress['address_id']);
        $orderId = addNewOrder($userId, $subtotal, $orderNumber, $address);

        addOrderItems($orderId, $selectedItems);

        updateProductStock($selectedItems);
        updateProductSoldCount($selectedItems);

        if (!empty($selectedCartIds)) {
            deleteCartItems($selectedCartIds);
        }
        
        unset($_SESSION['selected_products'],$_SESSION['selected_address_id'],$_SESSION['subtotal']);

        $_db->commit();

        temp('info','✅ Order placed successful');
        redirect('../public/home.php');

    }catch(Exception $e){
        $_db->rollBack();

        temp('info', '❌ ' . $e->getMessage());
        redirect();
    }
}
?>

<link rel="stylesheet" href="../assets/css/orderConfirm.css">
<link rel="stylesheet" href="../assets/css/orderSummary.css">
<link rel="stylesheet" href="../assets/css/payment.css">

<div class="container">
    <h2>Order Confirmation</h2>
    <h3>Shipping Address </h3>

    <div class="address-box">
    <div class="address-info">
        <?php if ($userAddress): ?>
            <strong><?= htmlspecialchars($userAddress['full_name']) ?></strong><br>
            <?= htmlspecialchars($userAddress['phone']) ?><br>
            <?= htmlspecialchars($userAddress['address_line']) ?><br>
            <?= htmlspecialchars($userAddress['postcode']) ?>, <?= htmlspecialchars($userAddress['city']) ?>
        <?php else: ?>
            <span>No address selected.</span><br>
        <?php endif; ?>
    </div>
        <div class="arrow">&gt;</div>
    </div>

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
                    $image = $images[0];
                    $price = $item['price'] - ($item['price'] * $item['discount'] / 100);
                    $totalPrice = $price * $item['quantity'];
                    $subtotal += $totalPrice;
            ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($image) ?>" alt="Product Image" class="productImage"></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= $item['quantity'] ?></td> <!-- Removed Size -->
                    <td>RM<?= number_format($price, 2) ?></td>
                    <td>RM<?= number_format($totalPrice, 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php 
                $_SESSION['subtotal'] = $subtotal;
                $_SESSION['selected_items'] = $selectedItem;
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
            <span>Shipping Fees</span>
            <strong>RM10.00</strong>
        </div>
        <hr>
        <div class="grand-total">
            <span><strong>Grand Total</strong></span>
            <strong>RM<?= number_format($subtotal + 10, 2) ?></strong>
        </div>
    </div>
</div>


    </div>
<form method="POST">
<h3>💳 Payment Information</h3>
<div class="payment-form">
    <label for="card-number">Card Number</label>
    <input type="text" id="card-number" name="card_number" maxlength="19" placeholder="1234 5678 9012 3456" required>

    <label for="card-name">Cardholder Name</label>
    <input type="text" id="card-name" name="card_name" placeholder="Cardholder Name" required>

    <div class="payment-row">
        <div>
            <label for="expiry-date">Expiry Date eg.(12/25)</label>
            <input type="text" id="expiry-date" name="expiry_date" maxlength="5" placeholder="MM/YY" required>
        </div>
        <div>
            <label for="cvv">CVV</label>
            <input type="password" id="cvv" name="cvv" maxlength="3" placeholder="123" required>
        </div>
    </div>
</div>

        <button type="submit" class="place-order-btn" data-confirm="Do you want to place order?">Place Order</button>
    </form>  
</div>


<script src="../assets/js/orderConfirm.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
