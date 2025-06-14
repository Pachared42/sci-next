<?php
require_once('../controller/controllerSaleSuperadmin.php');
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sale-page SCi-NEXT</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Noto+Sans:ital,wght@0,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Noto Sans Thai', 'Noto Sans', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #f5f5f5;
        }

        .search-bar {
            padding: 45px 15px 15px;
            background-color: #fff;
            position: sticky;
            top: 0;
            z-index: 10;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);

            .logo-container {
                display: flex;
                text-align: center;
                justify-content: center;
                align-items: center;
                margin-bottom: 10px;
                gap: 10px;

                .logo {
                    width: 35px;
                    height: 35px;
                    display: block;
                    background-color: #000000;
                }

                h1 {
                    font-size: 24px;
                    font-weight: bold;
                    color: #000000;
                }
            }
        }

        .search-bar input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
        }

        .cart-bar {
            background-color: #ffebcc;
            color: #333;
            padding: 10px 15px;
            text-align: center;
            font-weight: 500;
            font-size: 16px;
            position: fixed;
            bottom: 95px;
            left: 0;
            width: 100%;
            z-index: 999;
            border-top: 1px solid #ddd;
        }

        .product-grid {
            height: auto;
            flex: 1;
            overflow-y: auto;
            padding: 12px 12px 150px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
        }

        .product-card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 10px;
            transition: transform 0.2s;
            cursor: pointer;

            h2 {
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                gap: 8px;
                font-size: 15px;
                margin: 8px 0 4px;

                svg {
                    width: 24px;
                    height: 24px;
                    fill: #000000;
                }
            }

            h3 {
                font-size: 16px;
                margin: 4px 0;
                color: #333;
                font-weight: 500;
            }

            p {
                font-size: 14px;
                color: #4CAF50;
                font-weight: bold;
            }
        }

        .product-card img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 10px;
        }

        .bottom-navbar {
            background-color: #fff;
            border-top: 1px solid #ddd;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 95px;
            display: flex;
            justify-content: center;
            z-index: 998;
        }

        .bottom-navbar button {
            display: flex;
            align-items: center;
            gap: 8px;
            background-color: #00796b;
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .bottom-navbar button:hover {
            background-color: #005f56;
        }

        #reader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.95);
            display: none;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            z-index: 999;
            overflow: hidden;

            #scanner-title {
                position: absolute;
                top: 35px;
                left: 50%;
                transform: translateX(-50%);
                color: white;
                font-size: 20px;
                font-weight: bold;
                z-index: 1001;
            }

            #reader {
                width: 100% !important;
                height: 100% !important;
                max-width: none !important;
                max-height: none !important;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            #close-scanner {
                position: absolute;
                top: 20px;
                right: 20px;
                background-color: transparent;
                border: none;
                padding: 10px 15px;
                cursor: pointer;
                z-index: 1001;

                span {
                    font-size: 40px;
                    color: #ffffff;
                }
            }


            #scanner-instruction {
                position: absolute;
                bottom: 250px;
                color: #ffffff;
                font-size: 16px;
                font-weight: 500;
                text-align: center;
                z-index: 1001;
                padding: 0 20px;
            }
        }

        #reader-container #qr-shaded-region {
            border-width: 370px 20px !important;
            border-color: rgba(0, 0, 0, 0.6) !important;
        }

        #reader-container #qr-shaded-region div {
            background-color: #F79824 !important;
        }
    </style>
</head>

<body>
    <div class="search-bar">
        <div class="logo-container">
            <img src="\sci-next\img\NEXT.png" alt="Logo" class="logo">
            <h1>SCi-NEXT</h1>
        </div>
        <input type="text" placeholder="ค้นหาสินค้า..." oninput="searchProducts(this.value)" />
    </div>

    <div class="product-grid" id="productGrid">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card" onclick="addToCart('<?= htmlspecialchars($row['barcode']) ?>')" data-name="<?= htmlspecialchars($row['product_name']) ?>">
                    <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                    <h2><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                            <path d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                        </svg><?= htmlspecialchars($row['barcode']) ?></h2>
                    <h3><?= htmlspecialchars($row['product_name']) ?></h3>
                    <p><?= number_format($row['price'], 2) ?> บาท</p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="grid-column: 1/-1; text-align: center;">ไม่พบสินค้า</p>
        <?php endif; ?>
    </div>

    <div class="cart-bar">
        สินค้าในตะกร้า: <span id="cart-count">0</span> รายการ
    </div>

    <navbar class="bottom-navbar">
        <button onclick="startScanner()">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                <path d="M40-120v-200h80v120h120v80H40Zm680 0v-80h120v-120h80v200H720ZM160-240v-480h80v480h-80Zm120 0v-480h40v480h-40Zm120 0v-480h80v480h-80Zm120 0v-480h120v480H520Zm160 0v-480h40v480h-40Zm80 0v-480h40v480h-40ZM40-640v-200h200v80H120v120H40Zm800 0v-120H720v-80h200v200h-80Z" />
            </svg>
            สแกนบาร์โค้ด
        </button>
    </navbar>

    <div id="reader-container">
        <button id="close-scanner" onclick="stopScanner()"><span class="material-icons">close</span></button>
        <div id="scanner-title">สแกนบาร์โค้ด</div>
        <div id="reader"></div>
        <div id="scanner-instruction">นำบาร์โค้ดของคุณมาสแกนที่นี่</div>
    </div>

    <audio id="beep" src="https://www.soundjay.com/buttons/sounds/beep-07.mp3"></audio>

    <script>
        let cartCount = 0;
        let html5QrcodeScanner;
        let currentCamera = "environment"; // default กล้องหลัง

        function addToCart(barcode) {
            cartCount++;
            document.getElementById('cart-count').innerText = cartCount;
            console.log('เพิ่มบาร์โค้ด:', barcode);
            document.getElementById('beep').play(); // เล่นเสียง beep
        }

        function searchProducts(keyword) {
            const cards = document.querySelectorAll('.product-card');
            cards.forEach(card => {
                const name = card.getAttribute('data-name').toLowerCase();
                card.style.display = name.includes(keyword.toLowerCase()) ? 'block' : 'none';
            });
        }

        function startScanner() {
            document.getElementById('reader-container').style.display = 'flex';

            html5QrcodeScanner = new Html5Qrcode("reader");
            html5QrcodeScanner.start({
                    facingMode: currentCamera
                }, {
                    fps: 10,
                    qrbox: {
                        width: 320,
                        height: 160
                    }
                },
                (decodedText, decodedResult) => {
                    addToCart(decodedText);
                    stopScanner();
                },
                (errorMessage) => {
                    // optional
                }
            ).catch(err => console.error(err));
        }

        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    html5QrcodeScanner.clear();
                    document.getElementById('reader-container').style.display = 'none';
                }).catch(err => console.error('Stop scanner failed', err));
            }
        }

        function toggleCamera() {
            currentCamera = currentCamera === "environment" ? "user" : "environment";
            stopScanner();
            setTimeout(() => startScanner(), 500);
        }
    </script>
</body>

</html>