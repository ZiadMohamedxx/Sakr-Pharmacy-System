<?php
class Database {
    private static $instance = null;
    private $connection;

    private $host = 'localhost';
    private $db   = 'pharmacy_db';
    private $user = 'root';
    private $pass = '';

    private function __construct() {
        try {
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>
