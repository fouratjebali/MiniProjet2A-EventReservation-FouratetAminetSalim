<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/Event.php';
require_once __DIR__ . '/../models/Reservation.php';


class AdminController {
    private $db;
    private $admin;
    private $event;
    private $reservation;
    
    public function __construct() {
        session_start();
        $database = new Database();
        $this->db = $database->getConnection();
        $this->admin = new Admin($this->db);
        $this->event = new Event($this->db);
        $this->reservation = new Reservation($this->db);
    }
    
    public function showLogin() {
        if (isset($_SESSION['user_id'])) {
            header('Location: /admin/dashboard');
            exit;
        }
        require_once __DIR__ . '/../views/admin/login.php';

    }
    
    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $user = $this->admin->login($email, $password);
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            if ($user['role'] === 'participant') {
                header('Location: /participant/dashboard');
            } else {
                header('Location: /admin/dashboard');
            }
            exit;
        } else {
            $_SESSION['error'] = 'Invalid email or password';
            header('Location: /admin/login');
            exit;
        }
    }
    
    public function showRegister() {
        if (isset($_SESSION['user_id'])) {
            header('Location: /admin/dashboard');
            exit;
        }
        require_once __DIR__ . '/../views/admin/register.php';
    }
    
    public function register() {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'participant';
        
        if ($this->admin->register($username, $email, $password, $role)) {
            $_SESSION['success'] = 'Registration successful! Please login.';
            header('Location: /admin/login');
        } else {
            $_SESSION['error'] = 'Registration failed. Email may already exist.';
            header('Location: /admin/register');
        }
        exit;
    }
    
    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
    
    public function dashboard() {
        $this->checkAuth(['admin', 'organizer']);

        $stats = $this->admin->getStats();

        if ($_SESSION['role'] === 'organizer') {
            $stmt = $this->event->getByOrganizer($_SESSION['user_id']);
        } else {
            $stmt = $this->event->getAll();
        }

        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/admin/dashboard.php';

    }
    
    public function manageEvents() {
        $this->checkAuth(['admin', 'organizer']);

        if ($_SESSION['role'] === 'organizer') {
            $stmt = $this->event->getByOrganizer($_SESSION['user_id']);
        } else {
            $stmt = $this->event->getAllForAdmin();
        }

        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/admin/events.php';

    }
    
    public function showEventForm($id = null) {
        $this->checkAuth(['admin', 'organizer']);

        $event = null;
        if ($id) {
            $stmt = $this->event->getById($id);
            if ($stmt->rowCount() === 0) {
                $_SESSION['error'] = 'Event not found';
                header('Location: /admin/events');
                exit;
            }
            $event = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if organizer owns this event
            if ($_SESSION['role'] === 'organizer' && $event['organizer_id'] != $_SESSION['user_id']) {
                $_SESSION['error'] = 'Access denied';
                header('Location: /admin/events');
                exit;
            }
        }

        require_once __DIR__ . '/../views/admin/form_event.php';

    }
    
    public function createEvent() {
        $this->checkAuth(['admin', 'organizer']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->event->title = $_POST['title'] ?? '';
            $this->event->description = $_POST['description'] ?? '';
            $this->event->date = $_POST['date'] ?? '';
            $this->event->time = $_POST['time'] ?? '';
            $this->event->location = $_POST['location'] ?? '';
            $this->event->capacity = $_POST['capacity'] ?? 0;
            $this->event->status = $_POST['status'] ?? 'upcoming';
            $this->event->organizer_id = $_SESSION['user_id'];

            if ($this->event->create()) {
                $_SESSION['success'] = 'Event created successfully';
            } else {
                $_SESSION['error'] = 'Failed to create event';
            }

            header('Location: /admin/events');
            exit;
        }

        // show the form if not POST
        require_once __DIR__ . '/../views/admin/form_event.php';
    }
    
    public function updateEvent($id) {
        $this->checkAuth(['admin', 'organizer']);

        $stmt = $this->event->getById($id);
        if ($stmt->rowCount() === 0) {
            $_SESSION['error'] = 'Event not found';
            header('Location: /admin/events');
            exit;
        }

        $eventRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($_SESSION['role'] === 'organizer' && $eventRow['organizer_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Access denied';
            header('Location: /admin/events');
            exit;
        }

        $this->event->id = $id;
        $this->event->title = $_POST['title'] ?? '';
        $this->event->description = $_POST['description'] ?? '';
        $this->event->date = $_POST['date'] ?? '';
        $this->event->time = $_POST['time'] ?? '';
        $this->event->location = $_POST['location'] ?? '';
        $this->event->capacity = $_POST['capacity'] ?? 0;
        $this->event->status = $_POST['status'] ?? 'upcoming';
        $this->event->organizer_id = $eventRow['organizer_id'];

        if ($this->event->update()) {
            $_SESSION['success'] = 'Event updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update event';
        }

        header('Location: /admin/events');
        exit;
    }
    
    public function deleteEvent($id) {
        $this->checkAuth(['admin', 'organizer']);

        $stmt = $this->event->getById($id);
        if ($stmt->rowCount() === 0) {
            $_SESSION['error'] = 'Event not found';
            header('Location: /admin/events');
            exit;
        }

        $eventRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($_SESSION['role'] === 'organizer' && $eventRow['organizer_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Access denied';
            header('Location: /admin/events');
            exit;
        }

        if ($this->event->delete($id)) {
            $_SESSION['success'] = 'Event deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete event';
        }

        header('Location: /admin/events');
        exit;
    }
    
    public function manageUsers() {
        $this->checkAuth(['admin']);
        $stmt = $this->admin->getAllUsers();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . '/../views/admin/users.php';

    }
    
    public function manageRegistrations() {
        $this->checkAuth(['admin', 'organizer']);
        $stmt = $this->reservation->getAll();
        $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . '/../views/admin/registrations.php';

    }
    
    private function checkAuth($allowed_roles = []) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /admin/login');
            exit;
        }
        
        if (!empty($allowed_roles) && !in_array($_SESSION['role'], $allowed_roles)) {
            $_SESSION['error'] = 'Access denied';
            header('Location: /');
            exit;
        }
    }
}
?>