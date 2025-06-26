<?php
// success.php - Order Confirmation Page
session_start();

// Verify order data exists
if (!isset($_SESSION['order_confirmation'])) {
    header("Location: cart.php");
    exit();
}

// Get order data from session
$order = $_SESSION['order_confirmation'];
unset($_SESSION['order_confirmation']); // Clear after display

// Database connection to fetch full order details
require_once ("Database.php");
$database = Database::getInstance();
$conn = $database->getConnection();

// Fetch order details
$stmt = $conn->prepare("
    SELECT o.*, c.first_name, c.last_name, c.email, c.phone 
    FROM orders o
    JOIN customers c ON o.customer_id = c.id
    WHERE o.id = ?
");
$stmt->execute([$order['order_id']]);
$orderDetails = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch order items
$stmt = $conn->prepare("
    SELECT product_name, quantity, unit_price, total_price 
    FROM order_items 
    WHERE order_id = ?
");
$stmt->execute([$order['order_id']]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Pharmacy System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .confirmation-card { border-left: 5px solid #28a745; }
        .order-item { border-bottom: 1px solid #eee; padding: 1rem 0; }
        .thank-you-icon { color: #28a745; font-size: 4rem; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card confirmation-card mb-4">
                    <div class="card-body text-center">
                        <div class="thank-you-icon mb-4">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h1 class="card-title">Thank You For Your Order!</h1>
                        <p class="lead">Your order <strong>#<?= htmlspecialchars($orderDetails['order_number']) ?></strong> has been placed successfully.</p>
                        <p>A confirmation email has been sent to <strong><?= htmlspecialchars($orderDetails['email']) ?></strong>.</p>
                        <div class="alert alert-success mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <?php if ($orderDetails['payment_method'] === 'Cash on Delivery (COD)'): ?>
                                Please prepare <strong>EGP <?= number_format($orderDetails['total'], 2) ?></strong> for the delivery person.
                            <?php else: ?>
                                Your payment of <strong>EGP <?= number_format($orderDetails['total'], 2) ?></strong> has been processed.
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>Shipping Information</h6>
                                <p>
                                    
                                    <?= htmlspecialchars($orderDetails['address']) ?><br>
                                    <?= htmlspecialchars($orderDetails['city']) ?>, <?= htmlspecialchars($orderDetails['state']) ?><br>
                                    Phone: <?= htmlspecialchars($orderDetails['phone']) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6>Order Details</h6>
                                <p>
                                    Order Number: <strong>#<?= htmlspecialchars($orderDetails['order_number']) ?></strong><br>
                                    Date: <?= date('F j, Y', strtotime($orderDetails['created_at'])) ?><br>
                                    Payment Method: <?= htmlspecialchars($orderDetails['payment_method']) ?><br>
                                    Status: <span class="badge bg-info"><?= htmlspecialchars($orderDetails['status']) ?></span>
                                </p>
                            </div>
                        </div>

                        <h6 class="mb-3">Items Ordered</h6>
                        <div class="order-items">
                            <?php foreach ($items as $item): ?>
                                <div class="order-item">
                                    <div class="row">
                                        <div class="col-6">
                                            <?= htmlspecialchars($item['product_name']) ?>
                                        </div>
                                        <div class="col-2 text-center">
                                            <?= $item['quantity'] ?>
                                        </div>
                                        <div class="col-2 text-end">
                                            EGP <?= number_format($item['unit_price'], 2) ?>
                                        </div>
                                        <div class="col-2 text-end">
                                            <strong>EGP <?= number_format($item['total_price'], 2) ?></strong>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="order-totals mt-4">
                            <div class="d-flex justify-content-between">
                                <span>Subtotal:</span>
                                <span>EGP <?= number_format($orderDetails['subtotal'], 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Tax (14%):</span>
                                <span>EGP <?= number_format($orderDetails['tax'], 2) ?></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total:</span>
                                <span>EGP <?= number_format($orderDetails['total'], 2) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="products.php" class="btn btn-primary me-md-2">
                        <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                    </a>
                    <a href="order_history.php" class="btn btn-outline-secondary">
                        <i class="fas fa-history me-2"></i>View Order History
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>