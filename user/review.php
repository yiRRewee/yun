<?php 
include "../public/header.php";
include "../models/reviewModels.php";
include "../models/orderModels.php";
require "../config/userAuth.php";

$orderId = $_SESSION['order_id'] ?? null;
$userId = $_SESSION['user_id'];

if (!$orderId) {
    redirect("/public/orderHistory.php"); // or another fallback
}

$orderItems = getOrderItemsByOrderId($orderId);

if (is_post()) {
    if (isset($_POST['submit_review'])) {
        $productIds = $_POST['product_ids'];
        $ratings = $_POST['ratings'];
        $reviews = $_POST['reviews'];

        $all_photos = get_multiple_files('photos');

        foreach ($productIds as $index => $productId) {
            $rating = $ratings[$productId] ?? 0;
            $review = $reviews[$index];

            $review_id = insertReview($productId, $userId, $rating, $review);
            updateReviewed($orderId);

            if (isset($all_photos[$productId])) {
                foreach ($all_photos[$productId] as $f) {
                    $uploadError = null;

                    if (!$uploadError && !str_starts_with($f->type, 'image/')) {
                        $uploadError = 'File must be an image';
                    }
                    
                    if (!$uploadError && $f->size > 1 * 1024 * 1024) {
                        $uploadError = 'File size exceeds 1MB';
                    }

                    if ($uploadError) {
                        temp('info', $uploadError);
                        continue;
                    }

                    $photo = uniqid() . '.jpg';

                    require_once '../lib/SimpleImage.php';
                    $img = new SimpleImage();
                    $img->fromFile($f->tmp_name)
                        ->thumbnail(200, 200)
                        ->toFile("../assets/image/uploads/$photo", 'image/jpeg');

                    insertReviewImage($review_id, $photo);

                }
            }
        }

        // Clear order ID and redirect
        unset($_SESSION['order_id']);
        temp('info', '✅ Leave Review Successful!');
        redirect("/public/orderHistory.php"); // Redirect after successful review
    }
}
?>


<link rel="stylesheet" href="../assets/css/review.css">
<link rel="stylesheet" href="../assets/css/backButton.css">
<a href="/public/orderDetail.php?order_id=<?= $orderId ?>" class="back-btn">← Back</a>
</form>
<div class="container">
    <form method="post" enctype="multipart/form-data">
        <?php foreach($orderItems as $item): ?>
            <div class="review-box">
                <input type="hidden" name="product_ids[]" value="<?= $item['product_id'] ?>">

                <label>Rating (1-5):</label>
                <div class="star-rating" data-product-id="<?= $item['product_id'] ?>">
                    <input type="hidden" name="ratings[<?= $item['product_id'] ?>]" value="0">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star" data-value="<?= $i ?>">★</span>
                    <?php endfor; ?>
                </div>

                <label for="photo">Upload Some Photos?</label>
                <label class="upload" tabindex="0">Upload Image
                <input type="file" class="images-uploaded" name="photos[<?= $item['product_id'] ?>][]" accept="image/*" data-product-id="<?= $item['product_id'] ?>" multiple hidden>

                </label>
                <div class="preview" id="previewContainer_<?= $item['product_id'] ?>"></div>

                <label>Review:</label>
                <textarea placeholder="Write your review here..." name="reviews[]" required></textarea>
            </div>
        <?php endforeach; ?>
        <button type="submit" name="submit_review">Submit Review</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/js/review.js"></script>
