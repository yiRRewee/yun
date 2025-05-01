<?php
require "../models/productModels.php";
require "../helper/html_helper.php";

if (!isset($_GET['image']) || !isset($_GET['product_id'])) {
    die("Missing parameters.");
}

$imagePath = $_GET['image'];
$productId = $_GET['product_id'];

deleteProductImage($productId, $imagePath);

if (file_exists($imagePath)) {
    unlink($imagePath);
}

redirect("editProduct.php?id=$productId");
