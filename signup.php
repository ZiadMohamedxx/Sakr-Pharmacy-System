<?php
require_once("Database.php");
$usernameError = false;
$emailError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $user = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    $conn = Database::getInstance()->getConnection();

    try {
        // Check if username already exists
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $checkStmt->execute([$user]);
        $userExists = $checkStmt->fetchColumn();

        // Check if email already exists
        $checkEmailStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $checkEmailStmt->execute([$email]);
        $emailExists = $checkEmailStmt->fetchColumn();

        if ($emailExists > 0) 
        {
        $emailError = true;
        }
        if ($userExists > 0) 
        {
            echo "Username already taken. Please choose another one.";
            $usernameError = true;
        }

         if(!$emailExists && !$userExists) 
         {
            $conn->beginTransaction(); 
            $stmt1 = $conn->prepare('INSERT INTO users (fname, lname, username, password, phone_number, email) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt1->execute([$fname, $lname, $user, $pass, $phone_number, $email]);
            $user_id = $conn->lastInsertId();

            $stmt2 = $conn->prepare('INSERT INTO customers (user_id, username, phone_number) VALUES (?, ?, ?)');
            $stmt2->execute([$user_id, $user, $phone_number]);

            $conn->commit();
            header("Location: signin.php");
            exit(); // Make sure to stop execution after redirect
        }
    } 
    catch (Exception $e) 
    {
        $conn->rollBack(); 
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap"
      rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/all.min.css" />
    <link rel="stylesheet" href="css/register.css" />
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <div class="container position-relative my-5">
      <nav id="mynav" class="navbar navbar-expand-lg fixed-top bg-white">
        <div class="container">
          <a class="navbar-brand text-black sakr-heading" href="home.php"
            >Sakr Pharma</a
          >
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul
              class="navbar-nav d-flex justify-content-between align-items-center mt-3 mx-3"
            >
              <li class="nav-item p-2 m-0 p-0">
                <a
                  class="nav-link text-black position-relative active m-0 p-0"
                  aria-current="page"
                  href=""
                  >HOME</a
                >
              </li>
              <li class="nav-item p-2">
                <a class="nav-link text-8e8e8e position-relative" href="Product.php"
                  >Products</a
                >
              </li>
              <li class="nav-item p-2">
                <a class="nav-link text-8e8e8e position-relative" href="home.php#AboutUS"
                  >Contacts</a
                >
              </li>
            </ul>
            <ul
              class="navbar-nav d-flex justify-content-between align-items-center ms-auto mb-2 mb-lg-0"
            >
              <li class="nav-item p-2">
                <a class="nav-link text-black position-relative" href="#"
                  ><span><i class="fa-solid fa-cart-shopping"></i></span
                ></a>
              </li>
              <li class="nav-item p-2">
                <a class="nav-link text-black position-relative" href="#"
                  ><i class="fa-solid fa-user"></i
                ></a>
              </li>
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown"
                  href="#"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
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
    <div class="container position-relative align-content-center my-5">
      <div class="col-xl-5 register-layout">
        <div class="box-register position-relative rounded-4 text-black">
          <div class="heading-register mb-5">
            <h1 class="p-0 text-center">Create New Account</h1>
          </div>
          <form method="POST" class="text-black">
            <div
              class="row d-flex flex-nowrap align-content-center justify-content-around"
            >
              <div class="col-md-12">
                <div class="form-floating mb-3">
                  <input
                    type="text"
                    oninput="validateFormRegister(this)"
                    placeholder="First Name"
                    id="fName"
                    name="fname"
                    required
                    class="form-control rounded-4 inputs-styling"
                  />
                  <div class="alert alert-danger d-none">
                    Please Enter Your Name Correctly (at least 3 Letters)
                  </div>
                  <label for="fName">First Name: </label>
                </div>
                <div class="form-floating mb-3">
                  <input
                    type="text"
                    oninput="validateFormRegister(this)"
                    placeholder="Second Name"
                    id="sName"
                    name="lname"
                    required
                    class="form-control rounded-4 inputs-styling"
                  />
                  <div class="alert alert-danger d-none">
                    Please Enter Your Name Correctly (at least 3 Letters)
                  </div>
                  <label for="sName">Second Name: </label>
                </div>
              </div>
            </div>
            <div class="row d-flex align-content-center justify-content-around">
              <div class="col-md-12">
                   <div class="form-floating mb-3">
                  <input
                    type="text"
                    name="username"
                    oninput="validateFormRegister(this)"
                    placeholder="Username"
                    id="username"
                    required
                    class="form-control rounded-4 inputs-styling"
                  />
                  <div class="alert alert-danger d-none">
                    Please Enter the username Correctly
                  </div>
                  <?php if ($usernameError): ?>
                  <div class="alert alert-danger mt-2">
                  Username already taken. Please choose another one.
                   </div>
                  <?php else: ?>
                  <div class="alert alert-danger d-none">
                  Please Enter the username Correctly
                  </div>
                  <?php endif; ?>
                  <label for="username">username: </label>
                </div>
                <div class="form-floating mb-3">
                <input
                type="email"
                name="email"
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                oninput="validateFormRegister(this)"
                placeholder="E-Mail"
                id="eMailRegister"
                required
                class="form-control rounded-4 inputs-styling"
                />
                <?php if ($emailError): ?>
                <div class="alert alert-danger mt-2">
                Email already registered. Please use a different one.
                </div>
                <?php else: ?>
                <div class="alert alert-danger d-none">
                Please Enter the Email Correctly
                </div>
                <?php endif; ?>
                <label for="eMailRegister">E-Mail: </label>
                </div>

             
              </div>
            </div>
            <div class="row d-flex align-content-center justify-content-around">
              <div class="col-md-12">
                <div class="form-floating mb-3">
                  <input
                    type="text"
                    name="phone_number"

                    oninput="validateFormRegister(this)"
                    placeholder="Phone"
                    id="Phone"
                    required
                    class="form-control rounded-4 inputs-styling"
                  />
                  <div class="alert alert-danger d-none">
                    Please Enter Your Phone Number Correctly(ex:01027351835)
                  </div>
                  <label for="Phone">Phone: </label>
                </div>
              </div>
            </div>
            <div class="row d-flex align-content-center justify-content-around">
              <div class="col-md-12">
                <div class="form-floating mb-3">
                  <input
                    type="password"
                    name="password"

                    oninput="validateFormRegister(this)"
                    placeholder="Password"
                    id="passwordRegister"
                    required
                    class="form-control rounded-4 inputs-styling"
                  />
                  <div class="alert alert-danger d-none">
                    Password length Must be at least 8 Contain at least 1
                    Capital Letter , 1 small letter numbers and special
                    characters:"#$@%!^&"
                  </div>
                  <label for="passwordRegister">Password: </label>
                </div>
              </div>
            </div>
            <div class="row d-flex align-content-center justify-content-around">
              <div
                class="col-md-5 d-flex align-content-center justify-content-around"
              >
                <button class="button-submit-login" type="submit">
                  submit
                </button>
              </div>
            </div>
            <div class="row text-center d-flex flex-column justify-content-center align-items-center flex-nowrap">
              <div class="already-registered p-0 m-0">
                <p class="p-0 m-0">Already Registered?</p>
              </div>
              <div class="login-text">
                <a class="text-decoration-none" href="signin.php">Login</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script src="JS/bootstrap.bundle.min.js"></script>
    <script src="JS/index.js"></script>
  </body>
</html>
