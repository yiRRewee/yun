<?php
include '../public/header.php';
include '../models/addressModels.php';

$user_id = $_SESSION['user_id'];

if (is_post()) {
    if (isset($_POST['update_address'])) {
        $errors = validateAddressForm($_POST);
        if (empty($errors)) {
            updateAddress($_POST['address_id'], $_POST['full_name'], $_POST['address_line'], $_POST['city'], $_POST['postcode'], $_POST['phone']);
            temp("info", "Address Updated ✅");
            redirect();
        }
    }
    if (isset($_POST['add_address'])) {
        $errors = validateAddressForm($_POST);
        if (empty($errors)) {
            addAddress($user_id, $_POST['full_name'], $_POST['address_line'], $_POST['city'], $_POST['postcode'], $_POST['phone']);
            temp("info", "Address Added ✅");
            redirect();
        }
    }
    if (isset($_POST['delete_address'])) {
        deleteAddress($_POST['address_id']);
        temp("info","Address Deleted ✅");
        redirect();
    }
    if (isset($_POST['set_default'])) {
        setDefaultAddress($user_id, $_POST['address_id']);
        temp("info","Set Deafult Address Successful ✅");
        redirect();
    }
}
$addresses = getAllUserAddressesById($user_id);

?>

<link rel="stylesheet" href="../assets/css/address.css">
<link rel="stylesheet" href="../assets/css/backButton.css">

<a href="/public/profile.php" class="back-btn">← Back</a>

<div class="container">
    <h2>Address</h2>

    <div class="address-list">
        <?php foreach ($addresses as $address): ?>
            <div class="address-card <?= $address['is_default'] ? 'default-address' : '' ?>">
                <p><b><?= $address['full_name']?></b></p>
                <p><?= $address['address_line'] ?>, <?= $address['city'] ?>, <?= $address['postcode'] ?></p>
                <p>☎️ <?= $address['phone'] ?></p>

                <form method="post" class="form-button">
                    <input type="hidden" name="address_id" value="<?= $address['address_id'] ?>">
                    <button type="submit" name="set_default" class="btn-default">Set Default</button>
                </form>

                <button type="button"
                        class="btn-edit"
                        data-address_id="<?= $address['address_id'] ?>"
                        data-full_name="<?= $address['full_name'] ?>"
                        data-address_line="<?= $address['address_line'] ?>"
                        data-city="<?= $address['city'] ?>"
                        data-postcode="<?= $address['postcode'] ?>"
                        data-phone="<?= $address['phone'] ?>">
                    Edit
                </button>
                <form method="post" class="form-button">
                <input type="hidden" name="address_id" value="<?= $address['address_id'] ?>">
                <button type="submit" name="delete_address" class="btn-delete" data-confirm="Are You Sure Want Delete Address?">Delete</button>
                </form>

            </div>
        <?php endforeach; ?>
    </div>

    <?php include 'addAddress.php'?>

</div>
<script src="../assets/js/address.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
