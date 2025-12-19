<?php
class Reservation {
    private $conn;
    private $table = "registrations";

    public $id;
    public $user_id;
    public $event_id;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " (user_id, event_id) VALUES (:user_id, :event_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':event_id', $this->event_id);
        return $stmt->execute();
    }

    public function cancel($user_id, $event_id) {
        $query = "UPDATE " . $this->table . " SET status = 'cancelled' 
                  WHERE user_id = :user_id AND event_id = :event_id AND status = 'registered'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':event_id', $event_id);
        return $stmt->execute();
    }

    public function getByUser($user_id) {
        $query = "SELECT e.*, r.registration_date, r.status as reg_status, u.username as organizer_name
                  FROM " . $this->table . " r
                  JOIN events e ON r.event_id = e.id
                  JOIN users u ON e.organizer_id = u.id
                  WHERE r.user_id = :user_id
                  ORDER BY e.date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt;
    }

    public function getByEvent($event_id) {
        $query = "SELECT r.*, u.username, u.email 
                  FROM " . $this->table . " r 
                  JOIN users u ON r.user_id = u.id 
                  WHERE r.event_id = :event_id AND r.status = 'registered'
                  ORDER BY r.registration_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->execute();
        return $stmt;
    }

    public function getAll() {
        $query = "SELECT r.*, e.title as event_title, e.date, e.time, u.username, u.email 
                  FROM " . $this->table . " r 
                  JOIN events e ON r.event_id = e.id 
                  JOIN users u ON r.user_id = u.id 
                  ORDER BY r.registration_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function isRegistered($user_id, $event_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE user_id = :user_id AND event_id = :event_id AND status = 'registered'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>