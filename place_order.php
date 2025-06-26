<?php
require_once 'Database.php';

$order_id = '';
$error = '';

try {
  $pdo = Database::getInstance()->getConnection();

  // Get any cart item to fetch the customer_id (assumes same customer for all items)
  $stmt = $pdo->query("SELECT customer_id FROM cart_items LIMIT 1");
  $row = $stmt->fetch();

  if ($row) {
    $customer_id = $row['customer_id'];

    // Calculate total amount from cart
    $stmt = $pdo->prepare("
      SELECT SUM(p.price * c.quantity) AS total_amount
      FROM cart_items c
      JOIN products p ON c.product_id = p.id
      WHERE c.customer_id = :customer_id
    ");
    $stmt->execute([':customer_id' => $customer_id]);
    $totalRow = $stmt->fetch();

    $total_amount = $totalRow['total_amount'];

    if ($total_amount !== null) {
      $order_id = uniqid('ORD-');
      $order_date = date('Y-m-d H:i:s');

      $sql = "INSERT INTO orders (order_id, customer_id, order_date, status, total_amount)
              VALUES (:order_id, :customer_id, :order_date, 'pending', :total_amount)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ':order_id' => $order_id,
        ':customer_id' => $customer_id,
        ':order_date' => $order_date,
        ':total_amount' => $total_amount,
      ]);
    } else {
      $error = "Cart is empty or products not found.";
    }
  } else {
    $error = "No cart data found.";
  }
} catch (Exception $e) {
  $error = "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html>

<head>
  <title>Place Order</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/all.min.css">
  <link rel="stylesheet" href="css/order.css">
</head>

<body>
  <div class="container text-center">

    <h2>Order Placed Successfully!</h2>
    <p>Your Order ID: <strong><?php echo htmlspecialchars($order_id); ?></strong></p>
    <div class="trak-order d-flex justify-content-around">
      <a class="btn btn-primary text-center my-3" href="track_order.php">Track Order</a>
    </div>
    <div class="return-to-home d-flex justify-content-around">
      <a href="home.php" class="btn my-3 text-center btn-success">Home</a>
    </div>
  </div>
</body>

</html>