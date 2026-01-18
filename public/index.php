<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;
try {
    $db = Database::getConnection();
} catch (Exception $e) {
    echo "erreur: " . $e->getMessage();
}
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../views');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);  

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '/';

switch ($url) {
    case '/':
        echo $twig->render('home.html.twig', ['title' => 'home']);
        break;

    case 'login':
        echo $twig->render('views/login.html.twig');
        break;

    case 'register':
        echo $twig->render('views/register.html.twig');
        break;
 case 'dashboard':
    $userRepo = new \App\Models\UserRepository();
    $controller = new \App\Controllers\DashboardController($twig, $userRepo);
    $controller->index();
    break;
    default:
        header("HTTP/1.0 404 Not Found");
        echo "404 - page invalid";
        break;
}