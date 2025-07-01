let cartCount = 0;
let currentCamera = "environment";
let html5QrcodeScanner;
let lastScanned = "";
let cart = {};
let previewTimeout;

// เริ่มเมื่อโหลดหน้าเสร็จ
document.addEventListener('DOMContentLoaded', () => {
    initProducts(); // <<< เพิ่มบรรทัดนี้
    document.getElementById('cart-count').innerText = getTotalItems();
    updateCartUI();

    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', () => {
            const barcode = card.getAttribute('data-barcode');
            addToCart(barcode);
        });
    });
});

function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}

function initProducts() {
    fetch('/sci-next/controller/controllerSale/controllerSaleProduct.php')
        .then(res => {
            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
            return res.json();
        })
        .then(data => {
            products = data || [];
            renderProducts(products);
        })
        .catch(err => {
            const productGrid = document.getElementById('productGrid');
            productGrid.innerHTML = `<p style="color:red;">ไม่สามารถโหลดสินค้าได้ กรุณาลองใหม่</p>`;
        });
}

function renderProducts(products) {
    const productGrid = document.getElementById('productGrid');
    productGrid.innerHTML = '';

    products.forEach(product => {
        const card = document.createElement('div');
        card.className = 'product-card';
        card.dataset.barcode = product.barcode;
        card.dataset.name = product.product_name;
        card.dataset.price = product.price;
        card.dataset.image = product.image_url;

        const stock = parseInt(product.stock);
        const reorder = parseInt(product.reorder_level);

        // ป้องกันกดได้ถ้าหมด
        if (stock <= 0) card.classList.add('disabled');

        let badge = '';
        if (stock <= 0) {
            badge = `<span class="badge out-of-stock">สินค้าหมด</span>`;
        } else if (stock <= reorder) {
            badge = `<span class="badge low-stock">สินค้าใกล้หมด</span>`;
        }

        card.innerHTML = `
            <div class="image-container">
                <img src="${product.image_url}" alt="${escapeHtml(product.product_name)}">
                ${badge}
            </div>
            <h3>${escapeHtml(product.product_name)}</h3>
            <p>${parseFloat(product.price).toFixed(2)} บาท</p>
        `;

        // ✅ เพิ่ม event กดเพื่อเพิ่มตะกร้า
        card.addEventListener('click', () => {
            addToCart(product.barcode);
        });

        productGrid.appendChild(card);
    });
}


function addToCart(barcode) {
    const card = document.querySelector(`.product-card[data-barcode="${barcode}"]`);
    if (!card) return;

    if (card.classList.contains('disabled')) {
        showProductPreview("❌ สินค้าหมด");
        return;
    }

    const name = card.dataset.name;
    const price = parseFloat(card.dataset.price);
    const image = card.dataset.image;

    if (!cart[barcode]) {
        cart[barcode] = {
            name,
            price,
            quantity: 1,
            image
        };
    } else {
        cart[barcode].quantity++;
    }

    document.getElementById('cart-count').innerText = getTotalItems();
    document.getElementById('beep')?.play();
    if (navigator.vibrate) navigator.vibrate(200);
    showProductPreview(name);
    updateCartUI();
}

async function checkout() {
    const btn = document.getElementById("checkout-btn");
    if (!btn) {
        console.error("ไม่พบปุ่ม checkout-btn");
        return;
    }

    try {
        // ปิดปุ่มเพื่อป้องกันการกดซ้ำ
        btn.disabled = true;
        btn.innerText = "กำลังดำเนินการ...";

        // ส่งข้อมูลตะกร้าสินค้าไปยังเซิร์ฟเวอร์
        const response = await fetch('../controller/controllerSale/controllerSaleCheckout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(cart)
        });

        // ตรวจสอบสถานะ HTTP
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        // แปลงข้อมูลที่ได้เป็น JSON
        const data = await response.json();

        if (data.success) {
            // ถ้าสำเร็จ ปิดตะกร้า รีเซ็ตสถานะ และแจ้งผู้ใช้
            toggleCart();
            setTimeout(() => {
                resetPageState();
                showProductPreview("ชำระเงินสำเร็จ");
                setTimeout(() => location.reload(), 1000);
            }, 500);
        } else {
            // กรณีล้มเหลวจากฝั่งเซิร์ฟเวอร์ แจ้งผู้ใช้และเปิดปุ่มใหม่
            alert('ชำระเงินไม่สำเร็จ: ' + (data.message || "เกิดข้อผิดพลาด"));
            btn.disabled = false;
            btn.innerText = "ชำระเงิน";
        }
    } catch (error) {
        // กรณีเกิดข้อผิดพลาดอื่นๆ (เช่น เน็ตไม่ดี, server down)
        console.error("เกิดข้อผิดพลาดระหว่างตัด stock:", error);
        alert("ไม่สามารถตัด stock ได้ กรุณาลองใหม่");
        btn.disabled = false;
        btn.innerText = "ชำระเงิน";
    }
}


function showProductPreview(barcodeOrText) {
    const preview = document.getElementById('product-preview');

    if (previewTimeout) clearTimeout(previewTimeout);

    const item = cart[barcodeOrText];
    const text = item ? `เพิ่ม: ${item.name}` : barcodeOrText;

    preview.innerText = text;
    preview.style.display = 'block';
    preview.style.opacity = '1';

    previewTimeout = setTimeout(() => {
        preview.style.opacity = '0';
        previewTimeout = setTimeout(() => {
            preview.style.display = 'none';
        }, 300);
    }, 2000);
}

function startScanner() {
    document.getElementById('reader-container').style.display = 'flex';

    if (!html5QrcodeScanner) {
        html5QrcodeScanner = new Html5Qrcode("reader");
    }

    const config = {
        fps: 15,
        qrbox: { width: 300, height: 150 },
        aspectRatio: 1.777,
        disableFlip: true,
        videoConstraints: {
            facingMode: { ideal: currentCamera },
            focusMode: "continuous",
            width: { ideal: 1280 },
            height: { ideal: 720 },
            advanced: [
                { zoom: 1 },
                { focusMode: "continuous" }
            ]
        }
    };

    html5QrcodeScanner.start(
        { facingMode: currentCamera },
        config,
        (decodedText) => {
            if (decodedText !== lastScanned) {
                lastScanned = decodedText;
                addToCart(decodedText);
                stopScanner().then(() => {
                    if (navigator.vibrate) navigator.vibrate(200);
                });
            }
        },
        (errorMessage) => {
            console.warn("QR Scan Error:", errorMessage);
        }
    ).catch(err => {
        console.error("ไม่สามารถเริ่มกล้องได้:", err);
    });
}

async function stopScanner() {
    if (html5QrcodeScanner) {
        try {
            await html5QrcodeScanner.stop();
            await html5QrcodeScanner.clear();
            document.getElementById('reader-container').style.display = 'none';
        } catch (err) {
            console.error("หยุดกล้องไม่สำเร็จ:", err);
        }
    }
}

function toggleCamera() {
    currentCamera = currentCamera === "environment" ? "user" : "environment";
    stopScanner();
    setTimeout(startScanner, 300);
}

function toggleCart() {
    const cartPopup = document.getElementById('cart-popup');
    const overlay = document.getElementById('overlay');
    const isOpen = cartPopup.classList.contains('show');

    if (isOpen) {
        cartPopup.classList.remove('show');
        overlay.style.display = 'none';
        document.body.style.overflow = 'auto';
    } else {
        cartPopup.classList.add('show');
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function getTotalItems() {
    return Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
}

function changeQty(code, delta) {
    if (cart[code]) {
        cart[code].quantity += delta;
        if (cart[code].quantity <= 0) delete cart[code];
        updateCartUI();
        document.getElementById('cart-count').innerText = getTotalItems();
    }
}

function removeItem(code) {
    delete cart[code];
    updateCartUI();
    document.getElementById('cart-count').innerText = getTotalItems();
}

function updateCartUI() {
    const cartItems = document.getElementById('cart-items');
    const checkoutBtn = document.getElementById('checkout-btn');
    const paymentOptions = document.getElementById('payment-options');
    const totalPriceEl = document.getElementById('total-price');

    cartItems.innerHTML = '';
    const codes = Object.keys(cart);
    let totalPrice = 0;

    if (codes.length === 0) {
        cartItems.innerHTML = `<p class="empty-cart-msg">ยังไม่มีสินค้าในตะกร้า</p>`;
        checkoutBtn.style.display = 'none';
        paymentOptions.style.display = 'none';
        return;
    }

    checkoutBtn.style.display = 'block';
    paymentOptions.style.display = 'block';

    for (let code of codes) {
        const item = cart[code];
        totalPrice += item.price * item.quantity;

        cartItems.innerHTML += `
            <div class="item">
                <img src="${item.image}" alt="product">
                <div class="item-info">
                    <b>${item.name}</b>
                    <small>${item.price} บาท</small>
                </div>
                <div class="quantity-controls">
                    <button onclick="changeQty('${code}', -1)"><span class="material-symbols-outlined">remove</span></button>
                    <span>${item.quantity}</span>
                    <button onclick="changeQty('${code}', 1)"><span class="material-symbols-outlined">add</span></button>
                    <button onclick="removeItem('${code}')">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </div>
            </div>
        `;
    }

    if (totalPriceEl) {
        totalPriceEl.textContent = totalPrice.toFixed(2);
    }
}

function payWithQR() {
    document.getElementById('qr-popup').style.display = 'flex';
    enableCheckout();
}

function closeQR() {
    document.getElementById('qr-popup').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    const qrPopup = document.getElementById('qr-popup');
    if (qrPopup) {
        qrPopup.addEventListener('click', (event) => {
            // ถ้าคลิกตรงที่เป็น background ของ popup (ไม่ได้คลิกในตัวเนื้อหา)
            if (event.target === qrPopup) {
                closeQR();
            }
        });
    }
});

function payWithCash() {
    showProductPreview("คุณเลือกชำระเงินสด");
    enableCheckout();
}

function enableCheckout() {
    const btn = document.getElementById("checkout-btn");
    btn.disabled = false;
    btn.classList.add("enabled");
}

function resetPageState() {
    cart = {};
    updateCartUI();
    document.getElementById('cart-count').innerText = 0;
    document.getElementById('checkout-btn').disabled = true;

    document.getElementById('product-preview').innerHTML = "";
    document.getElementById('product-preview').style.display = "none";

    document.querySelectorAll('.payment-button').forEach(btn => btn.disabled = false);
}

function searchProducts(keyword) {
    const cards = document.querySelectorAll('.product-card');
    const lowerKeyword = keyword.toLowerCase();

    cards.forEach(card => {
        const name = card.getAttribute('data-name').toLowerCase();
        const barcode = card.getAttribute('data-barcode');
        const matches = name.includes(lowerKeyword) || barcode.includes(lowerKeyword);
        card.style.display = matches ? 'block' : 'none';
    });
}

function toggleDropdown(event) {
    event.stopPropagation();
    const dropdown = document.getElementById('filter-dropdown');
    const overlay = document.getElementById('dropdown-overlay');

    const isOpen = dropdown.classList.contains('show');
    dropdown.classList.toggle('show');
    overlay.style.display = isOpen ? 'none' : 'block';
}

function closeDropdown() {
    const dropdown = document.getElementById('filter-dropdown');
    const overlay = document.getElementById('dropdown-overlay');

    dropdown.classList.remove('show');
    overlay.style.display = 'none';
}

// ปิด dropdown เมื่อคลิกที่อื่นบนหน้า
document.addEventListener('click', (e) => {
    const dropdown = document.getElementById('filter-dropdown');
    if (dropdown && dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
    }
});

function filterByCategory(category) {
    const cards = document.querySelectorAll('.product-card');

    cards.forEach(card => {
        const cardCategory = card.getAttribute('data-category');
        if (category === 'ทั้งหมด' || cardCategory === category) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function filterCategoryAndClose(category, event) {
    event.stopPropagation();
    filterByCategory(category);
    document.getElementById('filter-dropdown').classList.remove('show');
    document.getElementById('dropdown-overlay').style.display = 'none';
}

function logout() {
    if (confirm("คุณต้องการออกจากระบบหรือไม่?")) {
        window.location.href = "../logout.php"; // เปลี่ยน path ตามไฟล์ logout ที่คุณมี
    }
}



