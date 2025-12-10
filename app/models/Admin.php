<?php
class Admin {
    private $conn;
    private $table_name = "admin";

    public $id;
    public $username;
    public $password_hash;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($username, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $username);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row && password_verify($password, $row['password_hash'])) {
            return $row;
        }
        return false;
    }
}
?>
