<?php

// Session settings
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 0,      // Session ends when browser closes
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

try {

    $pdo = new PDO("sqlite:crop_calculator.db");

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Users Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (

            id INTEGER PRIMARY KEY AUTOINCREMENT,

            name TEXT NOT NULL,

            email TEXT UNIQUE NOT NULL,

            password TEXT NOT NULL,

            created_at DATETIME DEFAULT CURRENT_TIMESTAMP

        )
    ");

    // Customers Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS customers (

            id INTEGER PRIMARY KEY AUTOINCREMENT,

            user_id INTEGER NOT NULL,

            customer_name TEXT NOT NULL,

            length REAL NOT NULL,

            breadth REAL NOT NULL,

            rate REAL NOT NULL,

            calculated_area REAL NOT NULL,

            display_area REAL NOT NULL,

            amount REAL NOT NULL,

            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

            FOREIGN KEY(user_id) REFERENCES users(id)

        )
    ");

} catch(PDOException $e){

    die("Connection Failed: " . $e->getMessage());

}

?>