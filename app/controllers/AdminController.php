<?php
session_start();
require_once '../config/database.php';
require_once '../app/models/Admin.php';
require_once '../app/models/Event.php';
require_once '../app/models/Reservation.php';

class AdminController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $admin = new Admin($this->db);
            $result = $admin->login($_POST['username'], $_POST['password']);
            
            if ($result) {
                $_SESSION['admin_id'] = $result['id'];
                $_SESSION['admin_username'] = $result['username'];
                header('Location: /admin/dashboard');
                exit();
            } else {
                $error = "Identifiants incorrects";
            }
        }
        
        require_once '../app/views/admin/login.php';
    }

    public function dashboard() {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit();
        }
        
        $event = new Event($this->db);
        $stmt = $event->readAll();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once '../app/views/admin/dashboard.php';
    }

    public function logout() {
        session_destroy();
        header('Location: /admin/login');
        exit();
    }
}
?>
