<?php
// Prevent direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php");
    exit();
}
?>

<?php
include "../config/db.php";

function getAllCartProductByIdAndSearch($userId, $search = '') {
    global $_db;
    $query = "SELECT 
        c.id AS cart_id,
        p.id AS product_id, 
        p.name, 
        p.price, 
        p.discount, 
        (p.price - (p.price * (p.discount / 100))) AS final_price, 
        c.quantity, 
        p.stock AS available_stock
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?";

    $params = [$userId];

    if (!empty($search)) {
        $query .= " AND p.name LIKE ?";
        $params[] = "%$search%";
    }

    $query .= " ORDER BY c.id DESC"; 

    $stm = $_db->prepare($query);
    $stm->execute($params);
    return $stm->fetchAll(PDO::FETCH_ASSOC);
}

function getCartItem($userId, $productId) {
    global $_db;
    $stm = $_db->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stm->execute([$userId, $productId]);
    return $stm->fetch(PDO::FETCH_ASSOC); 
}

function addToCart($userId, $productId, $quantity) {
    global $_db;

    $cartItem = getCartItem($userId, $productId);

    if ($cartItem) {
        $newQuantity = $cartItem['quantity'] + $quantity;
        $stm = $_db->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stm->execute([$newQuantity, $cartItem['id']]);
    } else {
        $stm = $_db->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stm->execute([$userId, $productId, $quantity]);
    }
}

function removeFromCart($cartId){
    global $_db;
    $stm = $_db->prepare("DELETE FROM cart WHERE id=?");
    $stm->execute([$cartId]);
}

function deleteCartItems($cartIds) {
    global $_db;
    
    $placeholders = implode(',', array_fill(0, count($cartIds), '?'));
    $stm = $_db->prepare("DELETE FROM cart WHERE id IN ($placeholders)");
    $stm->execute($cartIds);
}

function updateCartQuantity($quantity, $cartId){
    global $_db;
    $stm = $_db->prepare("UPDATE cart SET quantity = quantity + ? WHERE id=?");
    $stm->execute([$quantity, $cartId]);
}

function getStockAndQuantityByCartId($cartId){
    global $_db;
    $stm = $_db->prepare("SELECT c.quantity, p.stock FROM cart c JOIN products p ON c.product_id = p.id WHERE c.id = ?");
    $stm->execute([$cartId]);
    return $stm->fetch(PDO::FETCH_ASSOC);
}

function getQuantityByCartId($cartId){
    global $_db;
    $stm = $_db->prepare("SELECT quantity FROM cart WHERE id = ?");
    $stm->execute([$cartId]);
    return $stm->fetchColumn();
}

function getAllItemByCartId($cartIds){
    global $_db;
    $placeholders = implode(',', array_fill(0, count($cartIds), '?'));
    $stm = $_db->prepare("SELECT * FROM cart 
                          JOIN products ON cart.product_id = products.id
                          WHERE cart.id IN ($placeholders)");
    $stm->execute($cartIds);
    return $stm->fetchAll(PDO::FETCH_ASSOC);
}

function getCartItemByUserProduct($userId, $productId) {
    global $_db;
    $stm = $_db->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stm->execute([$userId, $productId]);
    return $stm->fetch(PDO::FETCH_ASSOC);
}

function updateCartQuantityTo($quantity, $cartId) {
    global $_db;
    $stm = $_db->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stm->execute([$quantity, $cartId]);
}

?>
