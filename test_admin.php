<?php
require_once 'Admin.php';

//test object
$admin = new Admin("1", "Ahmed", "Mohamed","pharma@example.com", "secure123", "Owner",);

$output = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'addProduct':
                $output = $admin->addProduct($_POST['productId'], $_POST['productName'], $_POST['price']);
                break;

            case 'updateProduct':
                $output = $admin->updateProduct($_POST['productId'], $_POST['productName'], $_POST['price']);
                break;

            case 'deleteProduct':
                $output = $admin->deleteProduct($_POST['productId']);
                break;

            case 'manageInventory':
                ob_start();
                $admin->manageInventory();
                $output = ob_get_clean();
                break;

            case 'updateOrderStatus':
                $output = $admin->updateOrdersStatus($_POST['orderItem'], $_POST['orderId'], $_POST['orderStatus']);
                break;

            case 'cancelOrder':
                $output = $admin->cancelOrders($_POST['orderItem'], $_POST['orderId'],);
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel Test</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 20px;
            width: 500px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        input, select, button {
            display: block;
            width: 100%;
            margin-top: 10px;
            padding: 10px;
        }
        .output {
            margin-top: 20px;
            background: #e7f4e4;
            padding: 10px;
            border-left: 4px solid #4caf50;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin Function Testing</h2>
    <form method="post">
        <input type="text" name="productId" placeholder="Product ID">
        <input type="text" name="productName" placeholder="Product Name">
        <input type="number" name="price" placeholder="Price">
        <input type="text" name="orderItem" placeholder="Order Item">
        <input type="text" name="orderId" placeholder="Order ID">
        <select name="orderStatus">
            <option value="">--Select Status--</option>
            <option value="shipped">Shipped</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
        </select>
        
        <button type="submit" name="action" value="addProduct">Add Product</button>
        <button type="submit" name="action" value="updateProduct">Update Product</button>
        <button type="submit" name="action" value="deleteProduct">Delete Product</button>
        <button type="submit" name="action" value="manageInventory">View Inventory</button>
        <button type="submit" name="action" value="updateOrderStatus">Update Order Status</button>
        <button type="submit" name="action" value="cancelOrder">Cancel Order</button>
    </form>

    <?php if (!empty($output)): ?>
        <div class="output">
            <pre><?php echo $output; ?></pre>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
