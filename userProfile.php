<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: signin.php");
  exit;
}

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
  $new_fname = $_POST['new_fname'];
  $new_lname = $_POST['new_lname'];
  $new_email = $_POST['new_email'];
  $new_password = $_POST['new_password'];


  $update_query = "UPDATE users SET ";
  $update_values = [];


  if (!empty($new_fname)) {
    $update_query .= "fname = ?, ";
    $update_values[] = $new_fname;
  }
  if (!empty($new_lname)) {
    $update_query .= "lname = ?, ";
    $update_values[] = $new_lname;
  }
  if (!empty($new_email)) {
    $update_query .= "email = ?, ";
    $update_values[] = $new_email;
  }
  if (!empty($new_password)) {
    $update_query .= "password = ?, ";
    $update_values[] = $new_password;
  }

  $update_query = rtrim($update_query, ", ");
  $update_query .= " WHERE username = ?";


  $update_values[] = $user;


  $stmt = $conn->prepare($update_query);


  $types = str_repeat("s", count($update_values) - 1) . "s";
  $stmt->bind_param($types, ...$update_values);


  if ($stmt->execute()) {
    echo "User updated successfully!";
  } else {
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
}

$conn->close();

class User
{
  protected $fname;
  protected $lname;
  protected $username;
  protected $email;
  protected $phone_number;

  public function __construct($username, $fname, $lname, $email, $phone_number)
  {
    $this->username = $username;
    $this->fname = $fname;
    $this->lname = $lname;
    $this->email = $email;
    $this->phone_number = $phone_number;
  }
  public function getUserName()
  {
    return $this->username;
  }

  public function getEmail()
  {
    return $this->email;
  }
  public function getPhoneNumber()
  {
    return $this->phone_number;
  }

  public function getFullName()
  {
    return $this->fname . ' ' . $this->lname;
  }

  public function getFirstName()
  {
    return $this->fname;
  }
}

$user = new User(
  $_SESSION['username'],
  $_SESSION['fname'],
  $_SESSION['lname'],
  $_SESSION['email'],
  $_SESSION['phone_number']
);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sakr</title>
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
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/userProfile.css" />
</head>

<body>
  <main>
    <nav id="mynav" class="navbar navbar-expand-lg fixed-top bg-white">
      <div class="container">
        <a class="navbar-brand text-black sakr-heading" href="home.php">Sakr Pharma</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav d-flex justify-content-between align-items-center mt-3 mx-3">
            <li class="nav-item p-2 m-0 p-0">
              <a class="nav-link text-black position-relative m-0 p-0" aria-current="page" href="home.php">HOME</a>
            </li>
            <li class="nav-item p-2">
              <a class="nav-link text-8e8e8e position-relative" href="#myProfile">My
                Profile</a>
            </li>
            <li class="nav-item p-2">
              <a class="nav-link text-8e8e8e position-relative" href="#updateAccount">Update Password</a>
            </li>
          </ul>
          <ul class="navbar-nav d-flex justify-content-between align-items-center ms-auto mb-2 mb-lg-0">
            <li class="nav-item p-2">
              <h5 class="m-0 lead">Hello <?php echo htmlspecialchars($user->getFirstName()) ?> <i class="fa-regular fa-hand"></i></h5>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-align-right"></i>
              </a>
              <ul class="dropdown-menu">
                <li class="text-center mb-3">
                  <a class="btn btn-danger" href="logout.php" role="button">Logout</a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container">
      <div class="row position-relative">
        <div class="heading-my-profile text-center">
          <h1>My Profile</h1>
        </div>
      </div>
      <div id="myProfile" class="col-md-12 section">
        <div class="container user-profile rounded-5">
          <div class="row mb-3">
            <div class="name">
              <h3>Hello! <?php echo htmlspecialchars($user->getFullName()); ?></h3>
            </div>
          </div>
          <div class="row mb-4 border-bottom border-black">
            <div class="username">
              <h3><?php echo htmlspecialchars($user->getUserName()); ?></h3>
            </div>
          </div>
          <div class="row d-flex mb-2 justify-content-between ms-auto">
            <div class="name-fixed w-auto">
              <h4>Your Name:</h4>
            </div>
            <div class="name-backend w-auto">
              <h4 class="text-end"><?php echo htmlspecialchars($user->getFullName()); ?></h4>
            </div>
          </div>
          <div class="row d-flex mb-2 justify-content-between ms-auto">
            <div class="email-fixed w-auto">
              <h4>Your Email:</h4>
            </div>
            <div class="email-backend  w-auto">
              <h4 class="text-end"><?php echo htmlspecialchars($user->getEmail()); ?></h4>
            </div>
          </div>
          <div class="row d-flex mb-2 justify-content-between ms-auto">
            <div class="name-fixed w-auto">
              <h4>Mobile Number:</h4>
            </div>
            <div class="name-backend w-auto">
              <h4 class="text-end"><?php echo htmlspecialchars($user->getPhoneNumber()); ?></h4>
            </div>
          </div>
        </div>
      </div>
      <div id="updateAccount" class="col-md-12 section">
        <div class="update-account position-relative my-5 p-4">
          <div class="text-center position-relative heading-update-account">
            <h1>Update Account</h1>
          </div>
          <div class="calrify my-5 text-center lead">
            <span class="flex-nowrap justify-content-center d-flex align-items-center">
              <i class="fa-solid fa-arrow-right fs-2"></i>
              <p>If you want to update any thing of your information put your username in username input area and fill
                the information that you want to update it</p>
              <i class="fa-solid fa-arrow-left fs-2"></i>
            </span>
          </div>
          <div class="form bg-primary text-center align-items-center my-5 rounded-5 p-5">
            <form method="POST" action="">
              <input oninput="validateFormUpdateAccount(this)" type="text" placeholder="username" id="username"
                name="username" class="form-control bg-body-secondary my-3 form-control-lg" />
              <div class="alert alert-danger d-none">
                Please Enter Your Username Correctly
              </div>
              <input oninput="validateFormUpdateAccount(this)" type="text" placeholder="New First Name" id="fName"
                name="new_fname" class="form-control bg-body-secondary my-3 form-control-lg" />
              <div class="alert alert-danger d-none">
                Please Enter Your First Name Correctly
              </div>
              <input oninput="validateFormUpdateAccount(this)" type="text" placeholder="New Last Name" id="lName"
                name="new_lname" class="form-control bg-body-secondary my-3 form-control-lg" />
              <div class="alert alert-danger d-none">
                Please Enter Your Last Name Correctly
              </div>
              <input oninput="validateFormUpdateAccount(this)" type="text" placeholder="New Email" id="eMail"
                name="new_email" class="form-control bg-body-secondary my-3 form-control-lg" />
              <div class="alert alert-danger d-none">
                Please Enter Your Email Correctly
              </div>
              <input oninput="validateFormUpdateAccount(this)" placeholder="New Password" id="passWord"
                name="new_password" class="form-control bg-body-secondary my-3 form-control-lg" />
              <div class="alert alert-danger d-none">
                Password length Must be at least 8 Contain at least 1
                Capital Letter , 1 small letter numbers and special
                characters:"#$@%!^&"
              </div>
              <button class="btn-lg btn btn-light text-center" type="submit">
                Update Account
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/userProfile.js"></script>
</body>

</html>