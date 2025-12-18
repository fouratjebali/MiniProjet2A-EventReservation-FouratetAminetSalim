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
        $controller->showLogin();
        return;
    }

    if ($uri === 'admin/dashboard') {
        require_once $baseControllerPath . 'AdminController.php';
        $controller = new AdminController();
        $controller->dashboard();
        return;
    }

    if ($uri === 'admin/logout') {
        require_once $baseControllerPath . 'AdminController.php';
        $controller = new AdminController();
        $controller->logout();
        return;
    }

    if ($uri === 'admin/register') {
    require_once __DIR__ . '/../app/controllers/AdminController.php';
    $controller = new AdminController();
    $controller->showRegister();
    return;
}
    if ($uri === 'event/list') {
        require_once $baseControllerPath . 'EventController.php';
        $controller = new EventController();
        $controller->list();
        return;
    }


    http_response_code(404);
    echo 'Page non trouvée';
}
?>