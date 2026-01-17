<?php
namespace App\Controllers;

class HomeController extends BaseController {
    public function index(): void {
        $this->render('home.html.twig', [
            'title' => 'FacileAchat - Accueil',
            'welcome_message' => 'Bonjour à votre profil'
        ]);
    }
}
