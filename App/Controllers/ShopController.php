<?php 
namespace App\Controllers;

use App\Services\PurchaseService;
use App\Repositories\UserRepository;
use App\Repositories\ProductRepository;
use App\Models\Cart;
use App\Core\Auth;

class ShopController
{
    private $twig;
    private $purchaseService;
    private $userRepo;
    private $cart;
    private $productRepo;
    

       
    public function __construct(
        $twig, 
        PurchaseService $pService, 
        UserRepository $userRepo, 
        Cart $cart,
        ProductRepository $productRepo
        
    ) {
        $this->twig = $twig;
        $this->purchaseService = $pService;
        $this->userRepo = $userRepo;
         $this->cart = $cart;
        $this->productRepo = $productRepo;
       
    }

     public function index(): void
    {
        echo $this->twig->render('shop/index.html.twig', [
            'products' => $this->productRepo->getAll(),
            'user' => $this->getCurrentUser(),
            'cart_count' => $this->cart->getCount()
        ]);
    }

     public function cart(): void
    {
        echo $this->twig->render('shop/cart.html.twig', [
            'cart_items' => $this->cart->getItems(),
            'cart_total' => $this->cart->getTotal(),
            'loyalty_points' => $this->cart->calculateLoyaltyPoints(),
            'user' => $this->getCurrentUser(),
            'cart_count' => $this->cart->getCount()
        ]);
    }

     public function addToCart(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['product_id'] ?? 0;
            $qty =$_POST['quantity'] ?? 1;
            
            $product = $this->productRepo->findById($id);
            if ($product) {
                $this->cart->addItem($product['id'], $product['name'], $product['price'], $qty);
            }
        }
header('Location: /Loyalty_Points_System/public/shop/cart');
    exit;    }

public function removeFromCart(): void
{
     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $productId = (int)($_POST['product_id'] ?? 0);
        
        if ($productId > 0) {
            $this->cart->removeItem($productId);
        }
    }
    
     header('Location: /Loyalty_Points_System/public/shop/cart');
    exit;
}
    
     public function checkout(): void
    {
        Auth::requireLogin();
        if ($this->cart->isEmpty()) { $this->redirect('shop'); }

        echo $this->twig->render('shop/checkout.html.twig', [
            'cart_items' => $this->cart->getItems(),
            'cart_total' => $this->cart->getTotal(),
            'loyalty_points' => $this->cart->calculateLoyaltyPoints(),
            'user' => $this->getCurrentUser(),
            'cart_count' => $this->cart->getCount()
        ]);
    }

     public function processCheckout(): void
    {
        Auth::requireLogin();
        
        try {
             $result = $this->purchaseService->processPurchase(
                $_SESSION['user_id'], 
                $this->cart->getItems()
            );

             $_SESSION['last_purchase'] = $result;
            $this->cart->clear();

            $this->redirect('shop/purchase-result');
        } catch (\Exception $e) {
            $_SESSION['checkout_error'] = $e->getMessage();
            $this->redirect('shop/checkout');
        }
    }

    public function purchaseResult(): void
    {
        if (!isset($_SESSION['last_purchase'])) { $this->redirect('shop'); }

        echo $this->twig->render('shop/purchase_result.html.twig', [
            'purchase' => $_SESSION['last_purchase'],
            'user' => $this->getCurrentUser()
        ]);
    }

     private function getCurrentUser() {
        return isset($_SESSION['user_id']) ? $this->userRepo->findById($_SESSION['user_id']) : null;
    }

    private function redirect($path) {
        header("Location: /Loyalty_Points_System/public/" . $path);
        exit;
    }
}