<?php
namespace App\Controllers;

use App\Services\PurchaseService;
use App\Core\Auth;

class ShopController
{
    private $purchaseService;
    private $twig;

    public function __construct($twig, PurchaseService $pService)
    {
        $this->twig = $twig;
        $this->purchaseService = $pService;
    }

    public function processCheckout()
    {
        Auth::requireLogin();  
         $cartItems = $_SESSION['cart'] ?? [];
        $userId = $_SESSION['user_id'];

         $result = $this->purchaseService->processPurchase($userId, $cartItems);

         echo $this->twig->render('shop/purchase_result.html.twig', $result);
    }
}