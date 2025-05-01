<?php
if (!isset($_SESSION['role'])) {
    if($_SESSION['role'] !== 'admin' || $_SESSION['role'] !== 'manager'){
    header("Location: /public/home.php");
    }
}

?>