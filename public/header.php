<?php
session_start();
require "../helper/html_helper.php";
require "../models/userModels.php"; 

if (isset($_SESSION['user_id']) && !isset($_SESSION['role'])) {
    $_SESSION['role'] = getUserRole($_SESSION['user_id']);
}

$role = $_SESSION['role'] ?? null;
$profileImagePath = '/assets/image/logo/default.png'; 

if (isset($_SESSION['user_id'])) {
    $profileImage = getUserImageById($_SESSION['user_id']);

    if ($profileImage && !empty($profileImage->profile_image)) {
        $profileImagePath = htmlspecialchars($profileImage->profile_image);
    }
}
$results = [];

if (!empty($_GET['query'])) {
    $query   = trim($_GET['query']);
    $results = searchProducts($query);
}
?>
<link rel="icon" href="../assets/image/logo/JX_Favicon.png">
<link rel="stylesheet" href="../assets/css/header.css"> 
<title>BLOOM CHARM</title>

<?php if ($role == 'admin' || $role == 'manager'): ?>
    <nav class="nav-container">
        <div class="nav-left">
            <a href="/public/home.php">
                <img src="../assets/image/logo/logo3.png" alt="Logo" class="logo">
            </a>
        </div>

        <div class="nav-center">
            <a href="/public/home.php">Dashboard</a> 
            <div class="dropdown">
                <a href="#">Manage ▼</a>
                <div class="dropdown-content">
                <a href="/admin/manageProduct.php">Products</a> 
                <a href="/admin/manageUser.php">Users</a> 
                </div>
            </div>
            <a href="/public/orderHistory.php">Order</a> 
            <div class="dropdown">
                <a href="#">Others ▼</a>
                <div class="dropdown-content">
                <a href="/admin/reviews.php">Reviews</a> 
                <a href="/admin/homePosterChange.php">Home Poster</a> 
                <a href="/admin/adminLogs.php">Logs</a> 
                <?php if ($role == 'manager'): ?>
                    <a href="/admin/adminReport.php">Report</a>
                <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="nav-right" id="admin_nav_right">
        <div class="dropdown">
                <a href="#">
                <img src="<?= htmlspecialchars($profileImage) ?>" alt="profile" class="profile-img">
                </a>
                <div class="dropdown-content">
                    <a href="/public/profile.php">Profile</a>  
                    <a href="/public/logout.php">Log Out</a> 
                </div>
            </div>
        </div>
    </nav>
<?php elseif ($role == 'user'): ?>
    <nav class="nav-container">
        <div class="nav-left">
            <a href="/public/home.php">
                <img src="../assets/image/logo/logo3.png" alt="Logo" class="logo">
            </a>   
        </div>

        <div class="nav-center">
            <a href="/public/home.php">Home</a> 
            <div class="dropdown">
                <a href="../user/shop.php">Shop ▼</a> 
                <div class="dropdown-content">
                   <a href="../user/shop.php?brand=14">Bouquet</a>
                   <a href="../user/shop.php?brand=15">Key Chain</a>
                </div>
            </div>
            <a href="../user/shop.php?promo=1#">Promotion</a> 
        </div>

        <div class="nav-right">
        <a href="#" id="search-trigger">
            <img src="../assets/image/logo/search.png" alt="Search" class="search-icon">
            </a>
        <div id="search-popup" class="search-popup-user">
            <form action="../user/shop.php" method="GET">
            <?=html_search("name","Search products...")?>
            <button type="submit">Go</button>
            </form>
        </div>
            <div class="dropdown">
                <a href="#">
                <img src="<?= htmlspecialchars($profileImage) ?>" alt="profile" class="profile-img">
                </a>
                <div class="dropdown-content">
                    <a href="/public/profile.php">Profile</a> 
                    <a href="/public/orderHistory.php">Order</a> 
                    <a href="/public/logout.php">Log Out</a> 
                </div>
            </div>
            <a href="../user/cart.php">
                <img src="../assets/image/logo/cart.png" alt="cart" class="cart-img">
            </a>
        </div>
    </nav>

<?php else: ?>
    <nav class="nav-container">
        <div class="nav-left">
            <a href="/public/home.php">
                <img src="../assets/image/logo/logo3.png" alt="Logo" class="logo">
            </a>
        </div>
        <div class="nav-center">
            <a href="/public/home.php">Home</a> 
            <div class="dropdown">
                <a href="../user/shop.php">Shop ▼</a> 
                <div class="dropdown-content">
                <a href="../user/shop.php?brand=14">Bouquet</a>
                <a href="../user/shop.php?brand=15">Key Chain</a>
                </div>
            </div>
            <a href="../user/shop.php?promo=1">Promotion</a> 
        </div>
        <div class="nav-right">
            <a href="#" id="search-trigger">
            <img src="../assets/image/logo/search.png" alt="Search" class="search-icon">
            </a>
        <div id="search-popup" class="search-popup">
            <form action="../user/shop.php" method="GET">
            <?=html_search("name","Search products...")?>
            <button type="submit">Go</button>
            </form>
        </div>
            <a href="/public/login.php">Login</a>
            <a href="/public/register.php">Register</a>
        </div>
    </nav>
<?php endif; ?>
<div id="info"><?= temp('info') ?></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/js/search.js"></script>
