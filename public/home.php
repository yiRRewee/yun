<link rel="stylesheet" href="../assets/css/home.css">
<?php
include "../config/db.php";
include "header.php";
include "../models/homeModels.php";
include "../models/productModels.php";  
include "../models/orderModels.php";


if ($role == 'admin' || $role == 'manager' ) {
$totalOrders = getTotalCountOf('orders', 'id');

$canceledOrders = getTotalCountOf('orders', 'id', 'status = "Cancelled"');

$completedOrders = getTotalCountOf('orders', 'id', 'status = "Completed"');

$pendingOrders = getTotalCountOf('orders', 'id','status = "Pending"');

$shippedOrders = getTotalCountOf('orders', 'id','status = "Shipped"');

$requestToCancel = getTotalCountOf('orders','id','status = "Requested-Cancel"');

$lowStockItems = getLowStockProducts();

if(is_post()){
    if (isset($_POST['edit_product'])) {
        $product_id = $_POST['product_id'];
        $size_id = $_POST['size_id'];
        redirect("/admin/editProduct.php?id=$product_id&size=$size_id");
    }
}

}else{
$content = getHomeScreenContent();
include 'homeScreen.php';

echo '<br><br><br>';
}


?>
<?php if ($role == 'admin' || $role == 'manager'): ?>
    <link rel="stylesheet" href="../assets/css/adminHome.css">
<link rel="stylesheet" href="../assets/css/home.css">

<div class="dashboard-container">
    <h2 class="dashboard-title">Admin Dashboard</h2>

    <div class="dashboard-top">
        <div class="card-large" data-filter="all">
            <div class="card-title">Total Orders</div>
            <div class="card-number"><?=$totalOrders ?></div>
        </div>

        <div class="card-right-stack">
            <div class="card-small" data-filter="Cancelled">
                <div class="card-title">Cancelled Orders</div>
                <div class="card-number"><?=$canceledOrders ?></div>
            </div>
            <div class="card-small" data-filter="Pending">
                <div class="card-title">Pending Orders</div>
                <div class="card-number"><?=$pendingOrders ?></div>
            </div>
        </div>
    </div>

    <div class="dashboard-bottom">
        <div class="dash-card" data-filter="Completed">
            <div class="card-title">Completed Orders</div>
            <div class="card-number"><?=$completedOrders ?></div>
        </div>
        <div class="dash-card"  data-filter="Requested-Cancel">
            <div class="card-title">Cancel Requests</div>
            <div class="card-number"><?=$requestToCancel ?></div>
        </div>
        <div class="dash-card" data-filter="Shipping">
            <div class="card-title">Shipped Orders</div>
            <div class="card-number"><?=$shippedOrders ?></div>
        </div>
    </div>
</div>


<div class="low-stock-section">
    <h3 class="section-title">📦 Low Stock Reminder</h3>
    <table class="low-stock-table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lowStockItems as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $item['stock'] ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                        <button type="submit" class="low-stock-btn" name="edit_product">Manage</button>          
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php else: ?>
    <h2>Shop By Category</h2>

<div class="image-grid">
    <div class="grid-item">
        <a href="/user/shop.php?brand=14">
            <img src="../assets/image/logo/nike-poster.jpg" alt="nike">
        </a>
    </div>
    <div class="grid-item">
        <a href="/user/shop.php?brand=15">
            <img src="../assets/image/logo/adidas-poster1.png" alt="adidas">
        </a>
    </div>
</div>

<?php endif ?>
<?php include "../public/footer.php"; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/js/adminHome.js"></script>
