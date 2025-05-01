<?php 
include "header.php"; 
require_once "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $_db->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    die("User not found.");
}

// Fetch default address
$addrStmt = $_db->prepare("SELECT * FROM address WHERE user_id = :user_id AND is_default = 1 LIMIT 1");
$addrStmt->execute([':user_id' => $user_id]);
$addr = $addrStmt->fetch(PDO::FETCH_ASSOC);

$address_line = $addr['address_line'] ?? '';
$city         = $addr['city'] ?? '';
$postcode     = $addr['postcode'] ?? '';
$full_name    = $addr['full_name'] ?? '';
$phone        = $addr['phone'] ?? '';

$states = ["Perlis", "Kedah", "Kelantan", "Terrengganu", "Pahang", "Johor", "Melaka", "Negeri Sembilan", "Putrajaya", "Selangor", "Perak", "Pulau Pinang", "Sarawak", "Sabah"];

$field_errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username         = trim($_POST['username']);
    $email            = trim($_POST['email']);
    $new_phone        = trim($_POST['phone_num']);
    $full_name        = trim($_POST['username']);
    $address_line     = trim($_POST['address_line']);
    $city             = trim($_POST['city']);
    $postcode         = trim($_POST['postcode']);
    $password_raw     = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // VALIDATION
    if (empty($username)) {
        $field_errors['username'] = "Username is required.";
    }

    if (empty($email)) {
        $field_errors['email'] = "Email is required.";
    }  elseif (!preg_match('/^[^@]+@(gmail\.com|yahoo\.com|hotmail\.com|outlook\.com|live\.com|icloud\.com|aol\.com)$/i', $email)) {
        $field_errors['email']= "Only emails from gmail.com, yahoo.com, hotmail.com, outlook.com, live.com, icloud.com, or aol.com are allowed.";
    }

    if (empty($new_phone)) {
        $field_errors['phone_num'] = "Phone number is required.";
    } elseif (!preg_match('/^[0-9]{10,11}$/', $new_phone)) {
        $field_errors['phone_num'] = "Phone must be 10 or 11 digits.";
    }

    if (empty($address_line)) {
        $field_errors['address_line'] = "Address line is required.";
    }

    if (empty($city)) {
        $field_errors['city'] = "City is required.";
    }

    if (empty($postcode)) {
        $field_errors['postcode'] = "Postcode is required.";
    } elseif (!preg_match('/^[0-9]{5}$/', $postcode)) {
        $field_errors['postcode'] = "Postcode must be exactly 5 digits.";
    }

    if (!empty($password_raw)) {
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
        if (!preg_match($pattern, $password_raw)) {
            $field_errors['password'] = 
                "Password must be at least 8 characters and include:<br>"
              . "- one uppercase letter<br>"
              . "- one lowercase letter<br>"
              . "- one digit<br>"
              . "- one special character";
        }
        elseif ($password_raw !== $confirm_password) {
            $field_errors['confirm_password'] = "Passwords do not match.";
        }
    }

    // Handle profile image upload
    $profile_image = $user['profile_image'];
    if (!empty($_FILES['profile_image']['name'])) {
        $upload_dir = "../assets/image/uploads/";
        $file_name  = time() . "_" . basename($_FILES['profile_image']['name']);
        $target_path = $upload_dir . $file_name;

        $allowed_types = ['image/jpg', 'image/jpeg', 'image/png'];
        if (!in_array($_FILES['profile_image']['type'], $allowed_types)) {
            $field_errors['profile_image'] = "Invalid file type. Only JPG, JPEG, PNG allowed.";
        } elseif (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_path)) {
            $field_errors['profile_image'] = "Failed to upload image.";
        } else {
            $profile_image = "/assets/image/uploads/" . $file_name;
        }
    }

    // Check duplicate email
    $emailCheck = $_db->prepare("SELECT id FROM users WHERE email = :email AND id != :user_id");
    $emailCheck->execute([':email' => $email, ':user_id' => $user_id]);
    if ($emailCheck->fetch()) {
        $field_errors['email'] = "Email already exists.";
    }

    // Check duplicate phone
    if ($new_phone !== $user['phone_num']) {
        $phoneCheck = $_db->prepare("SELECT id FROM users WHERE phone_num = :phone_num AND id != :user_id");
        $phoneCheck->execute([':phone_num' => $new_phone, ':user_id' => $user_id]);
        if ($phoneCheck->fetch()) {
            $field_errors['phone_num'] = "Phone number already exists.";
        }
    }

    if (empty($field_errors)) {
        // Save user
        $sql = "UPDATE users SET username = :username, email = :email, profile_image = :profile_image";
        $params = [
            ':username'      => $username,
            ':email'         => $email,
            ':profile_image' => $profile_image,
            ':user_id'       => $user_id
        ];
        
        if ($new_phone !== $user['phone_num']) {
            $sql .= ", phone_num = :phone_num";
            $params[':phone_num'] = $new_phone;
        }

        if (!empty($password_raw)) {
            $password_hashed = sha1($password_raw);
            $sql .= ", password = :password";
            $params[':password'] = $password_hashed;
        }

        $sql .= " WHERE id = :user_id";

        $update = $_db->prepare($sql);
        $update->execute($params);

        // Update or insert address
        $checkAddr = $_db->prepare("SELECT * FROM address WHERE user_id = :user_id AND is_default = 1");
        $checkAddr->execute([':user_id' => $user_id]);

        if ($checkAddr->fetch()) {
            $updateAddr = $_db->prepare("UPDATE address SET full_name = :full_name, address_line = :address_line, city = :city, postcode = :postcode, phone = :phone WHERE user_id = :user_id AND is_default = 1");
            $updateAddr->execute([
                ':full_name'    => $full_name,
                ':address_line' => $address_line,
                ':city'         => $city,
                ':postcode'     => $postcode,
                ':phone'        => $phone,
                ':user_id'      => $user_id
            ]);
        } else {
            $insertAddr = $_db->prepare("INSERT INTO address (user_id, full_name, address_line, city, postcode, phone, is_default) VALUES (:user_id, :full_name, :address_line, :city, :postcode, :phone, 1)");
            $insertAddr->execute([
                ':user_id'      => $user_id,
                ':full_name'    => $full_name,
                ':address_line' => $address_line,
                ':city'         => $city,
                ':postcode'     => $postcode,
                ':phone'        => $phone
            ]);
        }

        temp("info", "✅ Profile Updated!");
        header("Location: profile.php");
        exit();
    }
}
?>

<link rel="stylesheet" href="../assets/css/edit_profile.css">
<link rel="stylesheet" href="../assets/css/backButton.css">

<a href="/public/profile.php" class="back-btn">← Back</a>

<body>
    <div class="edit-container">
        <h2>Edit User Profile</h2>
        <form method="POST" enctype="multipart/form-data">
            <?php include '../component/editUserProfileTemplate.php'; ?>

            <div class="form-group">
                <input type="submit" value="Update Profile" class="btn">
            </div>
        </form>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/js/imagePreview.js"></script>

<?php include "../public/footer.php"; ?>
