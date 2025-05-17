<?php
require_once __DIR__ . '/../../controller/controllerSuperadmin.php';
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCi_ADMIN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wdth,wght@62.5..100,100..900&family=Noto+Sans:ital,wdth,wght@0,62.5..100,100..900;1,62.5..100,100..900&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="superadminDashboard.css">

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
            <img src="\sci-next\img\NEXT.png" alt="Logo" class="logo">
            <span class="site-name">SCi_ADMIN</span>
        </div>
    </div>

    <div id="tabLoadingBar"></div>

    <div class="sidebar">

        <!-- Avatar -->
        <div class="user-settings">
            <div class="avatar-wrapper">
                <img src="\sci-next\img\pachara.jpg" alt="Avatar" class="avatar">
                <span class="online-status"></span>
            </div>
        </div>

        <!-- รายการหลัก -->
        <div class="main-tabs">
            <h3>รายการหลัก</h3>

            <div class="tab" data-tab="graph">
                <span class="material-icons">analytics</span> แดชบอร์ดสถิติ
            </div>

            <div class="tab" id="orderTab" data-tab="order">
                <span class="material-icons">shopping_cart</span>
                <span class="badge">99+</span> <!-- ตัวเลขแจ้งเตือน -->
                รายการขาย
            </div>

            <div class="tab" data-tab="update_product">
                <span class="material-icons">analytics</span> ประวัติการอัปเดต
            </div>

        </div>
        <hr class="tab-divider">

        <!-- รายการอัพโหลด -->
        <div class="main-tabs-upload">
            <h3>รายการอัพโหลด</h3>
            <div class="tab" data-tab="admin_signup">
                <span class="material-icons">manage_accounts</span> สมัครแอดมิน
            </div>

            <div class="tab" data-tab="employee_signup">
                <span class="material-icons">group_add</span> สมัครพนักงาน
            </div>

            <div class="tab" data-tab="upload_prodect">
                <span class="material-icons">add_shopping_cart</span> อัพโหลดสินค้า
            </div>

            <div class="tab" data-tab="upload_file_excal">
                <span class="material-icons">upload_file</span> อัปโหลดไฟล์สินค้า
            </div>

            <button type="button" is="toggle-button" class="collapsible-toggle text-strong" aria-controls="menu" aria-expanded="false">
                <span class="material-icons">checklist</span> ตรวจสอบสินค้า
                <svg focusable="false" width="12" height="8" class="icon icon--chevron icon--inline" viewBox="0 0 12 8">
                    <path fill="none" d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="2"></path>
                </svg>
            </button>

            <div id="menu" class="menu">
                <div class="tab" data-tab="food_bank_check">
                    <span class="material-icons">food_bank</span> หมวดแห้ง
                </div>
                <div class="tab" data-tab="local_drink_check">
                    <span class="material-icons">local_drink</span> หมวดเครื่องดื่ม
                </div>
                <div class="tab" data-tab="fastfood_check">
                    <span class="material-icons">fastfood</span> หมวดแช่แข็ง
                </div>
                <div class="tab" data-tab="snack">
                    <span class="material-icons">cookie</span> หมวดขนม
                </div>
            </div>
        </div>
        <hr class="tab-divider">

        <!-- รายการสินค้า -->
        <div class="main-tabs-products">
            <h3>รายการสินค้า</h3>
            <div class="tab" data-tab="dried_food">
                <span class="material-icons">food_bank</span> ประเภทแห้ง
            </div>
            <div class="tab" data-tab="soft_drink">
                <span class="material-icons">local_drink</span> ประเภทเครื่องดื่ม
            </div>
            <div class="tab" data-tab="fresh_food">
                <span class="material-icons">fastfood</span> ประเภทแช่แข็ง
            </div>
            <div class="tab" data-tab="snack_food">
                <span class="material-icons">cookie</span> ประเภทขนม
            </div>
        </div>
        <hr class="tab-divider">


        <div class="main-tabs-products">
            <h3>รายการจัดการ</h3>
            <div class="tab" data-tab="customer">
                <span class="material-icons">manage_accounts</span> จัดการลูกค้า
            </div>
            <div class="tab" data-tab="sci_admin">
                <span class="material-icons">manage_accounts</span> จัดการแอดมิน
            </div>
            <div class="tab" data-tab="employee">
                <span class="material-icons">manage_accounts</span> จัดการพนักงาน
            </div>
        </div>
        <hr class="tab-divider">

        <div class="tab account" data-tab="account">
            <span class="material-icons">account_circle</span> โปรไฟล์แอดมิน
        </div>
        <a class="tab logout" href="#" onclick="showLogoutModal(event)">
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
                </tbody>
            </table>
        </div>
    </div>

    <div id="update_product" class="content">
        <!-- ส่วนหัว -->
        <h2 class="update-dashboard-title">แดชบอร์ดอัปเดตสินค้า</h2>

        <!-- ตัวกรอง -->
        <div class="update-filters">
            <input type="text" placeholder="ค้นหาชื่อสินค้า..." class="filter-input">
            <input type="text" placeholder="ค้นหาผู้ใช้งาน..." class="filter-input">
            <select class="filter-input">
                <option value="">ประเภทการกระทำ</option>
                <option value="add">เพิ่ม</option>
                <option value="update">แก้ไข</option>
                <option value="delete">ลบ</option>
            </select>
        </div>

        <!-- ตารางประวัติ -->
        <table class="update-history-table">
            <thead>
                <tr>
                    <th>วันที่เวลา</th>
                    <th>ผู้ดำเนินการ</th>
                    <th>ชื่อสินค้า</th>
                    <th>การกระทำ</th>
                    <th>รายละเอียด</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2025-05-17 18:42</td>
                    <td>admin01</td>
                    <td>โค้ก 1.25L</td>
                    <td class="action-edit">แก้ไข</td>
                    <td>ราคาจาก 20 → 22 บาท</td>
                </tr>
                <tr>
                    <td>2025-05-17 19:10</td>
                    <td>superadmin</td>
                    <td>ปลากระป๋องโรซ่า</td>
                    <td class="action-delete">ลบ</td>
                    <td>ลบสินค้าถาวร</td>
                </tr>
            </tbody>
        </table>
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
                    <img id="previewImageAdmin" src="/sci-next/img/product_food1.png" alt="รูปภาพแอดมิน">
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

                    <div class="form-group">
                        <label for="upload-img">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M440-440v-160H280v-80h160v-160h80v160h160v80H520v160h-80Zm40 280q-16 0-28-12t-12-28q0-16 12-28t28-12q16 0 28 12t12 28q0 16-12 28t-28 12Zm-320 80q-33 0-56.5-23.5T80-160v-480q0-33 23.5-56.5T160-720h640q33 0 56.5 23.5T880-640v480q0 33-23.5 56.5T800-80H160Zm0-80h640v-480H160v480Z" />
                            </svg>
                            รูปแอดมิน :</label>
                        </label>
                        <label for="upload-img-admin" class="custom-file-upload">เลือกไฟล์</label>
                        <input type="file" id="upload-img-admin" name="upload-img" accept="image/*" required>
                    </div>

                    <!-- แถวสำหรับชื่อและนามสกุล -->
                    <div class="from-container-stock">
                        <div class="form-group">
                            <label for="firstName-admin"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                    <path d="M80-80v-120q0-33 23.5-56.5T160-280h640q33 0 56.5 23.5T880-200v120H80Zm80-80h640v-40H160v40Zm40-180v-460q0-33 23.5-56.5T280-880h400q33 0 56.5 23.5T760-800v460h-80v-460H280v460h-80Zm120-60h23q44 0 70.5-44T440-560q0-72-26.5-116T343-720h-23v320Zm240-80q33 0 56.5-23.5T640-560q0-33-23.5-56.5T560-640q-33 0-56.5 23.5T480-560q0 33 23.5 56.5T560-480Zm-80 320Zm0-410Z" />
                                </svg>ชื่อ :</label>
                            <input type="text" id="firstName-admin" name="firstName" placeholder="กรอกชื่อ" required autocomplete="given-name">
                        </div>
                        <div class="form-group">
                            <label for="lastName-admin">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                    <path d="M481-781q106 0 200 45.5T838-604q7 9 4.5 16t-8.5 12q-6 5-14 4.5t-14-8.5q-55-78-141.5-119.5T481-741q-97 0-182 41.5T158-580q-6 9-14 10t-14-4q-7-5-8.5-12.5T126-602q62-85 155.5-132T481-781Zm0 94q135 0 232 90t97 223q0 50-35.5 83.5T688-257q-51 0-87.5-33.5T564-374q0-33-24.5-55.5T481-452q-34 0-58.5 22.5T398-374q0 97 57.5 162T604-121q9 3 12 10t1 15q-2 7-8 12t-15 3q-104-26-170-103.5T358-374q0-50 36-84t87-34q51 0 87 34t36 84q0 33 25 55.5t59 22.5q34 0 58-22.5t24-55.5q0-116-85-195t-203-79q-118 0-203 79t-85 194q0 24 4.5 60t21.5 84q3 9-.5 16T208-205q-8 3-15.5-.5T182-217q-15-39-21.5-77.5T154-374q0-133 96.5-223T481-687Zm0-192q64 0 125 15.5T724-819q9 5 10.5 12t-1.5 14q-3 7-10 11t-17-1q-53-27-109.5-41.5T481-839q-58 0-114 13.5T260-783q-8 5-16 2.5T232-791q-4-8-2-14.5t10-11.5q56-30 117-46t124-16Zm0 289q93 0 160 62.5T708-374q0 9-5.5 14.5T688-354q-8 0-14-5.5t-6-14.5q0-75-55.5-125.5T481-550q-76 0-130.5 50.5T296-374q0 81 28 137.5T406-123q6 6 6 14t-6 14q-6 6-14 6t-14-6q-59-62-90.5-126.5T256-374q0-91 66-153.5T481-590Zm-1 196q9 0 14.5 6t5.5 14q0 75 54 123t126 48q6 0 17-1t23-3q9-2 15.5 2.5T744-191q2 8-3 14t-13 8q-18 5-31.5 5.5t-16.5.5q-89 0-154.5-60T460-374q0-8 5.5-14t14.5-6Z" />
                                </svg>นามสกุล :</label>
                            <input type="text" id="lastName-admin" name="lastName" placeholder="กรอกนามสกุล" required autocomplete="family-name">
                        </div>
                    </div>

                    <hr class="tab-divider-admin">

                    <div class="form-group">
                        <label for="username-admin"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M560-440h200v-80H560v80Zm0-120h200v-80H560v80ZM200-320h320v-22q0-45-44-71.5T360-440q-72 0-116 26.5T200-342v22Zm160-160q33 0 56.5-23.5T440-560q0-33-23.5-56.5T360-640q-33 0-56.5 23.5T280-560q0 33 23.5 56.5T360-480ZM160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm0-80h640v-480H160v480Zm0 0v-480 480Z" />
                            </svg>ชื่อผู้ใช้งาน :</label>
                        <input type="text" id="username-admin" name="username" placeholder="กรอกชื่อผู้ใช้งาน" required autocomplete="username">
                    </div>

                    <div class="form-group">
                        <label for="password-admin">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M80-200v-80h800v80H80Zm46-242-52-30 34-60H40v-60h68l-34-58 52-30 34 58 34-58 52 30-34 58h68v60h-68l34 60-52 30-34-60-34 60Zm320 0-52-30 34-60h-68v-60h68l-34-58 52-30 34 58 34-58 52 30-34 58h68v60h-68l34 60-52 30-34-60-34 60Zm320 0-52-30 34-60h-68v-60h68l-34-58 52-30 34 58 34-58 52 30-34 58h68v60h-68l34 60-52 30-34-60-34 60Z" />
                            </svg>รหัสผ่าน :
                        </label>
                        <input type="password" id="password-admin" name="password" placeholder="กรอกรหัสผ่าน" required autocomplete="new-password">


                        <div id="password-strength-text" class="password-strength-text"></div>

                        <div class="password-progress">
                            <div id="password-progress-bar"></div>
                        </div>
                    </div>

                    <ul id="password-checklist">
                        <li id="length"><span class="icon">✖</span> อย่างน้อย 8 ตัวอักษร</li>
                        <li id="uppercase"><span class="icon">✖</span> มีตัวพิมพ์ใหญ่ (A-Z)</li>
                        <li id="number"><span class="icon">✖</span> มีตัวเลข (0-9)</li>
                        <li id="special"><span class="icon">✖</span> มีอักษรพิเศษ (!@#$...)</li>
                    </ul>

                    <div class="form-group">
                        <label for="confirmPassword-admin">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M480-480Zm0 400q-139-35-229.5-159.5T160-516v-244l320-120 320 120v262q0 9-1 19h-81q1-10 1.5-19t.5-18v-189l-240-90-240 90v189q0 121 68 220t172 132v84Zm200 0v-120H560v-80h120v-120h80v120h120v80H760v120h-80ZM420-360h120l-23-129q20-10 31.5-29t11.5-42q0-33-23.5-56.5T480-640q-33 0-56.5 23.5T400-560q0 23 11.5 42t31.5 29l-23 129Z" />
                            </svg>ยืนยันรหัสผ่าน :</label>
                        <input type="password" id="confirmPassword-admin" name="confirmPassword" placeholder="กรอกยืนยันรหัสผ่าน" required autocomplete="new-password">
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
                    <img id="previewImageEmployee" src="/sci-next/img/employee1.png" alt="รูปภาพพนักงาน">
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
                    <div class="form-group">
                        <label for="upload-img">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M440-440v-160H280v-80h160v-160h80v160h160v80H520v160h-80Zm40 280q-16 0-28-12t-12-28q0-16 12-28t28-12q16 0 28 12t12 28q0 16-12 28t-28 12Zm-320 80q-33 0-56.5-23.5T80-160v-480q0-33 23.5-56.5T160-720h640q33 0 56.5 23.5T880-640v480q0 33-23.5 56.5T800-80H160Zm0-80h640v-480H160v480Z" />
                            </svg>
                            รูปแอดมิน :</label>
                        </label>
                        <label for="upload-img" class="custom-file-upload">เลือกไฟล์</label>
                        <input type="file" id="upload-img" name="upload-img" accept="image/*" required>
                    </div>

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

                        <div id="password-strength-text" class="password-strength-text"></div>

                        <div class="password-progress">
                            <div id="password-progress-bar"></div>
                        </div>
                    </div>

                    <ul id="password-checklist">
                        <li id="length"><span class="icon">✖</span> อย่างน้อย 8 ตัวอักษร</li>
                        <li id="uppercase"><span class="icon">✖</span> มีตัวพิมพ์ใหญ่ (A-Z)</li>
                        <li id="number"><span class="icon">✖</span> มีตัวเลข (0-9)</li>
                        <li id="special"><span class="icon">✖</span> มีอักษรพิเศษ (!@#$...)</li>
                    </ul>

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
                    <img id="previewImageProduct" src="/sci-next/img/product_food1.png" alt="รูปภาพสินค้า">
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
                            <span class="material-icons">money</span>
                            <span id="previewCostProduct"></span>
                        </p>
                    </div>

                    <div class="detail-group">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                            <path d="M80-120v-560l400-160 400 160v560H640v-320H320v320H80Zm280 0v-80h80v80h-80Zm80-120v-80h80v80h-80Zm80 120v-80h80v80h-80Z" />
                        </svg>
                        <div>
                            <span id="previewStockProduct"></span>
                        </div>
                    </div>

                    <div class="detail-group">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                            <path d="M320-440v-287L217-624l-57-56 200-200 200 200-57 56-103-103v287h-80ZM600-80 400-280l57-56 103 103v-287h80v287l103-103 57 56L600-80Z" />
                        </svg>
                        <div>
                            <span id="previewReorderLevelProduct"></span>
                        </div>
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
                        <label for="productImage">
                            <span class="material-icons" style="vertical-align: middle;">add_photo_alternate</span> รูปภาพสินค้า :
                        </label>
                        <div class="input-clear-wrapper">
                            <input type="text" id="productImage" name="productImage" placeholder="กรอก URL ของรูปภาพ" required autocomplete="on"   
                            oninput="toggleClearBtn()">
                            <button type="button" class="clear-btn" data-tooltip="ลบ URL" onclick="clearInput()"><span class="material-icons">close</span>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="productName">
                            <span class="material-icons">sell</span> ชื่อสินค้า :
                        </label>
                        <input type="text" id="productName" name="productName" placeholder="กรอกชื่อสินค้า" required>
                    </div>

                    <div class="form-group">
                        <label for="barcode">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                                <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                            </svg>
                            บาร์โค้ด :
                        </label>
                        <input type="text" id="barcode" name="barcode" placeholder="กรอกบาร์โค้ด" required>
                    </div>

                    <div class="form-container-product">
                        <div class="form-group">
                            <label for="productPrice">
                                <span class="material-icons">paid</span> ราคาขาย :
                            </label>
                            <input type="number" id="productPrice" name="productPrice" placeholder="กรอกราคาขาย" required>
                        </div>

                        <div class="form-group">
                            <label for="productCost">
                                <span class="material-icons">money</span> ราคาต้นทุน :
                            </label>
                            <input type="number" id="productCost" name="productCost" placeholder="กรอกราคาต้นทุน" required>
                        </div>
                    </div>


                    <div class="from-container-stock">
                        <div class="form-group">
                            <label for="productStock">
                                <span class="material-icons">warehouse</span> จำนวนสต็อก :
                            </label>
                            <input type="number" id="productStock" name="productStock" placeholder="กรอกจำนวนสต็อก" required>
                        </div>

                        <div class="form-group">
                            <label for="productReorderLevel">
                                <span class="material-icons">swap_vert</span> สต็อกต่ำสุด :
                            </label>
                            <input type="number" id="productReorderLevel" name="productReorderLevel" placeholder="กรอกจำนวนสต็อกต่ำสุด" required>
                        </div>
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
        </div>

        <div class="excel-grid-upload">
            <!-- ประเภทแห้ง -->
            <form action="../../product/upload_product/upload_product_excal/upload_excel_dried.php" method="POST" enctype="multipart/form-data" class="excel-upload-form">
                <h4 class="excel-type-title"><span class="material-icons">food_bank</span>ประเภทแห้ง
                </h4>
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
            <form action="../../product/upload_product/upload_product_excal/upload_excel_drink.php" method="POST" enctype="multipart/form-data" class="excel-upload-form">
                <h4 class="excel-type-title"><span class="material-icons">local_drink</span>ประเภทเครื่องดื่ม
                </h4>
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
            <form action="../../product/upload_product/upload_product_excal/upload_excel_fresh.php" method="POST" enctype="multipart/form-data" class="excel-upload-form">
                <h4 class="excel-type-title"><span class="material-icons">fastfood</span>ประเภทแช่แข็ง
                </h4>
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
            <form action="../../product/upload_product/upload_product_excal/upload_excel_snack.php" method="POST" enctype="multipart/form-data" class="excel-upload-form">
                <h4 class="excel-type-title"><span class="material-icons">cookie</span>ประเภทขนม
                </h4>
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

        <div class="download-grid">
            <div class="download-template">
                <h3><span class="material-icons">food_bank</span>ฟอร์ม Excal ของแห้ง</h3>
                <a href="../../assets/templates/ฟอร์มของแห้ง.xlsx" download class="btn btn-download" data-tooltip="ฟอร์มของแห้ง">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                        <path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                    </svg>
                </a>
            </div>

            <div class="download-template">
                <h3><span class="material-icons">local_drink</span>ฟอร์ม Excal ของเครื่องดื่ม</h3>
                <a href="../../assets/templates/ฟอร์มเครื่องดื่ม.xlsx" download class="btn btn-download" data-tooltip="ฟอร์มเครื่องดื่ม">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                        <path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                    </svg>
                </a>
            </div>
            <div class="download-template">
                <h3><span class="material-icons">fastfood</span>ฟอร์ม Excal ของแช่แข็ง</h3>
                <a href="../../assets/templates/ฟอร์มของแช่แข็ง.xlsx" download class="btn btn-download" data-tooltip="ฟอร์มของแช่แข็ง">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                        <path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                    </svg>
                </a>
            </div>
            <div class="download-template">
                <h3><span class="material-icons">cookie</span>ฟอร์ม Excal ของขนม</h3>
                <a href="../../assets/templates/ฟอร์มขนม.xlsx" download class="btn btn-download" data-tooltip="ฟอร์มขนม">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                        <path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                    </svg>
                </a>
            </div>
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
                <button id="add_dried_food" class="btn btn-dried-food" data-tooltip="เพิ่มสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>
                </button>

                <button class="btn btn-edit-product-localStorage" data-tooltip="แก้ไขสินค้า" data-key="dried_food">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                    </svg>
                </button>

                <button class="btn btn-delete-product" data-tooltip="ลบสินค้า" data-key="dried_food">
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
                    <th>ราคาขาย</th>
                    <th>ราคาต้นทุน</th>
                    <th>จำนวนสต็อก</th>
                    <th>สต็อกต่ำสุด</th>
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
                <button id="add_soft_drink" class="btn btn-soft-drink" data-tooltip="เพิ่มสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>
                </button>

                <button class="btn btn-edit-product-localStorage" data-tooltip="แก้ไขสินค้า" data-key="soft_drink">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                    </svg>
                </button>

                <button class="btn btn-delete-product" data-tooltip="ลบสินค้า" data-key="soft_drink">
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
                    <th>ราคาขาย</th>
                    <th>ราคาต้นทุน</th>
                    <th>จำนวนสต็อก</th>
                    <th>สต็อกต่ำสุด</th>
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
                <button id="add_fresh_food" class="btn btn-fresh-food" data-tooltip="เพิ่มสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>
                </button>

                <button class="btn btn-edit-product-localStorage" data-tooltip="แก้ไขสินค้า" data-key="fresh_food">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                    </svg>
                </button>

                <button class="btn btn-delete-product" data-tooltip="ลบสินค้า" data-key="fresh_food">
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
                    <th>ราคาขาย</th>
                    <th>ราคาต้นทุน</th>
                    <th>จำนวนสต็อก</th>
                    <th>สต็อกต่ำสุด</th>
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
                <button id="add_snack" class="btn btn-snack" data-tooltip="เพิ่มสินค้า">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>
                </button>

                <button class="btn btn-edit-product-localStorage" data-tooltip="แก้ไขสินค้า" data-key="snack">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                    </svg>
                </button>

                <button class="btn btn-delete-product" data-tooltip="ลบสินค้า" data-key="snack">
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
                    <th>ราคาขาย</th>
                    <th>ราคาต้นทุน</th>
                    <th>จำนวนสต็อก</th>
                    <th>สต็อกต่ำสุด</th>
                </tr>
            </thead>
            <tbody id="snack_table"></tbody>
        </table>
    </div>

    <!-- Modal Edit Form loaclStorage-->
    <div id="editModal">
        <div class="modal-content">
            <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path d="M560-80v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T903-300L683-80H560Zm300-263-37-37 37 37ZM620-140h38l121-122-18-19-19-18-122 121v38ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v120h-80v-80H520v-200H240v640h240v80H240Zm280-400Zm241 199-19-18 37 37-18-19Z" />
                </svg>แบบฟอร์มแก้ไขสินค้า</h3>
            <form id="editForm" class="modal-form">
                <div class="modal-flex">
                    <!-- รูปภาพ -->
                    <div class="modal-image">
                        <img id="editImagePreview" src="" alt="รูปสินค้า" />
                    </div>

                    <!-- ฟอร์ม -->
                    <div class="modal-fields">
                        <label for="editName"><span class="material-icons">sell</span>ชื่อสินค้า
                            <p>*</p>
                        </label>
                        <input type="text" id="editName" required />

                        <label><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#444444">
                                <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                            </svg>บาร์โค้ด<p>*</p></label>
                        <input type="text" id="editBarcode" required />

                        <div class="field-row">
                            <div>
                                <label for="editPrice"><span class="material-icons">price_change</span>ราคาขาย<p>*</p></label>
                                <input type="number" id="editPrice" required />
                            </div>
                            <div>
                                <label for="editCost"><span class="material-icons">money</span>ราคาต้นทุน<p>*</p></label>
                                <input type="number" id="editCost" required />
                            </div>
                        </div>

                        <div class="field-row">
                            <div>
                                <label for="editStock"><span class="material-icons">warehouse</span>จำนวนสต็อก<p>*</p></label>
                                <input type="number" id="editStock" required />
                            </div>
                            <div>
                                <label for="editReorder"><span class="material-icons">swap_vert</span>สต็อกต่ำสุด<p>*</p></label>
                                <input type="number" id="editReorder" required />
                            </div>
                        </div>
                        <p class="image-warning"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                <path d="m40-120 440-760 440 760H40Zm440-120q17 0 28.5-11.5T520-280q0-17-11.5-28.5T480-320q-17 0-28.5 11.5T440-280q0 17 11.5 28.5T480-240Zm-40-120h80v-200h-80v200Z" />
                            </svg>กรุณาตรวจสอบข้อมูลอีกครั้งเพื่อความถูกต้อง</p>

                        <div class="modal-buttons field-row">
                            <button type="submit" class="modal-btn modal-btn-save"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d=" M200-200v-560 454-85 191Zm0 80q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v320h-80v-320H200v560h280v80H200Zm494 40L552-222l57-56 85 85 170-170 56 57L694-80ZM320-440q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 160h240v-80H440v80Zm0-160h240v-80H440v80Z" />
                                </svg>บันทึกการแก้ไข</button>
                            <button type="button" id="closeModal" class="modal-btn modal-btn-cancel"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
                                </svg>ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="confirmDeleteProductModal" class="custom-modal hidden">
        <div class="custom-modal-content">
            <span class="material-icons">feedback</span>
            <p id="confirmDeleteMessage"></p>
            <div class="modal-actions">
                <button id="btnConfirmDeleteProduct" class="btn-Confirm btn-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                    </svg>ลบสินค้า
                </button>
                <button id="btnCancelDeleteProduct" class="btn-Confirm btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>ยกเลิก
                </button>
            </div>
        </div>
    </div>


    <div id="deleteConfirmModal" class="custom-modal hidden">
        <div class="custom-modal-content">
            <span class="material-icons">feedback</span>
            <p id="deleteMessage"></p>
            <div class="modal-actions">
                <button id="confirmDeleteBtn" class="btn-Confirm btn-danger"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                    </svg>ลบสินค้า</button>
                <button id="cancelDeleteBtn" class="btn-Confirm btn-secondary"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>ยกเลิก</button>
            </div>
        </div>
    </div>

    <div id="addProductConfirmModal" class="custom-modal hidden">
        <div class="custom-modal-content">
            <span class="material-icons">trolley</span>
            <p id="confirmMessage">คุณต้องการเพิ่มสินค้าที่เลือกหรือไม่?</p>
            <div class="modal-actions">
                <button id="confirmAddProductBtn" class="btn-Confirm btn-success"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                    </svg>เพิ่มสินค้า</button>
                <button id="cancelAddProductBtn" class="btn-Confirm btn-secondary"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>ยกเลิก</button>
            </div>
        </div>
    </div>

    <div id="logoutConfirmModal" class="custom-modal hidden">
        <div class="custom-modal-content">
            <span class="material-icons">warning</span>
            <p id="confirmMessage">คุณต้องการออกจากระบบหรือไม่?</p>
            <div class="modal-actions">
                <button id="confirmLogoutBtn" class="btn-Confirm btn-danger"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z" />
                    </svg>ยืนยันออกจากระบบ</button>
                <button id="cancelLogoutBtn" class="btn-Confirm btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>ยกเลิก
                </button>
            </div>
        </div>
    </div>


    <div id="deleteSuccessToast" class="custom-toast">
        <span class="material-icons">check_circle</span>
        <p id="deleteSuccessMessage"></p>
    </div>

    <div id="alertToast" class="alert-toast">
        <span id="alertToastIcon" class="icon-container"></span>
        <span id="alertToastMessage"></span>
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
                    <!-- ประเภทสินค้า -->
                    <select id="filterCategory" onchange="applyFilters()">
                        <option value="all">ทั้งหมด</option>
                        <option value="lowStock">สินค้าใกล้หมด</option>
                    </select>

                    <!-- จำนวนแสดงผลต่อหน้า -->
                    <select id="itemsPerPageSelect" onchange="changeItemsPerPage(this.value)">
                        <option value="25">แสดง 25 ชิ้น</option>
                        <option value="50">แสดง 50 ชิ้น</option>
                        <option value="100">แสดง 100 ชิ้น</option>
                    </select>
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
            <table id="productTable" style="display: none;">
                <thead>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>รูปภาพ</th>
                        <th>บาร์โค้ด</th>
                        <th>ราคาขาย</th>
                        <th>ราคาต้นทุน</th>
                        <th>จำนวนสต็อก</th>
                        <th>สต็อกต่ำสุด</th>
                        <th>จัดการสินค้า</th>
                    </tr>
                </thead>
                <tbody id="productTableBody"></tbody>
            </table>

            <div id="productGrid" class="card-grid"></div>
        </div>

        <button id="scrollTopBtn" onclick="scrollToTop()" data-tooltip="เลื่อนขึ้น"><svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="#e3e3e3">
                <path d="M440-320h80v-168l64 64 56-56-160-160-160 160 56 56 64-64v168Zm40 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
            </svg></button>

        <div id="pagination" class="pagination"></div>

        <div id="overlay" class="overlay" onclick="closeEditPanel()"></div>

        <div id="editPanel" class="side-panel">
            <div class="side-panel-content">
                <span class="side-panel-close-btn" onclick="closeEditPanel()">&times;</span>

                <div id="side-panel-form-content">
                    <form id="editProductForm">
                        <input type="hidden" id="edit_product_id" name="id">

                        <div id="imagePreviewContainer">
                            <img id="imagePreview" alt="รูปสินค้า">
                        </div>

                        <label for="product_name"><span class="material-icons">sell</span>ชื่อสินค้า<p>*</p></label>
                        <input type="text" id="product_name" name="product_name" required>

                        <label for="barcode">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333">
                                <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                            </svg>บาร์โค้ด
                            <p>*</p>
                        </label>
                        <input type="text" id="barcodeDriedFood" name="barcode" maxlength="17" />

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="price"><span class="material-icons">price_change</span>ราคาขาย<p>*</p></label>
                                <input type="number" id="price" name="price" required>
                            </div>
                            <div class="group-item">
                                <label for="cost"><span class="material-icons">money</span>ราคาต้นทุน<p>*</p></label>
                                <input type="number" id="cost" name="cost" required>
                            </div>
                        </div>

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="stock"><span class="material-icons">warehouse</span>จำนวนสต็อก<p>*</p></label>
                                <input type="number" id="stock" name="stock" required>
                            </div>
                            <div class="group-item">
                                <label for="reorder_level"><span class="material-icons">swap_vert</span>สต็อกต่ำสุด<p>*</p></label>
                                <input type="number" id="reorder_level" name="reorder_level" required>
                            </div>
                        </div>

                        <button type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" "><path d=" M200-200v-560 454-85 191Zm0 80q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v320h-80v-320H200v560h280v80H200Zm494 40L552-222l57-56 85 85 170-170 56 57L694-80ZM320-440q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 160h240v-80H440v80Zm0-160h240v-80H440v80Z" /></svg>
                            บันทึกการแก้ไข
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- หมวด soft drink -->
    <div id="soft_drink" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">
                <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#ffffff">
                    <path d="M400-240h40v-160q25 0 42.5-17.5T500-460v-120h-40v120h-20v-120h-40v120h-20v-120h-40v120q0 25 17.5 42.5T400-400v160Zm160 0h40v-340q-33 0-56.5 23.5T520-500v120h40v140ZM160-120v-480l320-240 320 240v480H160Z" />
                </svg>
                สินค้าเครื่องดื่ม
            </h3>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="ค้นหาสินค้า..." id="searchInputSoftDrink" oninput="filterProductsSoftDrink()">
                <button class="search-button" onclick="filterProductsSoftDrink()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                    </svg>
                </button>
            </div>

            <div class="btn-container">
                <button class="btn btn-filter" data-tooltip="แสดง Filter" onclick="toggleMenuSoftDrink()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M400-240v-80h160v80H400ZM240-440v-80h480v80H240ZM120-640v-80h720v80H120Z" />
                    </svg>
                </button>

                <div id="filterMenuSoftDrink" class="filter-menu">
                    <select id="filterCategorySoftDrink" onchange="applyFiltersSoftDrink()">
                        <option value="all">ทั้งหมด</option>
                        <option value="lowStock">สินค้าใกล้หมด</option>
                    </select>

                    <select id="itemsPerPageSelectSoftDrink" onchange="changeItemsPerPageSoftDrink(this.value)">
                        <option value="25">แสดง 25 ชิ้น</option>
                        <option value="50">แสดง 50 ชิ้น</option>
                        <option value="100">แสดง 100 ชิ้น</option>
                    </select>
                </div>

                <button class="btn btn-table" data-tooltip="แสดง Table" onclick="switchViewSoftDrink('table')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M320-80q-33 0-56.5-23.5T240-160v-480q0-33 23.5-56.5T320-720h480q33 0 56.5 23.5T880-640v480q0 33-23.5 56.5T800-80H320Zm0-80h200v-120H320v120Zm280 0h200v-120H600v120ZM80-240v-560q0-33 23.5-56.5T160-880h560v80H160v560H80Zm240-120h200v-120H320v120Zm280 0h200v-120H600v120ZM320-560h480v-80H320v80Z" />
                    </svg>
                </button>
                <button class="btn btn-grid" data-tooltip="แสดง Grid" onclick="switchViewSoftDrink('grid')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M120-520v-320h320v320H120Zm0 400v-320h320v320H120Zm400-400v-320h320v320H520Zm0 400v-320h320v320H520ZM200-600h160v-160H200v160Zm400 0h160v-160H600v160Zm0 400h160v-160H600v160Zm-400 0h160v-160H200v160Zm400-400Zm0 240Zm-240 0Zm0-240Z" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="containerSoftDrink">
            <table id="productTableSoftDrink" style="display: none;">
                <thead>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>รูปภาพ</th>
                        <th>บาร์โค้ด</th>
                        <th>ราคาขาย</th>
                        <th>ราคาต้นทุน</th>
                        <th>จำนวนสต็อก</th>
                        <th>สต็อกต่ำสุด</th>
                        <th>จัดการสินค้า</th>
                    </tr>
                </thead>
                <tbody id="productTableBodySoftDrink"></tbody>
            </table>

            <div id="productGridSoftDrink" class="card-grid"></div>
        </div>

        <button id="scrollTopBtnSoftDrink" onclick="scrollToTopSoftDrink()" data-tooltip="เลื่อนขึ้น"><svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="#e3e3e3">
                <path d="M440-320h80v-168l64 64 56-56-160-160-160 160 56 56 64-64v168Zm40 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
            </svg></button>

        <div id="paginationSoftDrink" class="pagination"></div>

        <div id="overlaySoftDrink" class="overlay" onclick="closeEditPanelSoftDrink()"></div>

        <div id="editPanelSoftDrink" class="side-panel">
            <div class="side-panel-content">
                <span class="side-panel-close-btn" onclick="closeEditPanelSoftDrink()">&times;</span>

                <div id="side-panel-form-content-softdrink">
                    <form id="editProductFormSoftDrink">
                        <input type="hidden" id="edit_product_id_softdrink" name="id">

                        <div id="imagePreviewContainerSoftDrink">
                            <img id="imagePreviewSoftDrink" alt="รูปสินค้า">
                        </div>

                        <label for="product_name_softdrink"><span class="material-icons">sell</span>ชื่อสินค้า<p>*</p></label>
                        <input type="text" id="product_name_softdrink" name="product_name" required>

                        <label for="barcode_softdrink">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333">
                                <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                            </svg>บาร์โค้ด<p>*</p></label>
                        <input type="text" id="barcode_softdrink" name="barcode" maxlength="17" />

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="price_softdrink"><span class="material-icons">price_change</span>ราคาขาย<p>*</p></label>
                                <input type="number" id="price_softdrink" name="price" required>
                            </div>
                            <div class="group-item">
                                <label for="cost_softdrink"><span class="material-icons">money</span>ราคาต้นทุน<p>*</p></label>
                                <input type="number" id="cost_softdrink" name="cost" required>
                            </div>
                        </div>

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="stock_softdrink"><span class="material-icons">warehouse</span>จำนวนสต็อก<p>*</p></label>
                                <input type="number" id="stock_softdrink" name="stock" required>
                            </div>
                            <div class="group-item">
                                <label for="reorder_level_softdrink"><span class="material-icons">swap_vert</span>สต็อกต่ำสุด<p>*</p></label>
                                <input type="number" id="reorder_level_softdrink" name="reorder_level" required>
                            </div>
                        </div>

                        <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" "><path d=" M200-200v-560 454-85 191Zm0 80q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v320h-80v-320H200v560h280v80H200Zm494 40L552-222l57-56 85 85 170-170 56 57L694-80ZM320-440q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 160h240v-80H440v80Zm0-160h240v-80H440v80Z" /></svg>บันทึกการแก้ไข</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- หมวด fresh food -->
    <div id="fresh_food" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">
                <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#ffffff">
                    <path d="M400-240h40v-160q25 0 42.5-17.5T500-460v-120h-40v120h-20v-120h-40v120h-20v-120h-40v120q0 25 17.5 42.5T400-400v160Zm160 0h40v-340q-33 0-56.5 23.5T520-500v120h40v140ZM160-120v-480l320-240 320 240v480H160Z" />
                </svg>
                สินค้าแช่แข็ง
            </h3>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="ค้นหาสินค้า..." id="searchInputFreshFood" oninput="filterProductsFreshFood()">
                <button class="search-button" onclick="filterProductsFreshFood()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                    </svg>
                </button>
            </div>

            <div class="btn-container">
                <button class="btn btn-filter" data-tooltip="แสดง Filter" onclick="toggleMenuFreshFood()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M400-240v-80h160v80H400ZM240-440v-80h480v80H240ZM120-640v-80h720v80H120Z" />
                    </svg>
                </button>

                <div id="filterMenuFreshFood" class="filter-menu">
                    <select id="filterCategoryFreshFood" onchange="applyFiltersFreshFood()">
                        <option value="all">ทั้งหมด</option>
                        <option value="lowStock">สินค้าใกล้หมด</option>
                    </select>

                    <select id="itemsPerPageSelectFreshFood" onchange="changeItemsPerPageFreshFood(this.value)">
                        <option value="25">แสดง 25 ชิ้น</option>
                        <option value="50">แสดง 50 ชิ้น</option>
                        <option value="100">แสดง 100 ชิ้น</option>
                    </select>
                </div>

                <button class="btn btn-table" data-tooltip="แสดง Table" onclick="switchViewFreshFood('table')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M320-80q-33 0-56.5-23.5T240-160v-480q0-33 23.5-56.5T320-720h480q33 0 56.5 23.5T880-640v480q0 33-23.5 56.5T800-80H320Zm0-80h200v-120H320v120Zm280 0h200v-120H600v120ZM80-240v-560q0-33 23.5-56.5T160-880h560v80H160v560H80Zm240-120h200v-120H320v120Zm280 0h200v-120H600v120ZM320-560h480v-80H320v80Z" />
                    </svg>
                </button>
                <button class="btn btn-grid" data-tooltip="แสดง Grid" onclick="switchViewFreshFood('grid')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M120-520v-320h320v320H120Zm0 400v-320h320v320H120Zm400-400v-320h320v320H520Zm0 400v-320h320v320H520ZM200-600h160v-160H200v160Zm400 0h160v-160H600v160Zm0 400h160v-160H600v160Zm-400 0h160v-160H200v160Zm400-400Zm0 240Zm-240 0Zm0-240Z" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="containerFreshFood">
            <table id="productTableFreshFood" style="display: none;">
                <thead>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>รูปภาพ</th>
                        <th>บาร์โค้ด</th>
                        <th>ราคาขาย</th>
                        <th>ราคาต้นทุน</th>
                        <th>จำนวนสต็อก</th>
                        <th>สต็อกต่ำสุด</th>
                        <th>จัดการสินค้า</th>
                    </tr>
                </thead>
                <tbody id="productTableBodyFreshFood"></tbody>
            </table>

            <div id="productGridFreshFood" class="card-grid"></div>
        </div>

        <button id="scrollTopBtnFreshFood" onclick="scrollToTopFreshFood()" data-tooltip="เลื่อนขึ้น"><svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="#e3e3e3">
                <path d="M440-320h80v-168l64 64 56-56-160-160-160 160 56 56 64-64v168Zm40 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
            </svg></button>

        <div id="paginationFreshFood" class="pagination"></div>

        <div id="overlayFreshFood" class="overlay" onclick="closeEditPanelFreshFood()"></div>

        <div id="editPanelFreshFood" class="side-panel">
            <div class="side-panel-content">
                <span class="side-panel-close-btn" onclick="closeEditPanelFreshFood()">&times;</span>

                <div id="side-panel-form-content-freshfood">
                    <form id="editProductFormFreshFood" method="POST" action="">
                        <input type="hidden" id="edit_product_id_freshfood" name="id">

                        <div id="imagePreviewContainerFreshFood">
                            <img id="imagePreviewFreshFood" alt="รูปสินค้า">
                        </div>

                        <label for="product_name_freshfood"><span class="material-icons">sell</span>ชื่อสินค้า<p>*</p></label>
                        <input type="text" id="product_name_freshfood" name="product_name" required>

                        <label for="barcode_freshfood"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333">
                                <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                            </svg>บาร์โค้ด<p>*</p></label>
                        <input type="text" id="barcode_freshfood" name="barcode" maxlength="17" />

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="price_freshfood"><span class="material-icons">price_change</span>ราคาขาย<p>*</p></label>
                                <input type="number" id="price_freshfood" name="price" required>
                            </div>
                            <div class="group-item">
                                <label for="cost_freshfood"><span class="material-icons">money</span>ราคาต้นทุน<p>*</p></label>
                                <input type="number" id="cost_freshfood" name="cost" required>
                            </div>
                        </div>

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="stock_freshfood"><span class="material-icons">warehouse</span>จำนวนสต็อก<p>*</p></label>
                                <input type="number" id="stock_freshfood" name="stock" required>
                            </div>
                            <div class="group-item">
                                <label for="reorder_level_freshfood"><span class="material-icons">swap_vert</span>สต็อกต่ำสุด<p>*</p></label>
                                <input type="number" id="reorder_level_freshfood" name="reorder_level" required>
                            </div>
                        </div>

                        <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" "><path d=" M200-200v-560 454-85 191Zm0 80q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v320h-80v-320H200v560h280v80H200Zm494 40L552-222l57-56 85 85 170-170 56 57L694-80ZM320-440q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 160h240v-80H440v80Zm0-160h240v-80H440v80Z" /></svg>บันทึกการแก้ไข</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- หมวด snack food -->
    <div id="snack_food" class="content">
        <div class="header-container">
            <h3 class="h-text-upload">
                <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#ffffff">
                    <path d="M400-240h40v-160q25 0 42.5-17.5T500-460v-120h-40v120h-20v-120h-40v120h-20v-120h-40v120q0 25 17.5 42.5T400-400v160Zm160 0h40v-340q-33 0-56.5 23.5T520-500v120h40v140ZM160-120v-480l320-240 320 240v480H160Z" />
                </svg>
                สินค้าขนมขบเคี้ยว
            </h3>
            <div class="search-container">
                <input type="text" class="search-input" placeholder="ค้นหาสินค้า..." id="searchInputSnackFood" oninput="filterProductsSnackFood()">
                <button class="search-button" onclick="filterProductsSnackFood()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                    </svg>
                </button>
            </div>

            <div class="btn-container">
                <button class="btn btn-filter" data-tooltip="แสดง Filter" onclick="toggleMenuSnackFood()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M400-240v-80h160v80H400ZM240-440v-80h480v80H240ZM120-640v-80h720v80H120Z" />
                    </svg>
                </button>

                <div id="filterMenuSnackFood" class="filter-menu">
                    <select id="filterCategorySnackFood" onchange="applyFiltersSnackFood()">
                        <option value="all">ทั้งหมด</option>
                        <option value="lowStock">สินค้าใกล้หมด</option>
                    </select>

                    <select id="itemsPerPageSelectSnackFood" onchange="changeItemsPerPageSnackFood(this.value)">
                        <option value="25">แสดง 25 ชิ้น</option>
                        <option value="50">แสดง 50 ชิ้น</option>
                        <option value="100">แสดง 100 ชิ้น</option>
                    </select>
                </div>

                <button class="btn btn-table" data-tooltip="แสดง Table" onclick="switchViewSnackFood('table')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M320-80q-33 0-56.5-23.5T240-160v-480q0-33 23.5-56.5T320-720h480q33 0 56.5 23.5T880-640v480q0 33-23.5 56.5T800-80H320Zm0-80h200v-120H320v120Zm280 0h200v-120H600v120ZM80-240v-560q0-33 23.5-56.5T160-880h560v80H160v560H80Zm240-120h200v-120H320v120Zm280 0h200v-120H600v120ZM320-560h480v-80H320v80Z" />
                    </svg>
                </button>
                <button class="btn btn-grid" data-tooltip="แสดง Grid" onclick="switchViewSnackFood('grid')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                        <path d="M120-520v-320h320v320H120Zm0 400v-320h320v320H120Zm400-400v-320h320v320H520Zm0 400v-320h320v320H520ZM200-600h160v-160H200v160Zm400 0h160v-160H600v160Zm0 400h160v-160H600v160Zm-400 0h160v-160H200v160Zm400-400Zm0 240Zm-240 0Zm0-240Z" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="containerSnackFood">
            <table id="productTableSnackFood" style="display: none;">
                <thead>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>รูปภาพ</th>
                        <th>บาร์โค้ด</th>
                        <th>ราคาขาย</th>
                        <th>ราคาต้นทุน</th>
                        <th>จำนวนสต็อก</th>
                        <th>สต็อกต่ำสุด</th>
                        <th>จัดการสินค้า</th>
                    </tr>
                </thead>
                <tbody id="productTableBodySnackFood"></tbody>
            </table>

            <div id="productGridSnackFood" class="card-grid"></div>
        </div>

        <button id="scrollTopBtnSnackFood" onclick="scrollToTopSnackFood()" data-tooltip="เลื่อนขึ้น"><svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="#e3e3e3">
                <path d="M440-320h80v-168l64 64 56-56-160-160-160 160 56 56 64-64v168Zm40 240q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
            </svg></button>

        <div id="paginationSnackFood" class="pagination"></div>

        <div id="overlaySnackFood" class="overlay" onclick="closeEditPanelSnackFood()"></div>

        <div id="editPanelSnackFood" class="side-panel">
            <div class="side-panel-content">
                <span class="side-panel-close-btn" onclick="closeEditPanelSnackFood()">&times;</span>

                <div id="side-panel-form-content-snackfood">
                    <!-- ฟอร์มที่ใช้แก้ไขสินค้า -->
                    <form id="editProductFormSnackFood" method="POST" action="">
                        <input type="hidden" id="edit_product_id_snackfood" name="id">

                        <div id="imagePreviewContainerSnackFood">
                            <img id="imagePreviewSnackFood" alt="รูปสินค้า">
                        </div>

                        <label for="product_name_snackfood"><span class="material-icons">sell</span>ชื่อสินค้า<p>*</p></label>
                        <input type="text" id="product_name_snackfood" name="product_name" required>

                        <label for="barcode_snackfood"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333">
                                <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                            </svg>บาร์โค้ด<p>*</p></label>
                        <input type="text" id="barcode_snackfood" name="barcode" maxlength="17" />

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="price_snackfood"><span class="material-icons">price_change</span>ราคาขาย<p>*</p></label>
                                <input type="number" id="price_snackfood" name="price" required>
                            </div>
                            <div class="group-item">
                                <label for="cost_snackfood"><span class="material-icons">money</span>ราคาต้นทุน<p>*</p></label>
                                <input type="number" id="cost_snackfood" name="cost" required>
                            </div>
                        </div>

                        <div class="form-group-row">
                            <div class="group-item">
                                <label for="stock_snackfood"><span class="material-icons">warehouse</span>จำนวนสต็อก<p>*</p></label>
                                <input type="number" id="stock_snackfood" name="stock" required>
                            </div>
                            <div class="group-item">
                                <label for="reorder_level_snackfood"><span class="material-icons">swap_vert</span>สต็อกต่ำสุด<p>*</p></label>
                                <input type="number" id="reorder_level_snackfood" name="reorder_level" required>
                            </div>
                        </div>

                        <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" "><path d=" M200-200v-560 454-85 191Zm0 80q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v320h-80v-320H200v560h280v80H200Zm494 40L552-222l57-56 85 85 170-170 56 57L694-80ZM320-440q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 160h240v-80H440v80Zm0-160h240v-80H440v80Z" /></svg>บันทึกการแก้ไข</button>
                    </form>

                </div>
            </div>
        </div>
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
                <?php foreach ($admins as $user): ?>
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
                    <img id="profile-pic" src="\sci-next\img\pachara.jpg" alt="Admin Profile">
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
                                <input type="text" id="first-name-adminProfile" value="Pachara">
                            </div>
                            <div class="profile-info">
                                <label><span class="material-icons">fingerprint</span> นามสกุล</label>
                                <input type="text" id="last-name-adminProfile" value="Kalapakdee">
                            </div>
                        </div>

                        <div class="profile-row">
                            <div class="profile-info">
                                <label><span class="material-icons">person_outline</span> ชื่อผู้ใช้</label>
                                <input type="text" id="username-adminProfile" value="pachara">
                            </div>
                            <div class="profile-info">
                                <label><span class="material-icons">password</span> รหัสผ่าน</label>
                                <input type="password" id="password-adminProfile" placeholder="********">
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
        let toastTimeoutId = null;

        const icons = {
            empty: `
        <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20" fill="#E57300">
        <path d="M600-240v-120H488q-32 54-87 87t-121 33q-100 0-170-70T40-480q0-100 70-170t170-70q66 0 121 33t87 87h272v80H434q-8-39-48-79.5T280-640q-66 0-113 47t-47 113q0 66 47 113t113 47q66 0 106-40.5t48-79.5h246v120h80v80H600Z"/>
        </svg>`,
            select: `
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#E57300">
        <path d="M516-82q-9 2-18 2h-18q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480v18q0 9-2 18l-78-24v-12q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93h12l24 78Zm305 22L650-231 600-80 480-480l400 120-151 50 171 171-79 79Z"/>
        </svg>`,
            success: `
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#4CAF50"><path d="m562-225 199-199-57-56-142 142-56-57-57 57 113 113ZM280-360h120v-80H280v80Zm0-120h280v-80H280v80Zm0-120h280v-80H280v80ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>`,
            error: `<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#DC143C"><path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm-40-160h80v-240h-80v240Zm40 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg> `,
        };

        function showAlertToast(message, icon = "", customClass = "") {
            const toast = document.getElementById("alertToast");
            const text = document.getElementById("alertToastMessage");
            const iconSpan = document.getElementById("alertToastIcon");

            if (!toast || !text || !iconSpan) return;

            // เคลียร์ timeout และรีเซ็ตก่อน
            if (toastTimeoutId) {
                clearTimeout(toastTimeoutId);
                toast.style.display = "none";
                toast.style.animation = "none";
                toast.offsetHeight;
            }

            // ล้าง class ก่อน
            toast.className = "alert-toast"; // class base
            if (customClass) toast.classList.add(customClass); // เพิ่ม class เฉพาะ

            iconSpan.innerHTML = icon;
            text.textContent = message;

            toast.style.display = "flex";
            toast.style.animation = "bounceIn 0.4s ease, fadeOut 4s ease-in-out forwards";

            toastTimeoutId = setTimeout(() => {
                toast.style.display = "none";
                toastTimeoutId = null;
            }, 4000);
        }

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

        // ฟังก์ชันแสดงชื่อไฟล์ที่เลือก Excal
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

        function toggleDropdownSoftDrink(button) {
            // ปิด dropdown อื่นก่อน
            document.querySelectorAll('.action-dropdown-soft-drink').forEach(dropdown => {
                if (dropdown !== button.parentElement) {
                    dropdown.classList.remove('show');
                }
            });

            // toggle อันที่คลิก
            button.parentElement.classList.toggle('show');
        }

        function toggleDropdownFreshFood(button) {
            // ปิด dropdown อื่นก่อน
            document.querySelectorAll('.action-dropdown-fresh-food').forEach(dropdown => {
                if (dropdown !== button.parentElement) {
                    dropdown.classList.remove('show');
                }
            });

            // toggle อันที่คลิก
            button.parentElement.classList.toggle('show');
        }

        function toggleDropdownSnackFood(button) {
            // ปิด dropdown อื่นก่อน
            document.querySelectorAll('.action-dropdown-snack').forEach(dropdown => {
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

        window.addEventListener('click', function(e) {
            if (!e.target.matches('.action-dropdown-soft-drink button')) {
                document.querySelectorAll('.action-dropdown-soft-drink').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });

        window.addEventListener('click', function(e) {
            if (!e.target.matches('.action-dropdown-fresh-food button')) {
                document.querySelectorAll('.action-dropdown-fresh-food').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });

        window.addEventListener('click', function(e) {
            if (!e.target.matches('.action-dropdown-snack button')) {
                document.querySelectorAll('.action-dropdown-snack').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });

        document.getElementById('editProductForm').addEventListener('submit', function(e) {
            e.preventDefault(); // ป้องกัน submit แบบเปลี่ยนหน้า

            const form = e.target;
            const formData = new FormData(form);

            fetch('../../product/edit_product/edit_food_all/edit_dried_food.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlertToast('แก้ไขข้อมูลสินค้าเรียบร้อยแล้ว', icons.success, 'success');
                        setTimeout(() => location.reload(), 1000);
                        // ปิด panel
                        document.getElementById('editPanel').style.right = '-100%';
                        document.getElementById('overlay').style.display = 'none';
                        document.body.style.overflow = '';
                    } else {
                        showAlertToast('เกิดข้อผิดพลาด: ' + data.message, icons.error, 'alert-toast');
                    }
                })
                .catch(error => {
                    console.error('เกิดข้อผิดพลาด:', error);
                    showAlertToast('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์', icons.error, 'alert-toast');
                });
        });

        function openEditPanel(id) {
            const overlay = document.getElementById('overlay');
            const editPanel = document.getElementById('editPanel');

            if (overlay && editPanel) {
                overlay.style.display = 'block';
                editPanel.style.display = 'block';
                setTimeout(() => {
                    editPanel.style.right = '0';
                }, 10);
                document.body.style.overflow = 'hidden';

                // ใช้ fetch เพื่อดึงข้อมูลสินค้าตาม id ที่ส่งมา
                fetch('../../product/edit_product/get_food_all/get_dried_food.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('edit_product_id').value = data.id;
                        document.getElementById('imagePreview').src = data.image_url;
                        document.getElementById('product_name').value = data.product_name;
                        document.getElementById('barcodeDriedFood').value = data.barcode || '';
                        document.getElementById('price').value = data.price;
                        document.getElementById('cost').value = data.cost;
                        document.getElementById('stock').value = data.stock;
                        document.getElementById('reorder_level').value = data.reorder_level;
                    })
                    .catch(error => {
                        console.error('เกิดข้อผิดพลาดในการโหลดข้อมูลสินค้า:', error);
                    });
            } else {
                console.error("ไม่พบ overlay หรือ editPanel ใน DOM");
            }
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

        document.getElementById('editProductFormSoftDrink').addEventListener('submit', function(e) {
            e.preventDefault(); // ป้องกันการเปลี่ยนหน้า

            const form = e.target;
            const formData = new FormData(form);

            fetch('../../product/edit_product/edit_food_all/edit_soft_drink.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlertToast('แก้ไขข้อมูลสินค้าเรียบร้อยแล้ว', icons.success, 'success');
                        setTimeout(() => location.reload(), 1000);
                        // ปิด panel
                        document.getElementById('editPanelSoftDrink').style.right = '-100%';
                        document.getElementById('overlaySoftDrink').style.display = 'none';
                        document.body.style.overflow = '';
                    } else {
                        showAlertToast('เกิดข้อผิดพลาด: ' + data.message, icons.error, 'alert-toast');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showAlertToast('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์', icons.error, 'alert-toast');
                });
        });

        function openEditPanelSoftDrink(id) {
            const overlay = document.getElementById('overlaySoftDrink');
            const editPanel = document.getElementById('editPanelSoftDrink');

            if (overlay && editPanel) {
                overlay.style.display = 'block';
                editPanel.style.display = 'block';
                setTimeout(() => {
                    editPanel.style.right = '0';
                }, 10);
                document.body.style.overflow = 'hidden';

                // ใช้ fetch เพื่อดึงข้อมูลสินค้าตาม id ที่ส่งมา
                fetch('../../product/edit_product/get_food_all/get_soft_drink.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('edit_product_id_softdrink').value = data.id;
                        document.getElementById('imagePreviewSoftDrink').src = data.image_url;
                        document.getElementById('product_name_softdrink').value = data.product_name;
                        document.getElementById('barcode_softdrink').value = data.barcode || '';
                        document.getElementById('price_softdrink').value = data.price;
                        document.getElementById('cost_softdrink').value = data.cost;
                        document.getElementById('stock_softdrink').value = data.stock;
                        document.getElementById('reorder_level_softdrink').value = data.reorder_level;
                    })
                    .catch(error => {
                        console.error('เกิดข้อผิดพลาดในการโหลดข้อมูลสินค้า:', error);
                    });
            } else {
                console.error("ไม่พบ overlay หรือ editPanel ใน DOM");
            }
        }


        function closeEditPanelSoftDrink() {
            const overlay = document.getElementById('overlaySoftDrink');
            const editPanel = document.getElementById('editPanelSoftDrink');

            overlay.style.display = 'none';
            editPanel.style.right = '-500px';
            setTimeout(() => {
                editPanel.style.display = 'none';
            }, 300); // ต้องรอให้ side panel เลื่อนออกไปก่อนค่อยปิด display

            // เปิดการเลื่อนหน้าจอเมื่อปิด editPanel
            document.body.style.overflow = 'auto';
        }


        document.getElementById('editProductFormFreshFood').addEventListener('submit', function(e) {
            e.preventDefault(); // ป้องกันการเปลี่ยนหน้า

            const form = e.target;
            const formData = new FormData(form);

            fetch('../../product/edit_product/edit_food_all/edit_fresh_food.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlertToast('แก้ไขข้อมูลสินค้าเรียบร้อยแล้ว', icons.success, 'success');
                        setTimeout(() => location.reload(), 1000);
                        // ปิด panel
                        document.getElementById('editPanelFreshFood').style.right = '-100%';
                        document.getElementById('overlayFreshFood').style.display = 'none';
                        document.body.style.overflow = '';
                    } else {
                        showAlertToast('เกิดข้อผิดพลาด: ' + data.message, icons.error, 'alert-toast');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showAlertToast('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์', icons.error, 'alert-toast');
                });
        });

        function openEditPanelFreshFood(id) {
            const overlay = document.getElementById('overlayFreshFood');
            const editPanel = document.getElementById('editPanelFreshFood');

            if (overlay && editPanel) {
                overlay.style.display = 'block';
                editPanel.style.display = 'block';
                setTimeout(() => {
                    editPanel.style.right = '0';
                }, 10);
                document.body.style.overflow = 'hidden';

                // ใช้ fetch เพื่อดึงข้อมูลสินค้าตาม id ที่ส่งมา
                fetch('../../product/edit_product/get_food_all/get_fresh_food.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('edit_product_id_freshfood').value = data.id;
                        document.getElementById('imagePreviewFreshFood').src = data.image_url;
                        document.getElementById('product_name_freshfood').value = data.product_name;
                        document.getElementById('barcode_freshfood').value = data.barcode || '';
                        document.getElementById('price_freshfood').value = data.price;
                        document.getElementById('cost_freshfood').value = data.cost;
                        document.getElementById('stock_freshfood').value = data.stock;
                        document.getElementById('reorder_level_freshfood').value = data.reorder_level;
                    })
                    .catch(error => {
                        console.error('เกิดข้อผิดพลาดในการโหลดข้อมูลสินค้า:', error);
                    });
            } else {
                console.error("ไม่พบ overlay หรือ editPanel ใน DOM");
            }
        }

        function closeEditPanelFreshFood() {
            const overlay = document.getElementById('overlayFreshFood');
            const editPanel = document.getElementById('editPanelFreshFood');

            overlay.style.display = 'none';
            editPanel.style.right = '-500px';
            setTimeout(() => {
                editPanel.style.display = 'none';
            }, 300); // ต้องรอให้ side panel เลื่อนออกไปก่อนค่อยปิด display

            // เปิดการเลื่อนหน้าจอเมื่อปิด editPanel
            document.body.style.overflow = 'auto';
        }

        document.getElementById('editProductFormSnackFood').addEventListener('submit', function(e) {
            e.preventDefault(); // ป้องกันการเปลี่ยนหน้า

            const form = e.target;
            const formData = new FormData(form);

            fetch('../../product/edit_product/edit_food_all/edit_snack.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlertToast('แก้ไขข้อมูลสินค้าเรียบร้อยแล้ว', icons.success, 'success');
                        setTimeout(() => location.reload(), 1000);
                        // ปิด panel
                        document.getElementById('editPanelSnackFood').style.right = '-100%';
                        document.getElementById('overlaySnackFood').style.display = 'none';
                        document.body.style.overflow = '';
                    } else {
                        showAlertToast('เกิดข้อผิดพลาด: ' + data.message, icons.error, 'alert-toast');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showAlertToast('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์', icons.error, 'alert-toast');
                });
        });

        function openEditPanelSnackFood(id) {
            const overlay = document.getElementById('overlaySnackFood');
            const editPanel = document.getElementById('editPanelSnackFood');

            if (overlay && editPanel) {
                overlay.style.display = 'block';
                editPanel.style.display = 'block';
                setTimeout(() => {
                    editPanel.style.right = '0';
                }, 10);
                document.body.style.overflow = 'hidden';

                // ใช้ fetch เพื่อดึงข้อมูลสินค้าตาม id ที่ส่งมา
                fetch('../../product/edit_product/get_food_all/get_snack.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        // แสดงข้อมูลในฟอร์ม
                        document.getElementById('edit_product_id_snackfood').value = data.id;
                        document.getElementById('imagePreviewSnackFood').src = data.image_url;
                        document.getElementById('product_name_snackfood').value = data.product_name;
                        document.getElementById('barcode_snackfood').value = data.barcode || '';
                        document.getElementById('price_snackfood').value = data.price;
                        document.getElementById('cost_snackfood').value = data.cost;
                        document.getElementById('stock_snackfood').value = data.stock;
                        document.getElementById('reorder_level_snackfood').value = data.reorder_level;
                    })
                    .catch(error => {
                        console.error('เกิดข้อผิดพลาดในการโหลดข้อมูลสินค้า:', error);
                    });
            } else {
                console.error("ไม่พบ overlay หรือ editPanel ใน DOM");
            }
        }

        function closeEditPanelSnackFood() {
            const overlay = document.getElementById('overlaySnackFood');
            const editPanel = document.getElementById('editPanelSnackFood');

            overlay.style.display = 'none';
            editPanel.style.right = '-500px';
            setTimeout(() => {
                editPanel.style.display = 'none';
            }, 300); // ต้องรอให้ side panel เลื่อนออกไปก่อนค่อยปิด display

            // เปิดการเลื่อนหน้าจอเมื่อปิด editPanel
            document.body.style.overflow = 'auto';
        }

        function deleteProduct(id, type) {
            const modal = document.getElementById("confirmDeleteProductModal");
            const message = document.getElementById("confirmDeleteMessage");
            const confirmBtn = document.getElementById("btnConfirmDeleteProduct");
            const cancelBtn = document.getElementById("btnCancelDeleteProduct");

            const endpoints = {
                dried: "/sci-next/product/delete_product/delete_dried_food.php",
                soft: "/sci-next/product/delete_product/delete_soft_drink.php",
                fresh: "/sci-next/product/delete_product/delete_fresh_food.php",
                snack: "/sci-next/product/delete_product/delete_snack.php",
            };

            // ตรวจสอบว่าประเภทสินค้ามี endpoint รองรับ
            if (!endpoints[type]) {
                console.error("ไม่พบประเภทสินค้า:", type);
                showAlertToast("ประเภทสินค้าที่เลือกไม่ถูกต้อง", icons.error, "alert-toast");
                return;
            }

            message.textContent = "คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้?";
            modal.classList.remove("hidden");

            // รีเซ็ต event listener
            confirmBtn.replaceWith(confirmBtn.cloneNode(true));
            cancelBtn.replaceWith(cancelBtn.cloneNode(true));

            const newConfirmBtn = document.getElementById("btnConfirmDeleteProduct");
            const newCancelBtn = document.getElementById("btnCancelDeleteProduct");

            newConfirmBtn.addEventListener("click", () => {
                modal.classList.add("hidden");

                fetch(endpoints[type], {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `id=${encodeURIComponent(id)}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showAlertToast(data.message, icons.success, "success-toast");
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showAlertToast(data.message, icons.error, "alert-toast");
                        }
                    })
                    .catch(err => {
                        console.error("Delete failed:", err);
                        showAlertToast("ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้", icons.error, "alert-toast");
                    });
            });

            newCancelBtn.addEventListener("click", () => {
                modal.classList.add("hidden");
            });

            window.addEventListener("click", (event) => {
                if (event.target === modal) {
                    modal.classList.add("hidden");
                }
            });
        }


        function toggleMenu() {
            const filterMenu = document.getElementById('filterMenu');
            filterMenu.style.display = (filterMenu.style.display === 'block') ? 'none' : 'block';
        }

        function applyFilters() {
            const category = document.getElementById('filterCategory').value;
            const price = document.getElementById('filterPrice').value;

            console.log(`Applied Filters - Category: ${category}, Max Price: ${price}`);
        }

        function toggleMenuSoftDrink() {
            const filterMenu = document.getElementById('filterMenuSoftDrink');
            filterMenu.style.display = (filterMenu.style.display === 'block') ? 'none' : 'block';
        }

        function applyFiltersSoftDrink() {
            const category = document.getElementById('filterCategorySoftDrink').value;
            const price = document.getElementById('filterPrice').value;

            console.log(`Applied Filters - Category: ${category}, Max Price: ${price}`);
        }

        function toggleMenuFreshFood() {
            const filterMenu = document.getElementById('filterMenuFreshFood');
            filterMenu.style.display = (filterMenu.style.display === 'block') ? 'none' : 'block';
        }

        function applyFiltersFreshFood() {
            const category = document.getElementById('filterCategoryFreshFood').value;
            const price = document.getElementById('filterPrice').value;

            console.log(`Applied Filters - Category: ${category}, Max Price: ${price}`);
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
                        <td style="text-align:left; font-weight: bold;">${highlightText(item.product_name, searchText)}</td>
                        <td>
                            <div class="product-image-container">
                                <img src="${item.image_url}" alt="${item.product_name}" width="100">
                                ${item.stock <= item.reorder_level ? `
                                <div class="out-of-stock-label-table">สินค้าใกล้หมด</div>
                                ` : ''}
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
                        <td>${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} บาท</td>
                        <td>${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} บาท</td>
                        <td>${item.stock} ชิ้น</td>
                        <td>${item.reorder_level} ชิ้น</td>
                        <td>
                            <div class="action-dropdown">
                                <button onclick="toggleDropdown(this)">⋮</button>
                                <div class="dropdown-content">
                                    <a onclick="openEditPanel(${item.id}); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</a>
                                    <a onclick="deleteProduct(${item.id}, 'dried'); return false;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                                    </svg>
                                    ลบ
                                    </a>
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
                            ${item.stock <= item.reorder_level ? `
                            <div class="out-of-stock-label">สินค้าใกล้หมด</div>
                        ` : ''}
                        </div>
                        <div class="card-content">
                            <h3 class="name-grid">${highlightText(item.product_name, searchText)}</h3>
                            <div class="barcode">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg>
                                <span>${highlightText(item.barcode, searchText)}</span>
                            </div>
                            <p><span class="label">ราคา:</span> ${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} บาท</p>
                            <p><span class="label">ต้นทุน:</span> ${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} บาท</p>
                            <p><span class="label">สต็อก:</span> ${item.stock} ชิ้น</p>
                            <p><span class="label">เกณฑ์สั่งซื้อ:</span> ${item.reorder_level} ชิ้น</p>
                            <div class="card-actions">
                                <button onclick="openEditPanel(${item.id})"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</button>
                                <a onclick="deleteProduct(${item.id}, 'dried'); return false;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
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
            const searchText = document.getElementById('searchInput').value.trim().toLowerCase();

            if (!searchText) {
                filteredProducts = [...products];
            } else {
                filteredProducts = products.filter(item => {
                    const name = (item.product_name || '').toLowerCase();
                    const barcode = (item.barcode || '').toLowerCase();

                    const nameMatch = name.includes(searchText);
                    const barcodeMatch = barcode.includes(searchText); // ค้นหาง่ายขึ้น: ไม่จำกัดแค่ท้าย

                    return nameMatch || barcodeMatch;
                });

                // จัดลำดับ: barcode ที่ endsWith จะมาก่อน
                filteredProducts.sort((a, b) => {
                    const aEnds = a.barcode.toLowerCase().endsWith(searchText);
                    const bEnds = b.barcode.toLowerCase().endsWith(searchText);
                    return (bEnds - aEnds); // true = 1, false = 0
                });
            }

            currentPage = 1;
            displayProducts();
        }



        function switchView(viewType) {
            currentView = viewType; // บันทึกมุมมองที่เลือก
            localStorage.setItem('viewMode', viewType);
            container.scrollIntoView({
                behavior: 'smooth'
            })
            displayProducts();
        }

        // โหลดครั้งแรก
        displayProducts();

        window.onscroll = function() {
            toggleScrollButton();
        };

        // ฟังก์ชันโชว์/ซ่อนปุ่ม
        function toggleScrollButton() {
            const btn = document.getElementById("scrollTopBtn");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        }

        // ฟังก์ชันเลื่อนขึ้นบนสุด
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // เรียกฟังก์ชันเมื่อ scroll
        window.addEventListener("scroll", toggleScrollButton);

        // ตรวจสอบสถานะตอนโหลดหน้า (เผื่อโหลดในตำแหน่งเลื่อน)
        document.addEventListener("DOMContentLoaded", toggleScrollButton);


        // ฟังก์ชันสำหรับการค้นหาสินค้า Soft Drink
        const productsSoftDrink = <?php echo json_encode($soft_drink); ?>;
        const tableBodySoftDrink = document.getElementById('productTableBodySoftDrink');
        const gridSoftDrink = document.getElementById('productGridSoftDrink');
        const paginationSoftDrink = document.getElementById('paginationSoftDrink');
        const containerSoftDrink = document.getElementById('containerSoftDrink');
        const searchInputSoftDrink = document.getElementById('searchInputSoftDrink');

        let filteredProductsSoftDrink = [...productsSoftDrink];
        let currentPageSoftDrink = 1;
        const itemsPerPageSoftDrink = 10;
        let searchTextSoftDrink = '';
        let currentViewSoftDrink = localStorage.getItem('viewModeSoftDrink') || 'table';

        function highlightTextSoftDrink(text, keyword) {
            if (!keyword) return text;
            const pattern = new RegExp(`(${keyword})`, 'gi');
            return text.replace(pattern, '<span class="highlight">$1</span>');
        }

        function displayProductsSoftDrink() {
            tableBodySoftDrink.innerHTML = '';
            gridSoftDrink.innerHTML = '';

            const startIndex = (currentPageSoftDrink - 1) * itemsPerPageSoftDrink;
            const endIndex = startIndex + itemsPerPageSoftDrink;
            const paginatedItems = filteredProductsSoftDrink.slice(startIndex, endIndex);

            if (currentViewSoftDrink === 'table') {
                document.getElementById('productTableSoftDrink').style.display = 'table';
                gridSoftDrink.style.display = 'none';

                if (paginatedItems.length === 0) {
                    tableBodySoftDrink.innerHTML = `
            <tr><td colspan="8" class="no-items-message">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
                ไม่พบสินค้าที่ค้นหา
            </td></tr>`;
                } else {
                    paginatedItems.forEach(item => {
                        tableBodySoftDrink.innerHTML += `
                <tr>
                    <td style="text-align:left; font-weight: bold;">${highlightTextSoftDrink(item.product_name, searchTextSoftDrink)}</td>
                    <td>
                        <div class="product-image-container">
                            <img src="${item.image_url}" alt="${item.product_name}" width="100">
                            ${item.stock <= item.reorder_level ? `<div class="out-of-stock-label-table">สินค้าใกล้หมด</div>` : ''}
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <div class="barcode-cell">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg>
                            <span>${highlightTextSoftDrink(item.barcode, searchTextSoftDrink)}</span>
                        </div>
                    </td>
                    <td>${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</td>
                    <td>${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</td>
                    <td>${item.stock} ชิ้น</td>
                    <td>${item.reorder_level} ชิ้น</td>
                    <td>
                        <div class="action-dropdown">
                            <button onclick="toggleDropdownSoftDrink(this)">⋮</button>
                            <div class="dropdown-content">
                                <a onclick="openEditPanelSoftDrink(${item.id}); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</a>
                                <a onclick="deleteProduct(${item.id}, 'soft'); return false;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>ลบ</a>
                            </div>
                        </div>
                    </td>
                </tr>`;
                    });
                }
            } else {
                document.getElementById('productTableSoftDrink').style.display = 'none';
                gridSoftDrink.style.display = 'grid';

                if (paginatedItems.length === 0) {
                    gridSoftDrink.innerHTML = `<p style="grid-column: 1 / -1; text-align: center; display: flex; align-items: center; justify-content: center; gap: 2px; ">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333" style="margin-right: 10px;">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
            ไม่พบสินค้าที่ค้นหา
        </p>`;
                } else {
                    paginatedItems.forEach(item => {
                        gridSoftDrink.innerHTML += `
                <div class="card">
                    <div class="product-image-container">
                        <img class="product-image" src="${item.image_url}" alt="${item.product_name}">
                        ${item.stock <= item.reorder_level ? `<div class="out-of-stock-label">สินค้าใกล้หมด</div>` : ''}
                    </div>
                    <div class="card-content">
                        <h3 class="name-grid">${highlightTextSoftDrink(item.product_name, searchTextSoftDrink)}</h3>
                        <div class="barcode"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg><span>${highlightTextSoftDrink(item.barcode, searchTextSoftDrink)}</span></div>
                        <p><span class="label">ราคา:</span> ${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</p>
                        <p><span class="label">ต้นทุน:</span> ${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</p>
                        <p><span class="label">สต็อก:</span> ${item.stock} ชิ้น</p>
                        <p><span class="label">เกณฑ์สั่งซื้อ:</span> ${item.reorder_level} ชิ้น</p>
                        <div class="card-actions">
                            <button onclick="openEditPanelSoftDrink(${item.id})">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</button>
                            <a onclick="deleteProduct(${item.id}, 'soft'); return false;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>ลบ</a>
                        </div>
                    </div>
                </div>`;
                    });
                }
            }

            renderPaginationSoftDrink();
        }

        function renderPaginationSoftDrink() {
            paginationSoftDrink.innerHTML = '';

            const totalPages = Math.ceil(filteredProductsSoftDrink.length / itemsPerPageSoftDrink);
            if (totalPages <= 1) return;

            if (currentPageSoftDrink > 1) {
                paginationSoftDrink.innerHTML += `<button class="page-btn" onclick="goToPageSoftDrink(${currentPageSoftDrink - 1})">ก่อนหน้า</button>`;
            }

            let startPage = Math.max(1, currentPageSoftDrink - 2);
            let endPage = Math.min(totalPages, startPage + 4);

            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            for (let i = startPage; i <= endPage; i++) {
                paginationSoftDrink.innerHTML += `<button class="page-btn ${i === currentPageSoftDrink ? 'active' : ''}" onclick="goToPageSoftDrink(${i})">${i}</button>`;
            }

            if (currentPageSoftDrink < totalPages) {
                paginationSoftDrink.innerHTML += `<button class="page-btn" onclick="goToPageSoftDrink(${currentPageSoftDrink + 1})">ถัดไป</button>`;
            }
        }

        function goToPageSoftDrink(page) {
            currentPageSoftDrink = page;
            displayProductsSoftDrink();
        }

        function filterProductsSoftDrink() {
            searchTextSoftDrink = searchInputSoftDrink.value.trim().toLowerCase();

            if (!searchTextSoftDrink) {
                filteredProductsSoftDrink = [...productsSoftDrink];
            } else {
                filteredProductsSoftDrink = productsSoftDrink.filter(item => {
                    const name = (item.product_name || '').toLowerCase();
                    const barcode = (item.barcode || '').toLowerCase();

                    return name.includes(searchTextSoftDrink) || barcode.includes(searchTextSoftDrink);
                });

                // เรียงลำดับให้ barcode ที่ลงท้ายด้วย searchText แสดงก่อน
                filteredProductsSoftDrink.sort((a, b) => {
                    const aEnds = a.barcode.toLowerCase().endsWith(searchTextSoftDrink) ? 1 : 0;
                    const bEnds = b.barcode.toLowerCase().endsWith(searchTextSoftDrink) ? 1 : 0;
                    return bEnds - aEnds;
                });
            }

            currentPageSoftDrink = 1;
            displayProductsSoftDrink();
        }

        function switchViewSoftDrink(viewType) {
            currentViewSoftDrink = viewType;
            localStorage.setItem('viewModeSoftDrink', viewType);
            containerSoftDrink.scrollIntoView({
                behavior: 'smooth'
            });
            displayProductsSoftDrink();
        }

        displayProductsSoftDrink();


        window.onscroll = function() {
            toggleScrollButtonSoftDrink();
        };

        // ฟังก์ชันโชว์/ซ่อนปุ่ม
        function toggleScrollButtonSoftDrink() {
            const btn = document.getElementById("scrollTopBtnSoftDrink");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        }

        // ฟังก์ชันเลื่อนขึ้นบนสุด
        function scrollToTopSoftDrink() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        window.addEventListener("scroll", toggleScrollButtonSoftDrink);

        document.addEventListener("DOMContentLoaded", toggleScrollButtonSoftDrink);


        // ฟังก์ชันสำหรับการค้นหาสินค้า Fresh Food
        const productsFreshFood = <?php echo json_encode($fresh_food); ?>;
        const tableBodyFreshFood = document.getElementById('productTableBodyFreshFood');
        const gridFreshFood = document.getElementById('productGridFreshFood');
        const paginationFreshFood = document.getElementById('paginationFreshFood');
        const containerFreshFood = document.getElementById('containerFreshFood');
        const searchInputFreshFood = document.getElementById('searchInputFreshFood');
        let filteredProductsFreshFood = [...productsFreshFood];
        let currentPageFreshFood = 1;
        const itemsPerPageFreshFood = 10;
        let searchTextFreshFood = '';
        let currentViewFreshFood = localStorage.getItem('viewModeFreshFood') || 'table';

        function highlightTextFreshFood(text, keyword) {
            if (!keyword) return text;
            const pattern = new RegExp(`(${keyword})`, 'gi');
            return text.replace(pattern, '<span class="highlight">$1</span>');
        }

        function displayProductsFreshFood() {
            tableBodyFreshFood.innerHTML = '';
            gridFreshFood.innerHTML = '';

            const startIndex = (currentPageFreshFood - 1) * itemsPerPageFreshFood;
            const endIndex = startIndex + itemsPerPageFreshFood;
            const paginatedItems = filteredProductsFreshFood.slice(startIndex, endIndex);

            if (currentViewFreshFood === 'table') {
                document.getElementById('productTableFreshFood').style.display = 'table';
                gridFreshFood.style.display = 'none';

                if (paginatedItems.length === 0) {
                    tableBodyFreshFood.innerHTML = `
            <tr><td colspan="8" class="no-items-message">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
                ไม่พบสินค้าที่ค้นหา
            </td></tr>`;
                } else {
                    paginatedItems.forEach(item => {
                        tableBodyFreshFood.innerHTML += `
                <tr>
                    <td style="text-align:left; font-weight: bold;">${highlightTextFreshFood(item.product_name, searchTextFreshFood)}</td>
                    <td>
                        <div class="product-image-container">
                            <img src="${item.image_url}" alt="${item.product_name}" width="100">
                            ${item.stock <= item.reorder_level ? `<div class="out-of-stock-label-table">สินค้าใกล้หมด</div>` : ''}
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <div class="barcode-cell">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg>
                            <span>${highlightTextFreshFood(item.barcode, searchTextFreshFood)}</span>
                        </div>
                    </td>
                    <td>${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</td>
                    <td>${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</td>
                    <td>${item.stock} ชิ้น</td>
                    <td>${item.reorder_level} ชิ้น</td>
                    <td>
                        <div class="action-dropdown">
                            <button onclick="toggleDropdownFreshFood(this)">⋮</button>
                            <div class="dropdown-content">
                                <a onclick="openEditPanelFreshFood(${item.id}); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</a>
                                <a onclick="deleteProduct(${item.id}, 'fresh'); return false;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>ลบ</a>
                            </div>  
                        </div>
                    </td>
                </tr>`;
                    });
                }
            } else {
                document.getElementById('productTableFreshFood').style.display = 'none';
                gridFreshFood.style.display = 'grid';

                if (paginatedItems.length === 0) {
                    gridFreshFood.innerHTML = `<p style="grid-column: 1 / -1; text-align: center; display: flex; align-items: center; justify-content: center; gap: 2px; ">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333" style="margin-right: 10px;">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
            ไม่พบสินค้าที่ค้นหา
        </p>`;
                } else {
                    paginatedItems.forEach(item => {
                        gridFreshFood.innerHTML += `
                <div class="card">
                    <div class="product-image-container">
                        <img class="product-image" src="${item.image_url}" alt="${item.product_name}">
                        ${item.stock <= item.reorder_level ? `<div class="out-of-stock-label">สินค้าใกล้หมด</div>` : ''}
                    </div>
                    <div class="card-content">
                        <h3 class="name-grid">${highlightTextFreshFood(item.product_name, searchTextFreshFood)}</h3>
                        <div class="barcode">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg><span>${highlightTextFreshFood(item.barcode, searchTextFreshFood)}</span></div>
                        <p><span class="label">ราคา:</span> ${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</p>
                        <p><span class="label">ต้นทุน:</span> ${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</p>
                        <p><span class="label">สต็อก:</span> ${item.stock} ชิ้น</p>
                        <p><span class="label">เกณฑ์สั่งซื้อ:</span> ${item.reorder_level} ชิ้น</p>
                        <div class="card-actions">
                            <button onclick="openEditPanelFreshFood(${item.id})"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</button>
                            <a onclick="deleteProduct(${item.id}, 'fresh'); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>ลบ</a>
                        </div>
                    </div>
                </div>`;
                    });
                }
            }
            renderPaginationFreshFood();
        }

        function renderPaginationFreshFood() {
            paginationFreshFood.innerHTML = '';

            const totalPages = Math.ceil(filteredProductsFreshFood.length / itemsPerPageFreshFood);
            if (totalPages <= 1) return;

            if (currentPageFreshFood > 1) {
                paginationFreshFood.innerHTML += `<button class="page-btn" onclick="goToPageFreshFood(${currentPageFreshFood - 1})">ก่อนหน้า</button>`;
            }

            let startPage = Math.max(1, currentPageFreshFood - 2);
            let endPage = Math.min(totalPages, startPage + 4);

            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            for (let i = startPage; i <= endPage; i++) {
                paginationFreshFood.innerHTML += `<button class="page-btn ${i === currentPageFreshFood ? 'active' : ''}" onclick="goToPageFreshFood(${i})">${i}</button>`;
            }

            if (currentPageFreshFood < totalPages) {
                paginationFreshFood.innerHTML += `<button class="page-btn" onclick="goToPageFreshFood(${currentPageFreshFood + 1})">ถัดไป</button>`;
            }
        }

        function goToPageFreshFood(page) {
            currentPageFreshFood = page;
            displayProductsFreshFood();
        }

        function filterProductsFreshFood() {
            searchTextFreshFood = searchInputFreshFood.value.trim().toLowerCase();

            if (!searchTextFreshFood) {
                filteredProductsFreshFood = [...productsFreshFood];
            } else {
                filteredProductsFreshFood = productsFreshFood.filter(item => {
                    const name = (item.product_name || '').toLowerCase();
                    const barcode = (item.barcode || '').toLowerCase();

                    return name.includes(searchTextFreshFood) || barcode.includes(searchTextFreshFood);
                });

                filteredProductsFreshFood.sort((a, b) => {
                    const aEnds = a.barcode.toLowerCase().endsWith(searchTextFreshFood) ? 1 : 0;
                    const bEnds = b.barcode.toLowerCase().endsWith(searchTextFreshFood) ? 1 : 0;
                    return bEnds - aEnds;
                });
            }

            currentPageFreshFood = 1;
            displayProductsFreshFood();
        }

        function switchViewFreshFood(viewType) {
            currentViewFreshFood = viewType;
            localStorage.setItem('viewModeFreshFood', viewType);
            containerFreshFood.scrollIntoView({
                behavior: 'smooth'
            });
            displayProductsFreshFood();
        }
        displayProductsFreshFood();

        window.onscroll = function() {
            toggleScrollButtonFreshFood();
        };
        // ฟังก์ชันโชว์/ซ่อนปุ่ม
        function toggleScrollButtonFreshFood() {
            const btn = document.getElementById("scrollTopBtnFreshFood");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        }
        // ฟังก์ชันเลื่อนขึ้นบนสุด
        function scrollToTopFreshFood() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        window.addEventListener("scroll", toggleScrollButtonFreshFood);

        document.addEventListener("DOMContentLoaded", toggleScrollButtonFreshFood);


        // ฟังก์ชันสำหรับการค้นหาสินค้า SnackFood
        const productsSnackFood = <?php echo json_encode($snack); ?>;
        const tableBodySnackFood = document.getElementById('productTableBodySnackFood');
        const gridSnackFood = document.getElementById('productGridSnackFood');
        const paginationSnackFood = document.getElementById('paginationSnackFood');
        const containerSnackFood = document.getElementById('containerSnackFood');
        const searchInputSnackFood = document.getElementById('searchInputSnackFood');
        let filteredProductsSnackFood = [...productsSnackFood];
        let currentPageSnackFood = 1;
        const itemsPerPageSnackFood = 10;
        let searchTextSnackFood = '';
        let currentViewSnackFood = localStorage.getItem('viewModeSnackFood') || 'table';

        function highlightTextSnackFood(text, keyword) {
            if (!keyword) return text;
            const pattern = new RegExp(`(${keyword})`, 'gi');
            return text.replace(pattern, '<span class="highlight">$1</span>');
        }

        function displayProductsSnackFood() {
            tableBodySnackFood.innerHTML = '';
            gridSnackFood.innerHTML = '';

            const startIndex = (currentPageSnackFood - 1) * itemsPerPageSnackFood;
            const endIndex = startIndex + itemsPerPageSnackFood;
            const paginatedItems = filteredProductsSnackFood.slice(startIndex, endIndex);

            if (currentViewSnackFood === 'table') {
                document.getElementById('productTableSnackFood').style.display = 'table';
                gridSnackFood.style.display = 'none';

                if (paginatedItems.length === 0) {
                    tableBodySnackFood.innerHTML = `
        <tr><td colspan="8" class="no-items-message">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
            ไม่พบสินค้าที่ค้นหา
        </td></tr>`;
                } else {
                    paginatedItems.forEach(item => {
                        tableBodySnackFood.innerHTML += `
            <tr>
                <td style="text-align:left; font-weight: bold;">${highlightTextSnackFood(item.product_name, searchTextSnackFood)}</td>
                <td>
                    <div class="product-image-container">
                        <img src="${item.image_url}" alt="${item.product_name}" width="100">
                        ${item.stock <= item.reorder_level ? `<div class="out-of-stock-label-table">สินค้าใกล้หมด</div>` : ''}
                    </div>
                </td>
                <td style="text-align:center;">
                    <div class="barcode-cell">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg>
                        <span>${highlightTextSnackFood(item.barcode, searchTextSnackFood)}</span>
                    </div>
                </td>
                <td>${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</td>
                <td>${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</td>
                <td>${item.stock} ชิ้น</td>
                <td>${item.reorder_level} ชิ้น</td>
                <td>
                    <div class="action-dropdown">
                        <button onclick="toggleDropdownSnackFood(this)">⋮</button>
                        <div class="dropdown-content">
                            <a onclick="openEditPanelSnackFood(${item.id}); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</a>
                            <a onclick="deleteProduct(${item.id}, 'snack'); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>ลบ</a>
                        </div>  
                    </div>
                </td>
            </tr>`;
                    });
                }
            } else {
                document.getElementById('productTableSnackFood').style.display = 'none';
                gridSnackFood.style.display = 'grid';

                if (paginatedItems.length === 0) {
                    gridSnackFood.innerHTML = `<p style="grid-column: 1 / -1; text-align: center; display: flex; align-items: center; justify-content: center; gap: 2px; ">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#333333" style="margin-right: 10px;">
                <path d="M280-80q-83 0-141.5-58.5T80-280q0-83 58.5-141.5T280-480q83 0 141.5 58.5T480-280q0 83-58.5 141.5T280-80Zm544-40L568-376q-12-13-25.5-26.5T516-428q38-24 61-64t23-88q0-75-52.5-127.5T420-760q-75 0-127.5 52.5T240-580q0 6 .5 11.5T242-557q-18 2-39.5 8T164-535q-2-11-3-22t-1-23q0-109 75.5-184.5T420-840q109 0 184.5 75.5T680-580q0 43-13.5 81.5T629-428l251 252-56 56Zm-615-61 71-71 70 71 29-28-71-71 71-71-28-28-71 71-71-71-28 28 71 71-71 71 28 28Z"/>
            </svg>
            ไม่พบสินค้าที่ค้นหา
        </p>`;
                } else {
                    paginatedItems.forEach(item => {
                        gridSnackFood.innerHTML += `
            <div class="card">
                <div class="product-image-container">
                    <img class="product-image" src="${item.image_url}" alt="${item.product_name}">
                    ${item.stock <= item.reorder_level ? `<div class="out-of-stock-label">สินค้าใกล้หมด</div>` : ''}
                </div>
                <div class="card-content">
                    <h3 class="name-grid">${highlightTextSnackFood(item.product_name, searchTextSnackFood)}</h3>
                    <div class="barcode">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                    <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                </svg><span>${highlightTextSnackFood(item.barcode, searchTextSnackFood)}</span></div>
                    <p><span class="label">ราคา:</span> ${Number(item.price).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</p>
                    <p><span class="label">ต้นทุน:</span> ${Number(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2 })} บาท</p>
                    <p><span class="label">สต็อก:</span> ${item.stock} ชิ้น</p>
                    <p><span class="label">เกณฑ์สั่งซื้อ:</span> ${item.reorder_level} ชิ้น</p>
                    <div class="card-actions">
                        <button onclick="openEditPanelSnackFood(${item.id})"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M200-440h240v-160H200v160Zm0-240h560v-80H200v80Zm0 560q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v252q-19-8-39.5-10.5t-40.5.5q-21 4-40.5 13.5T684-479l-39 39-205 204v116H200Zm0-80h240v-160H200v160Zm320-240h125l39-39q16-16 35.5-25.5T760-518v-82H520v160Zm0 360v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-300L643-80H520Zm300-263-37-37 37 37Z" />
                                        </svg>แก้ไข</button>
                        <a onclick="deleteProduct(${item.id}, 'snack'); return false;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                                            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                        </svg>ลบ</a>
                    </div>
                </div>
            </div>`;
                    });
                }
            }
            renderPaginationSnackFood();
        }

        function renderPaginationSnackFood() {
            paginationSnackFood.innerHTML = '';

            const totalPages = Math.ceil(filteredProductsSnackFood.length / itemsPerPageSnackFood);
            if (totalPages <= 1) return;

            if (currentPageSnackFood > 1) {
                paginationSnackFood.innerHTML += `<button class="page-btn" onclick="goToPageSnackFood(${currentPageSnackFood - 1})">ก่อนหน้า</button>`;
            }

            let startPage = Math.max(1, currentPageSnackFood - 2);
            let endPage = Math.min(totalPages, startPage + 4);

            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            for (let i = startPage; i <= endPage; i++) {
                paginationSnackFood.innerHTML += `<button class="page-btn ${i === currentPageSnackFood ? 'active' : ''}" onclick="goToPageSnackFood(${i})">${i}</button>`;
            }

            if (currentPageSnackFood < totalPages) {
                paginationSnackFood.innerHTML += `<button class="page-btn" onclick="goToPageSnackFood(${currentPageSnackFood + 1})">ถัดไป</button>`;
            }
        }

        function goToPageSnackFood(page) {
            currentPageSnackFood = page;
            displayProductsSnackFood();
        }

        function filterProductsSnackFood() {
            searchTextSnackFood = searchInputSnackFood.value.trim().toLowerCase();

            if (!searchTextSnackFood) {
                filteredProductsSnackFood = [...productsSnackFood];
            } else {
                filteredProductsSnackFood = productsSnackFood.filter(item => {
                    const name = (item.product_name || '').toLowerCase();
                    const barcode = (item.barcode || '').toLowerCase();

                    return name.includes(searchTextSnackFood) || barcode.includes(searchTextSnackFood);
                });

                filteredProductsSnackFood.sort((a, b) => {
                    const aEnds = a.barcode.toLowerCase().endsWith(searchTextSnackFood) ? 1 : 0;
                    const bEnds = b.barcode.toLowerCase().endsWith(searchTextSnackFood) ? 1 : 0;
                    return bEnds - aEnds;
                });
            }

            currentPageSnackFood = 1;
            displayProductsSnackFood();
        }

        function switchViewSnackFood(viewType) {
            currentViewSnackFood = viewType;
            localStorage.setItem('viewModeSnackFood', viewType);
            containerSnackFood.scrollIntoView({
                behavior: 'smooth'
            });
            displayProductsSnackFood();
        }

        displayProductsSnackFood();

        window.onscroll = function() {
            toggleScrollButtonSnackFood();
        };

        // ฟังก์ชันโชว์/ซ่อนปุ่ม
        function toggleScrollButtonSnackFood() {
            const btn = document.getElementById("scrollTopBtnSnackFood");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        }

        // ฟังก์ชันเลื่อนขึ้นบนสุด
        function scrollToTopSnackFood() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        window.addEventListener("scroll", toggleScrollButtonSnackFood);

        document.addEventListener("DOMContentLoaded", toggleScrollButtonSnackFood);

        // ฟังก์ชันสำหรับการเลือกหมวดหมู่สินค้า
        let selectedCategory = '';

        function selectCategory(event, category) {
            selectedCategory = category;
            showAlertToast(`เลือกหมวดหมู่สินค้า : ${category}`, icons.success, 'success');
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
                showAlertToast("กรุณาเลือกหมวดหมู่สินค้าก่อน!", icons.error, 'error');
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
                sendDataToTable(
                    selectedCategory,
                    productName,
                    productImage,
                    barcode,
                    productPrice,
                    productCost,
                    productStock,
                    productReorderLevel
                );

                document.getElementById('uploadForm').reset(); // รีเซ็ตฟอร์ม

                showAlertToast("อัปโหลดสินค้าสำเร็จ!", icons.success, 'success'); // ✅ แสดง Toast

                setTimeout(() => {
                    location.reload(); // ✅ รีเฟรชหลัง 1 วิ
                }, 1000);
            } else {
                showAlertToast("กรุณากรอกข้อมูลให้ครบถ้วน!", icons.error, 'error');
            }
        }


        function sendDataToTable(category, productName, productImage, barcode, productPrice, productCost, productStock, productReorderLevel) {
            const tableBody = document.getElementById(`${category}_table`);

            if (!tableBody) {
                showAlertToast("ไม่พบตารางสำหรับหมวดหมู่นี้!", icons.error, 'error');
                return;
            }

            // สร้างแถวใหม่ในตาราง
            const row = document.createElement('tr');
            row.innerHTML = `
            <td><input type="checkbox" class="row-checkbox" data-product="${productName}" onchange="updateCheckboxStatus(event, '${category}')"></td>
            <td>${productName}</td>
            <td><img src="${productImage}" alt="${productName}" style="width: 100px; height: auto;"></td>
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
                    <td><img src="${data.productImage}" alt="${data.productName}" style="width: 100px; height: auto;"></td>
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

        function sendProductsToServer(selectedProducts, url, storageKey) {
            console.log("ส่งข้อมูลสินค้า:", selectedProducts);

            fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        products: selectedProducts
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlertToast("เพิ่มสินค้าสำเร็จ!", icons.success, "success-toast");

                        // ✅ ลบข้อมูลใน localStorage หลังเพิ่มสำเร็จ
                        localStorage.removeItem(storageKey);

                        setTimeout(() => location.reload(), 1000);
                        document.querySelectorAll(".row-checkbox:checked").forEach(cb => cb.closest("tr").remove());
                    } else {
                        showAlertToast("เกิดข้อผิดพลาด: " + data.message, icons.error, "alert-toast");
                    }
                })
                .catch(err => {
                    showAlertToast("เกิดข้อผิดพลาดในการส่งข้อมูล", icons.error, "alert-toast");
                    console.error(err);
                });
        }

        function setupProductButton(className, storageKey, postUrl) {
            document.querySelectorAll(className).forEach(button => {
                button.addEventListener("click", () => {
                    let storedData = JSON.parse(localStorage.getItem(storageKey) || "[]");

                    if (!Array.isArray(storedData) || storedData.length === 0) {
                        showAlertToast("ไม่มีข้อมูลที่จะเพิ่มสินค้า", icons.error, "alert-toast");
                        return;
                    }

                    let selectedProducts = [];
                    document.querySelectorAll(".row-checkbox:checked").forEach(checkbox => {
                        let row = checkbox.closest("tr");
                        selectedProducts.push({
                            productName: row.children[1].textContent.trim(),
                            productImage: row.children[2].querySelector("img")?.src || "",
                            barcode: row.children[3].textContent.trim(),
                            productPrice: parseFloat(row.children[4].textContent.replace(" บาท", "").trim()),
                            productCost: parseFloat(row.children[5].textContent.replace(" บาท", "").trim()),
                            productStock: parseInt(row.children[6].textContent.replace(" ชิ้น", "").trim()),
                            productReorderLevel: parseInt(row.children[7].textContent.replace(" ชิ้น", "").trim()),
                        });
                    });

                    if (selectedProducts.length === 0) {
                        showAlertToast("กรุณาเลือกสินค้าอย่างน้อยหนึ่งรายการ", icons.select, "alert-toast");
                        return;
                    }

                    const modal = document.getElementById("addProductConfirmModal");
                    modal.classList.remove("hidden");

                    const confirmBtn = document.getElementById("confirmAddProductBtn");
                    const cancelBtn = document.getElementById("cancelAddProductBtn");

                    confirmBtn.replaceWith(confirmBtn.cloneNode(true));
                    cancelBtn.replaceWith(cancelBtn.cloneNode(true));

                    const newConfirmBtn = document.getElementById("confirmAddProductBtn");
                    const newCancelBtn = document.getElementById("cancelAddProductBtn");

                    newConfirmBtn.addEventListener("click", () => {
                        modal.classList.add("hidden");
                        sendProductsToServer(selectedProducts, postUrl, storageKey); // ✅ ส่ง storageKey ไปด้วย
                    });

                    newCancelBtn.addEventListener("click", () => {
                        modal.classList.add("hidden");
                    });
                });
            });
        }

        // เรียกใช้งานกับประเภทต่าง ๆ
        setupProductButton(".btn-dried-food", "dried_food", "/sci-next/product/upload_product/add_food_all/add_dried_food.php");
        setupProductButton(".btn-soft-drink", "soft_drink", "/sci-next/product/upload_product/add_food_all/add_soft_drink.php");
        setupProductButton(".btn-fresh-food", "fresh_food", "/sci-next/product/upload_product/add_food_all/add_fresh_food.php");
        setupProductButton(".btn-snack", "snack", "/sci-next/product/upload_product/add_food_all/add_snack.php");

        // ปิด modal เมื่อคลิกข้างนอก
        window.addEventListener("click", function(event) {
            const modal = document.getElementById("addProductConfirmModal");
            if (event.target === modal) {
                modal.classList.add("hidden");
            }
        });


        // ฟังก์ชันสำหรับเปิด Modal แก้ไขสินค้า localStorage
        let editingBarcode = null;

        // เริ่มทำงานเมื่อกดปุ่ม "แก้ไขสินค้า"
        document.querySelectorAll(".btn-edit-product-localStorage").forEach(button => {
            button.addEventListener("click", function() {
                const key = this.dataset.key;
                const storedData = JSON.parse(localStorage.getItem(key)) || [];

                // ✅ ไม่มีข้อมูลใน localStorage
                if (storedData.length === 0) {
                    showAlertToast("ไม่มีข้อมูลที่จะแก้ไข", icons.error, "alert-toast");
                    return;
                }

                const checkedBoxes = document.querySelectorAll(".row-checkbox:checked");

                // ✅ ไม่ได้เลือกเลย
                if (checkedBoxes.length === 0) {
                    showAlertToast("กรุณาเลือกสินค้าที่ต้องการแก้ไข", icons.select, "alert-toast");
                    return;
                }

                // ✅ เลือกมากกว่า 1 รายการ
                if (checkedBoxes.length > 1) {
                    showAlertToast("กรุณาเลือกสินค้าเพียง 1 รายการเพื่อแก้ไข", icons.select, "alert-toast");
                    return;
                }

                const checked = checkedBoxes[0];
                const row = checked.closest("tr");

                if (!row) {
                    showAlertToast("ไม่พบแถวของสินค้าในตาราง", icons.error, "alert-toast");
                    return;
                }

                const barcode = row.children[3]?.textContent.trim();
                if (!barcode) {
                    showAlertToast("ไม่สามารถดึงข้อมูลบาร์โค้ดจากแถวที่เลือกได้", icons.error, "alert-toast");
                    return;
                }

                const product = storedData.find(item => item.barcode === barcode);
                if (!product) {
                    showAlertToast("ไม่พบสินค้านี้ใน localStorage", icons.empty, "alert-toast");
                    return;
                }

                // เติมข้อมูลลงฟอร์ม
                document.getElementById("editImagePreview").src = product.productImage || "";
                document.getElementById("editName").value = product.productName;
                document.getElementById("editBarcode").value = product.barcode;
                document.getElementById("editPrice").value = product.productPrice;
                document.getElementById("editCost").value = product.productCost;
                document.getElementById("editStock").value = product.productStock;
                document.getElementById("editReorder").value = product.productReorderLevel;

                editingBarcode = barcode;
                document.getElementById("editForm").dataset.key = key;
                document.getElementById("editModal").style.display = "flex";
            });
        });

        // ปิด Modal เมื่อกดปุ่มปิด
        document.getElementById("closeModal").addEventListener("click", () => {
            document.getElementById("editModal").style.display = "none";
            editingBarcode = null;
        });

        // ปิด Modal เมื่อคลิกนอกกล่อง
        window.addEventListener("click", function(e) {
            const modal = document.getElementById("editModal");
            if (e.target === modal) {
                modal.style.display = "none";
                editingBarcode = null;
            }
        });

        // บันทึกการแก้ไข
        document.getElementById("editForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const key = this.dataset.key;
            let storedData = JSON.parse(localStorage.getItem(key)) || [];

            const newBarcode = document.getElementById("editBarcode").value.trim();
            const updatedProduct = {
                productName: document.getElementById("editName").value.trim(),
                barcode: newBarcode,
                productImage: document.getElementById("editImagePreview").src,
                productPrice: document.getElementById("editPrice").value,
                productCost: document.getElementById("editCost").value,
                productStock: document.getElementById("editStock").value,
                productReorderLevel: document.getElementById("editReorder").value,
            };

            // อัปเดตใน localStorage
            storedData = storedData.map(item => {
                if (item.barcode === editingBarcode) {
                    return {
                        ...item,
                        ...updatedProduct
                    };
                }
                return item;
            });
            localStorage.setItem(key, JSON.stringify(storedData));

            // อัปเดตแถวในตาราง
            const checked = document.querySelector(".row-checkbox:checked");
            const row = checked?.closest("tr");

            if (!row || row.children.length < 8) {
                showAlertToast("เกิดข้อผิดพลาดในการอัปเดตแถว", icons.error, "alert-toast");
                return;
            }

            row.children[1].textContent = updatedProduct.productName;
            row.children[2].querySelector("img").src = updatedProduct.productImage;
            row.children[3].textContent = updatedProduct.barcode;
            row.children[4].textContent = `${updatedProduct.productPrice} บาท`;
            row.children[5].textContent = `${updatedProduct.productCost} บาท`;
            row.children[6].textContent = `${updatedProduct.productStock} ชิ้น`;
            row.children[7].textContent = `${updatedProduct.productReorderLevel} ชิ้น`;

            // ปิด Modal
            document.getElementById("editModal").style.display = "none";
            editingBarcode = null;

            // แสดง toast สำเร็จ
            showAlertToast(`แก้ไขสินค้าเรียบร้อยแล้ว`, icons.success, "success-toast");
            setTimeout(() => {
                location.reload();
            }, 1000); // รีเฟรชหน้าใหม่หลังจาก 1 วินาที
        });


        let deleteCallback = null;

        // เริ่มใช้งานปุ่มลบสินค้า
        document.querySelectorAll(".btn-delete-product").forEach(button => {
            button.addEventListener("click", function() {
                const key = this.dataset.key;
                const table = document.getElementById(`${key}_table`);
                const storedData = JSON.parse(localStorage.getItem(key)) || [];

                if (!table) {
                    showAlertToast("ไม่พบตารางสำหรับสินค้า", icons.error, "alert-toast");
                    return;
                }

                // ดึงบาร์โค้ดของสินค้าที่ถูกเลือก
                const checkedBarcodes = Array.from(table.querySelectorAll(".row-checkbox:checked"))
                    .map(cb => cb.closest("tr").children[3]?.textContent.trim())
                    .filter(Boolean); // กรองค่าที่เป็น null หรือ undefined

                if (storedData.length === 0) {
                    showAlertToast("ไม่มีข้อมูลที่จะลบ", icons.error, "alert-toast");
                    return;
                }

                if (checkedBarcodes.length === 0) {
                    showAlertToast("กรุณาเลือกสินค้าเพื่อทำการลบ", icons.select, "alert-toast");
                    return;
                }

                // แสดง Modal ยืนยัน
                const deleteMessage = document.getElementById("deleteMessage");
                deleteMessage.innerHTML = `คุณแน่ใจหรือไม่ว่าต้องการลบ <div class="highlight-text">สินค้า ${checkedBarcodes.length} รายการ?</div>`;

                const modal = document.getElementById("deleteConfirmModal");
                modal.style.display = "flex";

                // สร้าง callback สำหรับการยืนยันลบ
                deleteCallback = () => {
                    const updatedData = storedData.filter(item => !checkedBarcodes.includes(item.barcode));
                    localStorage.setItem(key, JSON.stringify(updatedData));

                    // ลบแถวจาก DOM
                    table.querySelectorAll(".row-checkbox:checked").forEach(cb => {
                        const row = cb.closest("tr");
                        if (row) row.remove();
                    });

                    showAlertToast(`ลบสินค้า ${checkedBarcodes.length} รายการเรียบร้อยแล้ว`, icons.success, "success-toast");
                    setTimeout(() => {
                        location.reload();
                    }, 1000); // รีเฟรชหน้าใหม่หลังจาก 1 วินาที

                    closeModal();
                };
            });
        });

        // ปุ่มยืนยันลบ
        document.getElementById("confirmDeleteBtn").addEventListener("click", () => {
            if (typeof deleteCallback === "function") {
                deleteCallback();
            }
        });

        // ปุ่มยกเลิก
        document.getElementById("cancelDeleteBtn").addEventListener("click", closeModal);

        window.addEventListener("click", function(e) {
            const modal = document.getElementById("deleteConfirmModal");
            if (e.target === modal) {
                closeModal();
            }
        });

        // ปิด Modal และล้าง callback
        function closeModal() {
            document.getElementById("deleteConfirmModal").style.display = "none";
            deleteCallback = null;
        }

        let loadingInterval; // สำหรับเก็บ interval ของ progress

        function showTab(tabId) {
            const loadingBar = document.getElementById("tabLoadingBar");
            let progress = 0;
            loadingBar.style.width = "0%";
            loadingBar.style.opacity = "1";

            // เริ่มโหลดแบบค่อยๆ เพิ่มขึ้น
            loadingInterval = setInterval(() => {
                if (progress < 90) {
                    progress += Math.random() * 5; // ค่อยๆ เพิ่มแบบสุ่ม
                    loadingBar.style.width = progress + "%";
                }
            }, 80);

            // โหลดคอนเทนต์จริง (mock delay 400ms)
            setTimeout(() => {
                clearInterval(loadingInterval); // หยุด interval

                // เปลี่ยนแท็บ
                document.querySelectorAll('.content').forEach(content => content.classList.remove('show'));
                document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));

                const activeContent = document.getElementById(tabId);
                if (activeContent) activeContent.classList.add('show');

                const activeTabButton = document.querySelector(`.tab[data-tab="${tabId}"]`);
                if (activeTabButton) activeTabButton.classList.add('active');

                localStorage.setItem("activeTab", tabId);

                // โหลดจบ → ดัน 100% แล้วจางหาย
                loadingBar.style.width = "100%";
                setTimeout(() => {
                    loadingBar.style.opacity = "0";
                }, 400);
            }, 400); // ความล่าช้าสำหรับ simulate loading จริง
        }

        // event listener
        document.querySelectorAll('.tab[data-tab]').forEach(tab => {
            tab.addEventListener('click', () => {
                const tabId = tab.getAttribute('data-tab');
                showTab(tabId);
            });
        });

        // โหลดแท็บล่าสุด
        const savedTab = localStorage.getItem("activeTab");
        showTab(savedTab);


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

        const passwordInput = document.getElementById('password');
        const progressBar = document.getElementById('password-progress-bar');
        const strengthText = document.getElementById('password-strength-text');

        const checklist = {
            length: document.getElementById('length'),
            uppercase: document.getElementById('uppercase'),
            number: document.getElementById('number'),
            special: document.getElementById('special')
        };

        // ฟังก์ชันเช็คแต่ละเงื่อนไข
        function validateCondition(element, condition) {
            const icon = element.querySelector('.icon');
            if (condition) {
                element.classList.add('valid');
                element.classList.remove('invalid');
                if (icon) icon.textContent = '✔';
                return 1;
            } else {
                element.classList.add('invalid');
                element.classList.remove('valid');
                if (icon) icon.textContent = '✖';
                return 0;
            }
        }

        passwordInput.addEventListener('input', function() {
            const password = passwordInput.value;

            let score = 0;
            score += validateCondition(checklist.length, password.length >= 8);
            score += validateCondition(checklist.uppercase, /[A-Z]/.test(password));
            score += validateCondition(checklist.number, /[0-9]/.test(password));
            score += validateCondition(checklist.special, /[^A-Za-z0-9]/.test(password));

            // อัปเดต Progress Bar
            const percentage = (score / 4) * 100;
            progressBar.style.width = `${percentage}%`;

            // เปลี่ยนสีและข้อความตามคะแนน
            if (score <= 1) {
                progressBar.style.backgroundColor = '#e74c3c'; // แดง
                strengthText.textContent = 'รหัสผ่านอ่อนมาก';
                strengthText.style.color = '#e74c3c';
            } else if (score === 2 || score === 3) {
                progressBar.style.backgroundColor = '#f39c12'; // ส้ม
                strengthText.textContent = 'รหัสผ่านปานกลาง';
                strengthText.style.color = '#f39c12';
            } else {
                progressBar.style.backgroundColor = '#27ae60'; // เขียว
                strengthText.textContent = 'รหัสผ่านแข็งแรงมาก';
                strengthText.style.color = '#27ae60';
            }
        });


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
                        imagePreview.src = "/sci-next/img/product_food1.png";
                    }
                });
            }
        });

        function showLogoutModal(event) {
            event.preventDefault();
            document.getElementById("logoutConfirmModal").classList.remove("hidden");
        }

        document.getElementById("confirmLogoutBtn").addEventListener("click", function() {
            window.location.href = "/sci-next/Logout.php";
        });

        document.getElementById("cancelLogoutBtn").addEventListener("click", function() {
            document.getElementById("logoutConfirmModal").classList.add("hidden");
        });

        // ปิด modal เมื่อคลิกนอก modal
        window.addEventListener("click", function(event) {
            const modal = document.getElementById("logoutConfirmModal");
            if (event.target === modal) {
                modal.classList.add("hidden");
            }
        });

        const input = document.getElementById('productImage');
        const clearBtn = document.querySelector('.clear-btn');

        function toggleClearBtn() {
            clearBtn.style.display = input.value.trim() ? 'block' : 'none';
        }

        function clearInput() {
            input.value = '';
            clearBtn.style.display = 'none';
            input.focus();
        }

        // เริ่มต้นตรวจสอบตอนโหลดหน้า (ถ้ามีค่าใน input)
        toggleClearBtn();
    </script>
</body>

</html>