<?php
class Event {
    private $conn;
    private $table = "events";

    public $id;
    public $title;
    public $description;
    public $date;
    public $time;
    public $location;
    public $capacity;
    public $organizer_id;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT e.*, u.username as organizer_name,
                  (SELECT COUNT(*) FROM registrations WHERE event_id = e.id AND status = 'registered') as registered_count
                  FROM " . $this->table . " e 
                  JOIN users u ON e.organizer_id = u.id 
                  WHERE e.status = 'upcoming' AND e.date >= CURDATE()
                  ORDER BY e.date ASC, e.time ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function search($search = '', $filter = 'all') {
        $query = "SELECT e.*, u.username as organizer_name,
                  (SELECT COUNT(*) FROM registrations WHERE event_id = e.id AND status = 'registered') as registered_count
                  FROM " . $this->table . " e 
                  JOIN users u ON e.organizer_id = u.id 
                  WHERE 1=1";

        if (!empty($search)) {
            $query .= " AND (e.title LIKE :search OR e.description LIKE :search OR e.location LIKE :search)";
        }

        if ($filter === 'upcoming') {
            $query .= " AND e.status = 'upcoming' AND e.date >= CURDATE()";
        } elseif ($filter === 'completed') {
            $query .= " AND e.status = 'completed'";
        }

        $query .= " ORDER BY e.date ASC, e.time ASC";

        $stmt = $this->conn->prepare($query);

        if (!empty($search)) {
            $searchParam = "%{$search}%";
            $stmt->bindParam(':search', $searchParam);
        }

        $stmt->execute();
        return $stmt;
    }

    public function getById($id) {
        $query = "SELECT e.*, u.username as organizer_name,
                  (SELECT COUNT(*) FROM registrations WHERE event_id = e.id AND status = 'registered') as registered_count
                  FROM " . $this->table . " e 
                  JOIN users u ON e.organizer_id = u.id 
                  WHERE e.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt;
    }

    public function getByOrganizer($organizer_id) {
        $query = "SELECT e.*,
                  (SELECT COUNT(*) FROM registrations WHERE event_id = e.id AND status = 'registered') as registered_count
                  FROM " . $this->table . " e 
                  WHERE e.organizer_id = :organizer_id 
                  ORDER BY e.date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':organizer_id', $organizer_id);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (title, description, date, time, location, capacity, organizer_id) 
                  VALUES (:title, :description, :date, :time, :location, :capacity, :organizer_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':time', $this->time);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':capacity', $this->capacity);
        $stmt->bindParam(':organizer_id', $this->organizer_id);
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title, description = :description, date = :date, 
                      time = :time, location = :location, capacity = :capacity, status = :status 
                  WHERE id = :id AND organizer_id = :organizer_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':time', $this->time);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':capacity', $this->capacity);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':organizer_id', $this->organizer_id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getAllForAdmin() {
        $query = "SELECT e.*, u.username as organizer_name,
                  (SELECT COUNT(*) FROM registrations WHERE event_id = e.id AND status = 'registered') as registered_count
                  FROM " . $this->table . " e 
                  JOIN users u ON e.organizer_id = u.id 
                  ORDER BY e.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>