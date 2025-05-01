<link rel="stylesheet" href="../assets/css/homeScreen.css"> 
<?php 
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php");
    exit();
}
if ($content): 
    echo '<h1>'.$content->title.'</h1>';

    if($role!=='admin'&& $role !=='manager'){
        echo '<a href="../user/shop.php">';
    }

        if ($content->type == "image") {
                echo '<img src="' . $content->path . '" alt="Displayed Image" id="screenDisplay">';
        }elseif ($content->type == "video") {
            echo '<video autoplay loop muted class="screenDisplay">
                <source src="' . $content->path . '" type="video/mp4">
            </video>';
        }

    if($role!=='admin' && $role !=='manager'){
        echo '</a>';
    }
endif; 
?>
