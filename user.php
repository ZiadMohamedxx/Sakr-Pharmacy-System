<?php
class User {
    protected $conn;
    protected $userId;
    protected $fname;
    protected $lname;
    protected $email;
    protected $password;

    public function __construct($userId, $fname, $lname, $email, $password, $conn) {
        $this->userId = $userId;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->conn = $conn;
    }
      public function getId() {
        return $this->userId;
    }
      public function getEmail() {
        return $this->email;
    }

    public function getFullName() {
        return $this->fname . ' ' . $this->lname;
    }

    public function creatAccount($name, $email, $password)
    {
        // $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        // $stmt = $this->conn->prepare($sql);
        // $stmt->bindParam(':name', $name);
        // $stmt->bindParam(':email', $email);
        // $stmt->bindParam(':password', password_hash($password, PASSWORD_BCRYPT));
        // if ($stmt->execute()) {
        //     return true;
        // } else {
        //     return false;
        // }
    }

    public function login() {
       //login logic
    }
    public function logout() {
        //logout logic
    }
    public function updateProfile($email,$name) {
    //update profile logic
    }
}
?>
