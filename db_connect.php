<?php

$host = 'localhost'; 
$db   = 'students'; 
$user = 'root'; 
$pass = '12345'; 
$charset = 'utf8mb4';

// DSN (Data Source Name) for PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options
$options = [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    PDO::ATTR_EMULATE_PREPARES   => false,                  
];

try {
    // Create a new PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);
    // Optionally, you can echo a success message for debugging
    // echo "Database connection successful.";
} catch (\PDOException $e) {
    // Catch and display error if connection fails
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
