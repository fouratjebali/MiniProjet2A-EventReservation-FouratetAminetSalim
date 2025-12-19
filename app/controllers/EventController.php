<?php
session_start();

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Event.php';
require_once __DIR__ . '/../models/Reservation.php';

class EventController
{
    private $db;
    private $event;
    private $reservation;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->event = new Event($this->db);
        $this->reservation = new Reservation($this->db);
    }

    public function index()
    {
        $stmt = $this->event->getAll();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/events/list.php';
    }

    public function list()
    {
        $search = $_GET['search'] ?? '';
        $filter = $_GET['filter'] ?? 'all';

        $stmt = $this->event->search($search, $filter);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/events/list.php';
    }

    // Compatibility handler for routes calling /events/{id}
    public function show($id)
    {
        if (empty($id)) {
            header('Location: /');
            exit;
        }

        $stmt = $this->event->getById($id);

        if ($stmt->rowCount() === 0) {
            header('Location: /');
            exit;
        }

        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        $isRegistered = false;
        if (isset($_SESSION['user_id'])) {
            $isRegistered = $this->reservation->isRegistered($_SESSION['user_id'], $id);
        }

        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            if ($event['status'] === 'upcoming' && !$isRegistered) {
                $availableSlots = $event['capacity'] - $event['registered_count'];
                if ($availableSlots > 0) {
                    $this->reservation->user_id = $_SESSION['user_id'];
                    $this->reservation->event_id = $id;
                    if ($this->reservation->create()) {
                        $message = 'Registration successful!';
                        $isRegistered = true;
                        $event['registered_count']++;
                    } else {
                        $message = 'Registration failed. Please try again.';
                    }
                } else {
                    $message = 'Sorry, this event is full.';
                }
            }
        }

        require_once __DIR__ . '/../views/events/details.php';
    }

    public function details()
    {
        if (!isset($_GET['id'])) {
            header('Location: /');
            exit;
        }

        $id = $_GET['id'];
        $stmt = $this->event->getById($id);

        if ($stmt->rowCount() === 0) {
            header('Location: /');
            exit;
        }

        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        $isRegistered = false;
        if (isset($_SESSION['user_id'])) {
            $isRegistered = $this->reservation->isRegistered($_SESSION['user_id'], $id);
        }

        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            if ($event['status'] === 'upcoming' && !$isRegistered) {
                $availableSlots = $event['capacity'] - $event['registered_count'];
                if ($availableSlots > 0) {
                    $this->reservation->user_id = $_SESSION['user_id'];
                    $this->reservation->event_id = $id;
                    if ($this->reservation->create()) {
                        $message = 'Registration successful!';
                        $isRegistered = true;
                        $event['registered_count']++;
                    } else {
                        $message = 'Registration failed. Please try again.';
                    }
                } else {
                    $message = 'Sorry, this event is full.';
                }
            }
        }

        require_once __DIR__ . '/../views/events/details.php';
    }

    public function create()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
            header('Location: /');
            exit;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->event->title = $_POST['title'] ?? '';
            $this->event->description = $_POST['description'] ?? '';
            $this->event->date = $_POST['date'] ?? '';
            $this->event->time = $_POST['time'] ?? '';
            $this->event->location = $_POST['location'] ?? '';
            $this->event->capacity = $_POST['capacity'] ?? 0;
            $this->event->organizer_id = $_SESSION['user_id'];

            if ($this->event->create()) {
                $success = 'Event created successfully!';
            } else {
                $error = 'Failed to create event';
            }
        }

        require_once __DIR__ . '/../views/admin/form_event.php';
    }

    public function myEvents()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
            header('Location: /');
            exit;
        }

        $stmt = $this->event->getByOrganizer($_SESSION['user_id']);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function edit()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer' || !isset($_GET['id'])) {
            header('Location: /');
            exit;
        }

        $id = $_GET['id'];
        $stmt = $this->event->getById($id);

        if ($stmt->rowCount() === 0) {
            header('Location: /event/myEvents');
            exit;
        }

        $event = $stmt->fetch(PDO::FETCH_ASSOC);
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->event->id = $id;
            $this->event->title = $_POST['title'] ?? '';
            $this->event->description = $_POST['description'] ?? '';
            $this->event->date = $_POST['date'] ?? '';
            $this->event->time = $_POST['time'] ?? '';
            $this->event->location = $_POST['location'] ?? '';
            $this->event->capacity = $_POST['capacity'] ?? 0;
            $this->event->status = $_POST['status'] ?? 'upcoming';
            $this->event->organizer_id = $_SESSION['user_id'];

            if ($this->event->update()) {
                $success = 'Event updated successfully!';
                $stmt = $this->event->getById($id);
                $event = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error = 'Failed to update event';
            }
        }

        require_once __DIR__ . '/../views/admin/form_event.php';
    }

    public function registrations()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer' || !isset($_GET['id'])) {
            header('Location: /');
            exit;
        }

        $eventId = $_GET['id'];
        $eventStmt = $this->event->getById($eventId);

        if ($eventStmt->rowCount() === 0) {
            header('Location: /event/myEvents');
            exit;
        }

        $event = $eventStmt->fetch(PDO::FETCH_ASSOC);
        $regStmt = $this->reservation->getByEvent($eventId);
        $registrations = $regStmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/admin/registrations.php';
    }

    public function myRegistrations()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /admin/login');
            exit;
        }

        $stmt = $this->reservation->getByUser($_SESSION['user_id']);
        $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/participant/dashboard.php';
    }

    public function cancelRegistration()
    {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            header('Location: /');
            exit;
        }

        $this->reservation->cancel($_SESSION['user_id'], $_GET['id']);
        header('Location: /event/myRegistrations');
        exit;
    }
}
