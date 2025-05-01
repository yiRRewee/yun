<?php
require "header.php";
include "../models/orderModels.php";

$searchQuery = $_GET['search'] ?? '';
$userId = $_SESSION['user_id'];
$statusFilter = $_GET['status'] ?? 'all';
if ($role == 'admin' || $role == 'manager' ) {
    $orders = getAllOrders($statusFilter, $searchQuery);
} else {
    $orders = getOrdersByUser($userId, $statusFilter, $searchQuery);
}


?>

<link rel="stylesheet" href="../assets/css/orderHistory.css">

<div class="order-header">
<h2>📦 Orders</h2>
        <form method="GET" class="order-search-form">
        <?php html_search('search', ($role == 'admin' || $role=='manager' ? 'Search Orders Id...' : 'Search your orders Id...'), 'class="order-search"'); ?>
        <button type="submit" class="order-search-btn">Search</button>
        </form>
</div>


<div class="status-filter">
    <a href="?status=all">All</a> |
    <a href="?status=Pending">Pending</a> |
    <a href="?status=Shipped">Shipped</a> |
    <a href="?status=Completed">Completed</a> |
    <a href="?status=Cancelled">Cancelled</a> |
    <a href="?status=Requested-Cancel">Request Cancel</a>
</div>

<table class="order-history">
    <thead>
        <tr>
            <th>Order ID #</th>
            <?php if($role == 'admin' || $role== 'manager'): ?>
                <th>User Name </th>
                <?php endif ?>
            <th>Order Date</th>
            <th>Total</th>
            <th>Status</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($orders as $order): ?>
        <tr>
            <td><?= htmlspecialchars($order['order_number']) ?></td>
            <?php if($role == 'admin' || $role== 'manager'): ?>
            <td><?= htmlspecialchars($order['username']) ?></td>
            <?php endif ?>
            <td><?= htmlspecialchars($order['created_at']) ?></td>
            <td>RM<?= number_format($order['total_price'], 2) ?></td>
            <td><?= ucfirst($order['status']) ?></td>
            <td><a href="orderDetail.php?order_id=<?= $order['id'] ?>">View Details</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include "../public/footer.php"; ?>
