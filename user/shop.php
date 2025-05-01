<?php 
include '../public/header.php';
include '../models/filterModels.php';
require "../config/userAuth.php";

$categoryOptions = getFilterType('category');
$priceOptions = ['Lower Price First', 'Higher Price First'];
$promoOptions=['All','Promotion']; 

?>
<link rel="stylesheet" href="../assets/css/shop.css">
<link rel="stylesheet" href="../assets/css/radioButton.css">

<div class="container">
    <div class="left-side">

    <div class="filter-container">
    <h3>Find Your Product Using Filter !</h3>

    <form method="GET">
        <h4>Category</h4>
        <div class="radio_label">
            <?= html_radios('brand', $categoryOptions, true); ?>
        </div>
        <h4>Price</h4>
        <div class="radio_label">
            <?= html_radios('price', $priceOptions, true); ?>
        </div>
        <h4>Promotion</h4>
        <div class="radio_label">
            <?= html_radios('promo', $promoOptions, true); ?>
        </div>
        <br>
        <button type="submit">Apply Filters</button>
    </form>
</div>
    </div>
    
    <div class="right-side">
        <?php
        include '../models/productModels.php'; 
        include '../component/productDisplay.php'; 

        $searchQuery = req('name', ''); 
        $selectedSexId = req('sex', '7');  // Default to "All" (ID 7)
        $selectedBrandId = req('brand', '7');  // Default to "All" (ID 7)
        $selectedPriceOrderId = req('price', ''); 
        $isPromo = req('promo', '7');

        if (!empty($searchQuery)) {
            $products = searchProducts($searchQuery); 
        } else {
            $products=filterProducts($selectedSexId, $selectedBrandId, $selectedPriceOrderId,$isPromo);
        }

        if (empty($products)){
           echo '<div class="product-not-found">
                No products found. 
            </div>';
        }else{ 
            displayProductList($products); 
        }
        ?>
    </div>
</div>


<?php include "../public/footer.php"; ?>
