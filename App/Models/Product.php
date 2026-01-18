<?php
namespace App\Models;

class Product {
    public $id;
    public $name;
    public $description;
    public $price;
    public $icon;

    public function __construct($id, $name, $description, $price, $icon) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->icon = $icon;
    }
}