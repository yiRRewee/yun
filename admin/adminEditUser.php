<?php 
include "../public/header.php"; 
require_once "../config/db.php";
include "../models/addressModels.php";
include "../models/logModels.php";
require "../config/adminAuth.php";

$user_id_to_edit = $_GET['user_id'] ?? null;
if (!$user_id_to_edit) {
    die("User ID is required.");
}

$admin_user_id = $_SESSION['user_id'] ?? null;
if (!$admin_user_id) {
    die("Admin is not logged in.");
}

$user = getUserById($user_id_to_edit);
if (!$user) {
    die("User not found.");
}

$admin = getUserById($admin_user_id);
if ($admin['role'] !== 'admin' && $admin['role'] !== 'manager') {
    die("Unauthorized access. You must be an admin.");
}

$addr = getDefaultAddress($user_id_to_edit);
$address_line = $addr['address_line'] ?? '';
$city = $addr['city'] ?? '';
$postcode = $addr['postcode'] ?? '';
$full_name = $addr['full_name'] ?? '';
$phone = $addr['phone'] ?? '';

$states = ["Perlis", "Kedah", "Kelantan", "Terrengganu", "Pahang", "Johor", "Melaka", "Negeri Sembilan", "Putrajaya", "Selangor", "Perak", "Pulau Pinang", "Sarawak", "Sabah"];

$field_errors = []; 

if (is_post()) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $new_phone = trim($_POST['phone_num']);
    $full_name = trim($_POST['username']);
    $address_line = trim($_POST['address_line']);
    $city = trim($_POST['city']);
    $postcode = trim($_POST['postcode']);
    $password_raw = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $profile_image = $user['profile_image'];
    $errors = [];

    if (empty($username)) {
        $field_errors['username'] = "Username is required.";
    }
    if (empty($email)) {
        $field_errors['email'] = "Email is required.";
    } elseif (!preg_match('/^[^@]+@(gmail\.com|yahoo\.com|hotmail\.com|outlook\.com|live\.com|icloud\.com|aol\.com)$/i', $email)) {
        $field_errors['email'] = "Only emails from gmail.com, yahoo.com, hotmail.com, outlook.com, live.com, icloud.com, or aol.com are allowed.";
    }
    if (empty($new_phone) || !preg_match('/^01[0-9]{8,9}$/', $new_phone)) {
        $field_errors['phone_num'] = "Valid phone number (10-11 digits) required.";
    }
    if (empty($address_line)) {
        $field_errors['address_line'] = "Address line is required.";
    }
    if (empty($city)) {
        $field_errors['city'] = "City is required.";
    }
    if (empty($postcode) || !preg_match('/^[0-9]{5}$/', $postcode)) {
        $field_errors['postcode'] = "Valid 5-digit postcode required.";
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
    

    if (!empty($_FILES['profile_image']['name'])) {
        $upload_dir = "../assets/image/uploads/";
        $file_name = basename($_FILES['profile_image']['name']);
        $target_path = $upload_dir . $file_name;
        $allowed_types = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($_FILES['profile_image']['type'], $allowed_types)) {
            $field_errors['profile_image'] = "Only JPG, JPEG, PNG, WEBP allowed.";
        } elseif (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_path)) {
            $field_errors['profile_image'] = "Failed to upload image.";
        } else {
            $profile_image = "/assets/image/uploads/" . $file_name;
        }
    }

    if (empty($field_errors)) {
        if (!empty($password_raw)) {
            $password_hashed = sha1($password_raw);
        }

        $changes = [];
        if ($user['username'] !== $username) {
            $changes[] = "Username: {$user['username']} -> {$username}";
        }
        if ($user['email'] !== $email) {
            $changes[] = "Email: {$user['email']} -> {$email}";
        }
        if ($user['phone_num'] !== $new_phone) {
            $changes[] = "Phone: {$user['phone_num']} -> {$new_phone}";
        }
        if ($user['profile_image'] !== $profile_image) {
            $changes[] = "Profile Image: Updated";
        }
        if (!empty($password_hashed) && $user['password'] !== $password_hashed) {
            $changes[] = "Password: Updated";
        }

        if (!empty($changes)) {
            $getEmail = getUserEmailById($user_id_to_edit);
            $email = $getEmail->email;
            $action = "Updated profile for user: {$user['username']} with changes: " . implode(", ", $changes);
            logAdminEdit($email, $admin_user_id, $user_id_to_edit, $action);
        }

        updateUserProfile($user_id_to_edit, $username, $email, $new_phone, $profile_image, $password_hashed ?? null);

        $addr = getDefaultAddress($user_id_to_edit);
        if ($addr) {
            updateAddress($addr['address_id'], $full_name, $address_line, $city, $postcode, $new_phone);
        } else {
            addAddress($user_id_to_edit, $full_name, $address_line, $city, $postcode, $new_phone);
        }

        temp("info", "✅ User Profile Updated");
        redirect("manageUser.php");
    }
}
?>

<link rel="stylesheet" href="../assets/css/edit_profile.css">
<?php include "../component/backButton.php"; ?>

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

<?php include "../public/footer.php" ?>
