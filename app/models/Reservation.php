<?php
class Reservation {
    private $conn;
    private $table_name = "reservations";

    public $id;
    public $event_id;
    public $name;
    public $email;
    public $phone;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET event_id=:event_id, name=:name, email=:email, phone=:phone";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":event_id", $this->event_id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        
        return $stmt->execute();
    }

    public function getByEvent($event_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE event_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $event_id);
        $stmt->execute();
        return $stmt;
    }

    public function countByEvent($event_id) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE event_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $event_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
?>
