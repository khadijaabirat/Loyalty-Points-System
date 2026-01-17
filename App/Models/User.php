<?php
namespace App\Models ;

class User{
public ?int $id;
    public $name;
    public $email;
    public $password_hash;
    public $total_points;

     public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->password_hash = $data['password_hash'] ?? null;
        $this->total_points = $data['total_points'] ?? 0;
    }
}
