<?php
require_once 'customer.php';

$customer = null;
$message = "";
$products = [];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['customer_id'])) {
    $userId = $_POST['customer_id'];

    // Create customer with dummy data for required fields (except ID)
    $customer = new Customer(
        $userId,
        "", "", "", "", "", ""
    );

    if (isset($_POST['add_to_cart'])) {
        $productId = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $message = $customer->addToCart($productId, $quantity);
    } elseif (isset($_POST['place_order'])) {
        $message = $customer->placeOrders();
    }

    // Load products
    $products = $customer->browseProducts();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Test Page</title>
</head>
<body>
    <h1>Customer Test Page</h1>

    <form method="POST">
        <label for="customer_id">Enter Customer ID:</label>
        <input type="number" name="customer_id" id="customer_id" required
            value="<?= isset($_POST['customer_id']) ? $_POST['customer_id'] : '' ?>">
        <button type="submit" name="load_products">Load Products</button>
    </form>

    <?php if ($customer): ?>
        <h2>Browse Products</h2>
        <?php if (is_array($products)) : ?>
            <form method="POST">
                <input type="hidden" name="customer_id" value="<?= $customer->getId() ?>">
                <select name="product_id">
                    <?php foreach ($products as $product): ?>
                        <option value="<?= $product['product_id'] ?>">
                            <?= $product['name'] ?> - <?= $product['price'] ?> EGP
                        </option>
                    <?php endforeach; ?>
                </select>
                Quantity:
                <input type="number" name="quantity" value="1" min="1" required>
                <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
        <?php else: ?>
            <p>Error loading products: <?= $products ?></p>
        <?php endif; ?>

        <h2>Place Order</h2>
        <form method="POST">
            <input type="hidden" name="customer_id" value="<?= $customer->getId() ?>">
            <button type="submit" name="place_order">Place Order</button>
        </form>
    <?php endif; ?>

    <?php if ($message): ?>
        <p><strong>Message:</strong> <?= $message ?></p>
    <?php endif; ?>
</body>
</html>
