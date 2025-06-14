<?php
session_start();
require_once(__DIR__ . '/../config/db.php');


if (!isset($_SESSION['role'])) {
    header("Location: /sci-next/index.php");
    exit();
}

$role = $_SESSION['role'];

if ($role === 'employee') {
    header("Location: /sci-next/employee/sale.php");
    exit();
}
?>

