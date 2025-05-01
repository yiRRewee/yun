<?php
// Prevent direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php");
    exit();
}
?>

<?php
include "../config/db.php";
function logAdminEdit($email,$admin_id, $user_id, $action) {
    global $_db;
    $stm = $_db->prepare("INSERT INTO admin_user_edit_logs (email,admin_id, user_id, action) VALUES (?,?, ?, ?)");
    $stm->execute([$email,$admin_id, $user_id, $action]);
}

function logProductEdit($product_id, $admin_user_id, $changes) {
    global $_db;
    $stm = $_db->prepare("INSERT INTO admin_product_edit_logs (product_id, admin_user_id, changes) VALUES (?, ?, ?)");
    $stm->execute([$product_id, $admin_user_id, $changes]);
}

function getLogsByTable($tableName) {
    global $_db;

    $stm = $_db->prepare("SELECT * FROM $tableName ORDER BY edited_at DESC");
    $stm->execute();
    return $stm->fetchAll(PDO::FETCH_ASSOC);
}


?>