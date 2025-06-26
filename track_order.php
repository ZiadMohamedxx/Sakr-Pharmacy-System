<?php
require_once __DIR__ . '/Database.php';

$order = null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = trim($_POST['order_id']);

    if ($order_id !== '') {
        $pdo = Database::getInstance()->getConnection();

        try {
            $stmt = $pdo->prepare("SELECT order_id, `status` FROM orders WHERE order_id = ?");
            $stmt->execute([$order_id]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                $message = "No order found.";
            }
        } catch (PDOException $e) {
            $message = "Error fetching order: " . $e->getMessage();
        }
    } else {
        $message = "Please enter a valid Order ID.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Track Your Order</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: url("images/arrow.png"), url("images/arrow2.png"), linear-gradient(to right, #b2fefa, #0ed2f7);
            background-repeat: no-repeat;
            padding: 60px 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .track-container {
            background: white;
            background-repeat: no-repeat;
            background-position: right;
            border-radius: 16px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #0b7285;
            margin-bottom: 30px;
            font-size: 28px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        input[type="text"] {
            padding: 12px 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        input[type="submit"] {
            padding: 12px 16px;
            background-color: #0b7285;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #095b6b;
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            border: 1px solid #ddd;
        }

        .order-table th,
        .order-table td {
            padding: 14px 18px;
            border: 1px solid #eee;
            text-align: center;
        }

        .order-table th {
            background-color: #0b7285;
            color: white;
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
        }

        .message {
            margin-top: 25px;
            color: #e74c3c;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="track-container p-5">
        <h2>Track Your Order</h2>

        <form method="POST" action="">
            <input type="text" name="order_id" id="order_id" placeholder="Enter your Order ID..." required>
            <input type="submit" value="Track Order">
        </form>

        <?php if ($order): ?>

            <table class="bg-white order-table">
                <tr>
                    <th>Order ID</th>
                    <th>Status</th>
                </tr>
                <tr>
                    <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                    <td><?php echo htmlspecialchars($order['status']); ?></td>
                </tr>
            </table>
        <?php elseif ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    </div>

</body>

</html>