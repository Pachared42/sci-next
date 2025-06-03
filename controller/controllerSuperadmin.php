<?php
session_start();

// ตรวจสอบสิทธิ์การเข้าถึง
if (!isset($_SESSION['gmail']) || $_SESSION['role'] !== 'superadmin') {
    $_SESSION['error'] = "คุณไม่มีสิทธิ์เข้าถึงหน้านี้";
    header("Location: /index.php");
    exit();
}

require_once(realpath(__DIR__ . "/../config/db.php"));

$username = $_SESSION['gmail'];

// ฟังก์ชันบันทึกข้อผิดพลาดลงไฟล์
function logError($message)
{
    $logDir = __DIR__ . '/../../logs';
    $logFile = $logDir . '/error.log';

    // สร้างโฟลเดอร์ logs หากยังไม่มี
    if (!is_dir($logDir)) {
        if (!mkdir($logDir, 0755, true)) {
            error_log("ไม่สามารถสร้างโฟลเดอร์ logs ได้: $logDir");
            return;
        }
    }

    // ตรวจสอบสิทธิ์การเขียนไฟล์
    if (!is_writable($logDir) && file_exists($logFile)) {
        error_log("ไม่สามารถเขียนไฟล์ log ได้: $logFile");
        return;
    }

    $time = date('Y-m-d H:i:s');
    $fullMessage = "[$time] ข้อผิดพลาด: $message\n";

    // ใช้ error suppression และระบุ encoding ป้องกันปัญหา charset
    @file_put_contents($logFile, $fullMessage, FILE_APPEND | LOCK_EX);
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
    $allowedTables = ['dried_food', 'soft_drink', 'fresh_food', 'snack', 'stationery'];
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


// ฟังก์ชันดึงข้อมูลผู้ใช้ที่เป็น admin
function fetchAdmins($conn)
{
    $sql = "
        SELECT 
            u.gmail,
            u.first_name,
            u.last_name,
            r.name AS role_name,
            u.profile_image
        FROM users u
        INNER JOIN roles r ON u.role_id = r.id
        WHERE u.role_id = 2
    ";
    
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        logError("ไม่สามารถเตรียมคำสั่ง SQL สำหรับ admin users ได้: " . $conn->error);
        return [];
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $admins = [];

    while ($row = $result->fetch_assoc()) {
        if (!empty($row['profile_image'])) {
            $row['profile_image'] = 'data:image/jpeg;base64,' . base64_encode($row['profile_image']);
        } else {
            $row['profile_image'] = null;
        }

        $admins[] = $row;
    }

    $stmt->close();
    return $admins;
}



// ดึงข้อมูลจากหลายๆ ตาราง
$dried_food = fetchProducts($conn, 'dried_food');
$soft_drink = fetchProducts($conn, 'soft_drink');
$fresh_food = fetchProducts($conn, 'fresh_food');
$snack = fetchProducts($conn, 'snack');
$stationery = fetchProducts($conn, 'stationery');
$admins = fetchAdmins($conn);
?>