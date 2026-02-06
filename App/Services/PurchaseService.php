<?php
namespace App\Services; 

class PurchaseService
{
    private $purchaseRepo;
    private $userRepo;
    private $db;

    public function __construct($purchaseRepo, $userRepo, $db)
    {
        $this->purchaseRepo = $purchaseRepo;
        $this->userRepo = $userRepo;
        $this->db = $db;
    }

    public function processPurchase($userId, $cartItems, $usePoints = false)
    {
        try {
            $this->db->beginTransaction(); 

            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }
            
            $finalAmountToPay = $totalAmount;
            $pointsToDeduct = 0;

             if ($usePoints) {
                $user = $this->userRepo->findById($userId);
                 $currentPoints = $user->total_points ?? 0;

                if ($currentPoints > 0) {
                    if ($currentPoints >= $totalAmount) {
                        $pointsToDeduct = $totalAmount;
                        $finalAmountToPay = 0;
                    } else {
                        $pointsToDeduct = $currentPoints;
                        $finalAmountToPay = $totalAmount - $currentPoints;
                    }
                    
                     $this->userRepo->deductPoints($userId, $pointsToDeduct);
                    
                     $this->userRepo->addTransaction(
                        $userId, 
                        'redeemed', 
                        $pointsToDeduct, 
                        "Achat avec points", 
                        ($currentPoints - $pointsToDeduct)
                    );
                }
            }

             $purchaseId = $this->purchaseRepo->create([
                'user_id' => $userId,
                'total_amount' => $finalAmountToPay,
                'points_used' => $pointsToDeduct,  
                'status' => 'completed'
            ]);

             $pointsEarned = $this->calculatePoints($finalAmountToPay);

            if ($pointsEarned > 0) {
                $this->userRepo->updatePoints($userId, $pointsEarned);
                
                 $updatedUser = $this->userRepo->findById($userId);
                
                 $this->userRepo->addTransaction(
                    $userId, 
                    'earned', 
                    $pointsEarned, 
                    "Points gagnés (Commande #$purchaseId)", 
                    $updatedUser->total_points
                );
            }

            $this->db->commit(); 

            return [
                'success' => true,
                'purchase_id' => $purchaseId,
                'total_amount' => $totalAmount,  
                'amount_paid' => $finalAmountToPay,  
                'points_used' => $pointsToDeduct,
                'points_earned' => $pointsEarned 
            ];

        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function calculatePoints($amount)
    {  
        return floor($amount / 100) * 10;
    }
}