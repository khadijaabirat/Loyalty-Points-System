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
    \App\Core\Auth::requireLogin();
   try {
        $userId = $_SESSION['user_id'];
        $cartItems = $this->cart->getItems();
        $totalOriginal = $this->cart->getTotal();
        
         $usePoints = isset($_POST['use_points']) && $_POST['use_points'] == '1';

         $result = $this->purchaseService->processPurchase($userId, $cartItems, $usePoints);

         $userAfter = $this->userRepo->findById($userId);

         $_SESSION['last_purchase'] = [
            'items'           => $cartItems,
            'total_original'  => $totalOriginal,
            'amount_paid'     => $result['amount_paid'],   
            'points_used'     => $result['points_used'],  
            'points_earned'   => $result['points_earned'],  
            'final_balance'   => $userAfter->total_points,  
            'date'            => date('d/m/Y H:i')
        ];

        $this->cart->clear();
        $this->redirect('shop/purchase-result');

    } catch (\Exception $e) {
        $_SESSION['checkout_error'] = $e->getMessage();
       echo "<h1>Error Found:</h1>";
    echo $e->getMessage();
    die();
        
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

 

    private function redirect($path) {
        header("Location: /Loyalty_Points_System/public/" . $path);
        exit;
    }

 private function getCurrentUser() {
    if (isset($_SESSION['user_id'])) {
         return $this->userRepo->findById($_SESSION['user_id']);
    }
    return null;
}
}