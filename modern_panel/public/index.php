<?php

require_once '../core/Config.php';
require_once '../core/Database.php';

// Simple Router
$request = $_SERVER['REQUEST_URI'];
$basePath = '/smmnepal/modern_panel/public'; // Adjust based on where it's hosted
$path = str_replace($basePath, '', $request);
$path = strtok($path, '?');

// Basic Routing Logic
switch ($path) {
    case '/':
    case '':
        require_once '../controllers/Home.php';
        $controller = new Home();
        $controller->index();
        break;
    case '/login':
        require_once '../controllers/Auth.php';
        $controller = new Auth();
        $controller->login();
        break;
    case '/signup':
        require_once '../controllers/Auth.php';
        $controller = new Auth();
        $controller->signup();
        break;
    case '/dashboard':
        require_once '../controllers/Dashboard.php';
        $controller = new Dashboard();
        $controller->index();
        break;
    case '/neworder':
        require_once '../controllers/Orders.php';
        $controller = new Orders();
        $controller->newOrder();
        break;
    case '/orders':
        require_once '../controllers/Orders.php';
        $controller = new Orders();
        $controller->listOrders();
        break;
    case '/addfunds':
        require_once '../controllers/AddFunds.php';
        $controller = new AddFunds();
        $controller->index();
        break;
    case '/services':
        require_once '../controllers/Services.php';
        $controller = new Services();
        $controller->index();
        break;
    case '/account':
        require_once '../controllers/Account.php';
        $controller = new Account();
        $controller->index();
        break;
    case '/logout':
        require_once '../controllers/Auth.php';
        $controller = new Auth();
        $controller->logout();
        break;
    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
