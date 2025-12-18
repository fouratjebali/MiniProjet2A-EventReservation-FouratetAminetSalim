<?php
class Database {
    private $host = "minievent_db";
    private $db_name = "minievent_db";
    private $username = "minievent_user";
    private $password = "minievent_pass";
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        $maxRetries = 10;
        $retryCount = 0;
        
        while ($retryCount < $maxRetries) {
            try {
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->exec("set names utf8");
                break;
            } catch(PDOException $e) {
                $retryCount++;
                if ($retryCount >= $maxRetries) {
                    die("Connection error: " . $e->getMessage());
                }
                sleep(2);
            }
        }
        
        return $this->conn;
    }
}
?>