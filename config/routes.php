<?php

function route($uri)
{
    $uri = trim($uri, '/');

    $baseControllerPath = __DIR__ . '/../app/controllers/';

    if ($uri === '' || $uri === 'events') {
        require_once $baseControllerPath . 'EventController.php';
        $controller = new EventController();
        $controller->list();
        return;
    }

    if (preg_match('#^events/(\d+)$#', $uri, $matches)) {
        require_once $baseControllerPath . 'EventController.php';
        $controller = new EventController();
        $controller->show((int)$matches[1]);
        return;
    }

    if ($uri === 'reservation') {
        require_once $baseControllerPath . 'EventController.php';
        $controller = new EventController();
        $controller->reserve();
        return;
    }

    if ($uri === 'admin' || $uri === 'admin/login') {
        require_once $baseControllerPath . 'AdminController.php';
        $controller = new AdminController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLogin();
        }
        return;
    }

    if ($uri === 'admin/dashboard') {
        require_once $baseControllerPath . 'AdminController.php';
        $controller = new AdminController();
        $controller->dashboard();
        return;
    }

    // Admin events list
    if ($uri === 'admin/events') {
        require_once $baseControllerPath . 'AdminController.php';
        $controller = new AdminController();
        $controller->manageEvents();
        return;
    }

    // Create event
    if ($uri === 'admin/events/create') {
        require_once $baseControllerPath . 'AdminController.php';
        $controller = new AdminController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->createEvent();
        } else {
            $controller->showEventForm();
        }
        return;
    }

    // Edit event
    if (preg_match('#^admin/events/edit/(\d+)$#', $uri, $matches)) {
        require_once $baseControllerPath . 'AdminController.php';
        $controller = new AdminController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updateEvent((int)$matches[1]);
        } else {
            $controller->showEventForm((int)$matches[1]);
        }
        return;
    }

    // Delete event
    if (preg_match('#^admin/events/delete/(\d+)$#', $uri, $matches)) {
        require_once $baseControllerPath . 'AdminController.php';
        $controller = new AdminController();
        $controller->deleteEvent((int)$matches[1]);
        return;
    }

    if ($uri === 'admin/logout') {
        require_once $baseControllerPath . 'AdminController.php';
        $controller = new AdminController();
        $controller->logout();
        return;
    }

    if ($uri === 'admin/register') {
        require_once $baseControllerPath . 'AdminController.php';
        $controller = new AdminController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register();
        } else {
            $controller->showRegister();
        }
        return;
    }
    if ($uri === 'event/list') {
        require_once $baseControllerPath . 'EventController.php';
        $controller = new EventController();
        $controller->list();
        return;
    }

    if ($uri === 'event/details') {
        require_once $baseControllerPath . 'EventController.php';
        $controller = new EventController();
        $controller->details();
        return;
    }

    // Participant dashboard (my registrations)
    if ($uri === 'participant/dashboard') {
        require_once $baseControllerPath . 'EventController.php';
        $controller = new EventController();
        $controller->myRegistrations();
        return;
    }

    if ($uri === 'event/myRegistrations') {
        require_once $baseControllerPath . 'EventController.php';
        $controller = new EventController();
        $controller->myRegistrations();
        return;
    }

    if ($uri === 'event/cancelRegistration') {
        require_once $baseControllerPath . 'EventController.php';
        $controller = new EventController();
        $controller->cancelRegistration();
        return;
    }

    if ($uri === 'event/myEvents') {
        require_once $baseControllerPath . 'EventController.php';
        $controller = new EventController();
        $controller->myEvents();
        return;
    }

    http_response_code(404);
    echo 'Page non trouvée';
}
?>