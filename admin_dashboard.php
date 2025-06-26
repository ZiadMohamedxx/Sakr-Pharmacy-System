<?php
require_once 'admin.php';if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $productId = $_POST['delete_product_id'];

    $admin = new Admin("userId", 'fname', 'lname', 'email', 'password', 'admin');

    $result = $admin->deleteProduct($productId);
    
    echo "<script>alert('$result');</script>";
}
?>
