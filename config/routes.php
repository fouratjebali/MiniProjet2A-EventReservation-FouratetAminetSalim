<?php
function route($uri) {
    $uri = trim($uri, '/');
    
    switch ($uri) {
        case '':
        case 'events':
            require_once '../app/controllers/EventController.php';
            $controller = new EventController();
            $controller->index();
            break;
        
        case (preg_match('/^events\/(\d+)$/', $uri, $matches) ? true : false):
            require_once '../app/controllers/EventController.php';
            $controller = new EventController();
            $controller->show($matches[1]);
            break;
        
        case 'reservation':
            require_once '../app/controllers/EventController.php';
            $controller = new EventController();
            $controller->reserve();
            break;
        
        case 'admin':
        case 'admin/login':
            require_once '../app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->login();
            break;
        
        case 'admin/dashboard':
            require_once '../app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->dashboard();
            break;
        
        case 'admin/logout':
            require_once '../app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->logout();
            break;
        
        default:
            http_response_code(404);
            echo "Page non trouvée";
            break;
    }
}
?>
