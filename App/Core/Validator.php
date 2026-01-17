<?php
namespace App\Core;

class Validator {
    
    public static function validateRegister(array $data): array {
        $errors = [];

        if (empty(trim($data['name'] ?? ''))) {
            $errors['name'] = "stp entrer ton nom";
        }

         if (empty(trim($data['email'] ?? ''))) {
            $errors['email'] = "email important";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "email est incorect";
        }

         if (empty($data['password'])) {
            $errors['password'] = "mot de passe est obligatoire  ";
        } elseif (strlen($data['password']) < 6) {
            $errors['password'] = "le mot de passe est petit";
        }

        return $errors;
    }
 
public static function validateLogin(array $data): array {
    $errors = [];

    if (empty(trim($data['email'] ?? ''))) {
        $errors['email'] = "Email est obligatoire";
    }

    if (empty($data['password'])) {
        $errors['password'] = " mot de passe est obligatoire";
    }

    return $errors;
}

}