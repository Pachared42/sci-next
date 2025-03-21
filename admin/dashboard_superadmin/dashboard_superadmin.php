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
        /* รีเซ็ตค่าพื้นฐาน */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Noto Sans Thai", "Noto Sans", sans-serif;
        }

        /* ปรับเนื้อหาเว็บให้ไม่ซ้อนกับ Sidebar */
        body {
            background-color: #000000;
            margin-left: 220px;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            /* ให้ ::after อยู่ในตำแหน่งที่ถูกต้อง */
        }

        /* การตั้งค่าให้พื้นหลังไม่เลื่อน */
        body::after {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('../img.content/img.content.png');
            /* ใช้ภาพพื้นหลัง */
            background-size: cover;
            /* หรือ 100% 100% */
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(3px);
            z-index: -1;
            background-attachment: fixed;
            background-color: #000;
            /* เติมสีพื้นหลัง */
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

        /* ทำให้ hamburger และ logo-name อยู่ชิดกัน */
        .hamburger {
            font-size: 30px;
            cursor: pointer;
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
            margin-right: 20px;
        }

        .mode-switch {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .mode-toggle {
            font-size: 36px;
        }

        .moon-icon {
            display: none;
        }

        .sun-icon {
            display: block;
        }

        .toggled .sun-icon {
            display: none;
        }

        .toggled .moon-icon {
            display: block;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 68px;
            /* ปรับให้ sidebar ลงมาจาก top */
            left: 0;
            width: 220px;
            height: calc(100vh - 68px);
            /* ปรับความสูงของ sidebar ให้ไม่ทับกับ navbar */
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.3);
            background-color: #000000;
            border-right: 2px solid rgba(255, 255, 255, 0.2);
        }

        /* ปุ่มเมนู */
        .tab {
            padding: 10px 15px;
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
            border-radius: 10px;
            /* ขอบมน */
        }

        .tab i,
        .tab span.material-icons {
            margin-right: 8px;
            /* เพิ่มระยะห่างระหว่างไอคอนกับข้อความ */
        }

        /* เมื่อเมาส์ hover เปลี่ยนพื้นหลัง */
        .tab:hover {
            background-color: rgba(211, 211, 211, 0.5);
            /* สีเทาอ่อนที่จางกว่า */
        }

        /* กำหนดสีพื้นหลังสำหรับสถานะที่ถูกเลือก (active) */
        .tab:active,
        .tab.selected {
            background-color: rgba(162, 207, 254, 0.5);
            /* สีฟ้าอ่อนที่จางกว่า */
        }

        /* ปุ่มออกจากระบบ */
        .logout {
            padding: 10px 15px;
            background: #ff4b4b;
            text-align: center;
            font-weight: 600;
            color: white;
            border-radius: 5px;
            border: none;
            /* เอาเส้นขอบออก */
            transition: 0.3s ease;
            margin-top: auto;
            /* ทำให้ปุ่ม logout ไปอยู่ด้านล่าง */
            text-decoration: none;
            /* เอาเส้นใต้ของลิงก์ออก */
        }

        .logout:hover {
            background: #ff0000;
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

        .chart-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
            /* ลดระยะห่างจากส่วนบน */
        }

        .chart-container h4 {
            font-size: 18px;
            /* ย่อขนาดฟอนต์ลง */
            color: #444;
            margin-bottom: 8px;
            /* ลดระยะห่างจากกราฟ */
        }

        canvas {
            background-color: #fff;
            border-radius: 6px;
            /* ลดมุมให้เล็กลง */
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            max-width: 100%;
            /* ทำให้ canvas ยืดตามขนาดของ container */
            height: auto;
        }

        #graph {
            margin-top: 60px;
            /* ขยับลงมาจาก Navbar (ปรับค่าตามความสูงของ Navbar) */
            padding: 20px;
        }

        #upload_prodect {
            margin-top: 60px;
            /* ขยับลงมาจาก Navbar (ปรับค่าตามความสูงของ Navbar) */
            padding: 20px;
        }

        #admin_signup {
            margin-top: 60px;
            /* ขยับลงมาจาก Navbar (ปรับค่าตามความสูงของ Navbar) */
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <!-- แฮมเบอร์เกอร์ -->
        <div class="hamburger" onclick="toggleSidebar()">&#9776;</div>

        <!-- โลโก้และชื่อ -->
        <div class="logo-name">
            <img src="\sci-shop-admin\img.content\pachara.jpg" alt="Logo" class="logo">
            <span class="site-name">SCI ADMIN</span>
        </div>

        <!-- Avatar และ Mode switch -->
        <div class="user-settings">
            <img src="\sci-shop-admin\img.content\pachara.jpg" alt="Avatar" class="avatar">
            <div class="mode-switch" onclick="toggleMode()">
                <span class="mode-toggle">
                    <span class="sun-icon material-icons">wb_sunny</span>
                    <span class="moon-icon material-icons">nights_stay</span>
                </span>
            </div>
        </div>
    </div>

    <div class="sidebar">
        <div class="tab" onclick="showTab('graph')">
            <span class="material-icons">show_chart</span> สถิติและกราฟ
        </div>
        <div class="tab" onclick="showTab('order')">
            <span class="material-icons">shopping_cart</span> คำสั่งซื้อสินค้า
        </div>
        <div class="tab" onclick="showTab('upload_prodect')">
            <span class="material-icons">file_upload</span> อัพโหลดสินค้าใหม่
        </div>
        <div class="tab" onclick="showTab('admin_signup')">
            <span class="material-icons">person_add</span> การสมัครพนักงาน
        </div>
        <div class="tab" onclick="showTab('keyboards')">
            <span class="material-icons">keyboard</span> คีย์บอร์ด
        </div>
        <div class="tab" onclick="showTab('switches')">
            <span class="material-icons">toggle_on</span> สวิตช์
        </div>
        <div class="tab" onclick="showTab('keycaps')">
            <span class="material-icons">settings_input_component</span> คีย์แคป
        </div>
        <div class="tab" onclick="showTab('accessories')">
            <span class="material-icons">settings_input_component</span> อุปกรณ์เสริม
        </div>
        <div class="tab" onclick="showTab('admin')">
            <span class="material-icons">group</span> การจัดการพนักงาน
        </div>
        <a class="tab logout" href="/sci-shop-admin/logout.php">
            <span class="material-icons">exit_to_app</span> ออกจากระบบ
        </a>
    </div>

    <div id="graph" class="content">

        <div class="chart-container">
            <h4>ยอดขายสินค้า</h4>
            <canvas id="salesChart" width="400" height="200"></canvas>
        </div>

        <div class="chart-container">
            <h4>สถิติการใช้งาน</h4>
            <canvas id="usageChart" width="400" height="200"></canvas>
        </div>

        <div class="chart-container">
            <h4>การเติบโตของผู้ใช้</h4>
            <canvas id="growthChart" width="400" height="200"></canvas>
        </div>
    </div>

    <div id="order" class="content">
        <p>แสดงข้อมูลสถิติต่าง ๆ</p>
    </div>

    <div id="upload_prodect" class="content">
        <!-- ฟอร์มอัปโหลดสินค้า -->
        <form class="form-upload" id="uploadForm" action="../product/upload_product/upload_product.php" method="POST" enctype="multipart/form-data" onsubmit="return handleFormSubmit()">
            <div class="form-group">
                <label for="productName">ชื่อสินค้า</label>
                <input type="text" id="productName" name="productName" placeholder="กรอกชื่อสินค้า" required>
            </div>

            <div class="form-group">
                <label for="productCategory">หมวดหมู่สินค้า</label>
                <select id="productCategory" name="productCategory" required onchange="toggleFields()">
                    <option value="">เลือกหมวดหมู่</option>
                    <option value="keyboards">คีย์บอร์ด</option>
                    <option value="switches">สวิตซ์</option>
                    <option value="keycaps">คีย์แคป</option>
                    <option value="accessories">อุปกรณ์เสริม</option>
                </select>
            </div>

            <!-- ฟิลด์สำหรับคีย์บอร์ด -->
            <div class="form-group" id="keyboardSizeField" style="display: none;">
                <label for="keyboardSize">ขนาดคีย์บอร์ด</label>
                <input type="text" id="keyboardSize" name="keyboardSize" placeholder="กรอกขนาดคีย์บอร์ด">
            </div>

            <!-- ฟิลด์สำหรับสวิตซ์ -->
            <div class="form-group" id="switchTypeField" style="display: none;">
                <label for="switchType">ประเภทสวิตซ์</label>
                <input type="text" id="switchType" name="switchType" placeholder="กรอกประเภทสวิตซ์">
            </div>

            <!-- ฟิลด์สำหรับคีย์แคป -->
            <div class="form-group" id="keycapProfileField" style="display: none;">
                <label for="keycapProfile">โปรไฟล์คีย์แคป</label>
                <input type="text" id="keycapProfile" name="keycapProfile" placeholder="กรอกโปรไฟล์คีย์แคป">
            </div>

            <!-- ฟิลด์สำหรับอุปกรณ์เสริม -->
            <div class="form-group" id="productTypeField" style="display: none;">
                <label for="productType">ประเภทอุปกรณ์เสริม</label>
                <input type="text" id="productType" name="productType" placeholder="กรอกประเภทอุปกรณ์เสริม">
            </div>

            <div class="form-group-wrapper">
                <div class="form-group-url">
                    <label for="productImage">ใส่ URL ของรูปภาพสินค้า</label>
                    <input type="text" id="productImage" name="productImage" placeholder="กรอก URL ของรูปภาพ" required>
                </div>

                <div class="form-group-url">
                    <label for="reviewVideoUrl">ใส่ URL รีวิวสินค้า</label>
                    <input type="text" id="reviewVideoUrl" name="reviewVideoUrl" placeholder="กรอก URL รีวิว (ถ้ามี)">
                </div>
            </div>

            <div class="form-group">
                <label for="productPrice">ราคาสินค้า</label>
                <input type="number" id="productPrice" name="productPrice" placeholder="กรอกราคาสินค้า" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-upload">อัปโหลดสินค้า</button>
            </div>
        </form>
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


    <div id="keyboards" class="content">
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

    <div id="switches" class="content">
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

    <div id="keycaps" class="content">
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

    <div id="accessories" class="content">
        <h3 class="h-text">🔧 อุปกรณ์เสริม</h3>
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
                <?php foreach ($accessories as $item): ?>
                    <tr>
                        <td><img src="<?php echo $item['IMAGE_URL']; ?>" alt="<?php echo $item['NAME']; ?>" width="100"></td>
                        <td><?php echo $item['NAME']; ?></td>
                        <td>฿<?php echo $item['PRICE']; ?></td>
                        <td><?php echo $item['PRODUCT_TYPE']; ?></td>
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
                                        <input type="hidden" name="productCategory" value="accessories"> <!-- หรือ หมวดหมู่อื่นๆ ตามความเหมาะสม -->
                                        <input type="text" name="productType" value="<?php echo $item['PRODUCT_TYPE']; ?>" required>
                                        <button type="submit" class="btn-edit-prodect">บันทึก</button>
                                    </form>

                                </div>
                            </div>

                            <form id="deleteForm-<?php echo $item['ID']; ?>" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $item['ID']; ?>">
                                <input type="hidden" name="category" value="accessories"> <!-- หมวดหมู่สินค้านี้ -->
                                <button type="button" class="btn-delete" onclick="deleteProduct(<?php echo $item['ID']; ?>, 'accessories')">ลบสินค้า</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="customers" class="content">
        <h3 class="h-text">👤 ข้อมูลลูกค้า</h3>

        <!-- ฟอร์มค้นหาลูกค้าตามอีเมล Gmail -->
        <form action="/melgeeks_admin/search_email/search_email.php" method="GET" style="margin-bottom: 20px;">
            <label for="email_search">ค้นหาลูกค้าจาก Gmail:</label>
            <input type="email" id="email_search" name="email_search" placeholder="กรอก Gmail" required>
            <button type="submit">ค้นหา</button>
        </form>

        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                    <th>อีเมล</th>
                    <th>ที่อยู่</th>
                    <th>เบอร์โทรศัพท์</th>
                    <th>ระดับสมาชิก</th>
                    <th>ยอดรวมที่ใช้จ่าย</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($customer['ID_NUMBER']); ?></td>
                        <td><?php echo htmlspecialchars($customer['FIRST_NAME']); ?></td>
                        <td><?php echo htmlspecialchars($customer['LAST_NAME']); ?></td>
                        <td><?php echo htmlspecialchars($customer['EMAIL']); ?></td>
                        <td><?php echo htmlspecialchars($customer['ADDRESS'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($customer['PHONE']); ?></td>
                        <td><?php echo htmlspecialchars($customer['MEMBERSHIP_LEVEL']); ?></td>
                        <td><?php echo htmlspecialchars($customer['TOTAL_SPENT']); ?></td>
                        <td>
                            <form action="/melgeeks_admin/customer/delete_customer/delete_customer.php" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบลูกค้านี้?');">
                                <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($customer['ID_NUMBER']); ?>">
                                <button type="submit" class="btn-delete">ลบลูกค้า</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <div id="admin" class="content">
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
            const activeTab = localStorage.getItem("activeTab") || "graph"; // ใช้แท็บ 'graph' เป็นค่าเริ่มต้น
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

        // สร้างกราฟยอดขาย
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line', // ประเภทกราฟ
            data: {
                labels: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน'],
                datasets: [{
                    label: 'ยอดขาย (บาท)',
                    data: [15000, 12000, 18000, 22000],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // สร้างกราฟการใช้งาน
        const usageCtx = document.getElementById('usageChart').getContext('2d');
        const usageChart = new Chart(usageCtx, {
            type: 'bar',
            data: {
                labels: ['แอป 1', 'แอป 2', 'แอป 3', 'แอป 4'],
                datasets: [{
                    label: 'การใช้งาน (ครั้ง)',
                    data: [200, 150, 300, 250],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // สร้างกราฟการเติบโตของผู้ใช้
        const growthCtx = document.getElementById('growthChart').getContext('2d');
        const growthChart = new Chart(growthCtx, {
            type: 'pie',
            data: {
                labels: ['ผู้ใช้ที่เพิ่มขึ้น', 'ผู้ใช้ที่หายไป'],
                datasets: [{
                    data: [60, 40],
                    backgroundColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                    hoverOffset: 4
                }]
            }
        });

        function toggleMode() {
            document.querySelector('.mode-switch').classList.toggle('toggled');
        }
    </script>
</body>

</html>