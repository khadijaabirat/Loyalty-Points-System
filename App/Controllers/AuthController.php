<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\UserRepository;

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $user = new User([
                'name' => $name,
                'email' => $email,
                'password_hash' => $hashedPassword,
                'total_points' => 0
            ]);

            if ($this->userRepo->save($user)) {
                header('Location: /Loyalty_Points_System/public/login');
                exit;
            } else {
                echo "erreur";
            }
        }
    }
}