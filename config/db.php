<?php
$host = getenv('MYSQLHOST');
$port = getenv('MYSQLPORT');
$username = getenv('MYSQLUSER');
$password = getenv('MYSQLPASSWORD');
$database = getenv('MYSQLDATABASE');

try {
    $conn = new mysqli($host, $username, $password, $database, $port);

    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    include_once __DIR__ . '/databaseError.html';
    exit;
}