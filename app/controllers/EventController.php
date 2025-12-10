<?php
require_once '../config/database.php';
require_once '../app/models/Event.php';
require_once '../app/models/Reservation.php';

class EventController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        $event = new Event($this->db);
        $stmt = $event->readAll();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once '../app/views/events/list.php';
    }

    public function show($id) {
        $event = new Event($this->db);
        $event->id = $id;
        $eventData = $event->readOne();
        
        if ($eventData) {
            require_once '../app/views/events/details.php';
        } else {
            echo "Événement non trouvé";
        }
    }

    public function reserve() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reservation = new Reservation($this->db);
            
            $reservation->event_id = $_POST['event_id'];
            $reservation->name = htmlspecialchars(strip_tags($_POST['name']));
            $reservation->email = htmlspecialchars(strip_tags($_POST['email']));
            $reservation->phone = htmlspecialchars(strip_tags($_POST['phone']));
            
            if ($reservation->create()) {
                $message = "Réservation effectuée avec succès!";
            } else {
                $message = "Erreur lors de la réservation.";
            }
            
            require_once '../app/views/events/confirmation.php';
        }
    }
}
?>
