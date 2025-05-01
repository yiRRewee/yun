<link rel="stylesheet" href="../assets/css/productDetail.css">
<link rel="stylesheet" href="../assets/css/radioButton.css">
<?php
include "../models/productModels.php";
include "../models/reviewModels.php";
include "../models/cartModels.php";
include "../public/header.php";
require "../config/userAuth.php";

$id=$_GET["id"];

$message = "";
$messageType = "error"; 

if (!$id) {
    die("Product ID is missing!");
}

$product=getProductById($id);
if (!$product) {
    die("Product not found.");
}

$images = getProductImageById($id);

if (is_post()) {

    if ($_SESSION['role'] != "user") {
        $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; 
        redirect("../public/login.php");
    }

    $userId = $_SESSION['user_id']; 
    $productId = $id;
    $quantity = $_POST['quantity']; 

    // Handling add to cart
    if (isset($_POST["add-to-cart"])) {
        $cartItem = getCartItemByUserProduct($userId, $productId);
        $stock = $product->stock;  // Total stock for the product

        // Check if the product is already in the cart
        if ($cartItem) {
            $existingQty = $cartItem['quantity'];
            $newQty = $existingQty + $quantity;

            // Check if the new quantity exceeds the stock
            if ($newQty > $stock) {
                temp('info', '❌ Only ' . ($stock - $existingQty) . ' more available in stock.');
                redirect();
            }

            // Update cart quantity if it's within the stock limit
            updateCartQuantityTo($newQty, $cartItem['id']);
            temp('info', '✅ Quantity updated in cart!');
        } else {
            // Add product to cart if not already in the cart
            if ($quantity > $stock) {
                temp('info', '❌ Only ' . $stock . ' units available in stock.');
                redirect();
            }

            addToCart($userId, $productId, $quantity);
            temp('info', '✅ Product Added Successfully!');
        }

        redirect("../user/shop.php");
    } 
    // Handling buy now
    else if (isset($_POST["buy-now"])) {
        $_SESSION["order_data"] = [
            "product_id" => $productId,
            "name" => $product->name,
            "price" => $product->price,
            "discount" => $product->discount,
            "quantity" => $quantity,
            "image" => $images[0]
        ];
        unset($_SESSION['selected_products'], $_SESSION['subtotal'], $_SESSION['selected_items']);
        redirect("orderConfirm.php");
        exit();
    }
}
?>
<?php include "../component/backButton.php"; ?>
<div class="container">
    <div class="left-side">
        <div class="image-display-container">
            <img id="image-display" src="<?= htmlspecialchars($images[0]) ?>" alt="Product Image">
        </div>
        <div class="images-selection">
            <?php foreach ($images as $image): ?>
                <img class="image-select" src="<?= htmlspecialchars($image) ?>">
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="right-side">
        <h2 class="product-name"><?= htmlspecialchars($product->name) ?></h2>
        <div class="product-details">
        <div class="product-details">
            <?php if ($product->discount > 0): ?>
                <div class="product-price-container">
                    <p class="original-price"><s>RM <?= number_format($product->price, 2) ?></s></p>
                    <p class="discounted-price">RM <?= number_format($product->price * (1 - $product->discount / 100), 2) ?> (<?= $product->discount ?>% OFF)</p>
                </div>
            <?php else: ?>
                <p class="product-price">RM <?= number_format($product->price, 2) ?></p>
            <?php endif; ?>        
            </div>
            <p class="product-sold">Sold: <?= $product->sold_count ?></p>
            </div>
                <p>Stock:<?=$product->stock ?></p>

            <form action="" method="POST">
        <p id="stock-info"></p>

        <div class="add-to-cart">
        <?php include "../component/quantityBox.php" ?>
        <script>
    const maxStock = <?= $product->stock ?>;
</script>
        <button type="submit" class="cart-btn" id="add-to-cart" name="add-to-cart">Add to Cart</button>
        <button type="submit" class="buy-btn" id="buy-now" name="buy-now">Buy Now</button>
        </div>
        </form>
    </div>
</div>
    <div class="product-nav">
        <div class="nav-button">
                <button class="nav-btn" data-target="reviews">Customer Reviews</button>
            </div>   
             <hr>

<div class="bottom-section">
            <div class="nav-content" id="reviews">
                <?php $reviews = getReviewAndImageByProductId($id);?>
                    <div class="customer-review">
                        <?php if ($reviews) : ?>
                            <?php foreach ($reviews as $review) : ?>
                                <p><strong>From:<?=$review->username ?></strong></p>
                                <p>Rating:
                                    <?php 
                                        $i = 0;
                                        while ($i < $review->rating) { 
                                            echo '⭐';
                                            $i++;
                                        }
                                    ?>
                                </p>
                                <p>Comment: <?=$review->review_text ?></p>
                                <?php if (!empty($review->images)) : ?>
                        <div class="review-images">
                            <?php foreach ($review->images as $imagePath) : ?>
                                <img src="../assets/image/uploads/<?= $imagePath ?>" alt="Review Image" class="review-image">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <p class="post-time">Posted on <?= $review->created_at ?></p><br><hr>
                                <?php endforeach; ?>
                        <?php else : ?>
                            <p>Opps! No customer leave comment for this shoes.</p>
                        <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/js/product.js"></script>
<script src="../assets/js/productDetailNav.js"></script>
