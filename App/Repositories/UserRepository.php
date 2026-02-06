<?php
namespace App\Repositories;
use App\Core\Database;
use App\Models\User;
use PDO;
class UserRepository {
    private $db;
public function __construct(PDO $db) {
    $this->db = $db;
}
public function findById(int $id): ?User {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    return $data ? new User($data) : null;
}

public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new User($data) : null;
    }
public function save(User $user) {
        $sql = "INSERT INTO users (name, email, password_hash, total_points) 
                VALUES (:name, :email, :pass, :points)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name'   => $user->name,
            ':email'  => $user->email,
            ':pass'   => $user->password_hash,
            ':points' => $user->total_points
        ]);
    }
public function updatePoints($userId, $points) {
    $sql = "UPDATE users SET total_points = total_points + :points WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([':points' => $points, ':id' => $userId]);
}
  
 
public function deductPoints(int $userId, int $points): bool
{
     $query = "UPDATE users 
              SET total_points = total_points - :points 
              WHERE id = :id AND total_points >= :points";
    
    $stmt = $this->db->prepare($query);
    return $stmt->execute([
        'points' => $points,
        'id' => $userId
    ]);
}
public function addTransaction($userId, $type, $amount, $description, $balanceAfter) {
     $sql = "INSERT INTO points_transactions (user_id, type, amount, description, balance_after, createdat) 
            VALUES (:user_id, :type, :amount, :description, :balance_after, NOW())";
    
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        'user_id'       => $userId,
        'type'          => $type,
        'amount'        => $amount,
        'description'   => $description,
        'balance_after' => $balanceAfter
    ]);
}
}