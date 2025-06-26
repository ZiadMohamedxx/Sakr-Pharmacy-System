<?php
require_once("Database.php"); 

$loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST["admin_id"];
    $admin_pass = $_POST["admin_pass"];

    $conn = Database::getInstance()->getConnection();

    $stmt = $conn->prepare("SELECT Admin_Password FROM admin_details WHERE Admin_ID = ?");

    $stmt->execute([$admin_id]);
    $row = $stmt->fetch();

    if ($row && $admin_pass === $row["Admin_Password"])
    {
        header("Location: tst.php");
        exit();
    } else {
        $loginError = "Invalid Admin ID or Password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Portal Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f1f1f1;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-container {
      background: #fff;
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
      width: 350px;
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
    }

    label {
      display: block;
      margin-top: 10px;
      font-weight: bold;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      margin-bottom: 15px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    button {
      width: 100%;
      padding: 10px;
      background: #007bff;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background: #0056b3;
    }

    .error {
      background-color: #f8d7da;
      color: #721c24;
      margin-bottom: 15px;
      padding: 10px;
      border-radius: 5px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Admin Login</h2>
    <?php if (!empty($loginError)): ?>
      <div class="error"><?php echo $loginError; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
      <label for="admin_id">Admin ID</label>
      <input type="text" name="admin_id" id="admin_id" required>

      <label for="admin_pass">Password</label>
      <input type="password" name="admin_pass" id="admin_pass" required>

      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
