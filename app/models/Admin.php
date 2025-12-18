<?php
class Admin {
    private $conn;
    private $users_table = "users";
    private $events_table = "events";
    private $registrations_table = "registrations";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getStats() {
        $query = "SELECT 
            (SELECT COUNT(*) FROM " . $this->users_table . ") as total_users,
            (SELECT COUNT(*) FROM " . $this->events_table . ") as total_events,
            (SELECT COUNT(*) FROM " . $this->registrations_table . " WHERE status = 'registered') as total_registrations,
            (SELECT COUNT(*) FROM " . $this->events_table . " WHERE status = 'upcoming') as upcoming_events";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUsers() {
        $query = "SELECT * FROM " . $this->users_table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function deleteUser($id) {
        $query = "DELETE FROM " . $this->users_table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function login($email, $password) {
        $query = "SELECT id, username, email, password, role FROM " . $this->users_table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    public function register($username, $email, $password, $role) {
        $checkQuery = "SELECT id FROM " . $this->users_table . " WHERE email = :email OR username = :username";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':email', $email);
        $checkStmt->bindParam(':username', $username);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO " . $this->users_table . " (username, email, password, role) 
                  VALUES (:username, :email, :password, :role)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role);
        
        return $stmt->execute();
    }
}
?>