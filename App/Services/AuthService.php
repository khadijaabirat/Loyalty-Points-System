<?php
namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;

class AuthService {
    private $userRepo;

    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }

    public function registerUser($data) {
         if ($this->userRepo->findByEmail($data['email'])) {
            throw new \Exception("ce email est déja existe");
        }

         $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

         $user = new User([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => $hashedPassword,
            'total_points' => 0
        ]);

         
        return $this->userRepo->save($user);
    }

 
public function loginUser($email, $password) {
     $user = $this->userRepo->findByEmail($email);

     if (!$user || !password_verify($password, $user->password_hash)) {
        return false;  
    }

   $_SESSION['user_id'] = $user->id;
    $_SESSION['user_name'] = $user->name;
    $_SESSION['logged_in'] = true;

    return true; 
}
}
