<?php

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

            reset_otp TEXT,

            otp_expiry DATETIME,

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