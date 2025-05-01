<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php");
    exit;
}
?>

<div class="form-group">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username"
       value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : htmlspecialchars($user['username']) ?>" required>
    <?php if (!empty($field_errors['username'])): ?>
        <div class="error-message"><?= $field_errors['username'] ?></div>
    <?php endif; ?>
</div>

<div class="form-group">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email"
       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : htmlspecialchars($user['email']) ?>" required>
    <?php if (!empty($field_errors['email'])): ?>
        <div class="error-message"><?= $field_errors['email'] ?></div>
    <?php endif; ?>
</div>

<div class="form-group">
    <label for="phone_num">Phone Number:</label>
    <input type="text" name="phone_num" id="phone_num"
       value="<?= isset($_POST['phone_num']) ? htmlspecialchars($_POST['phone_num']) : htmlspecialchars($user['phone_num']) ?>" required maxlength="11">
    <?php if (!empty($field_errors['phone_num'])): ?>
        <div class="error-message"><?= $field_errors['phone_num'] ?></div>
    <?php endif; ?>
</div>

<div class="form-group">
    <label for="address_line">Address Line:</label>
    <input type="text" name="address_line" id="address_line"
       value="<?= isset($_POST['address_line']) ? htmlspecialchars($_POST['address_line']) : htmlspecialchars($address_line) ?>" required>
    <?php if (!empty($field_errors['address_line'])): ?>
        <div class="error-message"><?= $field_errors['address_line'] ?></div>
    <?php endif; ?>
</div>

<div class="form-group">
    <label for="city">City:</label>
    <select name="city" required>
        <option value="" disabled <?= $city === '' ? 'selected' : '' ?>>-- Select City --</option>
        <?php foreach ($states as $s): ?>
            <option value="<?= htmlspecialchars($s) ?>" <?= $city === $s ? 'selected' : '' ?>>
                <?= htmlspecialchars($s) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if (!empty($field_errors['city'])): ?>
        <div class="error-message"><?= $field_errors['city'] ?></div>
    <?php endif; ?>
</div>

<div class="form-group">
    <label for="postcode">Postcode:</label>
    <input type="text" name="postcode" id="postcode"
       value="<?= isset($_POST['postcode']) ? htmlspecialchars($_POST['postcode']) : htmlspecialchars($postcode) ?>" required maxlength="5">
    <?php if (!empty($field_errors['postcode'])): ?>
        <div class="error-message"><?= $field_errors['postcode'] ?></div>
    <?php endif; ?>
</div>

<div class="form-group">
    <label for="password">New Password:</label>
    <input type="password" name="password" placeholder="New Password (optional)">
    <?php if (!empty($field_errors['password'])): ?>
        <div class="error-message"><?= $field_errors['password'] ?></div>
    <?php endif; ?>
</div>

<div class="form-group">
    <label for="confirm_password">Confirm Password:</label>
    <input type="password" name="confirm_password" placeholder="Confirm New Password">
    <?php if (!empty($field_errors['confirm_password'])): ?>
        <div class="error-message"><?= $field_errors['confirm_password'] ?></div>
    <?php endif; ?>
</div>

<div class="form-group">
    <label class="upload">
        Click to Upload New Profile Image:
        <input type="file" name="profile_image" accept="image/*" hidden><br><br>
        <img src="<?= $user['profile_image'] ?: '../assets/image/logo/default.png' ?>" alt="Preview" width="150">
    </label>
    <?php if (!empty($field_errors['profile_image'])): ?>
        <div class="error-message"><?= $field_errors['profile_image'] ?></div>
    <?php endif; ?>
</div>
