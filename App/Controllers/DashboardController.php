<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Models\UserRepository;

class DashboardController {
    private $userRepo;
    private $twig;

    public function __construct($twig, UserRepository $userRepo) {
        $this->twig = $twig;
        $this->userRepo = $userRepo;
    }
    public function index() {
        Auth::requireLogin();
        
         $user = $this->userRepo->findById($_SESSION['user_id']);

         echo $this->twig->render('dashboard/index.html.twig', [
            'user' => $user
        ]);
    }
}
