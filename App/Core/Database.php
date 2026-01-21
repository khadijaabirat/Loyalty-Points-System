<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;

    public static function getConnection() {
        if (self::$instance === null) {
            $host = 'localhost';
            $db   = 'loyalty_points_system';  
            $user = 'root';     
            $pass = '';           

            try {
                self::$instance = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
                 self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                 self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("erreur: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}