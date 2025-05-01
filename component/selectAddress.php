<?php
require "../public/header.php";
include "../models/addressModels.php";

$user_id=$_SESSION['user_id'];

$addresses=getAllUserAddressesById($user_id);

if(is_post()){
    if (isset($_POST['add_address'])) {
        addAddress($user_id, $_POST['full_name'], $_POST['address_line'], $_POST['city'], $_POST['postcode'], $_POST['phone']);
        temp("info","Address Added ✅");
        redirect();
    }
    if(isset($_POST['address_id'])){
        $_SESSION['selected_address_id'] = $_POST['address_id']; 
        temp("info","✅ Address has been changed !");
        redirect('../user/orderConfirm.php'); 
    }
    if(empty($_POST['address_id'])){
        temp("info","Please Select your shipping address ⚠️");
        redirect();
    }
}

?>

<link rel="stylesheet" href="../assets/css/selectAddress.css">
<link rel="stylesheet" href="../assets/css/backButton.css">

<a href="/user/orderConfirm.php" class="back-btn">← Back</a>
<div class="container">
    <h2>Select Shipping Address</h2>

   <?php include "../component/addAddress.php";?>

    <form method="post" class="content" >
        <?php
         $addressList = [];
            foreach ($addresses as $address) {
                $addressList[$address['address_id']] = "<b>".$address['full_name']."</b>" . "<br> 📞" .
                                                    $address['phone'] . "<br>" .
                                                    $address['address_line'] . "<br>" .
                                                    $address['city']  . ", " .$address['postcode'] ;
                                                }
            echo'<div class="radio_label">';
            html_radios('address_id', $addressList, true); 
            echo'</div>';

            
    ?>
        <button type="submit" class="submit-button">Select Address</button>
    </form> 

</div>