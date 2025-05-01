<?php
// Prevent direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("Location: /public/home.php");
    exit();
}
?>

<?php 
include '../config/db.php'; 
function getProducts() {
    global $_db;

    $stm = $_db->prepare("SELECT id, name, brand, price, discount, sold_count,status FROM products WHERE status = 'available'");
    $stm->execute();
    return $stm->fetchAll(PDO::FETCH_OBJ); 
}

function searchProducts($query) {
    global $_db;

    $stm = $_db->prepare("SELECT * FROM products WHERE name LIKE ?");
    $stm->execute(["%$query%"]);
    
    return $stm->fetchAll(PDO::FETCH_OBJ);
}

function filterProducts($sexId = null, $brandId = null, $priceOrder = '',$isPromo) {
    global $_db; 
    
    $sql = "SELECT p.* FROM products p";
    $params = [];

    if (!empty($brandId) && $brandId != 7) { // 7 is "All"
        $sql .= " JOIN product_categories pc2 ON p.id = pc2.product_id AND pc2.category_id = :brandId";
        $params['brandId'] = $brandId;
    }

    if(!empty($isPromo)&& $isPromo==1){
        $sql.=" WHERE p.discount > 0";
    }

    if ($priceOrder == '0') {
        $sql .= " ORDER BY p.price ASC";
    } elseif ($priceOrder == '1') {
        $sql .= " ORDER BY p.price DESC";
    }

    $stm = $_db->prepare($sql);
    $stm->execute($params);

    return $stm->fetchAll(PDO::FETCH_OBJ);
}

function getProductById($id){
    global $_db;
    
    $stm=$_db->prepare("SELECT * FROM products where id=?");
    $stm->execute([$id]);
    return $stm->fetch(PDO::FETCH_OBJ);
}

function getProductImageById($id) {
    global $_db;
    
    $stm = $_db->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
    $stm->execute([$id]);

    return $stm->fetchAll(PDO::FETCH_COLUMN); 
}

function deleteProductImage($productId, $imgPath) {
    global $_db;
    
    $stm = $_db->prepare("DELETE FROM product_images WHERE product_id = ? AND image_path=?");
    $stm->execute([$productId, $imgPath]);
}


function updateProductStock($items) {
    global $_db;

    foreach ($items as $item) {
        $stm = $_db->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stm->execute([$item['quantity'], $item['product_id']]);
    }
}

function updateProductSoldCount($items) {
    global $_db;

    foreach ($items as $item) {
        $productId = $item['product_id'];
        $quantity = $item['quantity'];

        $stm = $_db->prepare("UPDATE products SET sold_count = sold_count + ? WHERE id = ?");
        $stm->execute([$quantity,$productId]);
    }
}


///admin
function getFilteredProducts($brand = 'all', $sort = '', $search = '') {
    global $_db;

    $query = "SELECT p.*, pi.image_path, p.stock
              FROM products p 
              JOIN product_images pi ON pi.product_id = p.id 
              WHERE pi.id = (
                  SELECT id FROM product_images 
                  WHERE product_id = p.id 
                  ORDER BY id ASC LIMIT 1
              )";

    $params = [];

    if ($search) {
        $query .= " AND p.name LIKE ?";
        $params[] = "%" . $search . "%"; 
    }

    // Filter by brand
    if ($brand !== 'all') {
        $query .= " AND p.category = ?";
        $params[] = $brand;
    }

    // Sorting
    switch ($sort) {
        case 'price_asc':
            $query .= " ORDER BY p.price ASC";
            break;
        case 'price_desc':
            $query .= " ORDER BY p.price DESC";
            break;
        case 'discount_desc':
            $query .= " ORDER BY p.discount DESC";
            break;
        case 'stock_asc':
            $query .= " ORDER BY p.stock ASC";
            break;
        case 'stock_desc':
            $query .= " ORDER BY p.stock DESC";
            break;
        default:
            $query .= " ORDER BY p.id DESC";
            break;
    }

    $stm = $_db->prepare($query);
    $stm->execute($params);

    return $stm->fetchAll(PDO::FETCH_ASSOC);
}

function getProductToEditById($product_id) {
    global $_db;

    $stm = $_db->prepare("SELECT * FROM products WHERE id = ?");
    $stm->execute([$product_id]);
    return $stm->fetch(PDO::FETCH_ASSOC);
}


function insertProduct($name, $brand, $price, $discount, $status) {
    global $_db;

    $stm = $_db->prepare("INSERT INTO products (name, brand, price, discount, status) VALUES (?, ?, ?, ?, ?)");
    return $stm->execute([$name, $brand, $price, $discount, $status]);
}

function updateProduct($id, $name, $price, $discount,$category, $stock) {
    global $_db;

    $stm = $_db->prepare("UPDATE products SET name = ?, price = ?, discount = ?, stock = ?,category=? WHERE id = ?");
    return $stm->execute([$name, $price, $discount, $stock,$category, $id]);
}


function deleteProductSize($product_id,$size_id) {
    global $_db;

    $stm = $_db->prepare("DELETE FROM product_sizes WHERE product_id = ? AND size_id=?");
    $stm->execute([$product_id,$size_id]);
}

function deleteProduct($product_id) {
    global $_db;

    $stm = $_db->prepare("DELETE FROM products WHERE id = ?");
    $stm->execute([$product_id]);
}

function deleteProductImageById($product_id) {
    global $_db;

    $stm = $_db->prepare("DELETE FROM product_images WHERE product_id = ?");
    $stm->execute([$product_id]);
}
function getAllSize(){
    global $_db;

    $stm = $_db->prepare("SELECT size FROM sizes ORDER BY id");
    $stm->execute();
    return $stm->fetchAll(PDO::FETCH_COLUMN);
}

function getAllBrand(){
    global $_db;

    $stm = $_db->prepare("SELECT name FROM categories WHERE type='category' ORDER BY id");
    $stm->execute();
    return $stm->fetchAll(PDO::FETCH_COLUMN);
}

function insertProductImage($productId, $imageName) {
    global $_db;
    $stm = $_db->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
    return $stm->execute([$productId, $imageName]);
}

function updateEditProductStock($productId, $stock) {
    global $_db;

    $stm = $_db->prepare("UPDATE products SET stock = ? WHERE id = ? ");
    $stm->execute([$stock, $productId]);
}

function insertNewProduct($name, $brand, $price, $discount, $stock) {
    global $_db;

    // Validate and map brand to category_id
    $brandToCategoryId = [
        'bouquet' => 14,
        'key chain' => 15,
    ];

    // Normalize brand (e.g., to lowercase) to ensure matching
    $normalizedBrand = strtolower(trim($brand));

    if (!array_key_exists($normalizedBrand, $brandToCategoryId)) {
        throw new Exception("Invalid brand provided: $brand");
    }

    $categoryId = $brandToCategoryId[$normalizedBrand];

    // Insert into products table
    $stm = $_db->prepare("INSERT INTO products (name, category, price, discount, stock) VALUES (?, ?, ?, ?, ?)");
    $stm->execute([$name, $brand, $price, $discount, $stock]);

    $productId = $_db->lastInsertId();

    // Insert into product_categories table
    $stm2 = $_db->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
    $stm2->execute([$productId, $categoryId]);

    return $productId;
}

function getLowStockProducts() {
    global $_db;
    $stm = $_db->prepare("
        SELECT id AS product_id, name, stock
        FROM products
        WHERE stock < 10
        ORDER BY stock ASC
        LIMIT 10
    ");
    $stm->execute();
    return $stm->fetchAll(PDO::FETCH_ASSOC);
}


?>