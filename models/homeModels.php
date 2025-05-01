<?php
// Prevent direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php");
    exit();
}
?>

<?php
include "../config/db.php";

function getHomeScreenContent() {
    global $_db;
    
    $stm = $_db->prepare("SELECT * FROM home_screen");
    $stm->execute();
    return $stm->fetch();
}

function updateTitle($newTitle) {
    global $_db;
    $stm = $_db->prepare("UPDATE home_screen SET title = ? WHERE id = 1");
    $stm->execute([ $newTitle]);
}

function updateHomeContent($path, $type) {
    global $_db;
    $stm = $_db->prepare("UPDATE home_screen SET path = ?, type = ? WHERE id = 1");
    $stm->execute([ $path, $type]);
}

?>
