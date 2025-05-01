<?php
// Prevent direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php");
    exit();
}
?>

<?php 
include '../config/db.php'; 
function getReviewByProductId($productId) {
    global $_db; 

    $stm = $_db->prepare("SELECT r.*, u.username FROM reviews r 
                          JOIN users u ON r.user_id = u.id 
                          WHERE r.product_id = ? 
                          ORDER BY r.created_at DESC");
    $stm->execute([$productId]);
    return $stm->fetchAll(PDO::FETCH_OBJ);
}

function insertReview($productId, $userId, $rating, $review) {
    global $_db;
    $stm = $_db->prepare("INSERT INTO reviews (product_id, user_id, rating, review_text) VALUES (?, ?, ?, ?)");
    $stm->execute([$productId, $userId, $rating, $review]);
    return $_db->lastInsertId();
}

function insertReviewImage($reviewId, $filename) {
    global $_db;
    $stm = $_db->prepare("INSERT INTO review_image (review_id, image_path) VALUES (?, ?)");
    $stm->execute([$reviewId, $filename]);
}

function getReviewImagesByReviewId($reviewId) {
    global $_db;

    $stm = $_db->prepare("SELECT image_path FROM review_image WHERE review_id = ?");
    $stm->execute([$reviewId]);
    return $stm->fetchAll(PDO::FETCH_COLUMN);  
}

function getReviewAndImageByProductId($productId) {
    $reviews = getReviewByProductId($productId);

    foreach ($reviews as $review) {
        $review->images = getReviewImagesByReviewId($review->id);  
    }

    return $reviews;
}

function getAllReviews($star) {
    global $_db;

    if($star=='all'){
    $stm = $_db->prepare("SELECT r.*, u.username FROM reviews r
                          JOIN users u ON r.user_id = u.id
                          ORDER BY r.created_at DESC");
    $stm->execute();

    }else{
        $stm = $_db->prepare("SELECT r.*, u.username FROM reviews r
        JOIN users u ON r.user_id = u.id
        WHERE rating=? 
        ORDER BY r.created_at DESC");
        $stm->execute([$star]);
    }
    $reviews = $stm->fetchAll(PDO::FETCH_OBJ);

    foreach ($reviews as $review) {
        $review->images = getReviewImagesByReviewId($review->id);
    }

    return $reviews;
}

function deleteReviewById($review_id){
    global $_db;
    $stm=$_db->prepare("DELETE FROM reviews WHERE id=?");
    $stm->execute([$review_id]);
}

function updateReviewed($order_id) {
    global $_db;
    $stm = $_db->prepare("UPDATE orders SET is_reviewed = 1 WHERE id = ?");
    return $stm->execute([$order_id]);
}

function hasUserLeftReview($orderId) {
    global $_db;
    $stm = $_db->prepare("SELECT COUNT(*) FROM orders WHERE id = ? AND is_reviewed = 1");
    $stm->execute([$orderId]);
    $count = $stm->fetchColumn();
    return $count > 0;
}

?>