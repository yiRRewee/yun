<?php
require "../public/header.php";
include "../models/orderModels.php";
include "../models/addressModels.php";
include "../models/productModels.php";
include "../models/reviewModels.php";

$orderId = $_GET['order_id'] ?? null;
if(empty($orderId)){
    redirect("/public/home.php");
}

$userId = $_SESSION['user_id'];
$subtotal=0;
$orderItems = getOrderItemsByOrderId($orderId);
$orderInfo = getAddressByOrderId($orderId);
$cancelReason=getCancelReasonById($orderId)??null;
$status=$orderInfo['status'];
$hasReview = hasUserLeftReview($orderId); 

if(is_post()){
    if(isset($_POST['cancel-order'])){
        if ($orderInfo['status'] === 'Unpaid'|| $role==='admin'||$role==='manager') {
            $reason = trim($_POST['reason'])??null;
            $cancelStatus="approved";
            $statusUpdate='Cancelled';
            try{
            $_db->beginTransaction();
            
            sendCancelRequest($orderId,$userId,$reason);
            updateCancelStatus($cancelStatus,$orderId);
            updateOrderStatus($statusUpdate,$orderId);
            $_db->commit();

            temp("info","✅ Order Canceleed");
            redirect();

            }catch(Exception $e){
                $_db->rollBack();

                temp('info', '❌ ' . $e->getMessage());
                redirect();
            }
        }else{
            $reason = trim($_POST['reason'])??null;
            $statusUpdate='Requested-Cancel';
            sendCancelRequest($orderId,$userId,$reason);
            temp("info","❔Request Send Successful, Waiting for approve");
            updateOrderStatus($statusUpdate,$orderId);
            redirect();
        }
    }
    if(isset($_POST['complete_order'])){
        $statusUpdate='Completed';
        updateOrderStatus($statusUpdate,$orderId);
        temp('info','✅ Order Completed');
        redirect();
    }
    if(isset($_POST['leave-review'])){
        $order_id=req('order_id');
        $_SESSION['order_id']=$order_id;
        redirect('/user/review.php');
    }
    if(isset($_POST['button-approve'])){
        $order_id=req('order_id');
        $statusUpdate='Cancelled';
        $cancelStatus='approved';
        updateCancelStatus($cancelStatus,$order_id);
        updateOrderStatus($statusUpdate,$order_id);
        temp('info','✅ Order Approved to cancel');
        redirect(); 
    }
    if(isset($_POST['button-shipped'])){
        $order_id=req('order_id');
        $statusUpdate='Shipped';
        updateOrderStatus($statusUpdate,$order_id);
        temp('info','✅ Order Shipped !');
        redirect(); 
    }
    if(isset($_POST['button-cancel'])){
        $order_id=req('order_id');
        $statusUpdate='Cancelled';
        updateOrderStatus($statusUpdate,$order_id);
        temp('info','✅ Order cancelled');
        redirect(); 
    }
}    


?>

<link rel="stylesheet" href="../assets/css/orderDetail.css">
<link rel="stylesheet" href="../assets/css/orderSummary.css">
<link rel="stylesheet" href="../assets/css/backButton.css">
<a href="/public/orderHistory.php" class="back-btn">← Back</a>

<div class="container">
    <h2>📦 Order Info</h2>
    <div class="order-info">
        <div class="order_num">
        <p><strong>Order Number:</strong> <?=$orderInfo['order_number'] ?></p>
        <p><strong>Order Date:</strong><?=$orderInfo['created_at'] ?></p>
        <?php if($cancelReason) :?>
        <p><strong>Reason Cancel:</strong><?=$cancelReason ?></p>
        <?php endif ?>
        </div>
        <div class="status">
        <p class="status-<?=$orderInfo['status'] ?>"><?=$orderInfo['status'] ?></p>
        </div>
    </div>

    <h3>📍 Shipping Address</h3>
    <div class="address-box">
        <p><strong><?=$orderInfo['full_name'] ?></strong></p>
        <p><?=$orderInfo['phone'] ?></p>
        <p><?=$orderInfo['address_line'] ?></p>
        <p><?=$orderInfo['postcode'] ?> , <?=$orderInfo['city'] ?></p>
    </div>

<div class="product-selected">
    <div class="order-summary">
        <h3>🧾 Order Summary</h3>

        <table>
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($orderItems as $item): 
                        $images = getProductImageById($item['product_id']);
                        $image = $images[0] ;
                        $price= $item['price']-($item['price'] * $item['discount']/100);
                        $totalPrice = $price * $item['quantity'];
                        $subtotal += $totalPrice;
                ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($image) ?>" alt="Product Image" class="productImage"></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>RM<?=number_format($price,2)?></td>
                        <td>RM<?= number_format($totalPrice, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php $_SESSION['subtotal'] = $subtotal;
                ?>
            </tbody>
        </table>

        <hr>

        <div class="total-count">
            <div class="subtotal">
                <span>Subtotal</span>
                <strong>RM<?= number_format($subtotal, 2) ?></strong>
            </div>
            <div class="shipping-fee">
                <span>Shipping Fees </span>
                <strong>RM10.00</strong>
            </div>
            <hr>
            <div class="grand-total">
                <span><strong>Grand Total</strong></span>
                <strong>RM<?= number_format($subtotal + 10 , 2) ?></strong>
            </div>
        </div>
    </div>

    </div>
    <?php if ($role === 'user'){
        if ($orderInfo['status'] === 'Pending'){ ?>
                <button class="button-cancel" type="button">Cancel Order</button>
        <?php }
            elseif ($orderInfo['status'] === 'Shipped'){ ?>
        <form method="POST">
            <button name="complete_order" class="button">Complete Order</button>
        </form>
    <?php }elseif($orderInfo['status'] === 'Completed'){?>
        <?php if (!$hasReview) { ?>

        <form method="POST">
            <button name="leave-review" class="button">Leave Review</button>
        </form>
        <?php } else { ?>
            <p style="color:green; font-weight:bold;">✅ You have already left a review for this order.</p>
            <?php } ?>
    <?php }
    } elseif ($role === 'admin'||$role==='manager'){ 
      if($orderInfo['status']==='Requested-Cancel'){ ?>
      <form method="POST">
            <button name="button-approve" type="submit" data-confirm="Are sure want approve this cancel request?">Approve</button>
            </form>
      <?php }  
              elseif($orderInfo['status']=== 'Pending'){ ?>
        <form method="POST">
            <button class="button-cancel" name="button-cancel" type="submit" data-confirm="Are you sure you want cancel !">Cancel Order</button>
            <button name="button-shipped" type="submit" data-confirm="Ship order ?">Shipped</button>
        </form>
        <?php }
        elseif ($orderInfo['status'] !== 'Completed' && $orderInfo['status'] !== 'Cancelled'){ ?>
        <form method="POST">
            <button class="button-cancel" name="button-cancel" type="submit" data-confirm="Are you sure you want cancel !">Cancel Order</button>
            </form>
         <?php }
    }?>
</div>


<div id="reasonForm" class="form-container">
  <h3 id="formTitle">Provide your reason for cancel order.</h3>
        <form method="post" name="reason-form">
            <label for="reason">Reason:</label>
            <input type="text" name="reason" id="reason" value="" required>

            <button type="submit" id="cancel-order" name="cancel-order" class="form-button"  data-confirm="Are you sure want cancel?">Cancel Order</button>
        </form>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/js/orderDetail.js"></script>

<?php include "../public/footer.php"; ?>
