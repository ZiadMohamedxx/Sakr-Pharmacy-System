<?php
require_once 'Database.php';
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user = $_POST['username'];
  $pass = $_POST['password'];

  $conn = Database::getInstance()->getConnection();

  try {
    $stmt = $conn->prepare("SELECT id, username, fname, lname, email, phone_number, password FROM users WHERE username = ?");
    $stmt->execute([$user]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userData && password_verify($pass, $userData['password'])) {
      $_SESSION['user_id'] = $userData['id'];
      $_SESSION['username'] = $userData['username'];
      $_SESSION['fname'] = $userData['fname'];
      $_SESSION['lname'] = $userData['lname'];
      $_SESSION['email'] = $userData['email'];
      $_SESSION['phone_number'] = $userData['phone_number'];
      $_SESSION['customer_id'] = $userData['id'];
      header("Location: home.php");
      exit;
    } else {
      $error = "Invalid username or password.";
    }
  } catch (Exception $e) {
    $error = "Error: " . $e->getMessage();
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/all.min.css" />
  <link rel="stylesheet" href="css/login.css" />
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <div class="container position-relative mb-5">
    <nav id="mynav" class="navbar navbar-expand-lg fixed-top bg-opacity-50 bg-white">
      <div class="container">
        <a class="navbar-brand text-black sakr-heading" href="home.php">Sakr Pharma</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav d-flex justify-content-between align-items-center mt-3 mx-3">
            <li class="nav-item p-2 m-0 p-0">
              <a class="nav-link text-8e8e8e position-relative active m-0 p-0" aria-current="page" href="home.php">HOME</a>
            </li>
            <li class="nav-item p-2">
              <a class="nav-link text-8e8e8e position-relative" href="Product.php">Products</a>
            </li>
            <li class="nav-item p-2">
              <a class="nav-link text-8e8e8e position-relative" href="home.php#AboutUS">Contacts</a>
            </li>
          </ul>
          <ul class="navbar-nav d-flex justify-content-between align-items-center ms-auto mb-2 mb-lg-0">
            <li class="nav-item p-2">
              <a class="nav-link text-black position-relative" href="#"><span><i
                    class="fa-solid fa-cart-shopping"></i></span></a>
            </li>
            <li class="nav-item p-2">
              <a class="nav-link text-black position-relative" href="#"><i class="fa-solid fa-user"></i></a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-align-right"></i>
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li>
                  <a class="dropdown-item" href="#">Another action</a>
                </li>
                <li>
                  <a class="dropdown-item" href="#">Something else here</a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>
  <div class="container position-relative vh-100 align-content-center">
    <div class="col-xl-5">
      <div class="box-login position-relative rounded-4 bg-white">
        <div class="heading-login mb-5 p-0">
          <h1 class="lead p-0 fs-1 text-center">Login</h1>
        </div>
        <form method="POST">
          <div class="row d-flex">
            <div class="col-md-12">
              <div class="form-floating mb-3">
                <input type="text" name="username" oninput="validateFormLogin(this)" placeholder="Username:"
                  id="userName" required class="form-control" />
                <div class="alert alert-danger d-none">
                  Please Enter the User Name Correctly
                </div>
                <label for="userName">Username:</label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-floating mb-3">
                <input type="password" name="password" oninput="validateFormLogin(this)" placeholder="Password:"
                  id="password" required class="form-control" />
                <div id="password" class="alert alert-danger d-none">
                  Password length Must be at least 8 Contain at least 1
                  Capital Letter , 1 small letter numbers and special
                  characters:"#$@%!^&"
                </div>
                <label for="password">Password:</label>
              </div>
            </div>
          </div>
          <div class="row d-flex align-items-center justify-content-around">
            <div class="col-md-6 d-flex align-items-center justify-content-around text-center">
              <button class="button-submit-login" type="submit">Login</button>
            </div>
          </div>
          <div class="row d-flex align-items-center justify-content-around mt-5">
  <div class="col-md-6 d-flex flex-column align-items-center justify-content-around text-center">
    <p class="text-center">You Don't Have Account?</p>
    <a href="signup.php">
      <button class="btn btn-primary mb-2" type="button">Register</button>
    </a>
    <a href="admin_login.php">
      <button class="btn btn-secondary" type="button">Admin</button>
    </a>
  </div>
</div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/index.js"></script>
</body>

</html>