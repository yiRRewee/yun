<?php
require "../public/header.php";
include "../models/productModels.php";
include "../models/logModels.php";
require "../config/adminAuth.php";

if (!isset($_GET['id'])) {
    echo "<p>❌ Product ID not found.</p>";
    exit;
}

$productId = $_GET['id'];
$product = getProductToEditById($productId);
$productImages=getProductImageById($productId);

if (!$product) {
    echo "<p>❌ Product not found.</p>";
    exit;
}

if (is_post()) {
        $productName = $_POST['name'];
        $brand = $_POST['brand'];
        $price = $_POST['price'];
        $discount = $_POST['discount'];
        $stock = $_POST['stock'];

        if ($price < 0 || $discount < 0 || $stock < 0) {
            temp("info", "❌ Price, discount, and stock must be positive numbers.");
            redirect("editProduct.php?id=$productId&size=$sizeId");
            exit;
        }

        
        $existingImagesFromForm = $_POST['existing_images'] ?? [];

        $imagesInDb = getProductImageById($productId); 

        foreach ($imagesInDb as $imgPath) {
            if (!in_array($imgPath, $existingImagesFromForm)) {
                deleteProductImage($productId, $imgPath); 
                if (file_exists($imgPath)) {
                    unlink($imgPath); 
                }
            }
        }
        if (isset($_FILES['images'])) {
        require_once '../lib/SimpleImage.php';

        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $type = $_FILES['images']['type'][$key];
            $size = $_FILES['images']['size'][$key];
            $error = $_FILES['images']['error'][$key];
            $name = $_FILES['images']['name'][$key];

            // Validate
            if ($error === UPLOAD_ERR_OK) {
                if (!str_starts_with($type, 'image/')) {
                    temp('info', 'One file is not a valid image.');
                    continue;
                }

                if ($size > 1 * 1024 * 1024) {
                    temp('info', 'One image exceeded 1MB size.');
                    continue;
                }

                // Save image
                $newName = uniqid() . '.jpg';
                $destination = "../assets/image/uploads/$newName";

                $img = new SimpleImage();
                $img->fromFile($tmpName)
                    ->thumbnail(200, 200)
                    ->toFile($destination, 'image/jpeg');

                    if (!in_array($destination, $imagesInDb)) {
                        insertProductImage($productId, $destination);
                    }
            }
        }
    }
    updateProduct($productId,  $productName,  $price,$discount,$brand,$stock );
    
    $changes = [];

    if ($productName !== $product['name']) {
        $changes[] = "Name: {$product['name']} → $productName";
    }
    if ($brand !== $product['brand']) {
        $changes[] = "Brand: {$product['brand']} → $brand";
    }
    if ($price != $product['price']) {
        $changes[] = "Price: RM{$product['price']} → RM$price";
    }
    if ($discount != $product['discount']) {
        $changes[] = "Discount: {$product['discount']}% → {$discount}%";
    }
    if ($stock != $product['stock']) {
        $changes[] = "Stock: {$product['stock']} → $stock";
    }
    
    if (!empty($changes)) {
        $admin_user_id = $_SESSION['user_id']; 
        logProductEdit($productId, $admin_user_id, implode(", ", $changes));
    }
    

    temp("info", "✅ Product Updated Successfully");
    redirect("manageProduct.php");
}
?>

<link rel="stylesheet" href="../assets/css/editProduct.css">
<?php include "../component/backButton.php"; ?>

<div class="edit-product-form">
    <h2>✏️ Edit Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

        <label>Product Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label>Upload New Image:</label>
        <input type="file" name="images[]" multiple accept="image/*" id="imageUpload">
        <div class="preview" id="newImagePreview"></div>
        <br>

        <label>Existing Image:</label>
        <div class="image-preview">
        <?php foreach($productImages as $productImage): ?>
        <div class="image-preview-box">
        <input type="hidden" name="existing_images[]" value="<?= $productImage ?>">
            <img src="<?= $productImage ?>" alt="Current Image" class="product-image">
            <a href="deleteImage.php?image=<?= $productImage ?>&product_id=<?= $productId ?>" 
            data-confirm="Delete this image?"
               class="delete-image-btn">X</a>
        </div>  
    <?php endforeach; ?>
</div>

        <label>Brand:</label>
        <select name="brand">
            <?php
            $brands = getAllBrand();
            foreach ($brands as $brand) {
                $selected = $product['brand'] == $brand ? 'selected' : '';
                echo "<option value='$brand' $selected>$brand</option>";
            }
            ?>
        </select>

        <label>Price:(RM)</label>
        <input type="number" name="price" step="0.01" min="0" max="20000" value="<?= $product['price'] ?>" required>

        <label>Discount:(%)</label>
        <input type="number" name="discount" step="0.01" min="0" max="100" value="<?= $product['discount'] ?>" required>

        <label>Stock:</label>
        <input type="number" name="stock" min="0" max="10000" value="<?= $product['stock'] ?>" required>

        <br><br>
        <button type="submit" class="btn-update">Update Product</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/js/manageProduct.js"></script>

