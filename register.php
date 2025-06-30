<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครแอดมิน</title>
    <link rel="stylesheet" href="/sci-next/css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script defer src="/sci-next/js/register-admin.js"></script>
</head>

<body>
    <div id="admin_signup" class="content">
        <!-- กล่อง Preview ด้านซ้าย -->
        <div class="upload-container">
            <div class="product-preview-box">
                <div class="product-preview-image">
                    <img id="previewImageAdmin" src="/sci-next/img/product_food1.png" alt="รูปภาพแอดมิน">
                </div>
                <div class="product-details">
                    <div class="detail-group">
                        <span class="material-icons">person</span>
                        <div><span id="previewFirstNameAdmin"></span></div>
                    </div>
                    <div class="detail-group">
                        <span class="material-icons">person_outline</span>
                        <div><span id="previewLastNameAdmin"></span></div>
                    </div>
                    <div class="detail-group">
                        <span class="material-icons">email</span>
                        <div><span id="previewGmailAdmin"></span></div>
                    </div>
                    <div class="detail-group">
                        <span class="material-icons">lock</span>
                        <div><span id="previewPasswordAdmin"></span></div>
                    </div>
                    <div class="detail-group">
                        <span class="material-icons">lock_open</span>
                        <div><span id="previewConfirmPasswordAdmin"></span></div>
                    </div>
                </div>
            </div>

            <!-- ฟอร์มสมัคร -->
            <div class="form-container">
                <h1 class="h-text-upload">เพิ่มแอดมินใหม่<p>Create New Admin Account</p>
                </h1>
                <form class="form-upload" id="adminSignupForm" method="POST" enctype="multipart/form-data" action="register_action.php">

                    <div class="form-group">
                        <label for="upload-img-admin" class="custom-file-upload">
                            <svg ...></svg> เลือกรูปภาพ
                        </label>
                        <input type="file" id="upload-img-admin" name="profile_image" accept="image/*" required>
                        <div class="file-info-wrapper">
                            <span id="file-name"></span>
                            <small class="file-limit-note">* ไม่เกิน 1MB</small>
                        </div>
                    </div>

                    <div class="from-container-stock">
                        <div class="form-group">
                            <label for="firstName-admin">ชื่อ</label>
                            <input type="text" id="firstName-admin" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName-admin">นามสกุล</label>
                            <input type="text" id="lastName-admin" name="lastName" required>
                        </div>
                    </div>

                    <hr class="tab-divider-admin">

                    <div class="form-group">
                        <label for="gmail-admin">Gmail</label>
                        <input type="email" id="gmail-admin" name="gmail" required>
                    </div>

                    <div class="form-group">
                        <label for="password-admin">รหัสผ่าน</label>
                        <input type="password" id="password-admin" name="password" required>
                    </div>

                    <ul id="password-checklist">
                        <li id="length"><span class="icon">✖</span> อย่างน้อย 8 ตัวอักษร</li>
                        <li id="uppercase"><span class="icon">✖</span> มีตัวพิมพ์ใหญ่</li>
                        <li id="number"><span class="icon">✖</span> มีตัวเลข</li>
                        <li id="special"><span class="icon">✖</span> มีอักขระพิเศษ</li>
                    </ul>

                    <div class="form-group">
                        <label for="confirmPassword-admin">ยืนยันรหัสผ่าน</label>
                        <input type="password" id="confirmPassword-admin" name="confirmPassword" required>
                    </div>

                    <button type="submit" id="submitAdminBtn" class="btn-upload">สมัครแอดมิน</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ฟังก์ชั่นที่ทำงานเมื่อฟอร์มถูกส่ง (สำหรับการสร้าง admin)

        // ตรวจสอบความแข็งแรงของรหัสผ่าน
        const passwordInputAdmin = document.getElementById('password-admin');
        const checklistAdmin = {
            length: document.getElementById('length'),
            uppercase: document.getElementById('uppercase'),
            number: document.getElementById('number'),
            special: document.getElementById('special')
        };
        const progressBarAdmin = document.getElementById('password-progress-bar');

        passwordInputAdmin.addEventListener('input', function() {
            const value = this.value;
            let score = 0;

            if (value.length >= 8) {
                checklistAdmin.length.classList.add('valid');
                checklistAdmin.length.querySelector('.icon').textContent = '✔';
                score++;
            } else {
                checklistAdmin.length.classList.remove('valid');
                checklistAdmin.length.querySelector('.icon').textContent = '✖';
            }

            if (/[A-Z]/.test(value)) {
                checklistAdmin.uppercase.classList.add('valid');
                checklistAdmin.uppercase.querySelector('.icon').textContent = '✔';
                score++;
            } else {
                checklistAdmin.uppercase.classList.remove('valid');
                checklistAdmin.uppercase.querySelector('.icon').textContent = '✖';
            }

            if (/\d/.test(value)) {
                checklistAdmin.number.classList.add('valid');
                checklistAdmin.number.querySelector('.icon').textContent = '✔';
                score++;
            } else {
                checklistAdmin.number.classList.remove('valid');
                checklistAdmin.number.querySelector('.icon').textContent = '✖';
            }

            if (/[^A-Za-z0-9]/.test(value)) {
                checklistAdmin.special.classList.add('valid');
                checklistAdmin.special.querySelector('.icon').textContent = '✔';
                score++;
            } else {
                checklistAdmin.special.classList.remove('valid');
                checklistAdmin.special.querySelector('.icon').textContent = '✖';
            }

            progressBarAdmin.style.width = `${(score / 4) * 100}%`;
            progressBarAdmin.style.backgroundColor = score >= 3 ? '#4CAF50' : '#f44336';
        });

        document.getElementById('adminSignupForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const fileInput = document.getElementById('upload-img-admin');
            const file = fileInput.files[0];
            const gmail = document.getElementById('gmail-admin').value.trim();
            const password = document.getElementById('password-admin').value;
            const confirmPassword = document.getElementById('confirmPassword-admin').value;
            const firstName = document.getElementById('firstName-admin').value.trim();
            const lastName = document.getElementById('lastName-admin').value.trim();

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!gmail || !password || !confirmPassword || !firstName || !lastName) {
                showAlertToast("กรุณากรอกข้อมูลให้ครบถ้วน", icons.error, "error-toast");
                return;
            }

            if (!emailRegex.test(gmail)) {
                showAlertToast("รูปแบบอีเมลไม่ถูกต้อง", icons.error, "error-toast");
                return;
            }

            if (!file) {
                showAlertToast("กรุณาเลือกไฟล์รูปภาพของแอดมิน", icons.error, "error-toast");
                return;
            }

            if (password !== confirmPassword) {
                showAlertToast("รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน", icons.error, "error-toast");
                return;
            }

            try {
                const base64 = await toBase64(file);
                const formData = new FormData();
                formData.append('gmail', gmail);
                formData.append('password', password);
                formData.append('confirmPassword', confirmPassword);
                formData.append('firstName', firstName);
                formData.append('lastName', lastName);
                formData.append('profileImage', base64);

                const response = await fetch('/sci-next/admin/dashboard_superadmin/manageAdmin/adminSignup.php', {
                    method: 'POST',
                    body: formData
                });

                const contentType = response.headers.get("content-type") || "";
                const responseText = await response.text();
                console.log("Response text:", responseText);

                if (contentType.includes("application/json")) {
                    const data = JSON.parse(responseText);

                    if (data.status === 'error') {
                        if (data.message.includes("อีเมล") && data.message.includes("ใช้ไปแล้ว")) {
                            showAlertToast("อีเมลนี้ถูกใช้งานแล้ว", icons.error, "error-toast");
                        } else {
                            showAlertToast(data.message || "เกิดข้อผิดพลาด", icons.error, "error-toast");
                        }
                    }

                    if (data.status === 'success') {
                        showAlertToast("สมัครสมาชิกแอดมินสำเร็จ!", icons.success, "success-toast");
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                        this.reset();
                        document.getElementById('file-name').textContent = '';
                        if (typeof progressBar !== 'undefined') {
                            progressBar.style.width = '0%';
                        }
                    }
                } else {
                    showAlertToast("ไม่สามารถประมวลผลข้อมูลได้", icons.error, "error-toast");
                }
            } catch (error) {
                console.error('Error:', error);
                showAlertToast("เกิดข้อผิดพลาดในการส่งข้อมูล", icons.error, "error-toast");
            }
        });


        // ฟังก์ชันแปลงไฟล์เป็น base64
        function toBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        }
    </script>
</body>

</html>