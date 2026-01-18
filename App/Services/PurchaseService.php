<?php
namespace App\Services;

class PurchaseService
{
    private $purchaseRepo;
    private $userRepo;

    public function __construct($purchaseRepo, $userRepo)
    {
        $this->purchaseRepo = $purchaseRepo;
        $this->userRepo = $userRepo;
    }

    public function processPurchase($userId, $cartItems)
    {
        $totalAmount = 0;
        
         foreach ($cartItems as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }
        
         $purchaseId = $this->purchaseRepo->create([
            'user_id' => $userId,
            'total_amount' => $totalAmount,
            'status' => 'completed'
        ]);
        
         $pointsEarned = $this->calculatePoints($totalAmount);
        
         if ($pointsEarned > 0) {
            $this->userRepo->updatePoints($userId, $pointsEarned);
        }
        
        return [
            'success' => true,
            'purchase_id' => $purchaseId,
            'total_amount' => $totalAmount,
            'points_earned' => $pointsEarned 
        ];
    }
    
     private function calculatePoints($amount)
    {  return floor($amount / 100) * 10;
    }
}