<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php");
    exit();
}
?>

<?php
include '../config/db.php';
include '../helper/dbHelper.php';

function getAllUserAddressesById($user_id) {
    global $_db;
    $stm = $_db->prepare("SELECT * FROM address WHERE user_id = ? ORDER BY is_default DESC, address_id DESC");
    $stm->execute([$user_id]);
    return $stm->fetchAll(PDO::FETCH_ASSOC);
}

function getUserAddressesById($address_id) {
    global $_db;
    $stm = $_db->prepare("SELECT * FROM address WHERE address_id = ?");
    $stm->execute([$address_id]);
    return $stm->fetch(PDO::FETCH_ASSOC);
}

function addAddress($user_id, $full_name, $address_line, $city, $postcode, $phone) {
    global $_db;
    $table='address';
    $field='user_id';
    
    if(!is_exists($user_id,$table,$field)){
        $is_default = 1 ;
    }else{
        $is_default = 0 ;
    }

    $stm = $_db->prepare("INSERT INTO address (user_id, full_name, address_line, city, postcode, phone, is_default) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stm->execute([$user_id, $full_name, $address_line, $city, $postcode, $phone, $is_default]);
}

function updateAddress($address_id, $full_name, $address_line, $city, $postcode, $phone) {
    global $_db;
    $stm = $_db->prepare("UPDATE address SET full_name = ?, address_line = ?, city = ?, postcode = ?, phone = ? WHERE address_id = ?");
    $stm->execute([$full_name, $address_line, $city, $postcode, $phone, $address_id]);
}

function deleteAddress($address_id) {
    global $_db;
    $stm = $_db->prepare("DELETE FROM address WHERE address_id = ?");
    $stm->execute([$address_id]);
}

function setDefaultAddress($user_id, $address_id) {
    global $_db;
    
    $stm=$_db->prepare("UPDATE address SET is_default = 0 WHERE user_id = ?");
    $stm->execute([$user_id]);

    $stm=$_db->prepare("UPDATE address SET is_default = 1 WHERE address_id = ?");
    $stm->execute([$address_id]);
}

function getDefaultAddress($user_id){
    global $_db;

    $stm = $_db->prepare("SELECT * FROM address WHERE user_id = ? AND is_default = 1");
    $stm->execute([$user_id]);
    return $stm->fetch(PDO::FETCH_ASSOC);
}

function getAddressById($address_id) {
    global $_db;
    $stm = $_db->prepare("SELECT * FROM address WHERE address_id = ?");
    $stm->execute([$address_id]);
    return $stm->fetch(PDO::FETCH_ASSOC);
}


?>
