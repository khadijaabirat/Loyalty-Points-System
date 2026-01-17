<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\UserRepository;
use App\Core\Validator;

class AuthController {
    private $userRepo;
    private $twig;

public function __construct($twig, UserRepository $userRepo) {
        $this->twig = $twig;
        $this->userRepo = $userRepo;
    }

    public function showRegister() {
        echo $this->twig->render('auth/register.html.twig');
    }
public function handleRegister() {
     $errors = Validator::validateRegister($_POST);

 
    if (!empty($errors)) {
         echo $this->twig->render('auth/register.html.twig', [
            'errors' => $errors,
            'data' => $_POST 
        ]);
        return; 
    }

     try {
        $this->authService->registerUser($_POST);
        header('Location: /Loyalty_Points_System/public/login?success=1');
    } catch (\Exception $e) {
        
        echo $this->twig->render('auth/register.html.twig', [
            'error_global' => $e->getMessage()
        ]);
    }
}
 
public function showLogin() {
    echo $this->twig->render('auth/login.html.twig');
}

public function handleLogin() {
$errors = Validator::validateLogin($_POST);
 if (!empty($errors)) {
        echo $this->twig->render('auth/login.html.twig', [
            'errors' => $errors,
            'data' => $_POST
        ]);
        return;
    }

    if ($this->authService->loginUser($_POST['email'], $_POST['password'])) {
        header('Location: /Loyalty_Points_System/public/dashboard');
        exit;
    } else {
        echo $this->twig->render('auth/login.html.twig', [
            'error_global' => 'Email ou le mot de passe est incorect'
        ]);
    }
}
}