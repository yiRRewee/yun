<?php
include '../public/header.php';
include '../models/cartModels.php';
include '../models/productModels.php';
include "../config/userAuth.php";

$id = $_SESSION['user_id'];
$search = $_GET['search'] ?? ''; 
$cartProducts = getAllCartProductByIdAndSearch($id, $search);

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    if (isset($_POST['remove-item'])) {
        $cart_id = $_POST['remove-item'];
        removeFromCart($cart_id); 
        temp('info', '✅ Product Removed!');
        redirect("cart.php");
    }
    if (isset($_POST['plus-btn'])) {
        $cart_id = $_POST['plus-btn'];
        $product = getStockAndQuantityByCartId($cart_id);

        if ($product['quantity'] < $product['stock']) {
            updateCartQuantity(1, $cart_id);
        } else {
            temp('info', '❌ Only '. $product['stock'].' left for this product');
        }
        redirect();
    }

    if (isset($_POST['minus-btn'])) {
        $cart_id = $_POST['minus-btn'];
        $quantity = getQuantityByCartId($cart_id);

        if ($quantity > 1) {
            updateCartQuantity(-1, $cart_id);
        } else {
            removeFromCart($cart_id); 
            temp('info', '✅ Product Removed!');
        }      
        redirect();
    }
    
    if (isset($_POST['checkout-btn'])) {
        $_SESSION['selected_products'] = $_POST['selected_products'] ?? [];

        if (!$_SESSION['selected_products']) {
            temp('info', '❌ Please select at least one product to proceed.');
            redirect();
        }

        $errors = null;

        foreach ($_SESSION['selected_products'] as $cart_id) {
            $product = getStockAndQuantityByCartId($cart_id); 
    
            if ($product['quantity'] > $product['stock']) {
                $errors = "❌ '{$product['name']}' only has {$product['stock']} left. You have {$product['quantity']} in your cart.";
            }
        }
    
        if (!empty($errors)) {
            temp('info',  $errors);
            redirect();
        }
    
        redirect("orderConfirm.php");
    }
}
?>

<link rel="stylesheet" href="../assets/css/cart.css">

<!-- Search Form -->
<div class="cart-top-bar">
    <form method="get" class="search-form">
        <input type="text" name="search" placeholder="Search by product name..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
        <?php if (!empty($search)): ?>
            <a href="cart.php" class="clear-search">Clear</a>
        <?php endif; ?>
    </form>
</div>

<form method="post">
    <div class="container">  
        <div class="left-side">
        <div>Total Items: <?= count($cartProducts) ?></div>
            <?php if (empty($cartProducts)): ?>
                <div class="card-empty">
                    🛒 No products in your cart.
                </div>
            <?php else: ?>
                <?php foreach ($cartProducts as $product): 
                    $productImage = getProductImageById($product['product_id']);
                ?>
                    <div class="cart-container">
                        <input type="checkbox" 
                               name="selected_products[]" 
                               value="<?= $product['cart_id'] ?>" 
                               data-price="<?= $product['final_price'] ?>" 
                               class="product-checkbox"> 
                        <img src="<?= htmlspecialchars($productImage[0]) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="cart-content-left">
                            <p><b><?= htmlspecialchars($product['name']) ?></b></p>
                            <p>RM <?= number_format($product['final_price'], 2) ?></p>
                            <!-- Removed size-related display -->
                            <p class="a_stock">Available Stock: <?= $product['available_stock'] ?></p>
                        </div>
                        <div class="cart-content-right">
                            <button type="submit" name="remove-item" value="<?= $product['cart_id'] ?>" class="remove-cart-btn" data-confirm="Are you sure you want to remove?">
                                <img src="../assets/image/logo/remove.jpg" alt="remove from cart">
                            </button>   
                            <?php include "../component/quantityBoxCart.php"; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="right-side">
            <h3>Order Summary</h3>
            <p>Selected Items: <span id="selectedCount">0</span></p>
            <p>Total Price: RM <span id="totalPrice">0.00</span></p>
            <button name="checkout-btn" class="checkout-btn" type="submit">Checkout</button>
        </div> 
    </div>
</form>

<script src="../assets/js/cart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php include "../public/footer.php"; ?>
