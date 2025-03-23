<?php
session_start();

// ตรวจสอบสิทธิ์การเข้าถึง
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: /sci-/index.php");
    exit();
}

require_once __DIR__ . '/../../db.php'; // ตรวจสอบเส้นทางให้ถูกต้อง
$username = $_SESSION['user'];

// ฟังก์ชันตรวจสอบการเชื่อมต่อฐานข้อมูล
function checkConnection($conn)
{
    if (!$conn) {
        die("ไม่สามารถเชื่อมต่อฐานข้อมูล Oracle: " . oci_error());
    }
}

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
checkConnection($conn);

// ฟังก์ชันดึงข้อมูลสินค้า
function fetchProducts($conn, $table)
{
    $sql = "SELECT * FROM " . $table;
    $result = $conn->query($sql);

    if (!$result) {
        echo "Error executing query on table: " . $table;
        return [];
    }

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

// ดึงข้อมูลจากหลายๆ ตาราง
$dried_food = fetchProducts($conn, 'dried_food');
$soft_drink = fetchProducts($conn, 'soft_drink');
$fresh_food = fetchProducts($conn, 'fresh_food');

// ฟังก์ชันดึงข้อมูลพนักงาน
function fetchUsers($conn)
{
    $sql = "SELECT ID_NUMBER, USERNAME, PASSWORD, FIRST_NAME, LAST_NAME FROM users";
    $result = $conn->query($sql);

    if (!$result) {
        echo "Error executing query on users table.";
        return [];
    }

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    return $users;
}

// ดึงข้อมูลผู้ใช้ทั้งหมด
$users = fetchUsers($conn);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แดชบอร์ด Superadmin</title>
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
            background-color: #000000;
            margin-left: 220px;
            /* ค่าเริ่มต้นเมื่อ Sidebar เปิด */
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: margin-left 0.5s ease;
            /* ทำให้ยุบแบบ Smooth */
        }

        body.sidebar-collapsed {
            margin-left: 0;
            /* ขยายเต็มเมื่อ Sidebar ปิด */
        }


        /* เนื้อหาภายใน .container จะอยู่ทับพื้นหลัง */
        .container {
            position: relative;
            z-index: 1;
            /* เนื้อหาของคุณที่ต้องการแสดงทับบนพื้นหลัง */
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #000000;
            color: white;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .hamburger svg {
            transition: transform 0.3s ease-out, fill 0.3s ease-out;
            cursor: pointer;
        }

        .hamburger:hover svg {
            transform: scale(1.1);
            fill: #FF7043;
        }

        .logo-name {
            display: flex;
            align-items: center;
        }

        .logo {
            width: 40px;
            height: auto;
            margin-right: 10px;
            /* ถ้าต้องการระยะห่างเล็กน้อยจาก logo กับ site-name */
        }

        .site-name {
            font-size: 24px;
            font-weight: bold;
        }

        .user-settings {
            display: flex;
            align-items: center;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .mode-switch {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 68px;
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
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .sidebar .content {
            display: block;
        }

        /* สไตล์สำหรับเมื่อ Sidebar ยุบ */
        .sidebar.closed {
            transform: translateX(-100%);
        }

        /* สไตล์สำหรับเมื่อ sidebar ถูกยุบ */
        .sidebar.closed .content {
            display: none;
        }

        .main-tabs {
            margin-bottom: 5px;
        }

        .main-tabs h3 {
            font-size: 12px;
            font-weight: bold;
            color: #f39c12;
            /* หรือสีที่เหมาะสม */
        }

        /* กำหนดลักษณะของเส้น hr */
        .tab-divider {
            border: none;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
            /* สีของเส้น */
            margin: 1px;
            /* ระยะห่างจากหัวข้อ */
        }

        .main-tabs-upload {
            margin-bottom: 5px;
        }

        .main-tabs-upload h3 {
            margin-top: 5px;
            font-size: 12px;
            font-weight: bold;
            color: #f39c12;
            /* หรือสีที่เหมาะสม */
        }

        .main-tabs-products {
            margin-bottom: 5px;
        }

        .main-tabs-products h3 {
            margin-top: 5px;
            font-size: 12px;
            font-weight: bold;
            color: #f39c12;
            /* หรือสีที่เหมาะสม */
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
            /* สีเทาอ่อนที่จางเมื่อ hover */
        }

        /* กำหนดสีพื้นหลังสำหรับสถานะที่ถูกเลือก (active) */
        .tab:active,
        .tab.selected {
            background-color: #ffffff;
            /* สีเทาอ่อนที่เข้มขึ้นเมื่อถูกเลือก */
            color: black;
            /* เปลี่ยนสีตัวหนังสือเป็นสีดำ */
            position: relative;
            /* เพิ่มตำแหน่ง relative เพื่อให้สามารถจัดการกับ pseudo-element */
        }

        /* เปลี่ยนสีไอคอนเป็นสีส้ม */
        .tab:active .material-icons,
        .tab.selected .material-icons {
            color: #FF7043;
            /* สีส้ม */
        }

        .tab.account:active .material-icons,
        .tab.account.selected .material-icons {
            color: #2196F3;
            /* สีฟ้า */
        }

        /* เพิ่มจุดเขียวๆ กลมๆ ที่ขวาสุดของ tab */
        .tab:active::after,
        .tab.selected::after {
            content: "";
            /* ใช้เพื่อสร้าง pseudo-element */
            position: absolute;
            /* กำหนดตำแหน่งเป็น absolute */
            top: 50%;
            /* แนวตั้งตรงกลาง */
            right: 10px;
            /* กำหนดระยะห่างจากขวา */
            width: 10px;
            /* ขนาดของจุด */
            height: 10px;
            /* ขนาดของจุด */
            background-color: #4CAF50;
            /* สีเขียว */
            border-radius: 50%;
            /* ทำให้เป็นวงกลม */
            transform: translateY(-50%);
            /* ปรับตำแหน่งให้จุดอยู่ตรงกลางแนวตั้ง */
        }

        /* ปุ่มออกจากระบบ */
        .logout {
            padding: 10px 15px;
            background: #ff4b4b;
            text-align: center;
            font-weight: 600;
            color: white;
            border-radius: 10px;
            border: none;
            /* เอาเส้นขอบออก */
            transition: 0.3s ease;
            margin-top: 5px;
            /* ทำให้ปุ่ม logout ไปอยู่ด้านล่าง */
            text-decoration: none;
            /* เอาเส้นใต้ของลิงก์ออก */
        }

        .logout:hover {
            background: #ff0000;
        }

        .account {
            padding: 10px 15px;
            background: #3498db;
            /* เปลี่ยนเป็นสีฟ้าที่สวยงาม */
            text-align: center;
            font-weight: 600;
            color: white;
            border-radius: 10px;
            border: none;
            /* เอาเส้นขอบออก */
            transition: 0.3s ease;
            margin-top: 5px;
            text-decoration: none;
            /* เอาเส้นใต้ของลิงก์ออก */
        }

        .account:hover {
            background: #2980b9;
            /* สีเมื่อ hover เป็นฟ้าที่เข้มขึ้น */
        }

        .employee {
            padding: 10px 15px;
            background: #f39c12;
            /* เปลี่ยนเป็นสีส้มที่สวยงาม */
            text-align: center;
            font-weight: 600;
            color: white;
            border-radius: 10px;
            border: none;
            /* เอาเส้นขอบออก */
            transition: 0.3s ease;
            margin-top: auto;
            text-decoration: none;
            /* เอาเส้นใต้ของลิงก์ออก */
        }

        .employee:hover {
            background: #d35400;
            /* สีเมื่อ hover เป็นส้มที่เข้มขึ้น */
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

        /* ตั้งค่าสไตล์ของตาราง */
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 20px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.8);
            text-align: center;
            margin: 15px 0px 15px 0px;
        }

        /* เฮดเดอร์ของตาราง */
        th {
            background: linear-gradient(135deg, #2c2c54, #6d44a1);
            color: white;
            padding: 12px;
            font-size: 16px;
            padding: 10px;
            max-width: 200px;
            /* กำหนดความยาวสูงสุดที่เซลล์จะสามารถแสดงได้ */
            text-overflow: ellipsis;
            /* ทำให้ข้อความที่ยาวเกินไปแสดงเป็น "..." */
            overflow: hidden;
            /* ซ่อนข้อความที่เกินขอบเขต */
            white-space: nowrap;
            /* ห้ามให้ข้อความไปต่อในบรรทัดใหม่ */
        }

        /* แถวข้อมูล */
        td {
            padding: 12px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            color: black;
            padding: 10px;
            max-width: 200px;
            /* กำหนดความยาวสูงสุดที่เซลล์จะสามารถแสดงได้ */
            text-overflow: ellipsis;
            /* ทำให้ข้อความที่ยาวเกินไปแสดงเป็น "..." */
            overflow: hidden;
            /* ซ่อนข้อความที่เกินขอบเขต */
            white-space: nowrap;
            /* ห้ามให้ข้อความไปต่อในบรรทัดใหม่ */
        }

        /* ทำให้รูปภาพโค้งมน */
        td img {
            border-radius: 10px;
        }

        .btn-edit {
            font-size: 18px;
            padding: 6px 12px;
            background-color: #03c9a0;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }

        .btn-delete {
            font-size: 18px;
            padding: 6px 12px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-edit:hover {
            background-color: #45a049;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        #uplord_prodect {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
            overflow: hidden;
            /* ป้องกันการล้นออกจากฟอร์ม */
            box-sizing: border-box;
            /* ทำให้การตั้งขนาดของฟอร์มไม่ล้น */
        }


        .form-upload {
            margin-top: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group-wrapper {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 15px;
            /* กำหนดระยะห่างระหว่างฟิลด์ */
        }

        .form-group-url {
            width: 48%;
            /* กำหนดขนาดฟิลด์ให้เหมาะสม */
        }

        .form-group-url input {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            /* ทำให้ input ใช้ความกว้างเต็มของฟิลด์ */
        }


        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        /* ทำให้ชื่อและนามสกุลอยู่ในแถวเดียวกัน */
        .name-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            /* ระยะห่างระหว่างช่องกรอกชื่อและนามสกุล */
        }

        /* ทำให้ช่องกรอกชื่อและนามสกุลมีขนาดเท่ากัน */
        .half-width {
            width: 48%;
            /* กำหนดให้ครึ่งหนึ่งของพื้นที่ */
        }

        .full-width {
            width: 100%;
            /* ครอบคลุมพื้นที่ทั้งหมด */
        }

        .btn-upload {
            background-color: #6c5ce7;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-upload:hover {
            background-color: #5e4bb6;
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
            /* เปลี่ยนจาก center เป็น flex-start */
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

        /* Submit button */
        .btn-edit-prodect {
            padding: 20px;
            background-color: #03c9a0;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-top: 215px;
            width: 100%;
            /* ขยายให้เต็มความกว้างของกรอบ */
            transition: background-color 0.3s ease;
            box-sizing: border-box;
            /* ทำให้ padding ไม่เพิ่มขนาด */
        }

        .btn-edit-prodect:hover {
            background-color: #45a049;
        }


        .btn-close {
            position: absolute;
            top: 10px;
            /* ชิดขอบบน */
            right: 20px;
            /* ชิดขอบขวา */
            background: none;
            border: none;
            font-size: 34px;
            color: #f44336;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-close:hover {
            color: #d32f2f;
            transform: scale(1.2);
        }

        .popup-content button:focus {
            outline: none;
        }

        /* Close button outside of popup */
        .btn-close-popup {
            background-color: transparent;
            color: #f44336;
            border: none;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            display: block;
            margin: 20px auto;
        }

        .btn-close-popup:hover {
            text-decoration: underline;
        }

        /* Background overlay */
        .edit-popup .popup-content {
            transition: opacity 0.3s ease;
        }

        /* Make the popup visible */
        .edit-popup.show {
            display: flex;
            /* Show popup */
        }

        .description {
            font-size: 14px;
            /* ย่อขนาดฟอนต์ลง */
            color: #666;
            margin-top: 8px;
        }

        .summary-grid {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .summary-card {
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            flex: 1;
            margin: 10px;
            color: #000000;
            font-weight: bold;
        }

        .summary-card.daily {
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
        }

        .summary-card.monthly {
            background: linear-gradient(135deg, #a18cd1, #fbc2eb);
        }

        .summary-card.yearly {
            background: linear-gradient(135deg, #ff758c, #ff7eb3);
        }

        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .chart-container {
            background: #fff;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }

        .chart-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 14px rgba(0, 0, 0, 0.2);
        }

        .chart-container.daily-chart {
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
        }

        .chart-container.monthly-chart {
            background: linear-gradient(135deg, #a18cd1, #fbc2eb);
        }

        .chart-container.yearly-chart {
            background: linear-gradient(135deg, #ff758c, #ff7eb3);
        }

        canvas {
            width: 100% !important;
            height: 320px !important;
        }

        h4 {
            color: #000000;
            font-size: 18px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        #order {
            margin-top: 68px;
            /* ขยับลงมาจาก Navbar (ปรับค่าตามความสูงของ Navbar) */
            padding: 20px;
        }

        #graph {
            margin-top: 68px;
            /* ขยับลงมาจาก Navbar (ปรับค่าตามความสูงของ Navbar) */
            padding: 20px;
        }

        #upload_prodect {
            margin-top: 68px;
            /* ขยับลงมาจาก Navbar (ปรับค่าตามความสูงของ Navbar) */
            padding: 20px;
        }

        #admin_signup {
            margin-top: 68px;
            /* ขยับลงมาจาก Navbar (ปรับค่าตามความสูงของ Navbar) */
            padding: 20px;
        }

        #food_bank {
            margin-top: 68px;
            /* ขยับลงมาจาก Navbar (ปรับค่าตามความสูงของ Navbar) */
            padding: 20px;
        }

        #local_drink {
            margin-top: 68px;
            /* ขยับลงมาจาก Navbar (ปรับค่าตามความสูงของ Navbar) */
            padding: 20px;
        }

        #fastfood {
            margin-top: 68px;
            /* ขยับลงมาจาก Navbar (ปรับค่าตามความสูงของ Navbar) */
            padding: 20px;
        }

        #employee {
            margin-top: 68px;
            /* ขยับลงมาจาก Navbar (ปรับค่าตามความสูงของ Navbar) */
            padding: 20px;
        }

        #account {
            margin-top: 68px;
            /* ขยับลงมาจาก Navbar (ปรับค่าตามความสูงของ Navbar) */
            padding: 20px;
        }

        .category-buttons {
            display: flex;
            gap: 10px;
        }

        .category-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
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
            background-color: #FF7043;
            /* สีเมื่อเลือก */
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
            /* เพิ่ม margin ที่ต้องการ */
        }

        .collapsible-toggle:hover {
            background-color: #555;
            /* เปลี่ยนสีเมื่อ hover */
        }

        .collapsible-toggle svg {
            transition: transform 0.3s ease;
        }

        /* ทำให้ลูกศรหมุนเมื่อเมนูเปิด */
        .collapsible-toggle[aria-expanded="true"] svg {
            transform: rotate(180deg);
        }

        /* ปรับสไตล์ของเมนู */
        .menu {
            display: block;
            /* ปรับให้เมนูแสดง */
            overflow: hidden;
            /* ซ่อนเนื้อหาที่เกิน */
            max-height: 0;
            /* เริ่มต้นให้ความสูงเป็น 0 */
            padding: 0 0 0 15px;
            /* เพิ่ม padding ด้านข้าง */
            border-radius: 5px;
            transition: max-height 0.5s ease-out, padding 0.5s ease;
            /* เลื่อนเมนูนุ่มนวล */
        }

        /* แสดงเมนูเมื่อคลาส active ถูกเพิ่ม */
        .menu.active {
            max-height: 200px;
            /* ความสูงที่สามารถแสดงได้สูงสุด */
            padding: 0 0 0 15px;
            /* เพิ่ม padding เมื่อแสดง */
        }
    </style>
</head>

<body>
    <div class="navbar">
        <!-- แฮมเบอร์เกอร์ -->
        <div class="hamburger" onclick="toggleSidebar()">
            <span class="open-icon">
                <svg xmlns="http://www.w3.org/2000/svg" height="40px" width="40px" viewBox="0 -960 960 960" fill="#e3e3e3">
                    <path d="M120-240v-80h520v80H120Zm664-40L584-480l200-200 56 56-144 144 144 144-56 56ZM120-440v-80h400v80H120Zm0-200v-80h520v80H120Z" />
                </svg>
            </span>
            <span class="close-icon" style="display: none;">
                <svg xmlns="http://www.w3.org/2000/svg" height="40px" width="40px" viewBox="0 -960 960 960" fill="#e3e3e3">
                    <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
                </svg>
            </span>
        </div>

        <!-- โลโก้และชื่อ -->
        <div class="logo-name">
            <img src="\sci-shop-admin\img.content\pachara.jpg" alt="Logo" class="logo">
            <span class="site-name">SCI ADMIN</span>
        </div>

        <!-- Avatar และ Mode switch -->
        <div class="user-settings">
            <img src="\sci-shop-admin\img.content\pachara.jpg" alt="Avatar" class="avatar">
        </div>
    </div>


    <div class="sidebar">
        <!-- รายการหลัก -->
        <div class="main-tabs">
            <h3>รายการหลัก</h3>
            <div class="tab" id="orderTab" onclick="showTab('order')">
                <span class="material-icons">shopping_cart</span> รายการขาย
            </div>
            <div class="tab" onclick="showTab('graph')">
                <span class="material-icons">show_chart</span> สถิติการขาย
            </div>
        </div>
        <hr class="tab-divider">

        <!-- รายการอัพโหลด -->
        <div class="main-tabs-upload">
            <h3>รายการอัพโหลด</h3>
            <div class="tab" onclick="showTab('upload_prodect')">
                <span class="material-icons">add_shopping_cart</span> อัพโหลดสินค้า
            </div>
            <div class="tab" onclick="showTab('admin_signup')">
                <span class="material-icons">person_add</span> สมัครพนักงาน
            </div>
            <button type="button" is="toggle-button" class="collapsible-toggle text--strong" aria-controls="menu" aria-expanded="false">
                <span class="material-icons">shopping_cart_checkout</span> ตรวจสอบสินค้า
                <svg focusable="false" width="12" height="8" class="icon icon--chevron icon--inline" viewBox="0 0 12 8">
                    <path fill="none" d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="2"></path>
                </svg>
            </button>

            <div id="menu" class="menu">
                <div class="tab">
                    <span class="material-icons">food_bank</span> อาหารแห้ง
                </div>
                <div class="tab">
                    <span class="material-icons">local_drink</span> เครื่องดื่ม
                </div>
                <div class="tab">
                    <span class="material-icons">fastfood</span> อาหารสด
                </div>
            </div>
        </div>
        <hr class="tab-divider">

        <!-- รายการสินค้า -->
        <div class="main-tabs-products">
            <h3>รายการสินค้า</h3>
            <div class="tab" onclick="showTab('food_bank')">
                <span class="material-icons">food_bank</span> อาหารแห้ง
            </div>
            <div class="tab" onclick="showTab('local_drink')">
                <span class="material-icons">local_drink</span> เครื่องดื่ม
            </div>
            <div class="tab" onclick="showTab('fastfood')">
                <span class="material-icons">fastfood</span> อาหารสด
            </div>
        </div>


        <div class="tab employee" onclick="showTab('employee')">
            <span class="material-icons">group</span> จัดการพนักงาน
        </div>
        <div class="tab account" onclick="showTab('account')">
            <span class="material-icons">account_circle</span> โปรไฟล์แอดมิน
        </div>
        <a class="tab logout" href="/sci-shop-admin/logout.php">
            <span class="material-icons">exit_to_app</span> ออกจากระบบ
        </a>
    </div>

    <div id="order" class="content">
        <p>แสดงข้อมูลสถิติต่าง ๆ</p>
    </div>

    <div id="graph" class="content">
        <!-- สรุปยอดขายเป็นตัวเลข -->
        <div class="summary-grid">
            <div class="summary-card daily">
                <h4>ยอดขายรายวัน</h4>
                <p>฿12,500</p>
            </div>
            <div class="summary-card monthly">
                <h4>ยอดขายรายเดือน</h4>
                <p>฿350,000</p>
            </div>
            <div class="summary-card yearly">
                <h4>ยอดขายรายปี</h4>
                <p>฿4,200,000</p>
            </div>
        </div>

        <!-- กราฟยอดขาย -->
        <div class="chart-grid">
            <div class="chart-container daily-chart">
                <h4>ยอดขายรายวัน</h4>
                <canvas id="dailySalesChart"></canvas>
            </div>
            <div class="chart-container monthly-chart">
                <h4>ยอดขายรายเดือน</h4>
                <canvas id="monthlySalesChart"></canvas>
            </div>
            <div class="chart-container yearly-chart">
                <h4>ยอดขายรายปี</h4>
                <canvas id="yearlySalesChart"></canvas>
            </div>
        </div>
    </div>

    <div id="upload_prodect" class="content">
        <h3 class="h-text">🆔 อัพโหลดสินค้าใหม่</h3>
        <p>อัพโหลดสินค้าใหม่ได้ที่จุดนี้เลยครับ</p>
        <!-- ฟอร์มอัปโหลดสินค้า -->
        <form class="form-upload" id="uploadForm" action="../product/upload_product/upload_product.php" method="POST" enctype="multipart/form-data" onsubmit="return handleFormSubmit()">
            <div class="form-group category-buttons">
                <button class="category-btn" data-category="dried_food">
                    <span class="material-icons">food_bank</span>
                    อาหารแห้ง
                </button>
                <button class="category-btn" data-category="soft_drink">
                    <span class="material-icons">local_drink</span>
                    เครื่องดื่ม
                </button>
                <button class="category-btn" data-category="fresh_food">
                    <span class="material-icons">fastfood</span>
                    อาหารสด
                </button>
            </div>
            <input type="hidden" id="productCategory" name="productCategory">


            <div class="form-group">
                <label for="productName">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                        <path d="M160-80q-33 0-56.5-23.5T80-160v-440q0-33 23.5-56.5T160-680h200v-120q0-33 23.5-56.5T440-880h80q33 0 56.5 23.5T600-800v120h200q33 0 56.5 23.5T880-600v440q0 33-23.5 56.5T800-80H160Zm0-80h640v-440H600q0 33-23.5 56.5T520-520h-80q-33 0-56.5-23.5T360-600H160v440Zm80-80h240v-18q0-17-9.5-31.5T444-312q-20-9-40.5-13.5T360-330q-23 0-43.5 4.5T276-312q-17 8-26.5 22.5T240-258v18Zm320-60h160v-60H560v60Zm-200-60q25 0 42.5-17.5T420-420q0-25-17.5-42.5T360-480q-25 0-42.5 17.5T300-420q0 25 17.5 42.5T360-360Zm200-60h160v-60H560v60ZM440-600h80v-200h-80v200Zm40 220Z" />
                    </svg> ชื่อสินค้า
                </label>
                <input type="text" id="productName" name="productName" placeholder="กรอกชื่อสินค้า" required>
            </div>


            <div class="form-group">
                <label for="barcode">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                        <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                    </svg>
                    Barcode
                </label>
                <input type="text" id="barcode" name="barcode" placeholder="กรอกบาร์โค้ด" required>
            </div>

            <div class="form-group">
                <label for="productPrice">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                        <path d="M560-440q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM280-320q-33 0-56.5-23.5T200-400v-320q0-33 23.5-56.5T280-800h560q33 0 56.5 23.5T920-720v320q0 33-23.5 56.5T840-320H280Zm80-80h400q0-33 23.5-56.5T840-480v-160q-33 0-56.5-23.5T760-720H360q0 33-23.5 56.5T280-640v160q33 0 56.5 23.5T360-400Zm440 240H120q-33 0-56.5-23.5T40-240v-440h80v440h680v80ZM280-400v-320 320Z" />
                    </svg> ราคาสินค้า
                </label>
                <input type="number" id="productPrice" name="productPrice" placeholder="กรอกราคาสินค้า" required>
            </div>

            <div class="form-group">
                <label for="productCost">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                        <path d="M600-320h120q17 0 28.5-11.5T760-360v-240q0-17-11.5-28.5T720-640H600q-17 0-28.5 11.5T560-600v240q0 17 11.5 28.5T600-320Zm40-80v-160h40v160h-40Zm-280 80h120q17 0 28.5-11.5T520-360v-240q0-17-11.5-28.5T480-640H360q-17 0-28.5 11.5T320-600v240q0 17 11.5 28.5T360-320Zm40-80v-160h40v160h-40Zm-200 80h80v-320h-80v320ZM80-160v-640h800v640H80Zm80-560v480-480Zm0 480h640v-480H160v480Z" />
                    </svg> ต้นทุน
                </label>
                <input type="number" id="productCost" name="productCost" placeholder="กรอกต้นทุนสินค้า" required>
            </div>

            <div class="form-group">
                <label for="productStock">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                        <path d="M200-80q-33 0-56.5-23.5T120-160v-451q-18-11-29-28.5T80-680v-120q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v120q0 23-11 40.5T840-611v451q0 33-23.5 56.5T760-80H200Zm0-520v440h560v-440H200Zm-40-80h640v-120H160v120Zm200 280h240v-80H360v80Zm120 20Z" />
                    </svg> สต็อก
                </label>
                <input type="number" id="productStock" name="productStock" placeholder="กรอกจำนวนสต็อกสินค้า" required>
            </div>

            <div class="form-group">
                <label for="productReorderLevel">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                        <path d="M600-320h120q17 0 28.5-11.5T760-360v-240q0-17-11.5-28.5T720-640H600q-17 0-28.5 11.5T560-600v240q0 17 11.5 28.5T600-320Zm40-80v-160h40v160h-40Zm-280 80h120q17 0 28.5-11.5T520-360v-240q0-17-11.5-28.5T480-640H360q-17 0-28.5 11.5T320-600v240q0 17 11.5 28.5T360-320Zm40-80v-160h40v160h-40Zm-200 80h80v-320h-80v320ZM80-160v-640h800v640H80Zm80-560v480-480Zm0 480h640v-480H160v480Z" />
                    </svg> ระดับการสั่งซื้อใหม่
                </label>
                <input type="number" id="productReorderLevel" name="productReorderLevel" placeholder="กรอกระดับการสั่งซื้อใหม่" required>
            </div>

            <div class="form-group-wrapper">
                <div class="form-group-url">
                    <label for="productImage">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                            <path d="M480-480ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h320v80H200v560h560v-320h80v320q0 33-23.5 56.5T760-120H200Zm40-160h480L570-480 450-320l-90-120-120 160Zm440-320v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80Z" />
                        </svg> ใส่ URL ของรูปภาพสินค้า
                    </label>
                    <input type="text" id="productImage" name="productImage" placeholder="กรอก URL ของรูปภาพ" required>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-upload">อัปโหลดสินค้า</button>
            </div>
    </div>


    <div id="admin_signup" class="content">
        <h3 class="h-text">🆔 การสมัครสมัครพนักงาน</h3>
        <!-- ข้อความแสดงผลจาก PHP -->
        <?php if (!empty($error)): ?>
            <div style="color: red;"><?php echo $error; ?></div>
        <?php elseif (!empty($success)): ?>
            <div style="color: green;"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- ฟอร์มสมัครสมาชิก admin พร้อมแอททริบิวต์ autocomplete -->
        <form class="form-upload" id="adminSignupForm" action="../admin/admin_signup/admin_signup.php" method="POST" onsubmit="return submitAdminForm()" autocomplete="on">
            <div class="form-group">
                <label for="username">ชื่อผู้ใช้งาน</label>
                <input type="text" id="username" name="username" placeholder="กรอกชื่อผู้ใช้งาน" required autocomplete="username">
            </div>

            <!-- แถวสำหรับชื่อและนามสกุล -->
            <div class="form-group full-width">
                <div class="name-group">
                    <div class="half-width">
                        <label for="firstName">ชื่อ</label>
                        <input type="text" id="firstName" name="firstName" placeholder="กรอกชื่อ" required autocomplete="given-name">
                    </div>
                    <div class="half-width">
                        <label for="lastName">นามสกุล</label>
                        <input type="text" id="lastName" name="lastName" placeholder="กรอกนามสกุล" required autocomplete="family-name">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="password">รหัสผ่าน</label>
                <input type="password" id="password" name="password" placeholder="กรอกรหัสผ่าน" required autocomplete="new-password">
            </div>

            <div class="form-group">
                <label for="confirmPassword">ยืนยันรหัสผ่าน</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="กรอกยืนยันรหัสผ่าน" required autocomplete="new-password">
            </div>

            <div class="form-group">
                <button type="submit" class="btn-upload">สมัครสมาชิก</button>
            </div>
        </form>
    </div>


    <div id="food_bank" class="content">
        <h3 class="h-text">⌨️ คีย์บอร์ด</h3>
        <table border="1" cellspacing="0" cellpadding="10">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Size</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($keyboards as $item): ?>
                    <tr>
                        <td><img src="<?php echo $item['IMAGE_URL']; ?>" alt="<?php echo $item['NAME']; ?>" width="100"></td>
                        <td><?php echo $item['NAME']; ?></td>
                        <td>฿<?php echo $item['PRICE']; ?></td>
                        <td><?php echo $item['KEYBOARD_SIZE']; ?></td>
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
                                        <input type="hidden" name="productCategory" value="keyboards"> <!-- หรือ หมวดหมู่อื่นๆ ตามความเหมาะสม -->
                                        <input type="text" name="keyboardSize" value="<?php echo $item['KEYBOARD_SIZE']; ?>" required>
                                        <button type="submit" class="btn-edit-prodect">บันทึก</button>
                                    </form>
                                </div>
                            </div>

                            <form id="deleteForm-<?php echo $item['ID']; ?>" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $item['ID']; ?>">
                                <input type="hidden" name="category" value="keyboards"> <!-- หมวดหมู่สินค้านี้ -->
                                <button type="button" class="btn-delete" onclick="deleteProduct(<?php echo $item['ID']; ?>, 'keyboards')">ลบสินค้า</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="local_drink" class="content">
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

    <div id="fastfood" class="content">
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

    <script>
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

        function showEditPasswordForm(id) {
            // ซ่อนปุ่ม "แก้ไขรหัสผ่าน"
            document.querySelector(`form[action="edit_password.php"] button[onclick="showEditPasswordForm(${id})"]`).style.display = 'none';

            // แสดงฟอร์มแก้ไขรหัสผ่าน
            document.getElementById(`password-form-${id}`).style.display = 'block';
        }

        function hideEditPasswordForm(id) {
            // แสดงปุ่ม "แก้ไขรหัสผ่าน" กลับมา
            document.querySelector(`form[action="edit_password.php"] button[onclick="showEditPasswordForm(${id})"]`).style.display = 'inline-block';

            // ซ่อนฟอร์มแก้ไขรหัสผ่าน
            document.getElementById(`password-form-${id}`).style.display = 'none';
        }

        function deleteProduct(id, category) {
            if (confirm("คุณต้องการลบสินค้านี้ใช่หรือไม่?")) {
                // ส่งคำขอลบสินค้าไปยัง server ด้วย AJAX
                var formData = new FormData();
                formData.append('id', id);
                formData.append('category', category);

                // ส่ง AJAX Request ไปยัง PHP
                fetch('../product/delete_product/delete_product.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json()) // รับผลลัพธ์จาก PHP
                    .then(data => {
                        if (data.success) {
                            // ถ้าลบสำเร็จ, รีเฟรชหน้า
                            displayMessage(data.message, 'success'); // แสดงข้อความลบสำเร็จ
                            location.reload(); // รีเฟรชหน้าเพื่อแสดงผลลัพธ์ใหม่
                        } else {
                            displayMessage('เกิดข้อผิดพลาดในการลบ: ' + data.message, 'error'); // แสดงข้อความข้อผิดพลาด
                        }
                    })
                    .catch(error => {
                        console.error('เกิดข้อผิดพลาด:', error);
                        displayMessage('เกิดข้อผิดพลาดในการลบ!', 'error');
                    });
            }
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

        // ฟังก์ชั่นที่ทำงานเมื่อฟอร์มถูกส่ง
        function handleFormSubmit() {
            var form = document.getElementById('uploadForm');

            // ตรวจสอบว่า password และ confirmPassword ตรงกัน
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;

            if (password !== confirmPassword) {
                alert("รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน");
                return false; // หยุดการส่งฟอร์ม
            }

            // ส่งข้อมูลฟอร์มผ่าน AJAX
            var formData = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert('อัปโหลดสินค้าสำเร็จ');
                    form.reset(); // รีเซ็ตฟอร์มหลังจากส่งข้อมูลสำเร็จ
                    location.reload(); // รีเฟรชหน้าเพื่อล้างข้อมูลที่ถูกกรอก
                } else {
                    alert('เกิดข้อผิดพลาด: ' + xhr.responseText);
                }
            };

            xhr.send(formData);
            return false; // ป้องกันการรีเฟรชหน้าแบบปกติของฟอร์ม
        }

        // ฟังก์ชั่นที่ทำงานเมื่อฟอร์มถูกส่ง
        function submitAdminForm() {
            var form = document.getElementById('adminSignupForm');

            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;

            if (password !== confirmPassword) {
                alert("รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน");
                return false;
            }

            var formData = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = xhr.responseText.trim();
                    if (response === "สมัครสมาชิกสำเร็จแล้ว!") {
                        alert(response);
                        form.reset();
                        location.reload();
                    } else {
                        alert('เกิดข้อผิดพลาด: ' + response);
                    }
                } else {
                    alert('เกิดข้อผิดพลาดในการส่งข้อมูล: ' + xhr.responseText);
                }
            };

            xhr.send(formData);
            return false;
        }


        // ฟังก์ชั่นเพื่อแสดงหรือซ่อนฟิลด์ตามหมวดหมู่สินค้า
        function toggleFields() {
            var category = document.getElementById('productCategory').value;

            // ซ่อนฟิลด์ทั้งหมดก่อน
            document.getElementById('keyboardSizeField').style.display = 'none';
            document.getElementById('switchTypeField').style.display = 'none';
            document.getElementById('keycapProfileField').style.display = 'none';
            document.getElementById('productTypeField').style.display = 'none';

            // แสดงฟิลด์ที่ตรงกับหมวดหมู่
            if (category === 'keyboards') {
                document.getElementById('keyboardSizeField').style.display = 'block';
            } else if (category === 'switches') {
                document.getElementById('switchTypeField').style.display = 'block';
            } else if (category === 'keycaps') {
                document.getElementById('keycapProfileField').style.display = 'block';
            } else if (category === 'accessories') {
                document.getElementById('productTypeField').style.display = 'block';
            }
        }

        // Function to open the edit popup
        function openEditPopup(id) {
            document.getElementById('editPopup-' + id).style.display = 'flex';
        }

        // Function to close the edit popup
        function closeEditPopup(id) {
            document.getElementById('editPopup-' + id).style.display = 'none';
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

        document.addEventListener("DOMContentLoaded", function() {
            function createChart(ctx, label, labels, data) {
                return new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: labels,
                        datasets: [{
                            label: label,
                            data: data,
                            backgroundColor: ["#ff6384", "#36a2eb", "#ffce56", "#4bc0c0", "#9966ff", "#ff9f40", "#c9cbcf"],
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            createChart(document.getElementById("dailySalesChart").getContext("2d"), "ยอดขายรายวัน", ["จันทร์", "อังคาร", "พุธ", "พฤหัส", "ศุกร์", "เสาร์", "อาทิตย์"], [18750, 21500, 23400, 19800, 26900, 31000, 28500]);
            createChart(document.getElementById("monthlySalesChart").getContext("2d"), "ยอดขายรายเดือน", ["ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค."], [480000, 520000, 540000, 495000, 580000, 600000, 625000]);
            createChart(document.getElementById("yearlySalesChart").getContext("2d"), "ยอดขายรายปี", ["2018", "2019", "2020", "2021", "2022", "2023", "2024"], [5200000, 5500000, 5700000, 5900000, 6200000, 6400000, 6750000]);
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

        document.getElementById("barcode").addEventListener("input", function(e) {
            let value = e.target.value.replace(/\s+/g, ""); // ลบช่องว่างทั้งหมดก่อน
            let formatted = "";

            // ตัวอย่าง: รองรับ EAN-13 (13 หลัก, เว้นวรรคที่ตำแหน่ง 3, 7, 11)
            if (/^\d{1,13}$/.test(value)) {
                formatted = value.replace(/(\d{1})(\d{6})?(\d{6})?/, function(_, g1, g2, g3, g4) {
                    return [g1, g2, g3, g4].filter(Boolean).join(" ");
                });
            }

            // อัปเดตค่ากลับไปที่ input
            e.target.value = formatted;
        });

        document.querySelector('.collapsible-toggle').addEventListener('click', function() {
            const menu = document.getElementById('menu');
            menu.classList.toggle('active'); // เพิ่มหรือเอาคลาส active ออก
            const isExpanded = menu.classList.contains('active');
            this.setAttribute('aria-expanded', isExpanded); // อัพเดตค่า aria-expanded
        });
    </script>
</body>

</html>