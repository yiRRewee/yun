<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php"); 
    exit;
}

?>

<link rel="stylesheet" href="../assets/css/backButton.css">
<button class="back-btn" onclick="goBack()">← Back</button>

<script>
function goBack() {
    window.location = document.referrer;
}
</script>
