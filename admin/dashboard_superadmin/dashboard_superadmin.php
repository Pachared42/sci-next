<?php
session_start();

// ตรวจสอบสิทธิ์การเข้าถึง
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: /sci-/index.php");
    exit();
}

require_once __DIR__ . '/../../config/db.php';
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

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCi_ADMIN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wdth,wght@62.5..100,100..900&family=Noto+Sans:ital,wdth,wght@0,62.5..100,100..900;1,62.5..100,100..900&display=swap');
    </style>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Noto Sans Thai", "Noto Sans", sans-serif;
        }

        body {
            background-color: #111111;
            margin-left: 220px;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: margin-left 0.5s ease;
        }

        body.sidebar-collapsed {
            margin-left: 0;
        }

        .h-text-upload {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 40px;
            font-weight: bold;
            color: #F79824;
        }

        .h2-text-upload {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 28px;
            font-weight: bold;
            color: #F79824;
            margin-bottom: 15px;
        }

        .tab-divider-category {
            border: none;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
            margin: 15px 0px 15px 0px;
        }

        .tab-divider-admin {
            border: none;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
            margin: 0px 0px 15px 0px;
        }

        /* เนื้อหาภายใน .container จะอยู่ทับพื้นหลัง */
        .container {
            position: relative;
            z-index: 1;
        }

        /* Navbar */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #000000;
            color: #ffffff;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.3);
        }

        .hamburger {
            position: absolute;
            left: 20px;
            display: flex;
            align-items: center;
        }

        .hamburger svg {
            transition: transform 0.3s ease-out, fill 0.3s ease-out;
            cursor: pointer;
        }

        .hamburger:hover svg {
            transform: scale(1.1);
            fill: #ff006e;
        }

        .logo-name {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo {
            width: 40px;
            height: auto;
            margin-right: 10px;
        }


        .site-name {
            font-size: 26px;
            font-weight: bold;
        }

        .user-settings {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 15px 0 20px 0;
        }

        .avatar-wrapper {
            position: relative;
            display: inline-block;
        }

        .avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #222222;
        }

        .online-status {
            position: absolute;
            bottom: 20px;
            right: 14px;
            width: 22px;
            height: 22px;
            background-color: #00ff00;
            border-radius: 50%;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 64px;
            left: 0;
            width: 220px;
            height: calc(100vh - 68px);
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.3);
            background-color: #000000;
            border-right: 2px solid rgba(255, 255, 255, 0.2);
            transform: translateX(0);
            opacity: 1;
            transition: transform 0.5s ease, opacity 0.5s ease, width 0.5s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::-webkit-scrollbar {
            width: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #000000;
            border-radius: 50px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar .content {
            display: block;
        }

        .sidebar.closed {
            transform: translateX(-100%);
        }

        .sidebar.closed .content {
            display: none;
        }

        .main-tabs {
            margin-bottom: 5px;
        }

        .main-tabs h3 {
            font-size: 12px;
            font-weight: bold;
            color: #F79824;
        }

        /* กำหนดลักษณะของเส้น hr */
        .tab-divider {
            border: none;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
            margin: 1px;
        }

        .main-tabs-upload {
            margin-bottom: 5px;
        }

        .main-tabs-upload h3 {
            margin-top: 5px;
            font-size: 12px;
            font-weight: bold;
            color: #F79824;
        }

        .main-tabs-products {
            margin-bottom: 5px;
        }

        .main-tabs-products h3 {
            margin-top: 5px;
            font-size: 12px;
            font-weight: bold;
            color: #F79824;
        }

        /* ปุ่มเมนู */
        .tab {
            padding: 10px 0 10px 15px;
            color: #ffffff;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
            border-radius: 10px;
            margin: 2px 0px;
        }

        .tab i,
        .tab span.material-icons {
            margin-right: 8px;
        }

        /* เมื่อเมาส์ hover เปลี่ยนพื้นหลัง */
        .tab:hover {
            background-color: rgba(211, 211, 211, 0.4);
        }

        /* กำหนดสีพื้นหลังสำหรับสถานะที่ถูกเลือก (active) */
        .tab:active,
        .tab.selected {
            background-color: #ffffff;
            color: black;
            position: relative;
        }

        .tab:active .material-icons,
        .tab.selected .material-icons {
            color: #000000;
        }

        .tab.sci_admin:hover:active,
        .tab.sci_admin.selected:hover {
            color: white;
        }

        .tab.sci_admin:hover:active .material-icons,
        .tab.sci_admin.selected:hover .material-icons {
            color: #000000;
        }

        .tab.employee:hover:active,
        .tab.employee.selected:hover {
            color: white;
        }

        .tab.employee:hover:active .material-icons,
        .tab.employee.selected:hover .material-icons {
            color: #000000;
        }

        .tab.account:hover:active,
        .tab.account.selected:hover {
            color: white;
        }

        .tab.account:hover:active .material-icons,
        .tab.account.selected:hover .material-icons {
            color: #000000;
        }

        /* เพิ่มจุดเขียวๆ กลมๆ ที่ขวาสุดของ tab 
        .tab:active::after,
        .tab.selected::after {
            content: "";
            position: absolute;
            top: 50%;
            right: 10px;
            width: 10px;
            height: 10px;
            background-color: #4CAF50;
            border-radius: 50%;
            transform: translateY(-50%);

        } */

        /* ปุ่มออกจากระบบ */
        .logout {
            padding: 10px 15px;
            background: #ff4b4b;
            text-align: center;
            font-weight: 600;
            color: white;
            border-radius: 10px;
            border: none;
            transition: 0.3s ease;
            margin-top: 5px;
            text-decoration: none;
        }

        .logout:hover {
            background: #ff0000;
        }

        .sci_admin {
            padding: 10px 15px;
            background: #2176FF;
            text-align: center;
            font-weight: 600;
            color: white;
            border-radius: 10px;
            border: none;
            transition: 0.3s ease;
            margin-top: auto;
            text-decoration: none;
        }

        .sci_admin:hover {
            background: #1056CC;
        }

        .employee {
            padding: 10px 15px;
            background: #33A1FD;
            text-align: center;
            font-weight: 600;
            color: white;
            border-radius: 10px;
            border: none;
            margin-top: 5px;
            transition: 0.3s ease;
            text-decoration: none;
        }

        .employee:hover {
            background: #1980C6;
        }

        .account {
            padding: 10px 15px;
            background: #FDCA40;
            text-align: center;
            font-weight: 600;
            color: white;
            border-radius: 10px;
            border: none;
            transition: 0.3s ease;
            margin-top: 5px;
            text-decoration: none;
        }

        .account:hover {
            background: #D88A00;
        }

        .content {
            display: none;
            padding: 0px 15px 0px 15px;
        }

        .show {
            display: block;
        }

        .product-container {
            display: flex;
            flex-wrap: wrap;
        }

        .product-card {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px;
            width: 200px;
            text-align: center;
        }

        .product-card img {
            width: 100%;
            height: auto;
        }

        .highlight {
            background-color: #FFD700;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .page-btn {
            font-size: 16px;
            color: #000000;
            font-weight: bold;
            padding: 5px 12px;
            margin: 4px;
            cursor: pointer;
            background-color: #ffffff;
            border: none;
            border-radius: 5px;
        }

        .page-btn:hover {
            background-color: #dddddd;
            color: #000000;
        }

        .page-btn.active {
            background-color: #555555;
            color: #ffffff;
        }



        /* สไตล์ของตาราง */
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            text-align: center;
            margin-top: 10px;
        }

        th {
            background: #333333;
            color: #ffffff;
            padding: 12px;
            font-size: 16px;
            max-width: 200px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }

        td {
            border-bottom: 1px solid rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.8);
            color: black;
            padding: 12px;
            max-width: 200px;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        tbody tr {
            cursor: pointer;
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.9);
        }

        td img {
            border-radius: 10px;
        }

        .center-checkbox {
            text-align: center;
            padding: 12px 0 12px 0;
        }

        .center-checkbox input {
            transform: scale(1);
            vertical-align: middle;
        }

        td input[type="checkbox"].checkbox-select-item {
            width: 25px;
            height: 25px;
            cursor: pointer;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border: 2px solid #4CAF50;
            border-radius: 5px;
            background-color: #ffffff;
            position: relative;
            box-sizing: border-box;
            padding: 0;
            margin: 0;
            outline: none;
            vertical-align: middle;
            text-align: center;
        }

        td input[type="checkbox"].checkbox-select-item:checked {
            background-color: #4CAF50;
            border-color: #4CAF50;
            background-image: url('/sci-shop-admin/img/check.png');
            background-size: 20px 20px;
            background-position: center;
            background-repeat: no-repeat;
        }

        td input[type="checkbox"].checkbox-select-item:hover {
            border-color: #45a049;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px 0 20px 0;
        }

        .card {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s;

            .label {
                font-weight: bold;
                font-size: 16px;
                color: #000000;
            }

            p {
                font-size: 16px;
                color: #000000;
                margin-top: 8px;
            }
        }

        .card:hover {
            background: rgba(255, 255, 255, 0.9);
        }

        .product-image-container {
            position: relative;
            display: inline-block;
        }

        .out-of-stock-label-table {
            top: 10px;
            left: 50%;
            background-color: rgba(255, 0, 0, 0.8);
            color: #ffffff;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 15px;
            border-radius: 10px;
        }

        .out-of-stock-label {
            position: absolute;
            top: 55px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(255, 0, 0, 0.8);
            color: #ffffff;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 18px;
            border-radius: 10px;
        }

        .product-image {
            width: 50%;
            height: auto;
            object-fit: cover;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .card-content {
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            color: #000000;

            h3 {
                text-align: center;
            }
        }

        .barcode {
            text-align: center;
            justify-content: center;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;

            svg {
                width: 45px;
                height: 45px;
            }
        }

        .card-actions {
            margin-top: 10px;
            display: flex;
            gap: 10px;
        }

        .card-actions button,
        .card-actions a {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            padding: 8px;
            border: none;
            background: #2176FF;
            color: #ffffff;
            text-align: center;
            text-decoration: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            gap: 8px;

            svg {
                width: 24px;
                height: 24px;
                fill: #ffffff;
            }
        }

        .card-actions a {
            background: #F79824;
        }

        .card-actions button:hover,
        .card-actions a:hover {
            opacity: 0.9;
        }

        #uplord_prodect {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
            overflow: hidden;
            box-sizing: border-box;
        }

        .form-container {
            flex: 1;
        }

        .form-upload {
            margin-top: 15px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-container-product {
            display: flex;
            column-gap: 10px;
            flex-wrap: wrap;
            width: 100%;
        }

        .from-container-stock {
            display: flex;
            column-gap: 10px;
            flex-wrap: wrap;
            width: 100%;
        }

        .form-group {
            display: flex;
            align-items: center;
            background: #f4f4f4;
            padding: 8px 12px;
            border-radius: 10px;
            flex: 1;
            min-width: 340px;
            margin-bottom: 15px;
        }

        .form-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #000000;
            font-weight: bold;
            white-space: nowrap;
        }

        .form-group input {
            border: none;
            outline: none;
            padding: 6px 8px 6px 4px;
            font-size: 14px;
            flex: 1;
            background: transparent;
            min-width: 120px;
        }

        label svg {
            width: 24px;
            height: 24px;
        }

        input[type="file"] {
            display: none;
        }

        .custom-file-upload {
            display: inline-block;
            padding: 6px;
            margin-left: 6px;
            background-color: #5cb85c;
            color: #ffffff;
            font-size: 14px;
            font-weight: bold;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .custom-file-upload:hover {
            background-color: #4cae4c;
            /* สีเขียวเข้มเมื่อ hover */
        }


        /* ทำให้ชื่อและนามสกุลอยู่ในแถวเดียวกัน */
        .name-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        /* ทำให้ช่องกรอกชื่อและนามสกุลมีขนาดเท่ากัน */
        .half-width {
            width: 48%;
        }

        .full-width {
            width: 100%;
        }

        .btn-upload {
            display: flex;
            width: 100%;
            justify-content: center;
            align-items: center;
            gap: 8px;
            background: #2176FF;
            color: white;
            border: none;
            padding: 15px 20px;
            font-size: 20px;
            font-weight: bold;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-upload:hover {
            background: #1056CC;
        }


        /* Style for the popup */
        .edit-popup {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            width: 400px;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            justify-content: flex-start;
            align-items: flex-start;
        }

        /* Content inside the popup */
        .popup-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow-y: auto;
        }

        /* Heading inside the popup */
        .popup-content h3 {
            margin: 15px 0px 30px 0px;
            color: #333;
            font-size: 24px;
            text-align: center;
        }

        /* Label for inputs */
        .popup-content label {
            display: block;
            margin: 10px 0 5px;
            font-size: 16px;
        }

        /* Style for input fields */
        .popup-content input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }


        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            position: sticky;
            top: 64px;
            background: #111111;
            z-index: 1000;
            padding: 8px 0 8px 0;
        }

        .no-items-message {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            font-size: 16px;
            color: #000000;
            padding: 20px;
            gap: 8px;

            svg {
                fill: #333333;
                width: 24px;
                height: 24px;
                ;
            }
        }

        .search-container {
            width: 50%;
            display: flex;
            align-items: center;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 4px;
            transition: box-shadow 0.2s ease;
        }

        .search-container:focus-within {
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
            border-color: #007bff;
        }

        .search-input {
            border: none;
            outline: none;
            padding: 8px 12px;
            font-size: 14px;
            flex: 1;
            background: transparent;
        }

        .search-button {
            background: #000000;
            border: none;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease;
        }

        .search-button:hover {
            background: #333333;
        }

        .search-button svg {
            width: 24px;
            height: 24px;
            fill: white;
        }

        #dried-food-selected-count,
        #soft-drink-selected-count,
        #fresh-food-selected-count {
            font-weight: bold;
            color: #4caf50;
            margin-left: 10px;
        }

        .btn {
            position: relative;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
        }

        .btn-container {
            margin-top: 5px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn svg {
            width: 35px;
            height: 35px;
            transition: 0.3s;
        }

        .btn-addd svg {
            fill: #4CAF50;
        }

        .btn-adddd svg {
            fill: #4CAF50;
        }

        .btn-addddd svg {
            fill: #4CAF50;
        }

        .btn-adddddd svg {
            fill: #4CAF50;
        }

        .btn-editt svg {
            fill: #FFA500;
        }

        .btn-deletee svg {
            fill: #DC143C;
        }

        .btn-filter svg {
            fill: #FFA500;
        }

        .btn-grid svg {
            fill: #1E90FF;
        }

        .btn-table svg {
            fill: #f72585;
        }

        .btn:hover svg {
            opacity: 0.7;
        }

        /* Tooltip */
        .btn::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 75%;
            left: 50%;
            transform: translateX(-50%);
            background: #000000;
            color: #ffffff;
            padding: 6px 10px;
            font-size: 14px;
            white-space: nowrap;
            border-radius: 10px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        /* แสดง Tooltip เมื่อ hover */
        .btn:hover::after {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(-5px);
        }

        .filter-menu {
            display: none;
            background-color: #ffffff;
            color: #000000;
            border-radius: 10px;
            padding: 10px;
            position: absolute;
            top: 78px;
            right: 45px;
            width: 200px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);

            option {
                background-color: #ffffff;
                color: #000000;
                padding: 10px;
                font-size: 14px;
                font-weight: bold;
                border-radius: 10px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }
        }

        .filter-menu select,
        .filter-menu input {
            width: 100%;
            padding: 8px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            background: #f9f9f9;
        }

        .filter-menu button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 8px;
            background-color: #2176FF;
            color: #ffffff;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            border-radius: 10px;
            transition: background-color 0.3s ease;

            svg {
                width: 24px;
                height: 24px;
                fill: #ffffff;
            }
        }

        .filter-menu button:hover {
            background-color: #1056CC;
        }

        /* โครงสร้าง Grid หลัก */
        .parent {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(4, 1fr);
            gap: 16px;
            padding: 20px;
        }

        /* กำหนดตำแหน่ง */
        .div1 {
            grid-area: 1 / 1 / 2 / 2;
        }

        .div2 {
            grid-area: 1 / 2 / 2 / 3;
        }

        .div3 {
            grid-area: 1 / 3 / 2 / 4;
        }

        .div4 {
            grid-area: 1 / 4 / 2 / 5;
        }

        .div5 {
            grid-area: 2 / 1 / 5 / 4;
        }

        .div6 {
            grid-area: 2 / 4 / 5 / 5;
        }

        /* Card ยอดขายแต่ละช่อง */
        .stat-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 16px;
            display: flex;
            align-items: center;
            transition: transform 0.2s ease;
        }

        .stat-card .icon {
            background-color: var(--accent);
            color: #fff;
            padding: 10px;
            border-radius: 8px;
            font-size: 18px;
            margin-right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-card .icon i {
            font-size: 20px;
        }

        .stat-card .info h5 {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        .stat-card .info p {
            margin: 4px 0 0;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .chart-container {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .chart-container h4 {
            font-size: 16px;
            color: #333;
            margin-bottom: 12px;
        }

        #order {
            margin-top: 68px;
            padding: 20px;
        }

        #graph {
            margin-top: 68px;
            padding: 20px;
        }

        #upload_prodect {
            margin-top: 68px;
            padding: 20px;
        }

        #upload_file_excal {
            margin-top: 68px;
            padding: 20px;
        }

        #admin_signup {
            margin-top: 68px;
            padding: 20px;
        }

        #employee_signup {
            margin-top: 68px;
            padding: 20px;
        }

        #dried_food {
            margin-top: 60px;
            padding: 0 20px 20px 20px;
        }

        #soft_drink {
            margin-top: 60px;
            padding: 0 20px 20px 20px;
        }

        #fresh_food {
            margin-top: 60px;
            padding: 0 20px 20px 20px;
        }

        #snack_food {
            margin-top: 60px;
            padding: 0 20px 20px 20px;
        }

        #sci_admin {
            margin-top: 68px;
            padding: 20px;
        }

        #employee {
            margin-top: 68px;
            padding: 20px;
        }

        #account {
            margin-top: 68px;
            padding: 20px;
        }

        #food_bank_check {
            margin-top: 68px;
            padding: 20px;
        }

        #local_drink_check {
            margin-top: 68px;
            padding: 20px;
        }

        #fastfood_check {
            margin-top: 68px;
            padding: 20px;
        }

        #snack {
            margin-top: 68px;
            padding: 20px;
        }

        .category-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .category-btn {
            display: flex;
            align-items: center;
            padding: 13px 15px;
            background-color: #eee;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .category-btn:hover {
            background-color: #ddd;
        }

        .category-btn.selected {
            background-color: #F79824;
            color: white;
        }

        .collapsible-toggle {
            display: flex;
            align-items: center;
            color: white;
            padding: 10px 20px 10px 15px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            width: 190px;
            justify-content: space-between;
            background-color: transparent;
            transition: background-color 0.3s ease;
        }

        button .material-icons {
            margin-right: 8px;
        }

        .collapsible-toggle:hover {
            background-color: #555555;
        }

        .collapsible-toggle svg {
            transition: transform 0.3s ease;
        }

        /* ทำให้ลูกศรหมุนเมื่อเมนูเปิด */
        .collapsible-toggle[aria-expanded="true"] svg {
            transform: rotate(180deg);
            transition: transform 0.3s ease;
        }

        /* ปรับสไตล์ของเมนู */
        .menu {
            display: block;
            overflow: hidden;
            max-height: 0;
            padding: 0 0 0 15px;
            border-radius: 5px;
            transition: max-height 0.3s ease-out, padding 0.3s ease;
        }

        /* แสดงเมนูเมื่อคลาส active ถูกเพิ่ม */
        .menu.active {
            max-height: 200px;
            padding: 0 0 0 15px;
        }

        .badge {
            position: absolute;
            top: 2px;
            right: 138px;
            background-color: #ff4b4b;
            color: white;
            font-size: 12px;
            font-weight: bold;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        /* สไตล์พื้นฐานสำหรับ alert */
        .alert {
            font-weight: bold;
            text-align: left;
            padding: 15px;
            border-radius: 10px;
            color: white;
            position: fixed;
            top: 18%;
            right: 20px;
            transform: translateY(-50%);
            width: auto;
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease-out;
            display: flex;
            align-items: center;
            min-width: 200px;
            line-height: 24px;
        }

        /* สไตล์สำหรับ svg ใน alert */
        .alert svg {
            margin-right: 6px;
            vertical-align: middle;
        }

        /* สไตล์สำหรับ error */
        .alert.error {
            background-color: #ff4b4b;
        }

        /* สไตล์สำหรับ success */
        .alert.success {
            background-color: #4CAF50;
        }

        /* เส้นขอบสีแดงเมื่อมีข้อผิดพลาด */
        .error-input {
            border: 2px solid #ff4b4b transparent;
            border-radius: 20px;
            box-shadow: inset 0 0 5px rgba(255, 75, 78, 0.7);
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
            box-sizing: border-box;
        }

        /* เมื่อ input ถูกโฟกัสหรือมีข้อผิดพลาด */
        .error-input:focus {
            border-color: #ff4b4bed !important;
            box-shadow: inset 0 0 8px rgba(255, 75, 78, 0.9);
            outline: 2px solid #ff4b4bed;
        }

        /* สไตล์พื้นฐานสำหรับ alert box */
        .notify-box {
            font-weight: bold;
            text-align: left;
            padding: 15px;
            border-radius: 10px;
            color: white;
            position: fixed;
            top: 18%;
            right: 20px;
            transform: translateY(-50%);
            width: auto;
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease-out;
            display: flex;
            align-items: center;
            min-width: 200px;
            line-height: 24px;
        }

        /* สไตล์สำหรับ svg ใน alert */
        .notify-box svg {
            margin-right: 6px;
            vertical-align: middle;
        }

        /* สีของแจ้งเตือน */
        .notify-error {
            background-color: #ff4b4b;
        }

        .notify-success {
            background-color: #4CAF50;
        }

        /* เส้นขอบสีแดงเมื่อมีข้อผิดพลาด */
        .input-error {
            border: 2px solid #ff4b4b transparent;
            border-radius: 20px;
            box-shadow: inset 0 0 5px rgba(255, 75, 78, 0.7);
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
            box-sizing: border-box;
        }

        /* เมื่อ input โฟกัส + มี error */
        .input-error:focus {
            border-color: #ff4b4bed !important;
            box-shadow: inset 0 0 8px rgba(255, 75, 78, 0.9);
            outline: 2px solid #ff4b4bed;
        }


        th input[type="checkbox"] {
            width: 24px;
            height: 24px;
            cursor: pointer;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border: 2px solid #4CAF50;
            /* สีเขียว */
            border-radius: 5px;
            background-color: #ffffff;
            position: relative;
            box-sizing: border-box;
            padding: 0;
            margin: 0;
            outline: none;
        }

        th input[type="checkbox"]:checked {
            background-color: #4CAF50;
            border-color: #4CAF50;
            background-image: url('/sci-shop-admin/img/done_all.png');
            background-size: 20px 20px;
            background-position: center;
            background-repeat: no-repeat;
        }

        td input[type="checkbox"].row-checkbox {
            width: 24px;
            height: 24px;
            cursor: pointer;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border: 2px solid #4CAF50;
            border-radius: 5px;
            background-color: #ffffff;
            position: relative;
            box-sizing: border-box;
            padding: 0;
            margin: 0;
            outline: none;
            vertical-align: middle;
            text-align: center;
        }

        td input[type="checkbox"].row-checkbox:checked {
            background-color: #4CAF50;
            border-color: #4CAF50;
            background-image: url('/sci-shop-admin/img/check.png');
            background-size: 20px 20px;
            background-position: center;
            background-repeat: no-repeat;
        }

        td input[type="checkbox"].row-checkbox:hover {
            border-color: #45a049;
        }

        th input[type="checkbox"]:hover {
            border-color: #45a049;
        }

        .upload-container {
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }

        .product-preview-box {
            width: 280px;
            height: 540px;
            background: #f4f4f4;
            color: #000000;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .product-preview-image img {
            max-width: 250px;
            max-height: 250px;
            object-fit: cover;
            border-radius: 10px;
        }

        .product-details {
            margin-top: 10px;
            max-width: 500px;
            color: #333;
        }

        .detail-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px 14px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: inset 0 0 0 1px #dddddd;
            transition: background 0.3s;
        }

        .detail-group svg {
            margin-right: 12px;
            fill: #000000;
            flex-shrink: 0;
        }

        .detail-group div {
            flex: 1;
            font-size: 16px;
            color: #222222;
        }

        .detail-inline {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .detail-inline p {
            flex: 1 1 48%;
            background: #ffffff;
            padding: 10px 14px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            box-shadow: inset 0 0 0 1px #ddd;
            transition: background 0.3s;
        }

        .detail-inline .material-icons {
            margin-right: 10px;
            font-size: 24px;
            color: #000000;
        }

        #previewName,
        #previewBarcode,
        #previewPrice,
        #previewCost,
        #previewStock,
        #previewReorderLevel {
            color: #222;
        }


        .profile-container {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2, #a855f7, #d946ef);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .cover {
            padding: 50px;
        }

        .profile-card {
            background: #111111;
            padding: 90px 15px 15px;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            position: relative;
            align-items: center;
        }

        .profile-image {
            position: absolute;
            top: -85px;
            left: 40px;
        }

        .profile-image img {
            width: 160px;
            height: 160px;
            background-color: #ffffff;
            border-radius: 50%;
            object-fit: cover;
            border: 8px solid #111111;
        }

        .upload-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: #111111;
            color: white;
            width: 45px;
            height: 45px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            cursor: pointer;
            transition: background 0.3s;
        }

        .upload-btn:hover {
            background: #222222;
        }

        .upload-btn i {
            font-size: 22px;
        }

        #file-input {
            display: none;
        }

        .profile-details {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 20px;
            background: #f4f4f4;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-title {
            font-size: 24px;
            font-weight: bold;
            color: #000000;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            position: relative;
            display: inline-block;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .profile-title::after {
            content: "";
            width: 50px;
            height: 4px;
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .profile-row {
            display: flex;
            gap: 15px;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
            min-width: 45%;
        }

        .profile-info label {
            display: flex;
            font-weight: bold;
            color: #333333;
            gap: 8px;
        }

        .profile-info label .material-icons {
            font-size: 24px;
            color: #000000;
        }

        .profile-info input {
            padding: 10px;
            border: 1px solid #ffffff;
            border-radius: 10px;
            width: 100%;
            font-size: 14px;
            transition: 0.3s;
            margin-bottom: 20px;
        }

        .profile-info input:focus {
            border-color: #111111;
            outline: none;
        }

        .confirm-btn {
            margin-top: 5px;
            width: auto;
            padding: 10px 15px;
            background: #6a0dad;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: 0.3s;
        }

        .confirm-btn:hover {
            background: #5a0ba5;
        }

        .confirm-btn .material-icons {
            font-size: 24px;
        }

        .action-dropdown {
            position: relative;
            display: inline-block;
        }

        .action-dropdown button {
            background-color: #eeeeee;
            border: none;
            font-size: 25px;
            padding: 5px 15px;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .action-dropdown button:hover {
            background-color: #dddddd;
        }

        .dropdown-content {
            display: block;
            visibility: hidden;
            opacity: 0;
            position: absolute;
            right: -34px;
            top: calc(-75% + 5px);
            background-color: #ffffff;
            min-width: 105px;
            z-index: 1;
            border-radius: 10px;
            overflow: hidden;
            transform: translateY(-10px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .dropdown-content a {
            color: #000000;
            padding: 15px 10px 15px 10px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: background 0.2s;
            white-space: nowrap;
            gap: 8px;
            font-weight: bold;

            svg {
                width: 20px;
                height: 20px;
                fill: #000000;

            }
        }

        .dropdown-content a:hover {
            background-color: #f0f0f0;
        }

        .action-dropdown.show .dropdown-content {
            visibility: visible;
            opacity: 1;
            transform: translateY(0);
        }

        .barcode-cell {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .barcode-cell svg {
            width: 45px;
            height: 45px;
            fill: #000000;
        }

        #overlay {
            position: fixed;
            display: none;
            /* เริ่มต้นซ่อนไว้ */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* สีเทาโปร่ง ๆ */
            z-index: 1000;
            /* ต้องน้อยกว่า side-panel เล็กน้อย */
        }


        .overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        .side-panel {
            position: fixed;
            top: 0;
            right: -500px;
            width: 500px;
            height: 100%;
            background-color: #ffffff;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.3);
            transition: right 0.3s ease;
            z-index: 1001;
            display: none;
        }

        /* แสดง panel */
        .side-panel.show {
            transform: translateX(0);
            pointer-events: auto;
        }

        .side-panel-content {
            padding: 15px;
            position: relative;
        }

        .side-panel-close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 40px;
            cursor: pointer;
            color: #ffffff;
            background-color: #444444;
            border: none;
            transition: transform 0.2s ease, background-color 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 10px;
        }

        .side-panel-close-btn:hover {
            background-color: #555555;
            color: #ffffff;
            transition: transform 0.2s ease, background-color 0.2s ease;
            border-radius: 10px;
        }

        body.side-panel-open {
            overflow: hidden;
        }

        #editProductForm {
            max-width: 500px;
            padding: 15px;
            background-color: #f4f4f4;
            border-radius: 10px;
        }

        #editProductForm label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        #editProductForm input[type="text"],
        #editProductForm input[type="number"] {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            transition: border 0.3s, box-shadow 0.3s;
        }

        #editProductForm input[type="text"]:focus,
        #editProductForm input[type="number"]:focus {
            border-color: #000000;
            box-shadow: 0 0 0 1.5px rgba(0, 0, 0, 0.5);
            outline: none;
        }

        .form-group-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .form-group-row .group-item {
            flex: 1;
        }

        #editProductForm button {
            width: 100%;
            font-size: 20px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 15px 20px;
            background-color: #000000;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #editProductForm button:hover {
            background-color: #222222;
        }


        #editProductForm button svg {
            width: 28px;
            height: 28px;
            fill: #ffffff;
        }

        .excel-grid-upload {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 20px;
        }

        @media (max-width: 1200px) {
            .excel-grid-upload {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 900px) {
            .excel-grid-upload {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .excel-grid-upload {
                grid-template-columns: 1fr;
            }
        }

        .excel-upload-form {
            background: #f8f9fa;
            padding: 24px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .excel-type-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 20px;
            margin-bottom: 16px;
            color: #333333;
        }

        .excel-upload-group {
            margin-bottom: 16px;
        }

        .excel-upload-input {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #cccccc;
            border-radius: 10px;
            display: block !important;
            z-index: 999 !important;
            background: #ffffff;
            cursor: pointer;
        }

        .file-name-text {
            margin-top: 15px;
            font-size: 15px;
            color: #555555;
            text-align: center;
        }

        .excel-upload-button {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: #33A1FD;
            color: #ffffff;
            font-size: 16px;
            font-weight: 500;
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.25s ease, transform 0.2s ease;
        }

        .excel-upload-button:hover {
            background-color: #1980C6;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <!-- แฮมเบอร์เกอร์ -->
        <div class="hamburger" onclick="toggleSidebar()">
            <span class="open-icon">
                <svg xmlns="http://www.w3.org/2000/svg" height="40px" width="40px" viewBox="0 -960 960 960" fill="#ffffff">
                    <path d="M120-240v-80h520v80H120Zm664-40L584-480l200-200 56 56-144 144 144 144-56 56ZM120-440v-80h400v80H120Zm0-200v-80h520v80H120Z" />
                </svg>
            </span>
            <span class="close-icon" style="display: none;">
                <svg xmlns="http://www.w3.org/2000/svg" height="40px" width="40px" viewBox="0 -960 960 960" fill="#ffffff">
                    <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
                </svg>
            </span>
        </div>

        <!-- โลโก้และชื่อ -->
        <div class="logo-name">
            <img src="\sci-shop-admin\img\NEXT.png" alt="Logo" class="logo">
            <span class="site-name">SCi_ADMIN</span>
        </div>


    </div>


    <div class="sidebar">

        <!-- Avatar -->
        <div class="user-settings">
            <div class="avatar-wrapper">
                <img src="\sci-shop-admin\img\pachara.jpg" alt="Avatar" class="avatar">
                <span class="online-status"></span>
            </div>
        </div>

        <!-- รายการหลัก -->
        <div class="main-tabs">
            <h3>รายการหลัก</h3>

            <div class="tab" onclick="showTab('graph')">
                <span class="material-icons">analytics</span> แดชบอร์ดสถิติ
            </div>

            <div class="tab" id="orderTab" onclick="showTab('order')">
                <span class="material-icons">shopping_cart</span>
                <span class="badge">99+</span> <!-- ตัวเลขแจ้งเตือน -->
                รายการขาย
            </div>

        </div>
        <hr class="tab-divider">

        <!-- รายการอัพโหลด -->
        <div class="main-tabs-upload">
            <h3>รายการอัพโหลด</h3>
            <div class="tab" onclick="showTab('admin_signup')">
                <span class="material-icons">admin_panel_settings</span> สมัครแอดมิน
            </div>

            <div class="tab" onclick="showTab('employee_signup')">
                <span class="material-icons">person_add</span> สมัครพนักงาน
            </div>

            <div class="tab" onclick="showTab('upload_prodect')">
                <span class="material-icons">add_shopping_cart</span> อัพโหลดสินค้า
            </div>

            <div class="tab" onclick="showTab('upload_file_excal')">
                <span class="material-icons">upload_file</span> อัปโหลดไฟล์สินค้า
            </div>

            <button type="button" is="toggle-button" class="collapsible-toggle text--strong" aria-controls="menu" aria-expanded="false">
                <span class="material-icons">checklist</span> ตรวจสอบสินค้า
                <svg focusable="false" width="12" height="8" class="icon icon--chevron icon--inline" viewBox="0 0 12 8">
                    <path fill="none" d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="2"></path>
                </svg>
            </button>

            <div id="menu" class="menu">
                <div class="tab" onclick="showTab('food_bank_check')">
                    <span class="material-icons">food_bank</span> หมวดแห้ง
                </div>
                <div class="tab" onclick="showTab('local_drink_check')">
                    <span class="material-icons">local_drink</span> หมวดเครื่องดื่ม
                </div>
                <div class="tab" onclick="showTab('fastfood_check')">
                    <span class="material-icons">fastfood</span> หมวดแช่แข็ง
                </div>
                <div class="tab" onclick="showTab('snack')">
                    <span class="material-icons">cookie</span> หมวดขนม
                </div>
            </div>
        </div>
        <hr class="tab-divider">

        <!-- รายการสินค้า -->
        <div class="main-tabs-products">
            <h3>รายการสินค้า</h3>
            <div class="tab" onclick="showTab('dried_food')">
                <span class="material-icons">food_bank</span> ประเภทแห้ง
            </div>
            <div class="tab" onclick="showTab('soft_drink')">
                <span class="material-icons">local_drink</span> ประเภทเครื่องดื่ม
            </div>
            <div class="tab" onclick="showTab('fresh_food')">
                <span class="material-icons">fastfood</span> ประเภทแช่แข็ง
            </div>
            <div class="tab" onclick="showTab('snack_food')">
                <span class="material-icons">cookie</span> ประเภทขนม
            </div>
        </div>


        <div class="tab sci_admin" onclick="showTab('sci_admin')">
            <span class="material-icons">admin_panel_settings</span> จัดการแอดมิน
        </div>
        <div class="tab employee" onclick="showTab('employee')">
            <span class="material-icons">group</span> จัดการพนักงาน
        </div>
        <div class="tab account" onclick="showTab('account')">
            <span class="material-icons">account_circle</span> โปรไฟล์แอดมิน
        </div>
        <a class="tab logout" href="/sci-shop-admin/logout.php">
            <span class="material-icons">logout</span> ออกจากระบบ
        </a>
    </div>

    <!-- ^^ ส่วนของ Tabs ^^ -->


    <!-- แสดงหน้าของ Tabs -->
    <!-- แสดงข้อมูลสถิติ -->
    <div id="order" class="content">
        <div style="border-radius: 10px;">
            <h2 style="text-align:center; margin-bottom:20px; color:#ffffff;">ตารางรายการขายสินค้า</h2>

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #4CAF50; color: white;">
                        <th style="padding: 12px;">ชื่อสินค้า</th>
                        <th style="padding: 12px;">รูปสินค้า</th>
                        <th style="padding: 12px;">ราคา</th>
                        <th style="padding: 12px;">คนซื้อ</th>
                        <th style="padding: 12px;">เวลา</th>
                        <th style="padding: 12px;">วันที่/เดือน/ปี</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="text-align: center; border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">นิสชิน คัพ นูดเดิล รสหมูมะนาว</td>
                        <td style="padding: 10px;"><img src="https://nissinthailand.com/wp-content/uploads/2024/11/NISSIN-CUP-NOODLES-MOO-MANAO-FLAVOUR-New.png" alt="รูปสินค้า" style="border-radius: 5px; width: 60px;"></td>
                        <td style="padding: 10px;">20฿</td>
                        <td style="padding: 10px;">คุณสมชาย</td>
                        <td style="padding: 10px;">14:30</td>
                        <td style="padding: 10px;">14 เมษายน 2025</td>
                    </tr>
                    <tr style="text-align: center; border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">นิสชิน คัพ นูดเดิล รสหมูมะนาว</td>
                        <td style="padding: 10px;"><img src="https://nissinthailand.com/wp-content/uploads/2024/11/NISSIN-CUP-NOODLES-MOO-MANAO-FLAVOUR-New.png" alt="รูปสินค้า" style="border-radius: 5px; width: 60px;"></td>
                        <td style="padding: 10px;">20฿</td>
                        <td style="padding: 10px;">คุณมณี</td>
                        <td style="padding: 10px;">10:15</td>
                        <td style="padding: 10px;">13 เมษายน 2025</td>
                    </tr>
                    <tr style="text-align: center; border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">นิสชิน คัพ นูดเดิล รสหมูมะนาว</td>
                        <td style="padding: 10px;"><img src="https://nissinthailand.com/wp-content/uploads/2024/11/NISSIN-CUP-NOODLES-MOO-MANAO-FLAVOUR-New.png" alt="รูปสินค้า" style="border-radius: 5px; width: 60px;"></td>
                        <td style="padding: 10px;">20฿</td>
                        <td style="padding: 10px;">คุณวิชัย</td>
                        <td style="padding: 10px;">09:00</td>
                        <td style="padding: 10px;">12 เมษายน 2025</td>
                    </tr>
                    <tr style="text-align: center; border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">นิสชิน คัพ นูดเดิล รสหมูมะนาว</td>
                        <td style="padding: 10px;"><img src="https://nissinthailand.com/wp-content/uploads/2024/11/NISSIN-CUP-NOODLES-MOO-MANAO-FLAVOUR-New.png" alt="รูปสินค้า" style="border-radius: 5px; width: 60px;"></td>
                        <td style="padding: 10px;">20฿</td>
                        <td style="padding: 10px;">คุณสายชล</td>
                        <td style="padding: 10px;">15:40</td>
                        <td style="padding: 10px;">11 เมษายน 2025</td>
                    </tr>
                    <tr style="text-align: center; border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">นิสชิน คัพ นูดเดิล รสหมูมะนาว</td>
                        <td style="padding: 10px;"><img src="https://nissinthailand.com/wp-content/uploads/2024/11/NISSIN-CUP-NOODLES-MOO-MANAO-FLAVOUR-New.png" alt="รูปสินค้า" style="border-radius: 5px; width: 60px;"></td>
                        <td style="padding: 10px;">20฿</td>
                        <td style="padding: 10px;">คุณสมปอง</td>
                        <td style="padding: 10px;">17:20</td>
                        <td style="padding: 10px;">10 เมษายน 2025</td>
                    </tr>
                    <tr style="text-align: center;">
                        <td style="padding: 10px;">นิสชิน คัพ นูดเดิล รสหมูมะนาว</td>
                        <td style="padding: 10px;"><img src="https://nissinthailand.com/wp-content/uploads/2024/11/NISSIN-CUP-NOODLES-MOO-MANAO-FLAVOUR-New.png" alt="รูปสินค้า" style="border-radius: 5px; width: 60px;"></td>
                        <td style="padding: 10px;">20฿</td>
                        <td style="padding: 10px;">คุณปิ่น</td>
                        <td style="padding: 10px;">08:45</td>
                        <td style="padding: 10px;">9 เมษายน 2025</td>
                    </tr>
                    <tr style="text-align: center; border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">นิสชิน คัพ นูดเดิล รสหมูมะนาว</td>
                        <td style="padding: 10px;"><img src="https://nissinthailand.com/wp-content/uploads/2024/11/NISSIN-CUP-NOODLES-MOO-MANAO-FLAVOUR-New.png" alt="รูปสินค้า" style="border-radius: 5px; width: 60px;"></td>
                        <td style="padding: 10px;">20฿</td>
                        <td style="padding: 10px;">คุณสมชาย</td>
                        <td style="padding: 10px;">14:30</td>
                        <td style="padding: 10px;">14 เมษายน 2025</td>
                    </tr>
                    <tr style="text-align: center; border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">นิสชิน คัพ นูดเดิล รสหมูมะนาว</td>
                        <td style="padding: 10px;"><img src="https://nissinthailand.com/wp-content/uploads/2024/11/NISSIN-CUP-NOODLES-MOO-MANAO-FLAVOUR-New.png" alt="รูปสินค้า" style="border-radius: 5px; width: 60px;"></td>
                        <td style="padding: 10px;">20฿</td>
                        <td style="padding: 10px;">คุณมณี</td>
                        <td style="padding: 10px;">10:15</td>
                        <td style="padding: 10px;">13 เมษายน 2025</td>
                    </tr>
                    <tr style="text-align: center; border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">นิสชิน คัพ นูดเดิล รสหมูมะนาว</td>
                        <td style="padding: 10px;"><img src="https://nissinthailand.com/wp-content/uploads/2024/11/NISSIN-CUP-NOODLES-MOO-MANAO-FLAVOUR-New.png" alt="รูปสินค้า" style="border-radius: 5px; width: 60px;"></td>
                        <td style="padding: 10px;">20฿</td>
                        <td style="padding: 10px;">คุณวิชัย</td>
                        <td style="padding: 10px;">09:00</td>
                        <td style="padding: 10px;">12 เมษายน 2025</td>
                    </tr>
                    <tr style="text-align: center; border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">นิสชิน คัพ นูดเดิล รสหมูมะนาว</td>
                        <td style="padding: 10px;"><img src="https://nissinthailand.com/wp-content/uploads/2024/11/NISSIN-CUP-NOODLES-MOO-MANAO-FLAVOUR-New.png" alt="รูปสินค้า" style="border-radius: 5px; width: 60px;"></td>
                        <td style="padding: 10px;">20฿</td>
                        <td style="padding: 10px;">คุณสายชล</td>
                        <td style="padding: 10px;">15:40</td>
                        <td style="padding: 10px;">11 เมษายน 2025</td>
                    </tr>
                    <tr style="text-align: center; border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">นิสชิน คัพ นูดเดิล รสหมูมะนาว</td>
                        <td style="padding: 10px;"><img src="https://nissinthailand.com/wp-content/uploads/2024/11/NISSIN-CUP-NOODLES-MOO-MANAO-FLAVOUR-New.png" alt="รูปสินค้า" style="border-radius: 5px; width: 60px;"></td>
                        <td style="padding: 10px;">20฿</td>
                        <td style="padding: 10px;">คุณสมปอง</td>
                        <td style="padding: 10px;">17:20</td>
                        <td style="padding: 10px;">10 เมษายน 2025</td>
                    </tr>
                    <tr style="text-align: center;">
                        <td style="padding: 10px;">นิสชิน คัพ นูดเดิล รสหมูมะนาว</td>
                        <td style="padding: 10px;"><img src="https://nissinthailand.com/wp-content/uploads/2024/11/NISSIN-CUP-NOODLES-MOO-MANAO-FLAVOUR-New.png" alt="รูปสินค้า" style="border-radius: 5px; width: 60px;"></td>
                        <td style="padding: 10px;">20฿</td>
                        <td style="padding: 10px;">คุณปิ่น</td>
                        <td style="padding: 10px;">08:45</td>
                        <td style="padding: 10px;">9 เมษายน 2025</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- สรุปยอดขายเป็นตัวเลข -->
    <div id="graph" class="content">
        <!-- สรุปยอดขายรายวัน -->
        <div class="parent ">
            <div class="div1 stat-card" style="--accent: #6c5ce7;">
                <div class="icon"><i class="fas fa-calendar-day"></i></div>
                <div class="info">
                    <h5>รายวัน</h5>
                    <p>฿12,500</p>
                </div>
            </div>

            <!-- สรุปยอดขายรายเดือน -->
            <div class="div2 stat-card" style="--accent: #00b894;">
                <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="info">
                    <h5>รายเดือน</h5>
                    <p>฿350,000</p>
                </div>
            </div>

            <!-- รายปี -->
            <div class="div3 stat-card" style="--accent: #fdcb6e;">
                <div class="icon"><i class="fas fa-chart-line"></i></div>
                <div class="info">
                    <h5>รายปี</h5>
                    <p>฿4,200,000</p>
                </div>
            </div>

            <!-- จำนวนคำสั่งซื้อ -->
            <div class="div4 stat-card" style="--accent: #d63031;">
                <div class="icon"><i class="fas fa-receipt"></i></div>
                <div class="info">
                    <h5>คำสั่งซื้อ</h5>
                    <p>128 รายการ</p>
                </div>
            </div>

            <!-- กราฟซ้าย -->
            <div class="div5 chart-container">
                <h4>กราฟภาพรวมยอดขาย</h4>
                <canvas id="mainSalesChart"></canvas>
            </div>

            <!-- กราฟขวา -->
            <div class="div6 chart-container">
                <h4>ลูกค้าใหม่</h4>
                <canvas id="newCustomerChart"></canvas>
            </div>
        </div>
    </div>



    <!-- ฟอร์มสมัครสมาชิก admin พร้อมแอททริบิวต์ autocomplete -->

    <div id="admin_signup" class="content">

        <div class="upload-container">
            <div class="product-preview-box">
                <div class="product-preview-image">
                    <svg id="previewImage" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#333333">
                        <path d="M480-440q58 0 99-41t41-99q0-58-41-99t-99-41q-58 0-99 41t-41 99q0 58 41 99t99 41ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-46q-54-53-125.5-83.5T480-360q-83 0-154.5 30.5T200-246v46Z" />
                    </svg>
                </div>
                <div class="product-details">
                    <div class="detail-group">
                        <span class="material-icons">sell</span>
                        <div>
                            <span id="previewNamee"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <span class="material-icons">qr_code</span>
                        <div>
                            <span id="previewBarcodee"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <span class="material-icons">qr_code</span>
                        <div>
                            <span id="previewBarcodee"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <span class="material-icons">qr_code</span>
                        <div>
                            <span id="previewBarcodee"></span>
                        </div>
                    </div>
                </div>
            </div>


            <div id="alert" class="alert" style="display: none;">
                <span id="alert-message"></span>
            </div>


            <div class="form-container">

                <h3 class="h-text-upload">เพิ่มแอดมินใหม่</h3>

                <form class="form-upload" id="adminSignupForm" action="../admin_signup/admin_signup.php" method="POST" onsubmit="return submitAdminForm()" autocomplete="on">

                    <!-- แถวสำหรับชื่อและนามสกุล -->
                    <div class="from-container-stock">
                        <div class="form-group">
                            <label for="firstName"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                    <path d="M80-80v-120q0-33 23.5-56.5T160-280h640q33 0 56.5 23.5T880-200v120H80Zm80-80h640v-40H160v40Zm40-180v-460q0-33 23.5-56.5T280-880h400q33 0 56.5 23.5T760-800v460h-80v-460H280v460h-80Zm120-60h23q44 0 70.5-44T440-560q0-72-26.5-116T343-720h-23v320Zm240-80q33 0 56.5-23.5T640-560q0-33-23.5-56.5T560-640q-33 0-56.5 23.5T480-560q0 33 23.5 56.5T560-480Zm-80 320Zm0-410Z" />
                                </svg>ชื่อ :</label>
                            <input type="text" id="firstName" name="firstName" placeholder="กรอกชื่อ" required autocomplete="given-name">
                        </div>
                        <div class="form-group">
                            <label for="lastName">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                    <path d="M481-781q106 0 200 45.5T838-604q7 9 4.5 16t-8.5 12q-6 5-14 4.5t-14-8.5q-55-78-141.5-119.5T481-741q-97 0-182 41.5T158-580q-6 9-14 10t-14-4q-7-5-8.5-12.5T126-602q62-85 155.5-132T481-781Zm0 94q135 0 232 90t97 223q0 50-35.5 83.5T688-257q-51 0-87.5-33.5T564-374q0-33-24.5-55.5T481-452q-34 0-58.5 22.5T398-374q0 97 57.5 162T604-121q9 3 12 10t1 15q-2 7-8 12t-15 3q-104-26-170-103.5T358-374q0-50 36-84t87-34q51 0 87 34t36 84q0 33 25 55.5t59 22.5q34 0 58-22.5t24-55.5q0-116-85-195t-203-79q-118 0-203 79t-85 194q0 24 4.5 60t21.5 84q3 9-.5 16T208-205q-8 3-15.5-.5T182-217q-15-39-21.5-77.5T154-374q0-133 96.5-223T481-687Zm0-192q64 0 125 15.5T724-819q9 5 10.5 12t-1.5 14q-3 7-10 11t-17-1q-53-27-109.5-41.5T481-839q-58 0-114 13.5T260-783q-8 5-16 2.5T232-791q-4-8-2-14.5t10-11.5q56-30 117-46t124-16Zm0 289q93 0 160 62.5T708-374q0 9-5.5 14.5T688-354q-8 0-14-5.5t-6-14.5q0-75-55.5-125.5T481-550q-76 0-130.5 50.5T296-374q0 81 28 137.5T406-123q6 6 6 14t-6 14q-6 6-14 6t-14-6q-59-62-90.5-126.5T256-374q0-91 66-153.5T481-590Zm-1 196q9 0 14.5 6t5.5 14q0 75 54 123t126 48q6 0 17-1t23-3q9-2 15.5 2.5T744-191q2 8-3 14t-13 8q-18 5-31.5 5.5t-16.5.5q-89 0-154.5-60T460-374q0-8 5.5-14t14.5-6Z" />
                                </svg>นามสกุล :</label>
                            <input type="text" id="lastName" name="lastName" placeholder="กรอกนามสกุล" required autocomplete="family-name">
                        </div>
                    </div>

                    <hr class="tab-divider-admin">

                    <div class="form-group">
                        <label for="upload-img">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M440-440v-160H280v-80h160v-160h80v160h160v80H520v160h-80Zm40 280q-16 0-28-12t-12-28q0-16 12-28t28-12q16 0 28 12t12 28q0 16-12 28t-28 12Zm-320 80q-33 0-56.5-23.5T80-160v-480q0-33 23.5-56.5T160-720h640q33 0 56.5 23.5T880-640v480q0 33-23.5 56.5T800-80H160Zm0-80h640v-480H160v480Z" />
                            </svg>
                            รูปพนักงาน :
                        </label>
                        <label for="upload-img" class="custom-file-upload">เลือกไฟล์</label>
                        <input type="file" id="upload-img" name="upload-img" accept="image/*" required>
                    </div>

                    <div class="form-group">
                        <label for="username"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M560-440h200v-80H560v80Zm0-120h200v-80H560v80ZM200-320h320v-22q0-45-44-71.5T360-440q-72 0-116 26.5T200-342v22Zm160-160q33 0 56.5-23.5T440-560q0-33-23.5-56.5T360-640q-33 0-56.5 23.5T280-560q0 33 23.5 56.5T360-480ZM160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm0-80h640v-480H160v480Zm0 0v-480 480Z" />
                            </svg>ชื่อผู้ใช้งาน :</label>
                        <input type="text" id="username" name="username" placeholder="กรอกชื่อผู้ใช้งาน" required autocomplete="username">
                    </div>

                    <div class="form-group">
                        <label for="password">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M80-200v-80h800v80H80Zm46-242-52-30 34-60H40v-60h68l-34-58 52-30 34 58 34-58 52 30-34 58h68v60h-68l34 60-52 30-34-60-34 60Zm320 0-52-30 34-60h-68v-60h68l-34-58 52-30 34 58 34-58 52 30-34 58h68v60h-68l34 60-52 30-34-60-34 60Zm320 0-52-30 34-60h-68v-60h68l-34-58 52-30 34 58 34-58 52 30-34 58h68v60h-68l34 60-52 30-34-60-34 60Z" />
                            </svg>รหัสผ่าน :</label>
                        <input type="password" id="password" name="password" placeholder="กรอกรหัสผ่าน" required autocomplete="new-password">
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M480-480Zm0 400q-139-35-229.5-159.5T160-516v-244l320-120 320 120v262q0 9-1 19h-81q1-10 1.5-19t.5-18v-189l-240-90-240 90v189q0 121 68 220t172 132v84Zm200 0v-120H560v-80h120v-120h80v120h120v80H760v120h-80ZM420-360h120l-23-129q20-10 31.5-29t11.5-42q0-33-23.5-56.5T480-640q-33 0-56.5 23.5T400-560q0 23 11.5 42t31.5 29l-23 129Z" />
                            </svg>ยืนยันรหัสผ่าน :</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="กรอกยืนยันรหัสผ่าน" required autocomplete="new-password">
                    </div>

                    <div>
                        <button type="submit" class="btn-upload">
                            <svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#ffffff" stroke="#ffffff" stroke-width="20">
                                <path d="M200-200v-560 454-85 191Zm0 80q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v320h-80v-320H200v560h280v80H200Zm494 40L552-222l57-56 85 85 170-170 56 57L694-80ZM320-440q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 160h240v-80H440v80Zm0-160h240v-80H440v80Z" />
                            </svg>สมัครแอดมิน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ฟอร์มสมัครสมาชิก employee พร้อมแอททริบิวต์ autocomplete -->
    <div id="employee_signup" class="content">

        <div class="upload-container">
            <div class="product-preview-box">
                <div class="product-preview-image">
                    <svg id="previewImage" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#333333">
                        <path d="M480-440q58 0 99-41t41-99q0-58-41-99t-99-41q-58 0-99 41t-41 99q0 58 41 99t99 41ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-46q-54-53-125.5-83.5T480-360q-83 0-154.5 30.5T200-246v46Z" />
                    </svg>
                </div>
                <div class="product-details">
                    <div class="detail-group">
                        <span class="material-icons">sell</span>
                        <div>
                            <span id="previewNameee"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <span class="material-icons">qr_code</span>
                        <div>
                            <span id="previewBarcodeee"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <span class="material-icons">qr_code</span>
                        <div>
                            <span id="previewBarcodeee"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <span class="material-icons">qr_code</span>
                        <div>
                            <span id="previewBarcodeee"></span>
                        </div>
                    </div>
                </div>
            </div>


            <div id="notify" class="notify-box" style="display: none;">
                <span id="notify-message"></span>
            </div>


            <div class="form-container">

                <h3 class="h-text-upload">เพิ่มพนักงานใหม่</h3>

                <form class="form-upload" id="employeeSignupForm" action="../../employee/employee_signup/employee_signup.php" method="POST" onsubmit="return submitemployeeForm()" autocomplete="on">

                    <!-- แถวสำหรับชื่อและนามสกุล -->
                    <div class="from-container-stock">
                        <div class="form-group">
                            <label for="firstName"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                    <path d="M80-80v-120q0-33 23.5-56.5T160-280h640q33 0 56.5 23.5T880-200v120H80Zm80-80h640v-40H160v40Zm40-180v-460q0-33 23.5-56.5T280-880h400q33 0 56.5 23.5T760-800v460h-80v-460H280v460h-80Zm120-60h23q44 0 70.5-44T440-560q0-72-26.5-116T343-720h-23v320Zm240-80q33 0 56.5-23.5T640-560q0-33-23.5-56.5T560-640q-33 0-56.5 23.5T480-560q0 33 23.5 56.5T560-480Zm-80 320Zm0-410Z" />
                                </svg>ชื่อ :</label>
                            <input type="text" id="firstName" name="firstName" placeholder="กรอกชื่อ" required autocomplete="given-name">
                        </div>
                        <div class="form-group">
                            <label for="lastName">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                    <path d="M481-781q106 0 200 45.5T838-604q7 9 4.5 16t-8.5 12q-6 5-14 4.5t-14-8.5q-55-78-141.5-119.5T481-741q-97 0-182 41.5T158-580q-6 9-14 10t-14-4q-7-5-8.5-12.5T126-602q62-85 155.5-132T481-781Zm0 94q135 0 232 90t97 223q0 50-35.5 83.5T688-257q-51 0-87.5-33.5T564-374q0-33-24.5-55.5T481-452q-34 0-58.5 22.5T398-374q0 97 57.5 162T604-121q9 3 12 10t1 15q-2 7-8 12t-15 3q-104-26-170-103.5T358-374q0-50 36-84t87-34q51 0 87 34t36 84q0 33 25 55.5t59 22.5q34 0 58-22.5t24-55.5q0-116-85-195t-203-79q-118 0-203 79t-85 194q0 24 4.5 60t21.5 84q3 9-.5 16T208-205q-8 3-15.5-.5T182-217q-15-39-21.5-77.5T154-374q0-133 96.5-223T481-687Zm0-192q64 0 125 15.5T724-819q9 5 10.5 12t-1.5 14q-3 7-10 11t-17-1q-53-27-109.5-41.5T481-839q-58 0-114 13.5T260-783q-8 5-16 2.5T232-791q-4-8-2-14.5t10-11.5q56-30 117-46t124-16Zm0 289q93 0 160 62.5T708-374q0 9-5.5 14.5T688-354q-8 0-14-5.5t-6-14.5q0-75-55.5-125.5T481-550q-76 0-130.5 50.5T296-374q0 81 28 137.5T406-123q6 6 6 14t-6 14q-6 6-14 6t-14-6q-59-62-90.5-126.5T256-374q0-91 66-153.5T481-590Zm-1 196q9 0 14.5 6t5.5 14q0 75 54 123t126 48q6 0 17-1t23-3q9-2 15.5 2.5T744-191q2 8-3 14t-13 8q-18 5-31.5 5.5t-16.5.5q-89 0-154.5-60T460-374q0-8 5.5-14t14.5-6Z" />
                                </svg>นามสกุล :</label>
                            <input type="text" id="lastName" name="lastName" placeholder="กรอกนามสกุล" required autocomplete="family-name">
                        </div>
                    </div>

                    <hr class="tab-divider-admin">



                    <div class="form-group">
                        <label for="username"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M560-440h200v-80H560v80Zm0-120h200v-80H560v80ZM200-320h320v-22q0-45-44-71.5T360-440q-72 0-116 26.5T200-342v22Zm160-160q33 0 56.5-23.5T440-560q0-33-23.5-56.5T360-640q-33 0-56.5 23.5T280-560q0 33 23.5 56.5T360-480ZM160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm0-80h640v-480H160v480Zm0 0v-480 480Z" />
                            </svg>ชื่อผู้ใช้งาน :</label>
                        <input type="text" id="username" name="username" placeholder="กรอกชื่อผู้ใช้งาน" required autocomplete="username">
                    </div>

                    <div class="form-group">
                        <label for="password">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M80-200v-80h800v80H80Zm46-242-52-30 34-60H40v-60h68l-34-58 52-30 34 58 34-58 52 30-34 58h68v60h-68l34 60-52 30-34-60-34 60Zm320 0-52-30 34-60h-68v-60h68l-34-58 52-30 34 58 34-58 52 30-34 58h68v60h-68l34 60-52 30-34-60-34 60Zm320 0-52-30 34-60h-68v-60h68l-34-58 52-30 34 58 34-58 52 30-34 58h68v60h-68l34 60-52 30-34-60-34 60Z" />
                            </svg>รหัสผ่าน :</label>
                        <input type="password" id="password" name="password" placeholder="กรอกรหัสผ่าน" required autocomplete="new-password">
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M480-480Zm0 400q-139-35-229.5-159.5T160-516v-244l320-120 320 120v262q0 9-1 19h-81q1-10 1.5-19t.5-18v-189l-240-90-240 90v189q0 121 68 220t172 132v84Zm200 0v-120H560v-80h120v-120h80v120h120v80H760v120h-80ZM420-360h120l-23-129q20-10 31.5-29t11.5-42q0-33-23.5-56.5T480-640q-33 0-56.5 23.5T400-560q0 23 11.5 42t31.5 29l-23 129Z" />
                            </svg>ยืนยันรหัสผ่าน :</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="กรอกยืนยันรหัสผ่าน" required autocomplete="new-password">
                    </div>

                    <div>
                        <button type="submit" class="btn-upload">
                            <svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#ffffff" stroke="#ffffff" stroke-width="20">
                                <path d="M200-200v-560 454-85 191Zm0 80q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v320h-80v-320H200v560h280v80H200Zm494 40L552-222l57-56 85 85 170-170 56 57L694-80ZM320-440q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 160h240v-80H440v80Zm0-160h240v-80H440v80Z" />
                            </svg>สมัครพนักงาน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ฟอร์มอัปโหลดสินค้า -->
    <div id="upload_prodect" class="content">

        <div class="upload-container">
            <div class="product-preview-box">
                <div class="product-preview-image">
                    <img id="previewImageProduct" src="/sci-shop-admin/img/product_food1.png" alt="รูปภาพสินค้า">
                </div>

                <div class="product-details">
                    <div class="detail-group">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                            <path d="M856-390 570-104q-12 12-27 18t-30 6q-15 0-30-6t-27-18L103-457q-11-11-17-25.5T80-513v-287q0-33 23.5-56.5T160-880h287q16 0 31 6.5t26 17.5l352 353q12 12 17.5 27t5.5 30q0 15-5.5 29.5T856-390ZM260-640q25 0 42.5-17.5T320-700q0-25-17.5-42.5T260-760q-25 0-42.5 17.5T200-700q0 25 17.5 42.5T260-640Z" />
                        </svg>
                        <div>
                            <span id="previewNameProduct"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                            <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                        </svg>
                        <div>
                            <span id="previewBarcodeProduct"></span>
                        </div>
                    </div>

                    <div class="detail-inline">
                        <p>
                            <span class="material-icons">paid</span>
                            <span id="previewPriceProduct"></span>
                        </p>
                        <p>
                            <span class="material-icons">savings</span>
                            <span id="previewCostProduct"></span>
                        </p>
                    </div>

                    <div class="detail-inline">
                        <p>
                            <span class="material-icons">inventory_2</span>
                            <span id="previewStockProduct"></span>
                        </p>
                        <p>
                            <span class="material-icons">notification_important</span>
                            <span id="previewReorderLevelProduct"></span>
                        </p>
                    </div>
                </div>
            </div>


            <div class="form-container">
                <h3 class="h-text-upload">เพิ่มสินค้าใหม่</h3>

                <form class="form-upload" id="uploadForm" method="POST" action="" enctype="multipart/form-data" onsubmit="return handleFormSubmit(event)">
                    <div class="category-buttons">
                        <button type="button" class="category-btn" data-category="dried_food" onclick="selectCategory(event, 'dried_food')">
                            <span class="material-icons">food_bank</span>
                            หมวดของแห้ง
                        </button>

                        <button type="button" class="category-btn" data-category="soft_drink" onclick="selectCategory(event, 'soft_drink')">
                            <span class="material-icons">local_drink</span>
                            หมวดเครื่องดื่ม
                        </button>

                        <button type="button" class="category-btn" data-category="fresh_food" onclick="selectCategory(event, 'fresh_food')">
                            <span class="material-icons">fastfood</span>
                            หมวดของแช่แข็ง
                        </button>

                        <button type="button" class="category-btn" data-category="snack" onclick="selectCategory(event, 'snack')">
                            <span class="material-icons">cookie</span>
                            หมวดขนม
                        </button>
                    </div>

                    <input type="hidden" id="productCategory" name="productCategory">

                    <hr class="tab-divider-category">

                    <div class="form-group">
                        <label for="productName">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M160-80q-33 0-56.5-23.5T80-160v-440q0-33 23.5-56.5T160-680h200v-120q0-33 23.5-56.5T440-880h80q33 0 56.5 23.5T600-800v120h200q33 0 56.5 23.5T880-600v440q0 33-23.5 56.5T800-80H160Zm0-80h640v-440H600q0 33-23.5 56.5T520-520h-80q-33 0-56.5-23.5T360-600H160v440Zm80-80h240v-18q0-17-9.5-31.5T444-312q-20-9-40.5-13.5T360-330q-23 0-43.5 4.5T276-312q-17 8-26.5 22.5T240-258v18Zm320-60h160v-60H560v60Zm-200-60q25 0 42.5-17.5T420-420q0-25-17.5-42.5T360-480q-25 0-42.5 17.5T300-420q0 25 17.5 42.5T360-360Zm200-60h160v-60H560v60ZM440-600h80v-200h-80v200Zm40 220Z" />
                            </svg> ชื่อสินค้า :
                        </label>
                        <input type="text" id="productName" name="productName" placeholder="กรอกชื่อสินค้า" required>
                    </div>


                    <div class="form-container-product">
                        <div class="form-group">
                            <label for="barcode">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg>
                                บาร์โค้ด :
                            </label>
                            <input type="text" id="barcode" name="barcode" placeholder="กรอกบาร์โค้ด" required>
                        </div>

                        <div class="form-group">
                            <label for="productPrice">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                    <path d="M560-440q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM280-320q-33 0-56.5-23.5T200-400v-320q0-33 23.5-56.5T280-800h560q33 0 56.5 23.5T920-720v320q0 33-23.5 56.5T840-320H280Zm80-80h400q0-33 23.5-56.5T840-480v-160q-33 0-56.5-23.5T760-720H360q0 33-23.5 56.5T280-640v160q33 0 56.5 23.5T360-400Zm440 240H120q-33 0-56.5-23.5T40-240v-440h80v440h680v80ZM280-400v-320 320Z" />
                                </svg> ราคาสินค้า :
                            </label>
                            <input type="number" id="productPrice" name="productPrice" placeholder="กรอกราคาสินค้า" required>
                        </div>

                        <div class="form-group">
                            <label for="productCost">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                    <path d="M600-320h120q17 0 28.5-11.5T760-360v-240q0-17-11.5-28.5T720-640H600q-17 0-28.5 11.5T560-600v240q0 17 11.5 28.5T600-320Zm40-80v-160h40v160h-40Zm-280 80h120q17 0 28.5-11.5T520-360v-240q0-17-11.5-28.5T480-640H360q-17 0-28.5 11.5T320-600v240q0 17 11.5 28.5T360-320Zm40-80v-160h40v160h-40Zm-200 80h80v-320h-80v320ZM80-160v-640h800v640H80Zm80-560v480-480Zm0 480h640v-480H160v480Z" />
                                </svg> ต้นทุน :
                            </label>
                            <input type="number" id="productCost" name="productCost" placeholder="กรอกต้นทุนสินค้า" required>
                        </div>
                    </div>


                    <div class="from-container-stock">
                        <div class="form-group">
                            <label for="productStock">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                    <path d="M200-80q-33 0-56.5-23.5T120-160v-451q-18-11-29-28.5T80-680v-120q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v120q0 23-11 40.5T840-611v451q0 33-23.5 56.5T760-80H200Zm0-520v440h560v-440H200Zm-40-80h640v-120H160v120Zm200 280h240v-80H360v80Zm120 20Z" />
                                </svg> สต็อก :
                            </label>
                            <input type="number" id="productStock" name="productStock" placeholder="กรอกจำนวนสต็อกสินค้า" required>
                        </div>

                        <div class="form-group">
                            <label for="productReorderLevel">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                    <path d="M600-320h120q17 0 28.5-11.5T760-360v-240q0-17-11.5-28.5T720-640H600q-17 0-28.5 11.5T560-600v240q0 17 11.5 28.5T600-320Zm40-80v-160h40v160h-40Zm-280 80h120q17 0 28.5-11.5T520-360v-240q0-17-11.5-28.5T480-640H360q-17 0-28.5 11.5T320-600v240q0 17 11.5 28.5T360-320Zm40-80v-160h40v160h-40Zm-200 80h80v-320h-80v320ZM80-160v-640h800v640H80Zm80-560v480-480Zm0 480h640v-480H160v480Z" />
                                </svg> สต็อกต่ำสุด :
                            </label>
                            <input type="number" id="productReorderLevel" name="productReorderLevel" placeholder="กรอกจำนวนสต็อกสินค้าต่ำสุด" required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="productImage">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M480-480ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h320v80H200v560h560v-320h80v320q0 33-23.5 56.5T760-120H200Zm40-160h480L570-480 450-320l-90-120-120 160Zm440-320v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80Z" />
                            </svg> รูปภาพสินค้า :
                        </label>
                        <input type="text" id="productImage" name="productImage" placeholder="กรอก URL ของรูปภาพ" required>
                    </div>

                    <div>
                        <button type="submit" class="btn-upload">
                            <svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#ffffff" stroke="#ffffff" stroke-width="20">
                                <path d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                            </svg>
                            อัปโหลดสินค้า
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ฟอร์มอัปโหลดไฟล์ Excel -->

    <div id="upload_file_excal" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">
                เพิ่มสินค้าใหม่ผ่านไฟล์ Excel
            </h3>

            <div class="btn-container">
                <a href="../../assets/templates/template_dried_food.xlsx" download class="btn btn-addd" data-tooltip="ฟอร์ม Excel">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                        <path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="excel-grid-upload">
            <!-- ประเภทแห้ง -->
            <form action="upload_excel_handler_dried.php" method="POST" enctype="multipart/form-data" class="excel-upload-form">
                <h4 class="excel-type-title"><span class="material-icons">food_bank</span>ประเภทแห้ง</h4>
                <div class="excel-upload-group">
                    <input type="file" name="excel_file" class="excel-upload-input" accept=".xlsx, .xls" required onchange="showFileName(this)">
                    <div class="file-name-text">ยังไม่ได้เลือกไฟล์</div>
                </div>
                <div class="excel-upload-group">
                    <button type="submit" class="excel-upload-button"><svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#ffffff" stroke="#ffffff" stroke-width="20">
                            <path d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                        </svg>อัปโหลดไฟล์</button>
                </div>
            </form>

            <!-- ประเภทเครื่องดื่ม -->
            <form action="upload_excel_handler_drink.php" method="POST" enctype="multipart/form-data" class="excel-upload-form">
                <h4 class="excel-type-title"><span class="material-icons">local_drink</span>ประเภทเครื่องดื่ม</h4>
                <div class="excel-upload-group">
                    <input type="file" name="excel_file" class="excel-upload-input" accept=".xlsx, .xls" required onchange="showFileName(this)">
                    <div class="file-name-text">ยังไม่ได้เลือกไฟล์</div>
                </div>
                <div class="excel-upload-group">
                    <button type="submit" class="excel-upload-button"><svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#ffffff" stroke="#ffffff" stroke-width="20">
                            <path d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                        </svg>อัปโหลดไฟล์</button>
                </div>
            </form>

            <!-- ประเภทแช่แข็ง -->
            <form action="upload_excel_handler_fresh.php" method="POST" enctype="multipart/form-data" class="excel-upload-form">
                <h4 class="excel-type-title"><span class="material-icons">fastfood</span>ประเภทแช่แข็ง</h4>
                <div class="excel-upload-group">
                    <input type="file" name="excel_file" class="excel-upload-input" accept=".xlsx, .xls" required onchange="showFileName(this)">
                    <div class="file-name-text">ยังไม่ได้เลือกไฟล์</div>
                </div>
                <div class="excel-upload-group">
                    <button type="submit" class="excel-upload-button"><svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#ffffff" stroke="#ffffff" stroke-width="20">
                            <path d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                        </svg>อัปโหลดไฟล์</button>
                </div>
            </form>

            <!-- ประเภทขนม -->
            <form action="upload_excel_handler_fresh.php" method="POST" enctype="multipart/form-data" class="excel-upload-form">
                <h4 class="excel-type-title"><span class="material-icons">cookie</span>ประเภทขนม</h4>
                <div class="excel-upload-group">
                    <input type="file" name="excel_file" class="excel-upload-input" accept=".xlsx, .xls" required onchange="showFileName(this)">
                    <div class="file-name-text">ยังไม่ได้เลือกไฟล์</div>
                </div>
                <div class="excel-upload-group">
                    <button type="submit" class="excel-upload-button"><svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#ffffff" stroke="#ffffff" stroke-width="20">
                            <path d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                        </svg>อัปโหลดไฟล์</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ส่วนของการตรวจสอบเพิ่มสินค้า -->
    <div id="food_bank_check" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">
                รายละเอียดสินค้าประเภทแห้ง
                <span id="dried-food-selected-count">เลือกแล้ว +0</span>
            </h3>

            <div class="btn-container">
                <button id="add_dried_food" class="btn btn-addd" data-tooltip="เพิ่มสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>
                </button>

                <button class="btn btn-editt" data-tooltip="แก้ไขสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                    </svg>
                </button>

                <button class="btn btn-deletee" data-tooltip="ลบสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                    </svg>
                </button>

            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="center-checkbox"><input type="checkbox" onclick="toggleSelectAllForTable(this, 'dried_food_table')"></th>
                    <th>ชื่อสินค้า</th>
                    <th>รูปภาพ</th>
                    <th>บาร์โค้ด</th>
                    <th>ราคา</th>
                    <th>ต้นทุน</th>
                    <th>สต็อก</th>
                    <th>เกณฑ์สั่งซื้อ</th>
                </tr>
            </thead>
            <tbody id="dried_food_table">
            </tbody>
        </table>
    </div>

    <div id="local_drink_check" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">รายละเอียดสินค้าประเภทเครื่องดื่ม
                <span id="soft-drink-selected-count">เลือกแล้ว +0</span>
            </h3>

            <div class="btn-container">
                <button id="add_soft_drink" class="btn btn-adddd" data-tooltip="เพิ่มสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>
                </button>

                <button class="btn btn-editt" data-tooltip="แก้ไขสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                    </svg>
                </button>

                <button class="btn btn-deletee" data-tooltip="ลบสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                    </svg>
                </button>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="center-checkbox"><input type="checkbox" onclick="toggleSelectAllForTable(this, 'soft_drink_table')"></th>
                    <th>ชื่อสินค้า</th>
                    <th>รูปภาพ</th>
                    <th>บาร์โค้ด</th>
                    <th>ราคา</th>
                    <th>ต้นทุน</th>
                    <th>สต็อก</th>
                    <th>เกณฑ์สั่งซื้อ</th>
                </tr>
            </thead>
            <tbody id="soft_drink_table"></tbody>
        </table>
    </div>

    <div id="fastfood_check" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">รายละเอียดสินค้าประเภทแช่แข็ง
                <span id="fresh-food-selected-count">เลือกแล้ว +0</span>
            </h3>

            <div class="btn-container">
                <button id="add_fresh_food" class="btn btn-addddd" data-tooltip="เพิ่มสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>
                </button>

                <button class="btn btn-editt" data-tooltip="แก้ไขสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                    </svg>
                </button>

                <button class="btn btn-deletee" data-tooltip="ลบสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                    </svg>
                </button>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="center-checkbox"><input type="checkbox" onclick="toggleSelectAllForTable(this, 'fresh_food_table')"></th>
                    <th>ชื่อสินค้า</th>
                    <th>รูปภาพ</th>
                    <th>บาร์โค้ด</th>
                    <th>ราคา</th>
                    <th>ต้นทุน</th>
                    <th>สต็อก</th>
                    <th>เกณฑ์สั่งซื้อ</th>
                </tr>
            </thead>
            <tbody id="fresh_food_table"></tbody>
        </table>
    </div>

    <div id="snack" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">รายละเอียดสินค้าประเภทขนมขบเคี้ยว
                <span id="fresh-food-selected-count">เลือกแล้ว +0</span>
            </h3>

            <div class="btn-container">
                <button id="add_snack" class="btn btn-adddddd" data-tooltip="เพิ่มสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>
                </button>

                <button class="btn btn-editt" data-tooltip="แก้ไขสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                    </svg>
                </button>

                <button class="btn btn-deletee" data-tooltip="ลบสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                    </svg>
                </button>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="center-checkbox"><input type="checkbox" onclick="toggleSelectAllForTable(this, 'snack_table')"></th>
                    <th>ชื่อสินค้า</th>
                    <th>รูปภาพ</th>
                    <th>บาร์โค้ด</th>
                    <th>ราคา</th>
                    <th>ต้นทุน</th>
                    <th>สต็อก</th>
                    <th>เกณฑ์สั่งซื้อ</th>
                </tr>
            </thead>
            <tbody id="snack_table"></tbody>
        </table>
    </div>


    <div id="dried_food" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">
                <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#ffffff">
                    <path d="M400-240h40v-160q25 0 42.5-17.5T500-460v-120h-40v120h-20v-120h-40v120h-20v-120h-40v120q0 25 17.5 42.5T400-400v160Zm160 0h40v-340q-33 0-56.5 23.5T520-500v120h40v140ZM160-120v-480l320-240 320 240v480H160Z" />
                </svg>
                สินค้าประเภทแห้ง
            </h3>

            <div class="search-container">
                <input type="text" class="search-input" placeholder="ค้นหาสินค้า..." id="searchInput" oninput="filterProducts()">
                <button class="search-button" onclick="filterProducts()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                    </svg>
                </button>
            </div>

            <div class="btn-container">
                <button class="btn btn-filter" data-tooltip="แสดง Filter" onclick="toggleMenu()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M400-240v-80h160v80H400ZM240-440v-80h480v80H240ZM120-640v-80h720v80H120Z" />
                    </svg>
                </button>

                <div id="filterMenu" class="filter-menu">
                    <select id="filterCategory">
                        <option value="all">ทั้งหมด</option>
                        <option value="food">สินค้าใกล้หมด</option>
                    </select>

                    <!-- <button onclick="applyFilters()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M80-140v-320h320v320H80Zm80-80h160v-160H160v160Zm60-340 220-360 220 360H220Zm142-80h156l-78-126-78 126ZM863-42 757-148q-21 14-45.5 21t-51.5 7q-75 0-127.5-52.5T480-300q0-75 52.5-127.5T660-480q75 0 127.5 52.5T840-300q0 26-7 50.5T813-204L919-98l-56 56ZM660-200q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29 71q0 42 29 71t71 29ZM320-380Zm120-260Z"/></svg>ค้นหา</button> -->
                </div>

                <button class="btn btn-table" data-tooltip="แสดง Table" onclick="switchView('table')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M320-80q-33 0-56.5-23.5T240-160v-480q0-33 23.5-56.5T320-720h480q33 0 56.5 23.5T880-640v480q0 33-23.5 56.5T800-80H320Zm0-80h200v-120H320v120Zm280 0h200v-120H600v120ZM80-240v-560q0-33 23.5-56.5T160-880h560v80H160v560H80Zm240-120h200v-120H320v120Zm280 0h200v-120H600v120ZM320-560h480v-80H320v80Z" />
                    </svg>
                </button>

                <button class="btn btn-grid" data-tooltip="แสดง Grid" onclick="switchView('grid')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M120-520v-320h320v320H120Zm0 400v-320h320v320H120Zm400-400v-320h320v320H520Zm0 400v-320h320v320H520ZM200-600h160v-160H200v160Zm400 0h160v-160H600v160Zm0 400h160v-160H600v160Zm-400 0h160v-160H200v160Zm400-400Zm0 240Zm-240 0Zm0-240Z" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="container">
            <table id="productTable" display: none;">
                <thead>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>รูป</th>
                        <th>บาร์โค้ด</th>
                        <th>ราคา</th>
                        <th>ต้นทุน</th>
                        <th>สต็อก</th>
                        <th>เกณฑ์สั่งซื้อ</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody id="productTableBody"></tbody>
            </table>

            <div id="productGrid" class="card-grid"></div>
        </div>

        <div id="pagination" class="pagination "></div>

        <div id="overlay" class="overlay" onclick="closeEditPanel()"></div>

        <div id="editPanel" class="side-panel">
            <div class="side-panel-content">
                <h2 class="h2-text-upload">
                    <svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#ffffff">
                        <path d="M360-600v-80h360v80H360Zm0 120v-80h360v80H360ZM560-80v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T903-300L683-80H560Zm263-224 37-39-37-37-38 38 38 38ZM240-80q-50 0-85-35t-35-85v-120h120v-560h600v361q-20-2-40.5 1.5T760-505v-295H320v480h240l-80 80v160H240Z" />
                    </svg>แก้ไขข้อมูลสินค้า
                </h2>
                <span class="side-panel-close-btn" onclick="closeEditPanel()">&times;</span>

                <div id="side-panel-form-content">
                    <form id="editProductForm" method="POST" action="">
                        <input type="hidden" id="edit_product_id" name="id">

                        <label for="product_name">ชื่อสินค้า</label>
                        <input type="text" id="product_name" name="product_name" required>

                        <label for="barcode">บาร์โค้ด</label>
                        <input type="text" id="barcode" name="barcode" maxlength="17" />

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="price">ราคา</label>
                                <input type="number" id="price" name="price" required>
                            </div>
                            <div class="group-item">
                                <label for="cost">ต้นทุน</label>
                                <input type="number" id="cost" name="cost" required>
                            </div>
                        </div>

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="stock">สต็อก</label>
                                <input type="number" id="stock" name="stock" required>
                            </div>
                            <div class="group-item">
                                <label for="reorder_level">เกณฑ์สั่งซื้อ</label>
                                <input type="number" id="reorder_level" name="reorder_level" required>
                            </div>
                        </div>

                        <label for="image_url">URL รูปภาพ</label>
                        <input type="text" id="image_url" name="image_url" required>

                        <button type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" "><path d=" M200-200v-560 454-85 191Zm0 80q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v320h-80v-320H200v560h280v80H200Zm494 40L552-222l57-56 85 85 170-170 56 57L694-80ZM320-440q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 160h240v-80H440v80Zm0-160h240v-80H440v80Z" /></svg>บันทึก
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div id="soft_drink" class="content">
        <h3 class="h-text">🔘 สวิตช์</h3>
        <table border="1" cellspacing="0" cellpadding="10">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($switches as $item): ?>
                    <tr>
                        <td><img src="<?php echo $item['IMAGE_URL']; ?>" alt="<?php echo $item['NAME']; ?>" width="100"></td>
                        <td><?php echo $item['NAME']; ?></td>
                        <td>฿<?php echo $item['PRICE']; ?></td>
                        <td><?php echo $item['SWITCH_TYPE']; ?></td>
                        <td>
                            <form action="#" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $item['ID']; ?>">
                                <button type="button" class="btn-edit" onclick="openEditPopup(<?php echo $item['ID']; ?>)">แก้ไขสินค้า</button>
                            </form>
                            <!-- Popup Form -->
                            <div id="editPopup-<?php echo $item['ID']; ?>" class="edit-popup">
                                <div class="popup-content">
                                    <button type="button" class="btn-close" onclick="closeEditPopup(<?php echo $item['ID']; ?>)">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                    <h3>แก้ไขข้อมูลสินค้า</h3>
                                    <form action="../product/edit_product/edit_product.php" method="POST">
                                        <input type="hidden" name="productID" value="<?php echo $item['ID']; ?>">
                                        <input type="text" name="productName" value="<?php echo $item['NAME']; ?>" required>
                                        <input type="text" name="productPrice" value="<?php echo $item['PRICE']; ?>" required>
                                        <input type="hidden" name="productCategory" value="switches"> <!-- หรือ หมวดหมู่อื่นๆ ตามความเหมาะสม -->
                                        <input type="text" name="switchType" value="<?php echo $item['SWITCH_TYPE']; ?>" required>
                                        <button type="submit" class="btn-edit-prodect">บันทึก</button>
                                    </form>

                                </div>
                            </div>

                            <form id="deleteForm-<?php echo $item['ID']; ?>" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $item['ID']; ?>">
                                <input type="hidden" name="category" value="switches"> <!-- หมวดหมู่สินค้านี้ -->
                                <button type="button" class="btn-delete" onclick="deleteProduct(<?php echo $item['ID']; ?>, 'switches')">ลบสินค้า</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="fresh_food" class="content">
        <h3 class="h-text">🎨 คีย์แค็ป</h3>
        <table border="1" cellspacing="0" cellpadding="10">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Profile</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($keycaps as $item): ?>
                    <tr>
                        <td><img src="<?php echo $item['IMAGE_URL']; ?>" alt="<?php echo $item['NAME']; ?>" width="100"></td>
                        <td><?php echo $item['NAME']; ?></td>
                        <td>฿<?php echo $item['PRICE']; ?></td>
                        <td><?php echo $item['KEYCAP_PROFILE']; ?></td>
                        <td>
                            <form action="#" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $item['ID']; ?>">
                                <button type="button" class="btn-edit" onclick="openEditPopup(<?php echo $item['ID']; ?>)">แก้ไขสินค้า</button>
                            </form>
                            <!-- Popup Form -->
                            <div id="editPopup-<?php echo $item['ID']; ?>" class="edit-popup">
                                <div class="popup-content">
                                    <button type="button" class="btn-close" onclick="closeEditPopup(<?php echo $item['ID']; ?>)">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                    <h3>แก้ไขข้อมูลสินค้า</h3>
                                    <form action="../product/edit_product/edit_product.php" method="POST">
                                        <input type="hidden" name="productID" value="<?php echo $item['ID']; ?>">
                                        <input type="text" name="productName" value="<?php echo $item['NAME']; ?>" required>
                                        <input type="text" name="productPrice" value="<?php echo $item['PRICE']; ?>" required>
                                        <input type="hidden" name="productCategory" value="keycaps"> <!-- หรือ หมวดหมู่อื่นๆ ตามความเหมาะสม -->
                                        <input type="text" name="keycapProfile" value="<?php echo $item['KEYCAP_PROFILE']; ?>" required>
                                        <button type="submit" class="btn-edit-prodect">บันทึก</button>
                                    </form>

                                </div>
                            </div>
                            <form id="deleteForm-<?php echo $item['ID']; ?>" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $item['ID']; ?>">
                                <input type="hidden" name="category" value="keycaps"> <!-- หมวดหมู่สินค้านี้ -->
                                <button type="button" class="btn-delete" onclick="deleteProduct(<?php echo $item['ID']; ?>, 'keycaps')">ลบสินค้า</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="snack_food" class="content">
        <h3 class="h-text">🎨 คีย์แค็ป</h3>
        <table border="1" cellspacing="0" cellpadding="10">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Profile</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($keycaps as $item): ?>
                    <tr>
                        <td><img src="<?php echo $item['IMAGE_URL']; ?>" alt="<?php echo $item['NAME']; ?>" width="100"></td>
                        <td><?php echo $item['NAME']; ?></td>
                        <td>฿<?php echo $item['PRICE']; ?></td>
                        <td><?php echo $item['KEYCAP_PROFILE']; ?></td>
                        <td>
                            <form action="#" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $item['ID']; ?>">
                                <button type="button" class="btn-edit" onclick="openEditPopup(<?php echo $item['ID']; ?>)">แก้ไขสินค้า</button>
                            </form>
                            <!-- Popup Form -->
                            <div id="editPopup-<?php echo $item['ID']; ?>" class="edit-popup">
                                <div class="popup-content">
                                    <button type="button" class="btn-close" onclick="closeEditPopup(<?php echo $item['ID']; ?>)">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                    <h3>แก้ไขข้อมูลสินค้า</h3>
                                    <form action="../product/edit_product/edit_product.php" method="POST">
                                        <input type="hidden" name="productID" value="<?php echo $item['ID']; ?>">
                                        <input type="text" name="productName" value="<?php echo $item['NAME']; ?>" required>
                                        <input type="text" name="productPrice" value="<?php echo $item['PRICE']; ?>" required>
                                        <input type="hidden" name="productCategory" value="keycaps"> <!-- หรือ หมวดหมู่อื่นๆ ตามความเหมาะสม -->
                                        <input type="text" name="keycapProfile" value="<?php echo $item['KEYCAP_PROFILE']; ?>" required>
                                        <button type="submit" class="btn-edit-prodect">บันทึก</button>
                                    </form>

                                </div>
                            </div>
                            <form id="deleteForm-<?php echo $item['ID']; ?>" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $item['ID']; ?>">
                                <input type="hidden" name="category" value="keycaps"> <!-- หมวดหมู่สินค้านี้ -->
                                <button type="button" class="btn-delete" onclick="deleteProduct(<?php echo $item['ID']; ?>, 'keycaps')">ลบสินค้า</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <div id="sci_admin" class="content">
        <h3 class="h-text">🛠️ การจัดการ Admin</h3>
        <!-- ตารางข้อมูลผู้ใช้ -->
        <table border="1" cellspacing="0" cellpadding="10">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($adminUsers as $user): ?>
                    <tr>
                        <td><?php echo $user['ID']; ?></td>
                        <td><?php echo $user['USERNAME']; ?></td>
                        <td>
                            <!-- ปุ่มแก้ไขรหัสผ่าน -->
                            <form action="edit_password.php" method="POST" id="edit-password-form-<?php echo $user['ID']; ?>" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['ID']); ?>">
                                <button class="btn-edit" type="button" onclick="showEditPasswordForm(<?php echo $user['ID']; ?>)">แก้ไขรหัสผ่าน</button>
                            </form>

                            <!-- ฟอร์มแก้ไขรหัสผ่านที่แสดงเมื่อคลิกปุ่ม -->
                            <form action="../admin/update_password_admin/update_password_admin.php" method="POST" id="password-form-<?php echo $user['ID']; ?>" style="display: none;">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['ID']); ?>">

                                <!-- label ใช้ id ที่ไม่ซ้ำกับ input -->
                                <label for="new_password-<?php echo $user['ID']; ?>">รหัสผ่านใหม่:</label>
                                <input type="password" id="new_password-<?php echo $user['ID']; ?>" name="new_password" required>

                                <button type="submit">บันทึก</button>
                                <button type="button" onclick="hideEditPasswordForm(<?php echo $user['ID']; ?>)">ยกเลิก</button>
                            </form>
                        </td>

                        <td><?php echo $user['ROLE']; ?></td>
                        <td>
                            <!-- ปุ่มลบ -->
                            <form action="../admin/delete_admin/delete_admin.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $user['ID']; ?>">
                                <button type="submit" class="btn-delete">ลบผู้ดูแลระบบ</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="employee" class="content">
        <h3 class="h-text">🛠️ การจัดการ Admin</h3>
        <!-- ตารางข้อมูลผู้ใช้ -->
        <table border="1" cellspacing="0" cellpadding="10">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($adminUsers as $user): ?>
                    <tr>
                        <td><?php echo $user['ID']; ?></td>
                        <td><?php echo $user['USERNAME']; ?></td>
                        <td>
                            <!-- ปุ่มแก้ไขรหัสผ่าน -->
                            <form action="edit_password.php" method="POST" id="edit-password-form-<?php echo $user['ID']; ?>" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['ID']); ?>">
                                <button class="btn-edit" type="button" onclick="showEditPasswordForm(<?php echo $user['ID']; ?>)">แก้ไขรหัสผ่าน</button>
                            </form>

                            <!-- ฟอร์มแก้ไขรหัสผ่านที่แสดงเมื่อคลิกปุ่ม -->
                            <form action="../admin/update_password_admin/update_password_admin.php" method="POST" id="password-form-<?php echo $user['ID']; ?>" style="display: none;">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['ID']); ?>">

                                <!-- label ใช้ id ที่ไม่ซ้ำกับ input -->
                                <label for="new_password-<?php echo $user['ID']; ?>">รหัสผ่านใหม่:</label>
                                <input type="password" id="new_password-<?php echo $user['ID']; ?>" name="new_password" required>

                                <button type="submit">บันทึก</button>
                                <button type="button" onclick="hideEditPasswordForm(<?php echo $user['ID']; ?>)">ยกเลิก</button>
                            </form>
                        </td>

                        <td><?php echo $user['ROLE']; ?></td>
                        <td>
                            <!-- ปุ่มลบ -->
                            <form action="../admin/delete_admin/delete_admin.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $user['ID']; ?>">
                                <button type="submit" class="btn-delete">ลบผู้ดูแลระบบ</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="account" class="content">
        <div class="profile-container">
            <div class="cover"></div>
            <div class="profile-card">
                <div class="profile-image">
                    <img id="profile-pic" src="\sci-shop-admin\img\pachara.jpg" alt="Admin Profile">
                    <label for="file-input" class="upload-btn">
                        <i class="material-icons">photo_camera</i>
                    </label>
                    <input id="file-input" type="file" accept="image/*" onchange="previewImage(event)">
                </div>

                <div class="profile-details">
                    <form id="profile-form">
                        <h2 class="profile-title">ข้อมูลโปรไฟล์</h2>
                        <div class="profile-row">
                            <div class="profile-info">
                                <label><span class="material-icons">badge</span> ชื่อ</label>
                                <input type="text" id="first-name" value="Pachara">
                            </div>
                            <div class="profile-info">
                                <label><span class="material-icons">fingerprint</span> นามสกุล</label>
                                <input type="text" id="last-name" value="Kalapakdee">
                            </div>
                        </div>

                        <div class="profile-row">
                            <div class="profile-info">
                                <label><span class="material-icons">person_outline</span> ชื่อผู้ใช้</label>
                                <input type="text" id="username" value="pachara">
                            </div>
                            <div class="profile-info">
                                <label><span class="material-icons">password</span> รหัสผ่าน</label>
                                <input type="password" id="password" placeholder="********">
                            </div>
                        </div>

                        <div class="profile-row">
                            <button type="submit" class="confirm-btn">
                                <span class="material-icons">done_outline</span> ยืนยันการแก้ไข
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const reader = new FileReader();

            reader.onload = function() {
                const imgElement = document.getElementById('profile-pic');
                imgElement.src = reader.result;
            };

            if (input.files.length > 0) {
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById("profile-form").addEventListener("submit", function(event) {
            event.preventDefault(); // ป้องกันการรีโหลดหน้า

            const firstName = document.getElementById("first-name").value;
            const lastName = document.getElementById("last-name").value;
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;

            // ส่งข้อมูลไปบันทึก (เช่น ส่งไป API หรืออัปเดตฐานข้อมูล)
            alert("ข้อมูลได้รับการอัปเดตเรียบร้อย!");
        });


        function toggleDropdown(button) {
            // ปิด dropdown อื่นก่อน
            document.querySelectorAll('.action-dropdown').forEach(dropdown => {
                if (dropdown !== button.parentElement) {
                    dropdown.classList.remove('show');
                }
            });

            // toggle อันที่คลิก
            button.parentElement.classList.toggle('show');
        }

        // ปิด dropdown เมื่อคลิกนอก
        window.addEventListener('click', function(e) {
            if (!e.target.matches('.action-dropdown button')) {
                document.querySelectorAll('.action-dropdown').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });

        function openEditPanel(id) {
            const overlay = document.getElementById('overlay');
            const editPanel = document.getElementById('editPanel');

            // เปิด overlay กับ side panel
            overlay.style.display = 'block';
            editPanel.style.display = 'block'; // <<< เพิ่มบรรทัดนี้
            setTimeout(() => {
                editPanel.style.right = '0';
            }, 10); // เพิ่ม delay เล็กน้อยให้ CSS transition ทำงานนุ่มนวล

            // ปิดการเลื่อนหน้าจอ
            document.body.style.overflow = 'hidden';

            // Fetch data จาก server
            fetch('../../product/edit_product/get_food_all/get_dried_food.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_product_id').value = data.id;
                    document.getElementById('product_name').value = data.product_name;
                    document.getElementById('barcode').value = data.barcode || '';
                    document.getElementById('price').value = data.price;
                    document.getElementById('cost').value = data.cost;
                    document.getElementById('stock').value = data.stock;
                    document.getElementById('reorder_level').value = data.reorder_level;
                    document.getElementById('image_url').value = data.image_url;

                    document.getElementById('editProductForm').action = '../../product/edit_product/edit_food_all/edit_dried_food.php?id=' + data.id;
                })
                .catch(error => {
                    console.error('เกิดข้อผิดพลาดในการโหลดข้อมูลสินค้า:', error);
                });
        }

        function closeEditPanel() {
            const overlay = document.getElementById('overlay');
            const editPanel = document.getElementById('editPanel');

            overlay.style.display = 'none';
            editPanel.style.right = '-500px';
            setTimeout(() => {
                editPanel.style.display = 'none';
            }, 300); // ต้องรอให้ side panel เลื่อนออกไปก่อนค่อยปิด display

            // เปิดการเลื่อนหน้าจอเมื่อปิด editPanel
            document.body.style.overflow = 'auto';
        }

        function showFileName(input) {
            const fileNameText = input.nextElementSibling;
            if (input.files.length > 0) {
                fileNameText.textContent = input.files[0].name;
            } else {
                fileNameText.textContent = "ยังไม่ได้เลือกไฟล์";
            }
        }

        // กราฟภาพรวมยอดขาย (bar chart)
        const mainSalesCtx = document.getElementById('mainSalesChart').getContext('2d');
        const mainSalesChart = new Chart(mainSalesCtx, {
            type: 'bar',
            data: {
                labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.'],
                datasets: [{
                    label: 'ยอดขาย (บาท)',
                    data: [120000, 135000, 110000, 150000, 175000, 160000],
                    backgroundColor: '#4A90E2',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => '฿' + value.toLocaleString()
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // กราฟลูกค้าใหม่ (doughnut chart)
        const customerCtx = document.getElementById('newCustomerChart').getContext('2d');
        const newCustomerChart = new Chart(customerCtx, {
            type: 'doughnut',
            data: {
                labels: ['ใหม่', 'เก่า'],
                datasets: [{
                    label: 'ลูกค้า',
                    data: [42, 128],
                    backgroundColor: ['#50E3C2', '#E0E0E0'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        function toggleMenu() {
            const filterMenu = document.getElementById('filterMenu');
            filterMenu.style.display = (filterMenu.style.display === 'block') ? 'none' : 'block';
        }

        function applyFilters() {
            const category = document.getElementById('filterCategory').value;
            const price = document.getElementById('filterPrice').value;

            console.log(`Applied Filters - Category: ${category}, Max Price: ${price}`);

            // ทำการกรองข้อมูลตามฟิลเตอร์ที่เลือก
            // ตัวอย่างเช่น กรองสินค้าใน filteredProducts ตาม category และ price ที่เลือก
            // ฟังก์ชันนี้สามารถปรับให้ทำงานตามความต้องการของคุณ
        }


        const products = <?php echo json_encode($dried_food); ?>;
        const tableBody = document.getElementById('productTableBody');
        const grid = document.getElementById('productGrid');
        const pagination = document.getElementById('pagination');
        const container = document.getElementById('container');

        let filteredProducts = [...products];
        let currentPage = 1;
        const itemsPerPage = 10;
        let searchText = '';
        let currentView = localStorage.getItem('viewMode') || 'table'; // ใช้ค่าใน localStorage หรือ default เป็น table

        function highlightText(text, keyword) {
            if (!keyword) return text;
            const pattern = new RegExp(`(${keyword})`, 'gi');
            return text.replace(pattern, '<span class="highlight">$1</span>');
        }

        function displayProducts() {
            tableBody.innerHTML = '';
            grid.innerHTML = '';

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const paginatedItems = filteredProducts.slice(startIndex, endIndex);

            if (currentView === 'table') {
                document.getElementById('productTable').style.display = 'table';
                grid.style.display = 'none';

                if (paginatedItems.length === 0) {
                    tableBody.innerHTML = `
    <tr>
        <td colspan="8" class="no-items-message">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
            ไม่พบสินค้าที่ค้นหา
        </td>
    </tr>`;
                } else {
                    paginatedItems.forEach(item => {
                        tableBody.innerHTML += `
                    <tr>
                        <td style="text-align:left;">${highlightText(item.product_name, searchText)}</td>
                        <td>
                            <div class="product-image-container">
                                <img src="${item.image_url}" alt="${item.product_name}" width="100">
                                <div class="out-of-stock-label-table">สินค้าใกล้หมด</div>
                            </div>
                        </td>
                        <td style="text-align:center;">
                            <div class="barcode-cell">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg>
                                <span>${highlightText(item.barcode, searchText)}</span>
                            </div>
                        </td>
                        <td>${Number(item.price).toLocaleString()} บาท</td>
                        <td>${Number(item.cost).toLocaleString()} บาท</td>
                        <td>${item.stock} ชิ้น</td>
                        <td>${item.reorder_level} ชิ้น</td>
                        <td>
                            <div class="action-dropdown">
                                <button onclick="toggleDropdown(this)">⋮</button>
                                <div class="dropdown-content">
                                    <a onclick="openEditPanel(${item.id}); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</a>
                                    <a href="delete.php?id=${item.id}" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบ?')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>ลบ</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
                    });
                }
            } else if (currentView === 'grid') {
                document.getElementById('productTable').style.display = 'none';
                grid.style.display = 'grid';

                if (paginatedItems.length === 0) {
                    grid.innerHTML = `
        <p style="grid-column: 1 / -1; text-align: center; display: flex; align-items: center; justify-content: center; gap: 2px; ">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333" style="margin-right: 10px;">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
            ไม่พบสินค้าที่ค้นหา
        </p>
    `;
                } else {
                    paginatedItems.forEach(item => {
                        grid.innerHTML += `
                    <div class="card">
                        <div class="product-image-container">
                            <img class="product-image" src="${item.image_url}" alt="${item.product_name}">
                            <div class="out-of-stock-label">สินค้าใกล้หมด</div>
                        </div>
                        <div class="card-content">
                            <h3>${highlightText(item.product_name, searchText)}</h3>
                            <div class="barcode">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg>
                                <span>${highlightText(item.barcode, searchText)}</span>
                            </div>
                            <p><span class="label">ราคา:</span> ${Number(item.price).toLocaleString()} บาท</p>
                            <p><span class="label">ต้นทุน:</span> ${Number(item.cost).toLocaleString()} บาท</p>
                            <p><span class="label">สต็อก:</span> ${item.stock} ชิ้น</p>
                            <p><span class="label">เกณฑ์สั่งซื้อ:</span> ${item.reorder_level} ชิ้น</p>
                            <div class="card-actions">
                                <button onclick="openEditPanel(${item.id})"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</button>
                                <a href="delete.php?id=${item.id}" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบ?')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>ลบ</a>
                            </div>
                        </div>
                    </div>
                `;
                    });
                }
            }

            renderPagination();
        }

        function renderPagination() {
            pagination.innerHTML = '';

            const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
            if (totalPages <= 1) return;

            if (currentPage > 1) {
                pagination.innerHTML += `<button class="page-btn" onclick="goToPage(${currentPage - 1})">ก่อนหน้า</button>`;
            }

            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);

            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            for (let i = startPage; i <= endPage; i++) {
                pagination.innerHTML += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
            }

            if (currentPage < totalPages) {
                pagination.innerHTML += `<button class="page-btn" onclick="goToPage(${currentPage + 1})">ถัดไป</button>`;
            }
        }

        function goToPage(page) {
            currentPage = page;
            displayProducts();
        }

        function filterProducts() {
            searchText = document.getElementById('searchInput').value.trim().toLowerCase();

            if (searchText === '') {
                filteredProducts = [...products];
            } else {
                filteredProducts = products.filter(item =>
                    item.product_name.toLowerCase().includes(searchText) ||
                    item.barcode.toLowerCase().includes(searchText)
                );
            }

            currentPage = 1;
            displayProducts();
        }

        function switchView(viewType) {
            currentView = viewType; // บันทึกมุมมองที่เลือก
            localStorage.setItem('viewMode', viewType); // บันทึกการเลือกมุมมองใน localStorage
            displayProducts();
        }

        // โหลดครั้งแรก
        displayProducts();















        let selectedCategory = '';

        function selectCategory(event, category) {
            selectedCategory = category;
            console.log("หมวดหมู่ที่เลือก:", selectedCategory);

            // ล้าง class 'active' ออกจากปุ่มทั้งหมด
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // เพิ่ม class 'active' ให้กับปุ่มที่ถูกเลือก
            event.currentTarget.classList.add('active');
        }

        function handleFormSubmit(event) {
            event.preventDefault(); // ป้องกันการรีเฟรชหน้า

            if (!selectedCategory) {
                alert("กรุณาเลือกหมวดหมู่สินค้า");
                return;
            }

            const productName = document.getElementById('productName').value;
            const productImage = document.getElementById('productImage').value;
            const barcode = document.getElementById('barcode').value;
            const productPrice = document.getElementById('productPrice').value;
            const productCost = document.getElementById('productCost').value;
            const productStock = document.getElementById('productStock').value;
            const productReorderLevel = document.getElementById('productReorderLevel').value;

            if (productName && productImage && barcode && productPrice && productCost && productStock && productReorderLevel) {
                sendDataToTable(selectedCategory, productName, productImage, barcode, productPrice, productCost, productStock, productReorderLevel);

                document.getElementById('uploadForm').reset(); // รีเซ็ตฟอร์ม
                setTimeout(() => {
                    history.replaceState(null, null, location.href);
                }, 500); // ลบข้อมูล session หลัง 0.5 วิ
            } else {
                alert("กรุณากรอกข้อมูลให้ครบถ้วน!");
            }
        }

        function sendDataToTable(category, productName, productImage, barcode, productPrice, productCost, productStock, productReorderLevel) {
            const tableBody = document.getElementById(`${category}_table`);

            if (!tableBody) {
                alert(`ไม่พบตารางที่ตรงกับหมวดหมู่ที่เลือก: ${category}_table`);
                return;
            }

            // สร้างแถวใหม่ในตาราง
            const row = document.createElement('tr');
            row.innerHTML = `
        <td><input type="checkbox" class="row-checkbox" data-product="${productName}" onchange="updateCheckboxStatus(event, '${category}')"></td>
        <td>${productName}</td>
        <td><img src="${productImage}" alt="${productName}" style="width: 60px; height: auto;"></td>
        <td>${barcode}</td>
        <td>${productPrice} บาท</td>
        <td>${productCost} บาท</td>
        <td>${productStock} ชิ้น</td>
        <td>${productReorderLevel} ชิ้น</td>
    `;
            tableBody.appendChild(row);

            // บันทึกข้อมูลสินค้าและสถานะ checkbox ลงใน localStorage
            saveToLocalStorage(category, productName, productImage, barcode, productPrice, productCost, productStock, productReorderLevel);

            // เรียกใช้ฟังก์ชัน loadFromLocalStorage เพื่อรีเฟรชข้อมูลทันที
            loadFromLocalStorage();

            // รีเซ็ตฟอร์ม
            document.getElementById('uploadForm').reset();
        }


        function saveToLocalStorage(category, productName, productImage, barcode, productPrice, productCost, productStock, productReorderLevel) {
            let savedData = JSON.parse(localStorage.getItem(category)) || [];

            const productData = {
                productName,
                productImage,
                barcode,
                productPrice,
                productCost,
                productStock,
                productReorderLevel,
                isChecked: false // สถานะของ checkbox
            };

            savedData.push(productData);
            localStorage.setItem(category, JSON.stringify(savedData));
        }

        function loadFromLocalStorage() {
            const categories = ['dried_food', 'soft_drink', 'fresh_food', 'snack'];
            categories.forEach(category => {
                const savedData = JSON.parse(localStorage.getItem(category)) || [];
                const tableBody = document.getElementById(`${category}_table`);

                // ล้างข้อมูลเดิมก่อน
                tableBody.innerHTML = '';

                if (savedData.length === 0) {
                    // ถ้าไม่มีข้อมูลให้แสดงข้อความ
                    const noDataRow = document.createElement('tr');
                    noDataRow.innerHTML = `<td colspan="8" style="text-align: center; color: #000000;">ไม่มีสินค้าที่สามารถบันทึกได้</td>`;
                    tableBody.appendChild(noDataRow);
                } else {
                    // ถ้ามีข้อมูลให้เพิ่มแถวของสินค้า
                    savedData.forEach(data => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                    <td><input type="checkbox" class="row-checkbox" data-product="${data.productName}" ${data.isChecked ? 'checked' : ''} onchange="updateCheckboxStatus(event, '${category}')"></td>
                    <td>${data.productName}</td>
                    <td><img src="${data.productImage}" alt="${data.productName}" style="width: 60px; height: auto;"></td>
                    <td>${data.barcode}</td>
                    <td>${data.productPrice} บาท</td>
                    <td>${data.productCost} บาท</td>
                    <td>${data.productStock} ชิ้น</td>
                    <td>${data.productReorderLevel} ชิ้น</td>
                `;
                        tableBody.appendChild(row);
                    });
                }
            });
        }


        // ฟังก์ชันที่ใช้ในการอัปเดตสถานะของ checkbox ใน localStorage
        function updateCheckboxStatus(event, category) {
            const checkbox = event.target;
            const productName = checkbox.getAttribute('data-product');

            // ดึงข้อมูลจาก localStorage
            const savedData = JSON.parse(localStorage.getItem(category)) || [];

            // อัปเดตสถานะ checkbox ที่เลือก
            const updatedData = savedData.map(data => {
                if (data.productName === productName) {
                    data.isChecked = checkbox.checked; // ถ้าเลือก จะเป็น true, ถ้าไม่เลือก จะเป็น false
                }
                return data;
            });

            // บันทึกข้อมูลกลับลงใน localStorage
            localStorage.setItem(category, JSON.stringify(updatedData));
        }

        // เรียกใช้เมื่อโหลดหน้า
        window.onload = function() {
            loadFromLocalStorage();
        };

        // ฟังก์ชันเลือกสินค้าทั้งหมด tableId
        function toggleSelectAllForTable(source, tableId) {
            const table = document.getElementById(tableId);
            if (!table) return;

            table.querySelectorAll('.row-checkbox').forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }

        document.querySelectorAll(".btn-addd").forEach(button => {
            button.addEventListener("click", function() {
                let selectedProducts = [];

                // ค้นหาสินค้าที่ถูก checkbox ไว้ในทุกตาราง
                document.querySelectorAll(".row-checkbox:checked").forEach((checkbox) => {
                    let row = checkbox.closest("tr");
                    let productData = {
                        productName: row.children[1].textContent.trim(),
                        productImage: row.children[2].querySelector("img").src,
                        barcode: row.children[3].textContent.trim(),
                        productPrice: parseFloat(row.children[4].textContent.replace(" บาท", "").trim()),
                        productCost: parseFloat(row.children[5].textContent.replace(" บาท", "").trim()),
                        productStock: parseInt(row.children[6].textContent.replace(" ชิ้น", "").trim()),
                        productReorderLevel: parseInt(row.children[7].textContent.replace(" ชิ้น", "").trim()),
                    };
                    selectedProducts.push(productData);
                });

                if (selectedProducts.length === 0) {
                    alert("กรุณาเลือกสินค้าอย่างน้อยหนึ่งรายการ");
                    return;
                }

                console.log("🛒 Sending data:", JSON.stringify({
                    products: selectedProducts
                }));

                // ส่งข้อมูลไปยัง PHP
                fetch("/sci-shop-admin/product/upload_product/add_food_all/add_dried_food.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            products: selectedProducts
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Response from server:", data);
                        if (data.success) {
                            alert("เพิ่มสินค้าสำเร็จ!");

                            // ลบแถวที่ถูกเลือกออกจากตาราง
                            document.querySelectorAll(".row-checkbox:checked").forEach((checkbox) => {
                                let row = checkbox.closest("tr");
                                row.remove();
                            });

                            // ลบข้อมูลที่ถูกเลือกออกจาก localStorage
                            selectedProducts.forEach(product => {
                                const category = "dried_food"; // เปลี่ยนชื่อหมวดหมู่หากจำเป็น
                                let savedData = JSON.parse(localStorage.getItem(category)) || [];

                                savedData = savedData.filter(data => data.productName !== product.productName); // ลบสินค้าที่เลือก

                                // บันทึกข้อมูลที่อัปเดตลง localStorage
                                localStorage.setItem(category, JSON.stringify(savedData));
                            });
                        } else {
                            alert("เกิดข้อผิดพลาด: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
        });

        document.querySelectorAll(".btn-adddd").forEach(button => {
            button.addEventListener("click", function() {
                let selectedProducts = [];

                // ค้นหาสินค้าที่ถูก checkbox ไว้ในทุกตาราง
                document.querySelectorAll(".row-checkbox:checked").forEach((checkbox) => {
                    let row = checkbox.closest("tr");
                    let productData = {
                        productName: row.children[1].textContent.trim(),
                        productImage: row.children[2].querySelector("img").src,
                        barcode: row.children[3].textContent.trim(),
                        productPrice: parseFloat(row.children[4].textContent.replace(" บาท", "").trim()),
                        productCost: parseFloat(row.children[5].textContent.replace(" บาท", "").trim()),
                        productStock: parseInt(row.children[6].textContent.replace(" ชิ้น", "").trim()),
                        productReorderLevel: parseInt(row.children[7].textContent.replace(" ชิ้น", "").trim()),
                    };
                    selectedProducts.push(productData);
                });

                if (selectedProducts.length === 0) {
                    alert("กรุณาเลือกสินค้าอย่างน้อยหนึ่งรายการ");
                    return;
                }

                console.log("🛒 Sending data:", JSON.stringify({
                    products: selectedProducts
                }));

                // ส่งข้อมูลไปยัง PHP
                fetch("/sci-shop-admin/product/upload_product/add_food_all/add_soft_drink.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            products: selectedProducts
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Response from server:", data);
                        if (data.success) {
                            alert("เพิ่มสินค้าสำเร็จ!");

                            // ลบแถวที่ถูกเลือกออกจากตาราง
                            document.querySelectorAll(".row-checkbox:checked").forEach((checkbox) => {
                                let row = checkbox.closest("tr");
                                row.remove();
                            });

                            // ลบข้อมูลที่ถูกเลือกออกจาก localStorage
                            selectedProducts.forEach(product => {
                                const category = "soft_drink"; // เปลี่ยนชื่อหมวดหมู่หากจำเป็น
                                let savedData = JSON.parse(localStorage.getItem(category)) || [];

                                savedData = savedData.filter(data => data.productName !== product.productName); // ลบสินค้าที่เลือก

                                // บันทึกข้อมูลที่อัปเดตลง localStorage
                                localStorage.setItem(category, JSON.stringify(savedData));
                            });
                        } else {
                            alert("เกิดข้อผิดพลาด: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
        });

        document.querySelectorAll(".btn-addddd").forEach(button => {
            button.addEventListener("click", function() {
                let selectedProducts = [];

                // ค้นหาสินค้าที่ถูก checkbox ไว้ในทุกตาราง
                document.querySelectorAll(".row-checkbox:checked").forEach((checkbox) => {
                    let row = checkbox.closest("tr");
                    let productData = {
                        productName: row.children[1].textContent.trim(),
                        productImage: row.children[2].querySelector("img").src,
                        barcode: row.children[3].textContent.trim(),
                        productPrice: parseFloat(row.children[4].textContent.replace(" บาท", "").trim()),
                        productCost: parseFloat(row.children[5].textContent.replace(" บาท", "").trim()),
                        productStock: parseInt(row.children[6].textContent.replace(" ชิ้น", "").trim()),
                        productReorderLevel: parseInt(row.children[7].textContent.replace(" ชิ้น", "").trim()),
                    };
                    selectedProducts.push(productData);
                });

                if (selectedProducts.length === 0) {
                    alert("กรุณาเลือกสินค้าอย่างน้อยหนึ่งรายการ");
                    return;
                }

                console.log("🛒 Sending data:", JSON.stringify({
                    products: selectedProducts
                }));

                // ส่งข้อมูลไปยัง PHP
                fetch("/sci-shop-admin/product/upload_product/add_food_all/add_fresh_food.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            products: selectedProducts
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Response from server:", data);
                        if (data.success) {
                            alert("เพิ่มสินค้าสำเร็จ!");

                            // ลบแถวที่ถูกเลือกออกจากตาราง
                            document.querySelectorAll(".row-checkbox:checked").forEach((checkbox) => {
                                let row = checkbox.closest("tr");
                                row.remove();
                            });

                            // ลบข้อมูลที่ถูกเลือกออกจาก localStorage
                            selectedProducts.forEach(product => {
                                const category = "fresh_food"; // เปลี่ยนชื่อหมวดหมู่หากจำเป็น
                                let savedData = JSON.parse(localStorage.getItem(category)) || [];

                                savedData = savedData.filter(data => data.productName !== product.productName); // ลบสินค้าที่เลือก

                                // บันทึกข้อมูลที่อัปเดตลง localStorage
                                localStorage.setItem(category, JSON.stringify(savedData));
                            });
                        } else {
                            alert("เกิดข้อผิดพลาด: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
        });

        document.querySelectorAll(".btn-adddddd").forEach(button => {
            button.addEventListener("click", function() {
                let selectedProducts = [];

                // ค้นหาสินค้าที่ถูก checkbox ไว้ในทุกตาราง
                document.querySelectorAll(".row-checkbox:checked").forEach((checkbox) => {
                    let row = checkbox.closest("tr");
                    let productData = {
                        productName: row.children[1].textContent.trim(),
                        productImage: row.children[2].querySelector("img").src,
                        barcode: row.children[3].textContent.trim(),
                        productPrice: parseFloat(row.children[4].textContent.replace(" บาท", "").trim()),
                        productCost: parseFloat(row.children[5].textContent.replace(" บาท", "").trim()),
                        productStock: parseInt(row.children[6].textContent.replace(" ชิ้น", "").trim()),
                        productReorderLevel: parseInt(row.children[7].textContent.replace(" ชิ้น", "").trim()),
                    };
                    selectedProducts.push(productData);
                });

                if (selectedProducts.length === 0) {
                    alert("กรุณาเลือกสินค้าอย่างน้อยหนึ่งรายการ");
                    return;
                }

                console.log("🛒 Sending data:", JSON.stringify({
                    products: selectedProducts
                }));

                // ส่งข้อมูลไปยัง PHP
                fetch("/sci-shop-admin/product/upload_product/add_food_all/add_snack.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            products: selectedProducts
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Response from server:", data);
                        if (data.success) {
                            alert("เพิ่มสินค้าสำเร็จ!");

                            // ลบแถวที่ถูกเลือกออกจากตาราง
                            document.querySelectorAll(".row-checkbox:checked").forEach((checkbox) => {
                                let row = checkbox.closest("tr");
                                row.remove();
                            });

                            // ลบข้อมูลที่ถูกเลือกออกจาก localStorage
                            selectedProducts.forEach(product => {
                                const category = "snack"; // เปลี่ยนชื่อหมวดหมู่หากจำเป็น
                                let savedData = JSON.parse(localStorage.getItem(category)) || [];

                                savedData = savedData.filter(data => data.productName !== product.productName); // ลบสินค้าที่เลือก

                                // บันทึกข้อมูลที่อัปเดตลง localStorage
                                localStorage.setItem(category, JSON.stringify(savedData));
                            });
                        } else {
                            alert("เกิดข้อผิดพลาด: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
        });


        // แสดง Tab ที่ถูกเลือก
        document.addEventListener("DOMContentLoaded", function() {
            const activeTab = localStorage.getItem("activeTab") || "order";
            showTab(activeTab);
        });

        function showTab(tabId) {
            document.querySelectorAll('.content').forEach(c => c.classList.remove('show'));
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));

            document.getElementById(tabId).classList.add('show');
            document.querySelector(`[onclick="showTab('${tabId}')"]`).classList.add('active');

            localStorage.setItem("activeTab", tabId); // บันทึกแท็บที่เปิดล่าสุด
        }

        function displayMessage(message, type) {
            // สร้าง element สำหรับแสดงข้อความ
            var messageBox = document.createElement('div');
            messageBox.classList.add('message-box', type); // เพิ่มคลาสตามประเภทของข้อความ
            messageBox.innerHTML = message;

            // แสดงข้อความใน body
            document.body.appendChild(messageBox);

            // ซ่อนข้อความหลังจาก 3 วินาที
            setTimeout(function() {
                messageBox.remove();
            }, 500);
        }

        // ฟังก์ชั่นที่ทำงานเมื่อฟอร์มถูกส่ง (สำหรับการสร้าง employee)
        function submitemployeeForm() {
            var form = document.getElementById('employeeSignupForm');
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            var isValid = true;

            // ล้างเส้นขอบผิดพลาดจากฟอร์ม
            clearInputErrors();

            // ตรวจสอบรหัสผ่านและยืนยันรหัสผ่าน
            if (password !== confirmPassword) {
                showNotify('<svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#e3e3e3"><path d="M600-240v-120H488q-32 54-87 87t-121 33q-100 0-170-70T40-480q0-100 70-170t170-70q66 0 121 33t87 87h272v80H434q-8-39-48-79.5T280-640q-66 0-113 47t-47 113q0 66 47 113t113 47q66 0 106-40.5t48-79.5h246v120h80v80H600ZM280-400q33 0 56.5-23.5T360-480q0-33-23.5-56.5T280-560q-33 0-56.5 23.5T200-480q0 33 23.5 56.5T280-400Zm0-80Zm600 240q-17 0-28.5-11.5T840-280q0-17 11.5-28.5T880-320q17 0 28.5 11.5T920-280q0 17-11.5 28.5T880-240Zm-40-160v-200h80v200h-80Z"/></svg> รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน', "error");
                addInputError(document.getElementById("confirmPassword"));
                isValid = false;
            }

            if (!isValid) {
                return false;
            }

            var formData = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.setRequestHeader("Accept", "application/json");

            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log("Raw Server Response:", xhr.responseText);
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === "success") {
                            showNotify('<svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#e3e3e3"><path d="m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Zm34-102 102-44 104 44 56-96 110-26-10-112 74-84-74-86 10-112-110-24-58-96-102 44-104-44-56 96-110 24 10 112-74 86 74 84-10 114 110 24 58 96Zm102-318Zm-42 142 226-226-56-58-170 170-86-84-56 56 142 142Z"/></svg> สมัครสมาชิกสำเร็จ', "success");
                            setTimeout(function() {
                                form.reset();
                                location.reload();
                            }, 1000);
                        } else if (response.status === "error") {
                            if (response.message === 'ชื่อผู้ใช้นี้มีอยู่แล้วในระบบ') {
                                showNotify('<svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#e3e3e3"><path d="M800-520q-17 0-28.5-11.5T760-560q0-17 11.5-28.5T800-600q17 0 28.5 11.5T840-560q0 17-11.5 28.5T800-520Zm-40-120v-200h80v200h-80ZM360-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm80-80h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0-80Zm0 400Z"/></svg> ชื่อผู้ใช้นี้มีอยู่แล้วในระบบ', "error");
                            }
                        }
                    } catch (error) {
                        console.error("Error parsing JSON:", error);
                        showNotify("เกิดข้อผิดพลาดในการประมวลผลข้อมูล: " + error.message, "error");
                    }
                } else {
                    showNotify('เกิดข้อผิดพลาดในการส่งข้อมูล: ' + xhr.responseText, "error");
                }
            };

            xhr.send(formData);
            return false;
        }


        // ฟังก์ชันแสดงข้อความแจ้งเตือน (employee)
        function showNotify(message, type) {
            const notifyBox = document.getElementById('notify');
            const notifyMessage = document.getElementById('notify-message');

            notifyMessage.innerHTML = message; // ใส่ข้อความ
            notifyBox.className = 'notify-box notify-' + type; // เช่น notify-success หรือ notify-error

            notifyBox.style.display = 'flex';
            setTimeout(() => {
                notifyBox.style.opacity = 1;
            }, 10);

            setTimeout(() => {
                notifyBox.style.opacity = 0;
                setTimeout(() => {
                    notifyBox.style.display = 'none';
                }, 500);
            }, 3000);
        }

        function addInputError(inputElement) {
            inputElement.classList.add('input-error');
        }

        function clearInputErrors() {
            document.querySelectorAll('.input-error').forEach(input => {
                input.classList.remove('input-error');
            });
        }


        // ฟังก์ชั่นที่ทำงานเมื่อฟอร์มถูกส่ง (สำหรับการสร้าง admin)
        function submitAdminForm() {
            var form = document.getElementById('adminSignupForm');
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            var isValid = true;

            // ล้างเส้นขอบผิดพลาดจากฟอร์ม
            clearErrorBorders();

            // ตรวจสอบรหัสผ่านและยืนยันรหัสผ่าน
            if (password !== confirmPassword) {
                // ใส่ไอคอน SVG พร้อมข้อความแสดงเตือน
                showAlert('<svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#e3e3e3"><path d="M600-240v-120H488q-32 54-87 87t-121 33q-100 0-170-70T40-480q0-100 70-170t170-70q66 0 121 33t87 87h272v80H434q-8-39-48-79.5T280-640q-66 0-113 47t-47 113q0 66 47 113t113 47q66 0 106-40.5t48-79.5h246v120h80v80H600ZM280-400q33 0 56.5-23.5T360-480q0-33-23.5-56.5T280-560q-33 0-56.5 23.5T200-480q0 33 23.5 56.5T280-400Zm0-80Zm600 240q-17 0-28.5-11.5T840-280q0-17 11.5-28.5T880-320q17 0 28.5 11.5T920-280q0 17-11.5 28.5T880-240Zm-40-160v-200h80v200h-80Z"/></svg> รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน', "error");
                addErrorBorder(document.getElementById("confirmPassword")); // เพิ่มเส้นขอบสีแดงให้ที่ input confirmPassword
                isValid = false;
            }

            // ถ้าฟอร์มไม่ถูกต้อง ให้ไม่ส่งข้อมูล
            if (!isValid) {
                return false;
            }

            var formData = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.setRequestHeader("Accept", "application/json");

            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log("Raw Server Response:", xhr.responseText); // ดีบักข้อมูลที่ส่งมาจากเซิร์ฟเวอร์
                    try {
                        var response = JSON.parse(xhr.responseText); // ✅ แปลง JSON string เป็น Object

                        if (response.status === "success") {
                            // แจ้งเตือนเมื่อสมัครสมาชิกสำเร็จ
                            showAlert('<svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#e3e3e3"><path d="m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Zm34-102 102-44 104 44 56-96 110-26-10-112 74-84-74-86 10-112-110-24-58-96-102 44-104-44-56 96-110 24 10 112-74 86 74 84-10 114 110 24 58 96Zm102-318Zm-42 142 226-226-56-58-170 170-86-84-56 56 142 142Z"/></svg> สมัครสมาชิกสำเร็จ', "success");
                            // รีเฟรชหน้าหลังจากแสดงข้อความแจ้งเตือนแล้ว
                            setTimeout(function() {
                                form.reset();
                                location.reload();
                            }, 1000); // 3 วินาทีหลังจากแสดง alert
                        } else if (response.status === "error") {
                            // ตรวจสอบกรณีเมื่อชื่อผู้ใช้งานมีอยู่แล้วในระบบ
                            if (response.message === 'ชื่อผู้ใช้นี้มีอยู่แล้วในระบบ') {
                                // ใส่ไอคอน SVG พร้อมข้อความแสดงเตือน
                                showAlert('<svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#e3e3e3"><path d="M800-520q-17 0-28.5-11.5T760-560q0-17 11.5-28.5T800-600q17 0 28.5 11.5T840-560q0 17-11.5 28.5T800-520Zm-40-120v-200h80v200h-80ZM360-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm80-80h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0-80Zm0 400Z"/></svg> ชื่อผู้ใช้นี้มีอยู่แล้วในระบบ', "error");
                            }
                        }
                    } catch (error) {
                        console.error("Error parsing JSON:", error);
                        showAlert("เกิดข้อผิดพลาดในการประมวลผลข้อมูล: " + error.message, "error");
                    }
                } else {
                    showAlert('เกิดข้อผิดพลาดในการส่งข้อมูล: ' + xhr.responseText, "error");
                }
            };

            xhr.send(formData);
            return false;
        }

        // ฟังก์ชันแสดงข้อความแจ้งเตือน (admin)
        function showAlert(message, type) {
            const alertElement = document.getElementById('alert');
            const alertMessage = document.getElementById('alert-message');

            // ตั้งค่าข้อความและประเภท (success, error, warning)
            alertMessage.innerHTML = message; // ใช้ innerHTML เพื่อแทรก HTML โดยตรง
            alertElement.className = 'alert ' + type; // ตั้งค่าประเภทของ alert เช่น 'success', 'error'

            // แสดง alert
            alertElement.style.display = 'block';
            setTimeout(() => {
                alertElement.style.opacity = 1; // ทำให้ alert ค่อยๆ ปรากฏ
            }, 10);

            // ซ่อน alert หลังจาก 3 วินาที
            setTimeout(() => {
                alertElement.style.opacity = 0; // ทำให้ alert ค่อยๆ หายไป
                setTimeout(() => {
                    alertElement.style.display = 'none';
                }, 500); // รอให้การ fade out เสร็จสิ้น
            }, 3000);
        }


        // ฟังก์ชันเพิ่มเส้นขอบสีแดงให้กับ input ที่มีข้อผิดพลาด
        function addErrorBorder(inputElement) {
            inputElement.classList.add('error-input');
        }

        // ฟังก์ชันลบเส้นขอบสีแดงออกจาก input
        function clearErrorBorders() {
            var inputs = document.querySelectorAll('.error-input');
            inputs.forEach(function(input) {
                input.classList.remove('error-input');
            });
        }

        // ค้นหาเมนูทั้งหมดที่มีคลาส .tab
        const tabs = document.querySelectorAll('.tab');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // ลบคลาส 'selected' ออกจากทุกๆ tab
                tabs.forEach(t => t.classList.remove('selected'));

                // เพิ่มคลาส 'selected' ให้กับ tab ที่ถูกคลิก
                tab.classList.add('selected');
            });
        });

        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const body = document.body;
            const openIcon = document.querySelector('.open-icon');
            const closeIcon = document.querySelector('.close-icon');

            // สลับคลาสเพื่อให้ Sidebar ยุบ/ขยาย
            sidebar.classList.toggle('closed');
            body.classList.toggle('sidebar-collapsed');

            // เปลี่ยนไอคอนให้แสดง/ซ่อนตามสถานะของ Sidebar
            if (sidebar.classList.contains('closed')) {
                openIcon.style.display = "none";
                closeIcon.style.display = "inline-block";
            } else {
                openIcon.style.display = "inline-block";
                closeIcon.style.display = "none";
            }
        }

        document.querySelectorAll('.category-btn').forEach(button => {
            button.addEventListener('click', function() {
                // ลบคลาส selected ออกจากทุกปุ่ม
                document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('selected'));

                // เพิ่มคลาส selected ให้ปุ่มที่ถูกกด
                this.classList.add('selected');

                // อัปเดตค่าใน input hidden
                document.getElementById('productCategory').value = this.getAttribute('data-category');
            });
        });

        document.querySelector('.collapsible-toggle').addEventListener('click', function() {
            const menu = document.getElementById('menu');
            menu.classList.toggle('active'); // เพิ่มหรือเอาคลาส active ออก
            const isExpanded = menu.classList.contains('active');
            this.setAttribute('aria-expanded', isExpanded); // อัพเดตค่า aria-expanded
        });

        // อัปเดตจำนวนสินค้าในตะกร้า
        function updateOrderCount(count) {
            document.querySelector(".badge").textContent = count;
        }

        // JavaScript (การคำนวณจำนวนที่เลือก):
        // ตัวแปรที่เก็บ element ของ checkbox
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const selectedCountText = document.getElementById('selected-count');

        // ฟังก์ชั่นในการอัพเดทจำนวนที่เลือก
        function updateSelectedCount(category, count) {
            const countElement = document.getElementById(`${category}-selected-count`);
            if (countElement) {
                countElement.textContent = count;
            }
        }

        // เพิ่ม event listener ให้ทุก checkbox
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });

        // เรียกใช้ฟังก์ชั่นครั้งแรกเพื่อให้ข้อมูลถูกต้องตอนโหลด
        updateSelectedCount();


        document.addEventListener("DOMContentLoaded", () => {
            // จับคู่ input กับ preview แล้วอัปเดตค่าทันทีเมื่อพิมพ์
            [
                ["productName", "previewNameProduct"],
                ["barcode", "previewBarcodeProduct"],
                ["productPrice", "previewPriceProduct"],
                ["productCost", "previewCostProduct"],
                ["productStock", "previewStockProduct"],
                ["productReorderLevel", "previewReorderLevelProduct"]
            ].forEach(([inputId, previewId]) => {
                const input = document.getElementById(inputId);
                const preview = document.getElementById(previewId);
                if (input && preview) {
                    input.addEventListener("input", () => {
                        preview.textContent = input.value;
                    });
                }
            });

            // โหลดภาพตัวอย่างจาก URL ที่กรอก
            const imageInput = document.getElementById("productImage");
            const imagePreview = document.getElementById("previewImageProduct");

            if (imageInput && imagePreview) {
                imageInput.addEventListener("input", () => {
                    const url = imageInput.value.trim();

                    // เช็คว่าเป็น URL ที่น่าจะใช้ได้ไหม
                    if (url && (url.startsWith("http") || url.endsWith(".jpg") || url.endsWith(".png") || url.endsWith(".jpeg"))) {
                        // เพิ่ม timestamp เพื่อป้องกัน cache
                        imagePreview.src = `${url}?t=${new Date().getTime()}`;
                    } else {
                        imagePreview.src = "/sci-shop-admin/img/product_food1.png";
                    }
                });
            }
        });
    </script>
</body>

</html>