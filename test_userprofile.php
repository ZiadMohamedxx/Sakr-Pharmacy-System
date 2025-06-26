<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: test_signin.php");
    exit;
}

class User {
    protected $fname;
    protected $lname;
    protected $username;

    public function __construct($username, $fname, $lname) {
        $this->username = $username;
        $this->fname = $fname;
        $this->lname = $lname;
    }

    public function getFullName() {
        return $this->fname . ' ' . $this->lname;
    }
}

$user = new User($_SESSION['username'], $_SESSION['fname'], $_SESSION['lname']);
?>

<!-- HTML -->
<!DOCTYPE html>
<html>
<head>
  <title>User Profile</title>
</head>
<body>
  <h1>Welcome, <?php echo htmlspecialchars($user->getFullName()); ?>!</h1>
  <p><a href="test_logout.php">Logout</a></p>
</body>
</html>
