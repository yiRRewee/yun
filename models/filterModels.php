<?php
// Prevent direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php");
    exit();
}
?>

<?php
include "../config/db.php";

function getFilterType($type) {
    global $_db;

    $stm = $_db->prepare("SELECT id,name FROM categories WHERE type = 'all' or type=?");
    $stm->execute([$type]);
    
    return $stm->fetchAll(PDO::FETCH_KEY_PAIR); 
}

?>
