<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pharmacy_db";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user = $_POST['username'];

    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "User '$user' found!";
    } else {
        echo "User '$user' not found!";
    }

    $stmt->close();
}

$conn->close();
?>
