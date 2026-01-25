<?php
namespace App\Controllers;

 use App\Core\Validator;
use App\Services\AuthService;

class AuthController {
     private $twig;
    private $authService;
public function __construct($twig, AuthService $authService) {
        $this->twig = $twig;
     $this->authService = $authService;

    }

    public function showRegister() {
        echo $this->twig->render('register.html.twig');
    }
public function handleRegister() {
     $errors = Validator::validateRegister($_POST);

 
    if (!empty($errors)) {
         echo $this->twig->render('register.html.twig', [
            'errors' => $errors,
            'data' => $_POST 
        ]);
        return; 
    }

     try {
        $this->authService->registerUser($_POST);
        header('Location: /Loyalty_Points_System/public/login?success=1');
    } catch (\Exception $e) {
        
        echo $this->twig->render('register.html.twig', [
            'error_global' => $e->getMessage()
        ]);
    }
}
 
public function showLogin() {
    echo $this->twig->render('login.html.twig');
}

public function handleLogin() {
$errors = Validator::validateLogin($_POST);
 if (!empty($errors)) {
        echo $this->twig->render('login.html.twig', [
            'errors' => $errors,
            'data' => $_POST
        ]);
        return;
    }

    if ($this->authService->loginUser($_POST['email'], $_POST['password'])) {
        header('Location: /Loyalty_Points_System/public/dashboard');
        exit;
    } else {
        echo $this->twig->render('login.html.twig', [
            'error' => 'Email ou le mot de passe est incorect'
        ]);
    }
}
public function logout(): void
{
     $_SESSION = [];

     if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }

     header('Location: /Loyalty_Points_System/public/login');
    exit;
}
}