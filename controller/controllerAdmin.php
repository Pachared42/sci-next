<?php
session_start();

// ตรวจสอบสิทธิ์การเข้าถึง
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: /../index.php");
    $_SESSION['error'] = "คุณไม่มีสิทธิ์เข้าถึงหน้านี้";
    exit();
}

require_once(realpath(__DIR__ . "/../config/db.php"));

$username = $_SESSION['user'];

// ฟังก์ชันบันทึกข้อผิดพลาดลงไฟล์
function logError($message)
{
    $file = __DIR__ . '/../../logs/error.log';
    if (!file_exists(dirname($file))) {
        mkdir(dirname($file), 0777, true);
    }
    $time = date('Y-m-d H:i:s');
    $fullMessage = "[$time] ข้อผิดพลาด: $message\n";
    file_put_contents($file, $fullMessage, FILE_APPEND);
}

// ฟังก์ชันตรวจสอบการเชื่อมต่อฐานข้อมูล
function checkConnection($conn)
{
    if ($conn->connect_error) {
        logError("ไม่สามารถเชื่อมต่อฐานข้อมูลได้: " . $conn->connect_error);
        die("ไม่สามารถเชื่อมต่อฐานข้อมูลได้ กรุณาลองใหม่ภายหลัง.");
    }
}

// ตรวจสอบการเชื่อมต่อ
checkConnection($conn);

// ฟังก์ชันดึงข้อมูลสินค้าแบบปลอดภัย
function fetchProducts($conn, $table)
{
    $allowedTables = ['dried_food', 'soft_drink', 'fresh_food', 'snack'];
    if (!in_array($table, $allowedTables)) {
        logError("มีการร้องขอเข้าถึงตารางที่ไม่ได้รับอนุญาต: " . htmlspecialchars($table));
        return [];
    }

    $sql = "SELECT id, product_name, barcode, price, cost, stock, reorder_level, image_url FROM `$table`";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        logError("ไม่สามารถเตรียมคำสั่ง SQL สำหรับตาราง $table ได้: " . $conn->error);
        return [];
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    $stmt->close();
    return $products;
}

// ฟังก์ชันดึงข้อมูลพนักงานแบบปลอดภัย
function fetchUsers($conn)
{
    $sql = "SELECT ID_NUMBER, USERNAME, PASSWORD, FIRST_NAME, LAST_NAME FROM employee";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        logError("ไม่สามารถเตรียมคำสั่ง SQL สำหรับดึงข้อมูลพนักงานได้: " . $conn->error);
        return [];
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    $stmt->close();
    return $users;
}

// ดึงข้อมูลจากหลายๆ ตาราง
$dried_food = fetchProducts($conn, 'dried_food');
$soft_drink = fetchProducts($conn, 'soft_drink');
$fresh_food = fetchProducts($conn, 'fresh_food');
$snack = fetchProducts($conn, 'snack');

// ดึงข้อมูลผู้ใช้ทั้งหมด
$users = fetchUsers($conn);
?>