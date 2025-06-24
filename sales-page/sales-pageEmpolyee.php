<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SCi-NEXT-SALE</title>
    <link rel="icon" type="image/svg+xml" href="../img/sci-next.svg" />

    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Noto+Sans:ital,wght@0,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <link rel="stylesheet" href="sales-page.css" />
</head>

<body>
    <header class="search-bar">
        <div class="logo-container">
            <img src="../img/sci-next black.svg" alt="SCi-NEXT Logo" class="logo" />
            <h1>SCi-NEXT</h1>
        </div>
        <input type="text" placeholder="ค้นหาชื่อสินค้า หรือ บาร์โค้ด..." oninput="searchProducts(this.value)" />
    </header>

    <div class="product-grid" id="productGrid">
        <div class="product-card" data-barcode="8850000000003" data-name="โค้ก">
            <img src="https://www.cokeshopth.com/pub/media/catalog/product/cache/e0b9252e27a8956bf801d8ddef82be21/5/0/500-coke-original-2.jpg"
                alt="โค้ก">
            <h3>โค้ก ขวดเล็ก 500ml</h3>
            <p>15.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000010" data-name="เป๊ปซี่">
            <img src="https://st.bigc-cs.com/cdn-cgi/image/format=webp,quality=90/public/media/catalog/product/77/88/8858998571277/8858998571277_2-20240610141651-.jpg"
                alt="เป๊ปซี่">
            <h3>เป๊ปซี่ กระป๋อง 325ml</h3>
            <p>14.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000027" data-name="น้ำดื่มคริสตัล">
            <img src="https://st.bigc-cs.com/cdn-cgi/image/format=webp,quality=90/public/media/catalog/product/47/88/8851952350147/8851952350147_1-20250305102422-.jpg"
                alt="น้ำดื่มคริสตัล">

            <h3>น้ำดื่มคริสตัล 1.5L</h3>
            <p>7.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000034" data-name="สแน็กแจ็ค">
            <img src="https://st.bigc-cs.com/cdn-cgi/image/format=webp,quality=90/public/media/catalog/product/08/88/8852052220408/8852052220408_1-20230928151657-.jpg"
                alt="สแน็กแจ็ค">

            <h3>สแน็กแจ็ค ถั่วอบกรอบ</h3>
            <p>10.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000041" data-name="มันฝรั่ง LAYS">
            <img src="https://media.allonline.7eleven.co.th/pdmain/351685-01-allonline-sm.jpg" alt="มันฝรั่ง LAYS">

            <h3>เลย์ รสโนริสาหร่าย</h3>
            <p>25.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000058" data-name="KitKat">
            <img src="https://assets.tops.co.th/KITKAT-KitKatMiniChocolateSharebag72g-9556001282071-2?$JPEG$"
                alt="KitKat">

            <h3>คิทแคท มินิ</h3>
            <p>12.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000065" data-name="นม UHT">
            <img src="https://st.bigc-cs.com/cdn-cgi/image/format=webp,quality=85/public/media/catalog/product/18/88/8852537011118/8852537011118_1-20240926151652-.jpg"
                alt="นม UHT">

            <h3>นม UHT รสจืด 200ml</h3>
            <p>8.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000072" data-name="โออิชิ">
            <img src="https://media-stark.gourmetmarketthailand.com/products/cover/8854698005050-1.webp" alt="โออิชิ">

            <h3>โออิชิ กรีนที ขวด 500ml</h3>
            <p>18.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000089" data-name="โอวัลติน">
            <img src="https://media.allonline.7eleven.co.th/pdmain/359141-01-allonline-sm.jpg" alt="โอวัลติน">

            <h3>โอวัลติน 3in1 ซองเดี่ยว</h3>
            <p>5.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000096" data-name="ไมโลยูเอชที">
            <img src="https://down-th.img.susercontent.com/file/th-11134207-7r992-ln876igvc51v24" alt="ไมโลยูเอชที">

            <h3>ไมโล UHT กล่องเล็ก</h3>
            <p>10.00 บาท</p>
        </div>
        <div class="product-card" data-barcode="8850000000102" data-name="มาม่า">
            <img src="https://st.bigc-cs.com/cdn-cgi/image/format=webp,quality=90/public/media/catalog/product/18/88/8850987128318/8850987128318_2-20240703141944-.jpg"
                alt="มาม่า">

            <h3>มาม่ารสต้มยำกุ้ง</h3>
            <p>6.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000119" data-name="โยเกิร์ต">
            <img src="https://cdn8.devgodigit.net/wp-content/uploads/2021/09/30202024/075104946_P.jpg" alt="โยเกิร์ต">

            <h3>โยเกิร์ตรสสตรอเบอร์รี่</h3>
            <p>13.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000126" data-name="น้ำส้ม">
            <img src="https://down-th.img.susercontent.com/file/th-11134207-7qul2-lf835gmybmr143" alt="น้ำส้ม">

            <h3>น้ำส้ม 100% 250ml</h3>
            <p>20.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000133" data-name="ขนมปัง">
            <img src="https://halal.co.th/storages/products/288658.jpg" alt="ขนมปัง">

            <h3>ขนมปังแซนด์วิช</h3>
            <p>22.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000140" data-name="ไอศกรีมวอลล์">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSt03qoM9Xsb5sYCJseRVMxgd4HN2Ht8TBbaQ&s"
                alt="ไอศกรีมวอลล์">

            <h3>วอลล์ คอร์เน็ตโต</h3>
            <p>25.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000157" data-name="นมเปรี้ยว">
            <img src="https://fit-d.com/uploads/food/857c83b9d9f3087fd9b2f6c7e7d2031d.jpg" alt="นมเปรี้ยว">

            <h3>ยาคูลท์ 1 ขวด</h3>
            <p>10.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000164" data-name="เวเฟอร์">
            <img src="https://st.bigc-cs.com/cdn-cgi/image/format=webp,quality=90/public/media/catalog/product/20/89/8993175541220/8993175541220_2-20240619111112-.jpg"
                alt="เวเฟอร์">

            <h3>เวเฟอร์ช็อคโกแลต</h3>
            <p>9.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000171" data-name="ถั่วลิสงอบเกลือ">
            <img src="https://inwfile.com/s-cm/fc0db2.jpg" alt="ถั่วลิสงอบเกลือ">

            <h3>ถั่วลิสงอบเกลือ</h3>
            <p>12.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000188" data-name="ปลากระป๋อง">
            <img src="https://st.bigc-cs.com/cdn-cgi/image/format=webp,quality=90/public/media/catalog/product/66/88/8850511121266/8850511121266_1-20240207153002-.jpg"
                alt="ปลากระป๋อง">

            <h3>ปลากระป๋องซาร์ดีน</h3>
            <p>18.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000195" data-name="ไข่ไก่">
            <img src="https://cdn8.devgodigit.net/wp-content/uploads/2021/09/30191711/051177657_P.jpg" alt="ไข่ไก่">

            <h3>ไข่ไก่เบอร์ 2 (แพ็ค 4 ฟอง)</h3>
            <p>26.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000201" data-name="หมูหยอง">
            <img src="https://assets.tops.co.th/CHAINARONG-ChainarongDriedShreddedPork200BhtC-0000048492577-1"
                alt="หมูหยอง">

            <h3>หมูหยองแพ็คเล็ก</h3>
            <p>30.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000218" data-name="ซาลาเปา">
            <img src="https://salapaoshanghai.com/wp-content/uploads/2020/12/1.%E0%B8%AB%E0%B8%A1%E0%B8%B9%E0%B8%AA%E0%B8%B1%E0%B8%9A%E0%B9%84%E0%B8%82%E0%B9%88%E0%B9%80%E0%B8%84%E0%B9%87%E0%B8%A1-%E0%B8%88%E0%B8%B5%E0%B8%99-Lowres.jpg"
                alt="ซาลาเปา">

            <h3>ซาลาเปาไส้หมูสับ</h3>
            <p>12.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000225" data-name="กาแฟกระป๋อง">
            <img src="https://assets.tops.co.th/BIRDY-BirdyEspressoCoffee180ml-8850250006015-1?$JPEG$"
                alt="กาแฟกระป๋อง">

            <h3>กาแฟกระป๋องเบอร์ดี้</h3>
            <p>14.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000232" data-name="นมถั่วเหลือง">
            <img src="https://gda.thai-tba.or.th/wp-content/uploads/2018/07/uht-200-ml-original.png" alt="นมถั่วเหลือง">

            <h3>นมถั่วเหลืองไวตามิลค์</h3>
            <p>10.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000249" data-name="แครกเกอร์">
            <img src="https://media.allonline.7eleven.co.th/pdmain/603747-01-biscuits-violet.jpg" alt="แครกเกอร์">

            <h3>แครกเกอร์ไส้ครีม</h3>
            <p>11.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000256" data-name="พาย">
            <img src="https://shop.farmhouse.co.th/media/catalog/product/cache/09ad5326b5f9b030d858c3959bbb1f0b/p/i/pineapple_pie-2022_1.png"
                alt="พาย">

            <h3>พายไส้สับปะรด</h3>
            <p>13.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000263" data-name="เยลลี่">
            <img src="https://st.bigc-cs.com/cdn-cgi/image/format=webp,quality=90/public/media/catalog/product/02/88/8852047232102/8852047232102.jpg"
                alt="เยลลี่">

            <h3>เยลลี่ผลไม้รวม</h3>
            <p>5.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000270" data-name="ข้าวโพดอบกรอบ">
            <img src="https://assets.tops.co.th/CHEETOS-CheetosPuffsCheesyCheese66g-8850718820672-1?$JPEG$"
                alt="ข้าวโพดอบกรอบ">

            <h3>ข้าวโพดอบกรอบรสชีส</h3>
            <p>9.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000287" data-name="หมากฝรั่ง">
            <img src="https://media.allonline.7eleven.co.th/pdmain/351668-01-Grocery-Doublemint-v1.jpg" alt="หมากฝรั่ง">

            <h3>หมากฝรั่งมิ้นท์</h3>
            <p>4.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8850000000294" data-name="ขนมจีบแช่แข็ง">
            <img src="https://cdn8.devgodigit.net/wp-content/uploads/2021/09/30195338/051377714_P.jpg"
                alt="ขนมจีบแช่แข็ง">

            <h3>ขนมจีบหมู 6 ชิ้น</h3>
            <p>35.00 บาท</p>
        </div>

        <div class="product-card" data-barcode="8851123238007" data-name="คาลพิสแลคโตะ โซดา">
            <img src="https://assets.tops.co.th/CALPIS-CalpisLactoSodaYoghurtFlovour245ml-8851123238007-3?$JPEG$"
                alt="คาลพิสแลคโตะ โซดา">

            <h3>คาลพิสแลคโตะ</h3>
            <p>16.00 บาท</p>
        </div>
        <div class="product-card" data-barcode="8854698016933" data-name="โออิชิ ชาคูลล์ซ่า องุ่น">
            <img src="https://st.bigc-cs.com/cdn-cgi/image/format=webp,quality=90/public/media/catalog/product/33/88/8854698016933/8854698016933_1-20241113085008-.jpg"
                alt="โออิชิ ชาคูลล์ซ่า องุ่น">

            <h3>คาลพิสแลคโตะ</h3>
            <p>16.00 บาท</p>
        </div>
    </div>

    <navbar class="bottom-navbar">
        <div class="cart-bar" onclick="toggleCart()">
            <span class="material-symbols-outlined">shopping_bag</span>
            <span id="cart-count">0</span>
        </div>

        <div id="cart-popup" class="cart-popup">
            <div class="cart-popup-header">
                <strong><span class="material-symbols-outlined">shopping_cart</span>ตะกร้าสินค้า</strong>
                <button onclick="toggleCart()"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div id="cart-items" class="cart-popup-items"></div>

            <div class="payment-options" id="payment-options" style="display: none;">
                <div class="total-price">
                    <span>ราคารวม</span>
                    <span><span id="total-price" class="highlight-price">25.00</span> บาท</span>
                </div>

                <button class="payWithQR" onclick="payWithQR()">ชำระด้วย QR Code</button>
                <button class="payWithCash" onclick="payWithCash()">ชำระด้วย เงินสด</button>
            </div>

            <!-- ปุ่ม checkout -->
            <button id="checkout-btn" onclick="checkout()" disabled>
                <span class="btn-text">ดำเนินการต่อ</span>
                <span class="spinner" style="display:none;">⏳</span>
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


        <button class="scan-button" onclick="startScanner()">
            <div class="icon-circle">
                <span class="material-symbols-outlined">
                    barcode_scanner
                </span>
            </div>
            <span>สแกนบาร์โค้ด</span>
        </button>
    </navbar>


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