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
            $events = $this->event->getByOrganizer($_SESSION['user_id']);
        } else {
            $events = $this->event->getAll();
        }
        
        require_once __DIR__ . '/../views/admin/dashboard.php';

    }
    
    public function manageEvents() {
        $this->checkAuth(['admin', 'organizer']);
        
        if ($_SESSION['role'] === 'organizer') {
            $events = $this->event->getByOrganizer($_SESSION['user_id']);
        } else {
            $events = $this->event->getAll();
        }
        
        require_once __DIR__ . '/../views/admin/events.php';

    }
    
    public function showEventForm($id = null) {
        $this->checkAuth(['admin', 'organizer']);
        
        $event = null;
        if ($id) {
            $event = $this->event->getById($id);
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
        
        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'date' => $_POST['date'] ?? '',
            'time' => $_POST['time'] ?? '',
            'location' => $_POST['location'] ?? '',
            'capacity' => $_POST['capacity'] ?? 0,
            'organizer_id' => $_SESSION['user_id'],
            'status' => $_POST['status'] ?? 'upcoming'
        ];
        
        if ($this->event->create($data)) {
            $_SESSION['success'] = 'Event created successfully';
        } else {
            $_SESSION['error'] = 'Failed to create event';
        }
        
        header('Location: /admin/events');
        exit;
    }
    
    public function updateEvent($id) {
        $this->checkAuth(['admin', 'organizer']);
        
        $event = $this->event->getById($id);
        if ($_SESSION['role'] === 'organizer' && $event['organizer_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Access denied';
            header('Location: /admin/events');
            exit;
        }
        
        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'date' => $_POST['date'] ?? '',
            'time' => $_POST['time'] ?? '',
            'location' => $_POST['location'] ?? '',
            'capacity' => $_POST['capacity'] ?? 0,
            'status' => $_POST['status'] ?? 'upcoming'
        ];
        
        if ($this->event->update($id, $data)) {
            $_SESSION['success'] = 'Event updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update event';
        }
        
        header('Location: /admin/events');
        exit;
    }
    
    public function deleteEvent($id) {
        $this->checkAuth(['admin', 'organizer']);
        
        $event = $this->event->getById($id);
        if ($_SESSION['role'] === 'organizer' && $event['organizer_id'] != $_SESSION['user_id']) {
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
        $users = $this->admin->getAllUsers();
        require_once __DIR__ . '/../views/admin/users.php';

    }
    
    public function manageRegistrations() {
        $this->checkAuth(['admin', 'organizer']);
        $registrations = $this->reservation->getAll();
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