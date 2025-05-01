<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php");
    exit;
}
?>

<link rel="stylesheet" href="../assets/css/address.css">

<button class="btn-add">+ Add Address</button>

<div id="addForm" class="form-container">
  <h3 id="formTitle">Add New Address</h3>
    <form method="post">
        <input type="hidden" name="address_id" id="address_id" value="<?= isset($_POST['address_id']) ? htmlspecialchars($_POST['address_id']) : '' ?>">

        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li style="color:red"><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <label for="full_name">Full Name:</label>
        <input type="text" name="full_name" id="full_name" 
               value="<?= isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : '' ?>" required>

        <label for="address_line">Address Line:</label>
        <input type="text" name="address_line" id="address_line" 
               value="<?= isset($_POST['address_line']) ? htmlspecialchars($_POST['address_line']) : '' ?>" required>

        <label for="city">State:</label>
        <select name="city" id="city" required>
            <option value="">Select State</option>
            <?php
                $states = ["Perlis", "Kedah", "Kelantan", "Terrengganu", "Pahang", "Johor", "Melaka", "Negeri Sembilan", "Putrajaya", "Selangor", "Perak", "Pulau Pinang", "Sarawak", "Sabah"];
                foreach ($states as $s) {
                    $selected = (isset($_POST['city']) && $_POST['city'] === $s) ? "selected" : "";
                    echo "<option value=\"$s\" $selected>$s</option>";
                }
            ?>
        </select>

        <label for="postcode">Postcode:</label>
        <input type="text" name="postcode" id="postcode" 
               value="<?= isset($_POST['postcode']) ? htmlspecialchars($_POST['postcode']) : '' ?>" required maxlength="5">

        <label for="phone">Phone:</label>
        <input type="text" name="phone" id="phone" 
               value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>" required maxlength="11">

        <button type="submit" id="formSubmit" name="add_address">Add Address</button>
    </form>
</div>

<script src="../assets/js/address.js"></script>

<script>
$(document).ready(function () {
    <?php if (!empty($errors)): ?>
        $("#addForm").fadeIn();
    <?php endif; ?>
});
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
