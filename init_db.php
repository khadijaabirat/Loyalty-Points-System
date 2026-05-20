<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
     $pdo = new PDO("mysql:host=$host;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
     $pdo->exec("CREATE DATABASE IF NOT EXISTS loyalty_points_db");
    $pdo->exec("USE loyalty_points_db");
    
    echo "Creating tables...\n";
    
     $schema = file_get_contents('schema.sql');
    $pdo->exec($schema);
    
    echo "Tables created successfully.\n";
    
    $pdo->exec("DELETE FROM users WHERE email = 'khadija@gmail.com'");
    echo "Creating test user...\n";
    $hash = password_hash('khadija', PASSWORD_DEFAULT);
    $insert = $pdo->prepare("INSERT INTO users (name, email, password_hash, total_points) VALUES (?, ?, ?, ?)");
    $insert->execute(['Khadija', 'khadija@gmail.com', $hash, 500]);
    echo "User khadija@gmail.com created successfully with 500 points.\n";
    
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
