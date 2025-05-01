<?php 
include '../public/header.php'; 
include '../models/reviewModels.php';
require "../config/adminAuth.php";

$star = $_GET['star'] ?? 'all';

$reviews = getAllReviews($star);

if(is_post()){
    if(isset($_POST['delete-review'])){
        $review_id=$_POST['review_id'];
        deleteReviewById($review_id);
        temp("info","✅ Delete Successful");
        redirect();
    }
}
?>

<link rel="stylesheet" href="../assets/css/reviews.css">
<link rel="stylesheet" href="../assets/css/productDetail.css">

<h2>Customer Reviews</h2>

<div class="star">
    <a href="?star=all"><b>ALL</b></a> |
    <a href="?star=1">⭐</a> |
    <a href="?star=2">⭐⭐</a> |
    <a href="?star=3">⭐⭐⭐</a> |
    <a href="?star=4">⭐⭐⭐⭐</a> |
    <a href="?star=5">⭐⭐⭐⭐⭐</a> |
</div>

<div class="review-container">

<div class="nav-content" id="reviews">
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
                <p class="post-time">Posted on <?= $review->created_at ?></p>
                <form method="POST">
                    <input type="hidden" name="review_id" value="<?= $review->id ?>">
                    <button type="submit" class="delete-btn" name="delete-review" data-confirm="Are You Sure Want Delete This Review?">Delete Review</button>
                </form>
                <br><hr>
                            <?php endforeach; ?>
                     <?php else : ?>
                        <p>Opps! No comment</p>
                    <?php endif; ?>
            </div>
        </div>

</div>  

<?php include "../public/footer.php" ?>