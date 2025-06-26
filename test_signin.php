<?php
require_once 'Database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $conn = Database::getInstance()->getConnection();

    try {
        $stmt = $conn->prepare("SELECT id, username, fname, lname, password FROM users WHERE username = ?");
        $stmt->execute([$user]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData && password_verify($pass, $userData['password'])) {
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['username'] = $userData['username'];
            $_SESSION['fname'] = $userData['fname'];
            $_SESSION['lname'] = $userData['lname'];

            header("Location: test_userprofile.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }

    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html>
<head>
  <title>Test Login</title>
</head>
<body>
  <h2>Test Login Page</h2>
  <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
  <form method="POST" action="test_signin.php">
    <label>Username: <input type="text" name="username" required></label><br><br>
    <label>Password: <input type="password" name="password" required></label><br><br>
    <button type="submit">Login</button>
  </form>
</body>
</html>
