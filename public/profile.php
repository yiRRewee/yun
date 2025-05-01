<?php
require_once "../config/db.php";
include "header.php"; 

if (!isset($_SESSION['user_id'])) {
    redirect("/public/login.php");
}

$user_id = $_SESSION['user_id'];

if (is_post() && isset($_POST['verify'])) {
    $stored_hash = getUserPasswordById($user_id);
    $input_hash = sha1($_POST['verify_password']??'');

    if ($input_hash === $stored_hash->password) {
        temp("info","✅ Password Correct !");
        redirect("edit_profile.php");
    }else{
        temp("info","❌ Password Incorect");
    }
}

// 获取用户基本信息（不包括地址）
$query = $_db->prepare("SELECT * FROM users WHERE id = :user_id");
$query->execute([':user_id' => $user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

// 如果用户不存在，跳转到错误页面
if (!$user) {
    die("User not found.");
}

// 获取用户的地址信息
$address_query = $_db->prepare("SELECT * FROM address WHERE user_id = :user_id");
$address_query->execute([':user_id' => $user_id]);
$address = $address_query->fetch(PDO::FETCH_ASSOC);

// 如果没有找到地址，提供默认值
if (!$address) {
    $user_address = 'No address provided.';
} else {
    // 拼接地址格式
    $user_address = $address['address_line'] . ', ' . $address['city'] . ' ' . $address['postcode'];
}

// 处理 profile_image 路径，确保正确
$profile_image = !empty($user['profile_image']) ? htmlspecialchars($user['profile_image']) : "../assets/image/logo/default.png";
?>

<link rel="stylesheet" href="../assets/css/profile.css">
<body>
    <div class="profile-container">
        <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
        <div class="profile-card">
            <img src="<?php echo $profile_image; ?>" alt="Profile Picture" class="profile-pic">
            <div class="profile-info">
                <div class="info-item">
                    <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
                </div>
                <div class="info-item">
                    <strong>Phone:</strong> <?php echo htmlspecialchars($user['phone_num']); ?>
                </div>
                <div class="info-item">
                    <strong>Address:</strong><a href="/component/address.php" class="manage-address">Manage Address</a>
                </div>

                <div class="buttons">
                        <button type="button" class="btn" id="edit">Edit Profile</button>

                    <a href="logout.php" class="btn">Logout</a>
                </div>

            </div>
        </div>
    </div>
</body>


<div id="password-popup" style="display:none;">
    <div id="popup-box">
        <h3>Enter Password to Edit Profile</h3>
        <form id="verify-form" method="POST">
            <input type="password" name="verify_password" placeholder="Enter password" class="input-password" required><br><br>
            <button name="verify" class="button-password">Verify</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/js/profile.js"></script>

<?php include "../public/footer.php"; ?>
