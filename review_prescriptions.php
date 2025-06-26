<?php
require_once("Database.php");

session_start();

if (!isset($_SESSION['customer_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$customer_id = $_SESSION['customer_id'];

$conn = new mysqli("localhost", "root", "", "pharmacy_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT upload_date, image_url, status, comment FROM prescription WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$prescriptions = [];
while ($row = $result->fetch_assoc()) {
    $prescriptions[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Prescriptions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />

    <style>
        body {
            background: url("images/624dd0a951a1e8a118215b1b24a0da59-pharmacy-logo.webp"), linear-gradient(to right, #dbeafe, #f0f9ff);
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        .title {
            text-align: center;
            font-size: 2rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 900;
            margin-bottom: 30px;
            color:rgb(0, 2, 3);
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .card img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #eee;
        }

        .info {
            flex: 1;
        }

        .info p {
            margin: 6px 0;
            font-size: 1rem;
        }

        .status {
            font-weight: bold;
            color: #2980b9;
        }

        .comment {
            font-style: italic;
            color: #555;
        }

        .no-data {
            text-align: center;
            margin-top: 50px;
            font-size: 1.2rem;
            color: #777;
        }
    </style>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="logo-sakr-pharma fs-1 text-center justify-content-between sakr-heading">
            <h1 class="text-center my-5 ">Sakr Pharma</h1>
        </div>
        <div class="home-button-container text-center">
            <a href="home.php" class="btn btn-lg btn-primary">Home</a>
        </div>
        <div class="title my-3">My Uploaded Prescriptions</div>

        <?php if (empty($prescriptions)): ?>
            <div class="no-data">You haven’t uploaded any prescriptions yet.</div>
        <?php else: ?>
            <?php foreach ($prescriptions as $pres): ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($pres['image_url']) ?>" alt="Prescription">
                    <div class="info">
                        <p><strong>Uploaded:</strong> <?= htmlspecialchars($pres['upload_date']) ?></p>
                        <p><strong>Status:</strong> <span class="status"><?= htmlspecialchars($pres['status']) ?></span></p>
                        <p><strong>Admin Comment:</strong> <span class="comment"><?= htmlspecialchars($pres['comment']) ?></span></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>