<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sci_stock';

try {
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    include_once __DIR__ . '/databaseError.html';
    exit;
}
