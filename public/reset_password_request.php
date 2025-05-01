<?php
require '../config/db.php';
require '../lib/PHPMailer.php';
require '../lib/SMTP.php';

if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php");
    exit();
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!is_exists($email, 'users', 'email')) {
        $error = 'Email address not found.';
    } else {
        $token = bin2hex(random_bytes(16));
        save_reset_token($email, $token);
        $reset_link = 'http://' . $_SERVER['HTTP_HOST'] . '/public/reset_password.php?token=' . $token;

        $mail = get_mail();
        $mail->addAddress($email);
        $mail->Subject = 'Password Reset Request';
        $mail->isHTML(true);
        $mail->Body = "Click here to reset your password:<br><a href='$reset_link'>$reset_link</a>";

        try {
            $mail->send();
            $success = 'A password reset link has been sent to your email.';
        } catch (Exception $e) {
            $error = 'Mail sending failed: ' . $mail->ErrorInfo;
        }
    }
}

// Check if email exists
function is_exists($value, $table, $field) {
    global $_db;
    $stm = $_db->prepare("SELECT COUNT(*) FROM $table WHERE $field = ?");
    $stm->execute([$value]);
    return $stm->fetchColumn() > 0;
}

// Save token
function save_reset_token($email, $token) {
    global $_db;
    $expiresAt = date('Y-m-d H:i:s', time() + 3600);
    $stm = $_db->prepare("INSERT INTO password_resets (email, reset_token, created_at, expires_at) VALUES (?, ?, NOW(), ?)");
    $stm->execute([$email, $token, $expiresAt]);
}

// Mail configuration
function get_mail() {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->Username = 'your_email@gmail.com'; // Replace
    $mail->Password = 'your_app_password';    // Replace
    $mail->setFrom($mail->Username, 'System Admin');
    $mail->CharSet = 'utf-8';

    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ]
    ];

    return $mail;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../assets/css/forgot_password.css">
</head>
<body>
    <!-- Title on top of box -->
    <h2 style="position: absolute; top: 80px; font-size: 28px;">Forgot Password</h2>

    <!-- Content box -->
    <div class="container">
        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="submit" value="Send Reset Link">

            <?php if (!empty($error)) : ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <?php if (!empty($success)) : ?>
                <p class="success"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>