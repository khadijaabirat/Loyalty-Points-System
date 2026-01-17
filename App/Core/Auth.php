<?php
namespace App\Core;

class Auth {
     public static function check() {
        return isset($_SESSION['user_id']);
    }

     public static function requireLogin() {
        if (!self::check()) {
            header('Location: /Loyalty_Points_System/public/login');
            exit;
        }
    }
}