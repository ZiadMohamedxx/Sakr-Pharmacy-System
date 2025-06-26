<?php
require_once("Database.php");
session_start();



if (!isset($_SESSION['customer_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$customer_id = $_SESSION['customer_id'];


if (!isset($_SESSION['customer_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$customer_id = $_SESSION['customer_id'];


$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['product_id']) || !isset($data['quantity'])) {
    echo json_encode(["success" => false, "message" => "Missing data"]);
    exit;
}

$product_id = intval($data['product_id']);
$quantity = intval($data['quantity']);
$timestamp = date("Y-m-d H:i:s");

try {
    $db = Database::getInstance()->getConnection();

    // Check if item already exists
    $stmt = $db->prepare("SELECT id, quantity FROM cart_items WHERE customer_id = ? AND product_id = ?");
    $stmt->execute([$customer_id, $product_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // Update quantity (increment by 1 only, not replace)
        $newQty = $existing['quantity'] + 1;
        $updateStmt = $db->prepare("UPDATE cart_items SET quantity = ?, created_at = ? WHERE id = ?");
        $updateStmt->execute([$newQty, $timestamp, $existing['id']]);
    } else {
        // Insert new row
        $insertStmt = $db->prepare("INSERT INTO cart_items (customer_id, product_id, quantity, created_at) VALUES (?, ?, ?, ?)");
        $insertStmt->execute([$customer_id, $product_id, 1, $timestamp]);
    }

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
