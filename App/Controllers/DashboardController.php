<?php
namespace App\Controllers;

class DashboardController extends BaseController {
    public function index(): void {
        $this->render('dashboard.html.twig', [
            'title' => 'Dashboard - FacileAchat'
        ]);
    }
}
