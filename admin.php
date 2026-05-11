<?php
require_once __DIR__ . '/app/helpers/Session.php';
Session::start();

if(!Session::isAdmin()) {
    header('Location: /wanbubu/admin/login.php');
    exit;
}

$page = $_GET['page'] ?? 'dashboard';

switch($page) {
    case 'products':
        require_once __DIR__ . '/app/controllers/ProductController.php';
        $controller = new ProductController();
        $action = $_GET['action'] ?? 'index';
        if(method_exists($controller, $action)) {
            $controller->$action();
        } else {
            $controller->index();
        }
        break;
        
    case 'orders':
        require_once __DIR__ . '/app/views/admin/orders.php';
        break;
        
    case 'categories':
        require_once __DIR__ . '/app/views/admin/categories.php';
        break;
        
    case 'customers':
        require_once __DIR__ . '/app/views/admin/customers.php';
        break;
        
    case 'reviews':
        require_once __DIR__ . '/app/views/admin/reviews.php';
        break;
        
    case 'settings':
        require_once __DIR__ . '/app/views/admin/settings.php';
        break;
        
    default:
        require_once __DIR__ . '/app/views/admin/dashboard.php';
        break;
}