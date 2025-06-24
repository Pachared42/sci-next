let cartCount = 0;
let html5QrcodeScanner;
let currentCamera = "environment";
let lastScanned = "";
let cart = {};
let previewTimeout;

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('cart-count').innerText = getTotalItems();
    updateCartUI();

    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', () => {
            const barcode = card.getAttribute('data-barcode');
            addToCart(barcode);
        });
    });
});

function addToCart(barcode) {
    const productCard = document.querySelector(`#productGrid .product-card[data-barcode="${barcode}"]`);
    if (!productCard) {
        console.warn(`ไม่พบสินค้า barcode: ${barcode}`);
        return;
    }

    const name = productCard.getAttribute('data-name') || 'ไม่ทราบชื่อ';
    const image = productCard.querySelector('img')?.src || '';
    const priceText = productCard.querySelector('p')?.innerText || '0.00';
    const price = parseFloat(priceText.replace(/[^\d.]/g, '')) || 0;

    if (!cart[barcode]) {
        cart[barcode] = {
            name: name,
            price: price,
            quantity: 1,
            image: image
        };
    } else {
        cart[barcode].quantity++;
    }

    document.getElementById('cart-count').innerText = getTotalItems();
    document.getElementById('beep')?.play();
    if (navigator.vibrate) navigator.vibrate(200);
    showProductPreview(barcode);
    updateCartUI();
}

function showProductPreview(barcodeOrText) {
    const preview = document.getElementById('product-preview');

    if (previewTimeout) clearTimeout(previewTimeout);

    // ถ้ามีใน cart ให้แสดงชื่อสินค้า ถ้าไม่มีก็ใช้ข้อความปกติ
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
        fps: 30,
        qrbox: { width: 300, height: 150 },
        disableFlip: true,
        aspectRatio: 1.7,
        videoConstraints: {
            facingMode: currentCamera,
            focusMode: "continuous",
            zoom: 1.5,
            width: { ideal: 1280 },
            height: { ideal: 720 }
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
        (errorMessage) => console.warn("QR Scan Error:", errorMessage)
    ).catch(err => console.error("ไม่สามารถเริ่มกล้องได้:", err));
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
    stopScanner().then(() => setTimeout(startScanner, 300));
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

    // ซ่อน preview หรือ popup
    document.getElementById('product-preview').innerHTML = "";
    document.getElementById('product-preview').style.display = "none";

    // รีเซ็ตปุ่ม payment
    document.querySelectorAll('.payment-button').forEach(btn => btn.disabled = false);
}


function checkout() {
    document.getElementById('cart-count').innerText = 0;

    const btn = document.getElementById("checkout-btn");
    if (!btn) {
        console.error("ไม่พบปุ่ม checkout-btn");
        return;
    }

    btn.disabled = true;
    btn.innerText = "กำลังดำเนินการ...";

    setTimeout(() => {
        toggleCart();
        setTimeout(() => {
            resetPageState(); // ทำงานก่อน

            // แสดงข้อความ "ชำระเงินสำเร็จ" ก่อนรีเฟรช
            showProductPreview("ชำระเงินสำเร็จ");

            // รอ 2 วินาที ให้ข้อความโชว์ แล้วค่อยรีเฟรช
            setTimeout(() => {
                location.reload();
            }, 2000);

        }, 500);
    }, 2000);
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