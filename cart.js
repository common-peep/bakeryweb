// cart.js - Keranjang & Checkout WhatsApp
document.addEventListener("DOMContentLoaded", () => {
  const getCart = () => JSON.parse(localStorage.getItem("cart")) || [];
  const saveCart = (cart) => localStorage.setItem("cart", JSON.stringify(cart));

  // -----------------------
  // Elemen Form
  // -----------------------
  const nameEl = document.getElementById("customer-name");
  const methodEls = document.querySelectorAll('input[name="method"]');
  const outletSelect = document.getElementById("outlet-dropdown");
  const outletWrapper = document.getElementById("outlet-wrapper");
  const addrWrapper = document.getElementById("address-input");
  const addrEl = document.getElementById("delivery-address");
  const dateEl = document.getElementById("delivery-date");
  const timeEl = document.getElementById("delivery-time");
  const checkoutBtn = document.getElementById("checkout-btn");

  // -----------------------
  // Elemen Keranjang
  // -----------------------
  const cartItemsEl = document.getElementById("cart-items");
  const totalPriceEl = document.getElementById("total-price");
  const originalPriceEl = document.getElementById("original-price");
  const discountInfoEl = document.getElementById("discount-info");

  // -----------------------
  // Voucher
  // -----------------------
  let appliedVoucher = null;
  const voucherInput = document.getElementById("voucher-code");
  const voucherBtn = document.getElementById("apply-voucher");
  const voucherMsg = document.getElementById("voucher-msg");

  // -----------------------
  // Load outlets dari DB
  // -----------------------
  fetch("api/get_outlets.php")
    .then(res => res.json())
    .then(outlets => {
      outlets.forEach(o => {
        const opt = document.createElement("option");
        opt.value = o.id;
        opt.textContent = `${o.name} - ${o.address}`;
        outletSelect.appendChild(opt);
      });
    })
    .catch(err => console.error("Gagal load outlet:", err));

  // -----------------------
  // Hitung total dengan voucher
  // -----------------------
  function calculateTotal(cart) {
    let total = cart.reduce((sum, item) => sum + item.price * item.qty, 0);

    if (appliedVoucher && appliedVoucher.valid) {
      if (total < appliedVoucher.min_purchase) {
        appliedVoucher = null;
        voucherMsg.textContent = `⚠️ Total belanja di bawah minimum untuk voucher.`;
      } else {
        if (appliedVoucher.discount_type === "percent") {
          total = total * (1 - appliedVoucher.discount_value / 100);
        } else if (appliedVoucher.discount_type === "fixed") {
          total = Math.max(0, total - appliedVoucher.discount_value);
        }
      }
    }

    return total;
  }

  // -----------------------
  // Render Keranjang
  // -----------------------
  function loadCart() {
    const cart = getCart();
    cartItemsEl.innerHTML = "";

    if (cart.length === 0) {
      cartItemsEl.innerHTML = "<p>Keranjang kosong.</p>";
      totalPriceEl.textContent = "0";
      originalPriceEl.textContent = "0";
      discountInfoEl.style.display = "none";
      return;
    }

    let originalTotal = 0;
    cart.forEach((item, index) => {
      const subtotal = item.price * item.qty;
      originalTotal += subtotal;

      const itemEl = document.createElement("div");
      itemEl.classList.add("cart-item");
      itemEl.innerHTML = `
        <img src="${item.image || 'placeholder.png'}" alt="${item.name}">
        <div class="cart-info">
          <h3>${item.name}</h3>
          <p>Rp${item.price.toLocaleString()}</p>
          <div class="qty-control">
            <button class="decrease">-</button>
            <span class="qty-value">${item.qty}</span>
            <button class="increase">+</button>
          </div>
        </div>
        <div class="cart-actions">
          <p>Rp${subtotal.toLocaleString()}</p>
          <button class="remove">Hapus</button>
        </div>
      `;

      // Events qty & hapus
      itemEl.querySelector(".decrease").addEventListener("click", () => {
        if (cart[index].qty > 1) cart[index].qty--;
        else cart.splice(index, 1);
        saveCart(cart);
        loadCart();
      });

      itemEl.querySelector(".increase").addEventListener("click", () => {
        cart[index].qty++;
        saveCart(cart);
        loadCart();
      });

      itemEl.querySelector(".remove").addEventListener("click", () => {
        cart.splice(index, 1);
        saveCart(cart);
        loadCart();
      });

      cartItemsEl.appendChild(itemEl);
    });

    // Update total & diskon
    const grandTotal = calculateTotal(cart);
    originalPriceEl.textContent = originalTotal.toLocaleString();
    totalPriceEl.textContent = grandTotal.toLocaleString();

    if (appliedVoucher && appliedVoucher.valid) {
      const discountAmount = originalTotal - grandTotal;
      discountInfoEl.textContent = `${appliedVoucher.description} → Diskon Rp${discountAmount.toLocaleString()}`;
      discountInfoEl.style.display = "block";
    } else {
      discountInfoEl.style.display = "none";
    }
  }

  // -----------------------
  // Apply Voucher
  // -----------------------
  if (voucherBtn) {
    voucherBtn.addEventListener("click", () => {
      const code = voucherInput.value.trim().toUpperCase();
      if (!code) {
        voucherMsg.textContent = "⚠️ Masukkan kode voucher.";
        return;
      }

      fetch(`api/check_voucher.php?code=${code}`)
        .then(res => res.json())
        .then(data => {
          if (!data.valid) {
            appliedVoucher = null;
            voucherMsg.textContent = "⚠️ " + data.msg;
            loadCart();
            return;
          }

          const cart = getCart();
          const originalTotal = cart.reduce((sum, i) => sum + i.price * i.qty, 0);

          if (originalTotal < data.min_purchase) {
            appliedVoucher = null;
            voucherMsg.textContent = `⚠️ Minimal belanja Rp${data.min_purchase.toLocaleString()} untuk voucher ${data.code}.`;
            loadCart();
            return;
          }

          appliedVoucher = {
            code: data.code,
            description: data.description,
            discount_type: data.discount_type,
            discount_value: data.discount_value,
            min_purchase: data.min_purchase,
            valid: true
          };

          voucherMsg.textContent = `✅ Voucher ${data.code} berhasil diterapkan!`;
          loadCart();
        })
        .catch(() => {
          appliedVoucher = null;
          voucherMsg.textContent = "⚠️ Gagal cek voucher.";
          loadCart();
        });
    });
  }

  // -----------------------
  // Toggle metode pengiriman
  // -----------------------
  methodEls.forEach(radio => {
    radio.addEventListener("change", () => {
      if (radio.value === "outlet") {
        outletWrapper.style.display = "block";
        addrWrapper.style.display = "none";
      } else {
        outletWrapper.style.display = "none";
        addrWrapper.style.display = "block";
      }
    });
  });

  // -----------------------
  // Validasi tanggal H+2
  // -----------------------
  const today = new Date();
  today.setDate(today.getDate() + 2);
  dateEl.min = today.toISOString().split("T")[0];

  // -----------------------
  // Validasi jam 12–16
  // -----------------------
  timeEl.addEventListener("input", (e) => {
    if (e.target.value < "12:00" || e.target.value > "16:00") {
      alert("Jam pengiriman harus antara 12.00 - 16.00");
      e.target.value = "";
    }
  });

  // -----------------------
  // Checkout WhatsApp
  // -----------------------
  checkoutBtn.addEventListener("click", () => {
    const name = nameEl.value.trim();
    const method = document.querySelector('input[name="method"]:checked');
    const date = dateEl.value;
    const time = timeEl.value;

    if (!name || !method || !date || !time) {
      alert("Mohon lengkapi semua data pemesanan.");
      return;
    }

    let deliveryInfo = "";
    if (method.value === "outlet") {
      const selectedOption = outletSelect.selectedOptions[0];
      if (!selectedOption) {
        alert("Silakan pilih outlet.");
        return;
      }
      deliveryInfo = `Ambil di Outlet: ${selectedOption.textContent}`;
    } else {
      const addr = addrEl.value.trim();
      if (!addr) {
        alert("Alamat pengiriman wajib diisi.");
        return;
      }
      deliveryInfo = `Kirim ke Alamat: ${addr}`;
    }

    const cart = getCart();
    if (cart.length === 0) {
      alert("Keranjang masih kosong!");
      return;
    }

    // Susun pesan WA
    let message =
      `Halo Jessyco! Saya ingin memesan:\n\n` +
      `Nama: ${name}\n` +
      `Metode: ${deliveryInfo}\n` +
      `Tanggal Kirim: ${date}\n` +
      `Jam Kirim: ${time}\n\nPesanan:\n`;

    cart.forEach(item => {
      const subtotal = item.price * item.qty;
      message += `- ${item.name} x${item.qty} (Rp${subtotal.toLocaleString()})\n`;
    });

    const finalTotal = calculateTotal(cart);
    const originalTotal = cart.reduce((sum, i) => sum + i.price * i.qty, 0);
    message += `\nTotal: Rp${originalTotal.toLocaleString()}`;

    if (appliedVoucher && appliedVoucher.valid) {
      const discountAmount = originalTotal - finalTotal;
      message += `\nVoucher: ${appliedVoucher.code} (Diskon Rp${discountAmount.toLocaleString()})`;
    }

    message += `\nGrand Total: Rp${finalTotal.toLocaleString()}`;

    // Ganti dengan nomor WA toko
    const waNumber = "6281333068788";
    const waUrl = `https://wa.me/${waNumber}?text=${encodeURIComponent(message)}`;
    window.open(waUrl, "_blank");
  });

  // -----------------------
  // Init
  // -----------------------
  loadCart();
});
