<?php
require "../public/header.php";
require "../config/adminAuth.php";
include "../models/productModels.php";

if (is_post()) {
    $productName = $_POST['name'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $stock = $_POST['stock'] ?? 0;

    if ($price <= 0 || $discount < 0) {
        temp("info", "❌ Price must be positive and discount cannot be negative.");
        redirect("addProduct.php");
        exit;
    }

    $_SESSION['new_product_data'] = [
        'name' => $productName,
        'brand' => $brand,
        'price' => $price,
        'discount' => $discount,
        'stock' => $stock,
    ];

    // Step 3: Handle image uploads
    if (isset($_FILES['images'])) {
        require_once '../lib/SimpleImage.php';
        $_SESSION['new_product_images'] = [];

        if (empty($_FILES['images']['tmp_name'][0])) {
            temp("info", "❌ Please upload at least one product image.");
            redirect("addProduct.php");
            exit;
        }

        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $type = $_FILES['images']['type'][$key];
            $size = $_FILES['images']['size'][$key];
            $error = $_FILES['images']['error'][$key];
            $name = $_FILES['images']['name'][$key];

            if ($error === UPLOAD_ERR_OK) {
                if (!str_starts_with($type, 'image/')) {
                    temp('info', 'One file is not a valid image.');
                    continue;
                }

                if ($size > 1 * 1024 * 1024) {
                    temp('info', 'One image exceeded 1MB size.');
                    continue;
                }

                $newName = uniqid() . '.jpg';
                $destination = "../assets/image/uploads/$newName";

                $img = new SimpleImage();
                $img->fromFile($tmpName)
                    ->thumbnail(200, 200)
                    ->toFile($destination, 'image/jpeg');

                $_SESSION['new_product_images'][] = $destination;
            }
        }
    }

    // Insert new product into the database
    $newProductId = insertNewProduct(
        $productName,
        $brand,
        $price,
        $discount,
        $stock
    );

    // Insert product images into the database
    foreach ($_SESSION['new_product_images'] as $imgPath) {
        insertProductImage($newProductId, $imgPath);
    }

    // Clear session after done
    unset($_SESSION['new_product_data'], $_SESSION['new_product_images']);

    temp("info", "✅ Product added successfully.");
    redirect("manageProduct.php");
    exit;
}
?>

<link rel="stylesheet" href="../assets/css/editProduct.css">
<link rel="stylesheet" href="../assets/css/addProduct.css">
<?php include "../component/backButton.php"; ?>

<div class="edit-product-form">
    <h2>➕ Add New Product</h2>
    <form method="POST" enctype="multipart/form-data">

        <label>Product Name:</label>
        <input type="text" name="name" required>

        <label>Upload Product Images:</label>
        <input type="file" name="images[]" multiple accept="image/*" id="imageUpload">
        <div class="preview" id="newImagePreview"></div>
        <br>

        <label>Brand:</label>
        <select name="brand">
            <?php
            $brands = getAllBrand();
            foreach ($brands as $brand) {
                echo "<option value='$brand'>$brand</option>";
            }
            ?>
        </select>

        <label>Price:(RM)</label>
        <input type="number" name="price" step="0.01" max="20000" required>

        <label>Discount:(%)</label>
        <input type="number" name="discount" step="0.01" max="100" required>

        <label>Stock:</label>
        <input type="number" name="stock" min="0" max="10000" required>

        <br><br>
        <button type="submit" class="btn-update">Add Product</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/js/manageProduct.js"></script>

<?php include "../public/footer.php" ?>
