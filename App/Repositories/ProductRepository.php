<?php
namespace App\Repositories;

class ProductRepository {
    private array $products = [
        1 => ['id' => 1, 'name' => 'Smartphone Premium', 'price' => 799.99, 'icon' => 'SP', 'description' => 'Ecran OLED'],
        2 => ['id' => 2, 'name' => 'Casque Bluetooth', 'price' => 129.99, 'icon' => 'CB', 'description' => 'Son HD'],
        3 => ['id' => 3, 'name' => 'Livre PHP 8', 'price' => 49.99, 'icon' => 'LP', 'description' => 'Design Patterns'],
        4 => ['id' => 4, 'name' => 'T-shirt ShopEasy', 'price' => 24.99, 'icon' => 'TS', 'description' => 'Coton Bio'],
    ];

    public function getAll(): array {
        return $this->products;
    }

    public function findById(int $id): ?array {
        return $this->products[$id] ?? null;
    }
}