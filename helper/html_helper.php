<?php
// Prevent direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php");
    exit();
}
?>

<?php
// Encode HTML special characters
function encode($value) {
    return htmlentities($value);
}

function html_text($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='text' id='$key' name='$key' value='$value' $attr>";
}

function html_search($key, $placeholder = 'Search...', $attr = '') {
    $value = encode($GLOBALS[$key] ?? ''); 
    echo "<input type='text' id='$key' name='$key' value='$value' placeholder='$placeholder' $attr>";
}

function html_radios($key, $items, $br = false) {
    $value = encode($GLOBALS[$key] ?? '');
    foreach ($items as $id => $text) {
        $state = $id == $value ? 'checked' : '';
        echo "<input type='radio' id='{$key}_$id' name='$key' value='$id' $state>";
        echo "<label for='{$key}_$id'>$text</label>";
        if ($br) {
            echo '<br>';
        }
    }
}

function table_headers($fields, $sort, $dir, $href = '') {
    foreach ($fields as $k => $v) {
        $d = 'asc'; // Default direction
        $c = '';    // Default class
        
        // TODO
        if($k==$sort){
            $d=$dir=='asc' ? 'desc' : 'asc';
            $c=$dir;
        }
        echo "<th><a href='?sort=$k&dir=$d&$href' class='$c'>$v</a></th>";
    }
}

// Is POST request?
//make sure is post then execute the insert function
function is_post() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

//makesure execute correct function for exp post("add to cart")
function post($key, $value = null) {
    $value = $_POST[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}

function req($key, $value = null) {
    $value = $_REQUEST[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}

// Redirect to URL
function redirect($url = null) {
    $url ??= $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit();
}

function temp($key, $value = null) {
    if ($value !== null) {
        $_SESSION["temp_$key"] = $value;
    }
    else {
        $value = $_SESSION["temp_$key"] ?? null;
        unset($_SESSION["temp_$key"]);
        return $value;
    }
}

function err($key) {
    global $_err;
    if ($_err[$key] ?? false) {
        echo "<span class='err'>$_err[$key]</span>";
    }
    else {
        echo '<span></span>';
    }
}


// Generate <input type='file'> (multiple image)
function multiple_html_files($productId, $accept = 'image/*') {
    return "<input type='file' class='images-uploaded' data-product-id='{$productId}' name='photos[{$productId}][]' multiple accept='{$accept}' hidden>";
}   


function html_files($key, $accept = '', $attr = '') {
    echo "<input type='file' id='$key' name='$key' accept='$accept' $attr>";
}


function save_photo($f, $folder, $width = 200, $height = 200) {
    $photo = uniqid() . '.jpg';
    
    require_once '/lib/SimpleImage.php';
    $img = new SimpleImage();
    $img->fromFile($f->tmp_name)
        ->thumbnail($width, $height)
        ->toFile("$folder/$photo", 'image/jpeg');

    return $photo;
}

// Obtain uploaded file --> cast to object
function get_file($key) {
    $f = $_FILES[$key] ?? null;
    
    if ($f && $f['error'] == 0) {
        return (object)$f;
    }

    return null;
}

function get_multiple_files($key) {
    $files = [];
    if (!empty($_FILES[$key]['name'])) {
        foreach ($_FILES[$key]['name'] as $productId => $names) {
            foreach ($names as $index => $name) {
                $files[$productId][$index] = (object) [
                    'name' => $name,
                    'type' => $_FILES[$key]['type'][$productId][$index],
                    'tmp_name' => $_FILES[$key]['tmp_name'][$productId][$index],
                    'error' => $_FILES[$key]['error'][$productId][$index],
                    'size' => $_FILES[$key]['size'][$productId][$index]
                ];
            }
        }
    }
    error_log("Processed files: " . print_r($files, true));

    return $files;
}

function validateAddressForm($postData) {
    $errors = [];

    $full_name = trim($postData['full_name'] ?? '');
    $address_line = trim($postData['address_line'] ?? '');
    $city = trim($postData['city'] ?? '');
    $postcode = trim($postData['postcode'] ?? '');
    $phone = trim($postData['phone'] ?? '');

    if ($full_name === '') {
        $errors['full_name'] = "Full Name is required.";
    }
    if ($address_line === '') {
        $errors['address_line'] = "Address Line is required.";
    }
    if ($city === '') {
        $errors['city'] = "State is required.";
    }
    if (!preg_match('/^\d{5}$/', $postcode)) {
        $errors['postcode'] = "Postcode must be a 5-digit number.";
    }
    if (!preg_match('/^01[0-9]{8,9}$/', $phone)) {
        $errors['phone'] = "Phone number is invalid.";
    }

    return $errors;
}

?>
<link rel="stylesheet" href="../assets/css/htmlHelper.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/js/htmlHelper.js"></script>