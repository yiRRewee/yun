<?php
require "../public/header.php";
include "../models/productModels.php"; 
include "../helper/dbHelper.php"; 
require "../config/adminAuth.php";

$selectedBrand = $_GET['brand'] ?? 'all';
$search = $_GET['search'] ?? '';  
$sortOption = $_GET['sort'] ?? '';

$products = getFilteredProducts($selectedBrand, $sortOption, $search);

if(is_post()){
    if (isset($_POST['edit_product'])) {
        $product_id = $_POST['product_id'];
        redirect("editProduct.php?id=$product_id");
    }
    if (isset($_POST['delete_product'])) {
        $product_id = $_POST['product_id'];
        deleteProduct($product_id);
        temp("info","✅ Product Deleted Successfully");
        redirect();
    }
}
?>

<link rel="stylesheet" href="../assets/css/adminProduct.css">

<div class="product-management-container">
    <h2>🛍️ Manage Products</h2>

    <div class="top-bar">
        <div class="too-bar-btn">
            <div>
                <button class="btn-filter">Filter</button>
            </div>
            <form action="addProduct.php">
                <button class="btn-filter">Add New Product</button>
            </form>
        </div>

        <form method="GET" class="search-form">
            <?php html_search('search', 'Search by product name...', "class='search-input'"); ?>
            <button type="submit" class="btn-search">Search</button>
        </form>
    </div>

    <div id="filterForm" class="filter-popup" style="display:none;">
        <h3>Filter Products</h3>
        <form method="GET">
            <label>Brand:</label>
            <select name="brand">
                <option value="all">All</option>
                <?php
                $brands = getAllBrand(); 
                foreach ($brands as $brand) {
                    echo "<option value='$brand' ".(($_GET['category'] ?? '') == $brand ? 'selected' : '').">$brand</option>";
                }
                ?>
            </select>
            <label>Sort by:</label>
            <select name="sort">
                <option value="">Sort By</option>
                <option value="stock_asc">Stock: Low to High</option>
                <option value="stock_desc">Stock: High to Low</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
                <option value="discount_desc">Highest Discount</option>
            </select>

            <button type="submit" class="btn-apply">Apply Filters</button>
        </form>
    </div>

    <table class="product-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Sold Count</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><img src="<?=$product['image_path'] ?>" alt="Product Image" class="product-img"></td>
                <td><?= $product['name'] ?></td>
                <td><?= $product['category'] ?></td>
                <td>RM<?= number_format($product['price'], 2) ?></td>
                <td><?= $product['discount'] ?>%</td>
                <td><?= $product['sold_count'] ?></td>
                <td><?= $product['stock'] ?></td>
                <td>
                    <form method="post">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <button type="submit" name="edit_product" class="btn-edit">Edit</button>
                    <button type="submit" name="delete_product" class="btn-delete" data-confirm="Are You Sure Delete This Product❔">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include "../public/footer.php" ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/js/adminProduct.js"></script>
