<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php"); 
    exit;
}

function displayProductList($products) {
    foreach ($products as $product) {
        $price = number_format($product->price, 2);
        $discountedPrice = $product->discount > 0 ? number_format($product->price * (1 - $product->discount / 100), 2) : $price;
        $name = htmlspecialchars($product->name);
        $soldCount = $product->sold_count;

        $images = getProductImageById($product->id);

        echo "<a href='productDetail.php?id={$product->id}' class='product-link'>"; 
        echo '<div class="product-card">';

        echo "<div class='product-title'>{$name}</div>";

        echo "<div class='image-slider' data-id='{$product->id}'>";
        foreach ($images as $index => $image) {
            $activeClass = $index === 0 ? 'active' : ''; 
            echo "<img src='" . htmlspecialchars($image) . "' alt='{$name}' class='product-image {$activeClass}'>";
        }
        echo "</div>";

        echo "<div class='product-info'>";
        if ($product->discount > 0) {
            echo "<p class='original-price'><s>RM {$price}</s></p>";
            echo "<p class='discounted-price'>RM {$discountedPrice} ({$product->discount}% OFF)</p>";
        } else {
            echo "<p class='price'>RM {$price}</p>";
        }
        
        echo "<p class='sold-count'>Sold: {$soldCount}</p>";
        echo "</div>";

        echo '</div>';
        echo "</a>";

    }
}
?>
<script src="../assets/js/product.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
