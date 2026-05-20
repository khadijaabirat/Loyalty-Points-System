<?php
namespace App\Repositories;

class ProductRepository {
    private array $products = [
        1 => ['id' => 1, 'name' => 'Smartphone Premium', 'price' => 999.99, 'category' => 'Téléphonie', 'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=800&auto=format&fit=crop', 'description' => 'Écran OLED 120Hz, triple capteur photo, 5G ultra-rapide.'],
        2 => ['id' => 2, 'name' => 'Casque Bluetooth ANC', 'price' => 249.99, 'category' => 'Audio', 'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=800&auto=format&fit=crop', 'description' => 'Réduction de bruit active, son Hi-Res et 40h d\'autonomie.'],
        3 => ['id' => 3, 'name' => 'Montre Connectée Pro', 'price' => 349.99, 'category' => 'Accessoires', 'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=800&auto=format&fit=crop', 'description' => 'Suivi santé 24/7, GPS intégré, étanche jusqu\'à 50m.'],
        4 => ['id' => 4, 'name' => 'Ordinateur Ultra-fin', 'price' => 1499.99, 'category' => 'Informatique', 'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=800&auto=format&fit=crop', 'description' => 'Processeur M2, 16Go RAM, design ultra-fin en aluminium.'],
        5 => ['id' => 5, 'name' => 'Appareil Photo Hybride', 'price' => 1299.99, 'category' => 'Photo', 'image' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?q=80&w=800&auto=format&fit=crop', 'description' => 'Capteur plein format 24MP, vidéo 4K, autofocus hybride.'],
        6 => ['id' => 6, 'name' => 'Enceinte Sans Fil', 'price' => 179.99, 'category' => 'Audio', 'image' => 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?q=80&w=800&auto=format&fit=crop', 'description' => 'Son 360° immersif, basses profondes, design élégant.'],
        7 => ['id' => 7, 'name' => 'Tablette Tactile Pro', 'price' => 799.99, 'category' => 'Informatique', 'image' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?q=80&w=800&auto=format&fit=crop', 'description' => 'Écran 11 pouces Liquid Retina, stylet inclus, 128Go.'],
        8 => ['id' => 8, 'name' => 'Drone Caméra 4K', 'price' => 599.99, 'category' => 'Photo', 'image' => 'https://images.unsplash.com/photo-1473968512647-3e447244af8f?q=80&w=800&auto=format&fit=crop', 'description' => 'Transmission HD 10km, vidéo 4K/60fps, capteurs d\'obstacles.'],
    ];

    public function getAll(): array {
        return $this->products;
    }

    public function findById(int $id): ?array {
        return $this->products[$id] ?? null;
    }

    public function search(string $query, string $category = ''): array {
        $results = [];
        $query = strtolower(trim($query));
        
        foreach ($this->products as $product) {
            $matchQuery = empty($query) || str_contains(strtolower($product['name']), $query) || str_contains(strtolower($product['description']), $query);
            $matchCategory = empty($category) || strtolower($product['category']) === strtolower($category);
            
            if ($matchQuery && $matchCategory) {
                $results[] = $product;
            }
        }
        return $results;
    }
    
    public function getCategories(): array {
        $categories = [];
        foreach ($this->products as $product) {
            if (!in_array($product['category'], $categories)) {
                $categories[] = $product['category'];
            }
        }
        return $categories;
    }
}