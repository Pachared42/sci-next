<?php
session_start();
if (!isset($_SESSION['gmail']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: ../login.php"); // เปลี่ยน path ไปหน้า login ตามที่คุณใช้
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SCi_SALE</title>
    <link rel="icon" type="image/svg+xml" href="../img/sci-next.svg" />

    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Noto+Sans:ital,wght@0,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <link rel="stylesheet" href="sales-page.css?v=1" />
</head>

<body>
    <header class="search-bar">
        <div class="logo-container">
            <img src="../img/sci-next black.svg" alt="SCi-NEXT Logo" class="logo" />
            <h1>SCi-NEXT</h1>
        </div>
        <input type="text" placeholder="ค้นหาชื่อสินค้า หรือ บาร์โค้ด..." oninput="searchProducts(this.value)" />
    </header>

    <div class="product-grid" id="productGrid"></div>

    <navbar class="bottom-navbar">
        <div class="nav-button" onclick="logout()">
            <div class="icon-circle"><span class="material-symbols-outlined">logout</span></div>
            <span class="btn-label">ออกระบบ</span>
        </div>

        <div class="nav-button" onclick="addProduct()">
            <div class="icon-circle"><span class="material-symbols-outlined">add_box</span></div>
            <span class="btn-label">เพิ่มสินค้า</span>
        </div>

        <div class="nav-button scan-button" onclick="startScanner()">
            <div class="icon-circle"><span class="material-symbols-outlined">barcode_scanner</span></div>
            <span class="btn-label">สแกนบาร์โค้ด</span>
        </div>

        <div class="nav-button cart" onclick="toggleCart()">
            <div class="icon-circle">
                <span class="material-symbols-outlined">shopping_bag</span>
                <span class="cart-count" id="cart-count">0</span>
            </div>
            <span class="btn-label">ตะกร้าสินค้า</span>
        </div>

        <div id="dropdown-overlay" class="dropdown-overlay" onclick="closeDropdown()"></div>

        <div class="nav-button dropdown-wrapper" onclick="toggleDropdown(event)">
            <div class="icon-circle">
                <span class="material-symbols-outlined">tune</span>
            </div>
            <span class="btn-label">ตัวกรอง</span>

            <!-- Dropdown ด้านบน -->
            <div id="filter-dropdown" class="filter-dropdown">
                <button onclick="filterCategoryAndClose('ทั้งหมด', event)">ทั้งหมด</button>
                <button onclick="filterCategoryAndClose('ของแห้ง', event)">ของแห้ง</button>
                <button onclick="filterCategoryAndClose('ขนม', event)">ขนม</button>
                <button onclick="filterCategoryAndClose('เครื่องดื่ม', event)">เครื่องดื่ม</button>
                <button onclick="filterCategoryAndClose('เครื่องเขียน', event)">เครื่องเขียน</button>
                <button onclick="filterCategoryAndClose('ของแช่แข็ง', event)">ของแช่แข็ง</button>
            </div>
        </div>
    </navbar>

    <div id="cart-popup" class="cart-popup">
        <div class="cart-popup-header">
            <strong><span class="material-symbols-outlined">shopping_cart</span>ตะกร้าสินค้า</strong>
            <button onclick="toggleCart()"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div id="cart-items" class="cart-popup-items"></div>

        <div class="payment-options" id="payment-options" style="display: none;">
            <div class="total-price">
                <span>ราคารวม</span>
                <span><span id="total-price" class="highlight-price"></span> บาท</span>
            </div>

            <button class="payWithQR" onclick="payWithQR()">ชำระด้วย QR Code</button>
            <button class="payWithCash" onclick="payWithCash()">ชำระด้วย เงินสด</button>
        </div>

        <!-- ปุ่ม checkout -->
        <button id="checkout-btn" onclick="checkout()" disabled>
            <span class="btn-text">ดำเนินการต่อ</span>
            <span class="spinner" style="display:none;"></span>
        </button>

    </div>

    <!-- Popup QR Code -->
    <div id="qr-popup" class="qr-popup">
        <div class="qr-popup-content">
            <span class="material-symbols-outlined close-btn" onclick="closeQR()">close</span>
            <p>กรุณาสแกน QR Code เพื่อชำระเงิน</p>
            <img src="../img/QR code.jpg" alt="QR Code" class="qr-image">
        </div>
    </div>

    <div id="overlay" onclick="toggleCart()"></div>


    <div id="reader-container">
        <button id="close-scanner" onclick="stopScanner()"><span class="material-symbols-outlined">close</span></button>
        <div id="scanner-title">สแกนบาร์โค้ด</div>
        <div id="reader"></div>
        <div id="scanner-instruction">นำบาร์โค้ดของคุณมาสแกนที่นี่</div>
    </div>

    <div id="product-preview" </div>

        <script src="sales-page.js"></script>
</body>

</html>