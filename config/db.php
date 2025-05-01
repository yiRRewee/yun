<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
// Prevent direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php");
    exit();
}

try{
    $_db=new PDO('mysql:dbname=phpassignment','root','',[
    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,   
    ]);
}catch(PDOException $e){
    die("Database connection Failed ".$e->getMessage());
}

?>
