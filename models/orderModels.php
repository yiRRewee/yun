<?php
// Prevent direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php");
    exit();
}
?>

<?php
function generateOrderNumber() {
    global $_db;

    $today = date('Ymd'); 

    $stm = $_db->prepare("SELECT COUNT(*) FROM orders WHERE DATE(created_at) = CURDATE()");
    $stm->execute();
    $orderCount = $stm->fetchColumn() + 1;

    $orderNumber = 'ORD-' . $today . '-' . str_pad($orderCount, 4, '0', STR_PAD_LEFT);
    
    return $orderNumber;
}

function addNewOrder($userId, $totalPrice, $orderNumber, $address) {
    global $_db;

    $stm = $_db->prepare("INSERT INTO orders (user_id, full_name, address_line, city, postcode, phone, total_price, status, order_number) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', ?)");

    $stm->execute([
        $userId,
        $address['full_name'],
        $address['address_line'],
        $address['city'],
        $address['postcode'],
        $address['phone'],
        $totalPrice,
        $orderNumber
    ]);

    return $_db->lastInsertId();
}

function addOrderItems($orderId, $items) {
    global $_db;
    
    foreach ($items as $item) {
        $stm = $_db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_checkout) VALUES (?, ?, ?, ?)");
        $stm->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
    }
}

function getOrdersByUser($userId, $status = 'all',$search) {
    global $_db;

    if ($status === 'all') {
        if (!empty($search)) {
            $stm = $_db->prepare("SELECT * 
            FROM orders 
            WHERE user_id = ? 
            AND order_number LIKE ? 
            ORDER BY created_at DESC");
$stm->execute([$userId, "%$search%"]);
        }else{
        $stm = $_db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stm->execute([$userId]);
        }
    } else {
        if (!empty($search)) {
            $stm = $_db->prepare("SELECT * 
            FROM orders 
            WHERE user_id = ? 
            AND status = ? 
            AND order_number LIKE ? 
            ORDER BY created_at DESC");
            $stm->execute([$userId, $status, "%$search%"]);
        }else{
        $stm = $_db->prepare("SELECT * FROM orders WHERE user_id = ? AND status = ? ORDER BY created_at DESC");
        $stm->execute([$userId, $status]);
        }
    }

    return $stm->fetchAll(PDO::FETCH_ASSOC);
}

function getAddressByOrderId($orderId){
    global $_db;
    $stm=$_db->prepare("SELECT * FROM orders WHERE id=?");
    $stm->execute([$orderId]);
    return $stm->fetch(PDO::FETCH_ASSOC);
}

function getOrderItemsByOrderId($orderId){
    global $_db;
    $stm=$_db->prepare("SELECT * FROM order_items 
                          JOIN products ON order_items.product_id = products.id 
                          WHERE order_items.order_id =?");
    $stm->execute([$orderId]);
    return $stm->fetchAll(PDO::FETCH_ASSOC);
}

function sendCancelRequest($orderId,$user_id,$reason){
    global $_db;
    $stm=$_db->prepare("INSERT INTO order_cancel (order_id,user_id,reason,status) VALUES(?,?,?,'Requested-Cancel')");
    $stm->execute([$orderId,$user_id,$reason]);
    $status='Requested-Cancel';
    updateCancelStatus($status,$orderId);
}

function updateCancelStatus($status,$orderId){
    global $_db;
    $stm=$_db->prepare("UPDATE order_cancel SET status=? WHERE order_id=?");
    $stm->execute([$status,$orderId]);
}

function updateOrderStatus($status,$orderId){
    global $_db;
    $stm=$_db->prepare("UPDATE orders SET status=? WHERE id=?");
    $stm->execute([$status,$orderId]);
}

function getCancelReasonById($orderId){
    global $_db;
    $stm=$_db->prepare("SELECT reason FROM order_cancel WHERE order_id=?");
    $stm->execute([$orderId]);
    return $stm->fetchColumn();
}

function getProductsFromOrder($orderId, $userId) {
    global $_db;
    $stm = $_db->prepare("SELECT oi.product_id, p.name, oi.size_id
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            JOIN orders o ON oi.order_id = o.id
            WHERE oi.order_id = ? AND o.user_id = ?");
    $stm->execute([$orderId, $userId]);
    return $stm->fetchAll(PDO::FETCH_ASSOC);
}


//Admin //////////////////////////////////////////////////
function getTotalCountOf($table, $column, $condition = null) {
    global $_db;
    
    $query = "SELECT COUNT(*) AS total FROM $table WHERE $column IS NOT NULL";
    
    if ($condition) {
        $query .= " AND $condition";
    }

    $stm = $_db->prepare($query);
    $stm->execute();
    
    $result = $stm->fetch(PDO::FETCH_OBJ);
    
    return $result->total ?? 0; 
}

function getAllOrders( $status = 'all',$search='') {
    global $_db;

    if ($status === 'all') {
        if(!empty($search)){
            $stm = $_db->prepare("SELECT o.*, u.username 
                                  FROM orders o 
                                  JOIN users u ON o.user_id = u.id 
                                  WHERE o.order_number LIKE ? OR u.username LIKE ? 
                                  ORDER BY created_at DESC");
            $stm->execute(["%$search%", "%$search%"]);
        }
        else{
        $stm = $_db->prepare("SELECT o.*,u.username FROM orders o JOIN users u ON o.user_id=u.id ORDER BY created_at DESC");
        $stm->execute();
        }
    } else {
        if(!empty($search)){
            $stm = $_db->prepare("SELECT o.*, u.username 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.status = ? 
            AND (o.order_number LIKE ? OR u.username LIKE ?) 
            ORDER BY created_at DESC");
            
            $stm->execute([$status, "%$search%", "%$search%"]);
        }else{
            $stm = $_db->prepare("SELECT o.*,u.username FROM orders o JOIN users u ON o.user_id=u.id WHERE status = ? ORDER BY created_at DESC");
        $stm->execute([$status]);
        }
    }

    return $stm->fetchAll(PDO::FETCH_ASSOC);
}

?>