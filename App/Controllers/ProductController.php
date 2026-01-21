<?php
namespace App\Controllers;

class ProductController {
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function index() {
           $products = [
            new \App\Models\Product(1, "Casque Bluetooth", "Son haute fidélité", 150.00, "🎧"),
            new \App\Models\Product(2, "Smartphone X", "Écran OLED 6.7 pouces", 899.00, "📱"),
            new \App\Models\Product(3, "Montre Connectée", "Suivi santé et sport", 250.00, "⌚")
        ];

        echo $this->twig->render('shop/index.html.twig', [
            'products' => $products
        ]);
    }
}