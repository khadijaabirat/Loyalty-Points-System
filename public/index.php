<?php

require_once __DIR__ . '/../vendor/autoload.php';
use App\Core\Database;
use App\Controllers\ShopController;
use App\Services\PurchaseService;
use App\Repositories\UserRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\ProductRepository;
use App\Models\Cart;
use App\Services\AuthService;
use App\Controllers\AuthController;


use Twig\Environment;
use Twig\Loader\FilesystemLoader;
session_start();

$loader = new  FilesystemLoader(__DIR__ . '/../views');
$twig = new Environment($loader);

$db = Database::getConnection();
$userRepo = new UserRepository($db);
$purchaseRepo = new PurchaseRepository($db);
$purchaseService = new PurchaseService($purchaseRepo, $userRepo);
$authService = new AuthService($userRepo);
 $cart = new Cart();
$productRepo = new ProductRepository();
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '/';

switch ($url) {
case '/':
    case 'shop':
        $controller = new ShopController($twig, $purchaseService, $userRepo,$cart,$productRepo);
    $controller->index();
    break;
case 'login':
    $authController = new AuthController($twig, $authService);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $authController->handleLogin();
    } else {
        $authController->showLogin();
    }
    break;

case 'register':
    $authController = new AuthController($twig, $authService);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $authController->handleRegister();
    } else {
        $authController->showRegister();
    }
    break;


 case 'dashboard':
    $controller = new \App\Controllers\DashboardController($twig, $userRepo);
    $controller->index();
    break;         
    case 'shop/cart':
        $controller = new ShopController($twig, $purchaseService, $userRepo,$cart,$productRepo);
        $controller->cart();
        break;

    case 'shop/add-to-cart':
        $controller = new ShopController($twig, $purchaseService, $userRepo,$cart,$productRepo);
        $controller->addToCart();
        break;

    case 'shop/update-cart':
        $controller = new ShopController($twig, $purchaseService, $userRepo,$cart,$productRepo);
        $controller->updateCart();
        break;

case 'shop/remove-from-cart':
            $controller = new ShopController($twig, $purchaseService, $userRepo,$cart,$productRepo);
        $controller->removeFromCart();
        break;

    case 'shop/checkout':
        $controller = new ShopController($twig, $purchaseService, $userRepo,$cart,$productRepo);
        $controller->checkout();
        break;

    case 'shop/process-checkout':
        $controller = new ShopController($twig, $purchaseService, $userRepo,$cart,$productRepo);
        $controller->processCheckout();
        break;

    case 'shop/purchase-result':
        $controller = new ShopController($twig, $purchaseService, $userRepo,$cart,$productRepo);
        $controller->purchaseResult();
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        echo "404 - Page non valide";
        break;
}