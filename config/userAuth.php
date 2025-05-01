<?php

if (!isset($_SESSION['role'])) {
    if ($role === 'admin' || $role === 'manager') {
        redirect("/public/home.php");
        exit;
    }
}

?>