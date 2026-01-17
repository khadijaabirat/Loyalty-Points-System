<?php
namespace App\Controllers;

class AuthController extends BaseController {
    public function loginForm(): void {
        $this->render('login.html.twig', [
            'title' => 'Login - FacileAchat'
        ]);
    }
}
