// Update Cart UI
function updateCartUI() {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  let totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
  let totalPrice = cart.reduce((sum, item) => sum + item.price * item.qty, 0);

  // Badge
  const badge = document.getElementById("cart-badge");
  if (badge) badge.textContent = totalItems;

  // Floating Cart
  const floatingCart = document.getElementById("floating-cart");
  const floatingInfo = document.getElementById("floating-info");

  if (cart.length > 0) {
    floatingCart.style.display = "flex";
    floatingInfo.textContent = `${totalItems} item | Rp${totalPrice.toLocaleString()}`;
  } else {
    floatingCart.style.display = "none";
  }
}

// Toast Notif
function showToast(message) {
  let toast = document.createElement("div");
  toast.textContent = message;
  toast.style.position = "fixed";
  toast.style.bottom = "80px";
  toast.style.left = "50%";
  toast.style.transform = "translateX(-50%)";
  toast.style.background = "#ff00aa";
  toast.style.color = "#fff";
  toast.style.padding = "10px 20px";
  toast.style.borderRadius = "20px";
  toast.style.boxShadow = "0 4px 12px rgba(0,0,0,0.4)";
  toast.style.zIndex = "3000";
  document.body.appendChild(toast);

  setTimeout(() => {
    toast.remove();
  }, 2000);
}

// Tambah ke keranjang
const cartBtns = document.querySelectorAll(".add-cart-btn");
cartBtns.forEach(btn => {
  btn.addEventListener("click", () => {
    const name = btn.getAttribute("data-name");
    const price = parseInt(btn.getAttribute("data-price"));
    const img = btn.getAttribute("data-img");
    

    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    const existing = cart.find(item => item.name === name);
    if (existing) {
      existing.qty += 1;
    } else {
      cart.push({ name, price, qty: 1, image: img });
    }
    localStorage.setItem("cart", JSON.stringify(cart));

    showToast(`${name} ditambahkan ke keranjang`);
    updateCartUI();
  });
});

// Filter kategori
const filterBtns = document.querySelectorAll(".filter-btn");
const productCards = document.querySelectorAll(".product-card");

filterBtns.forEach(btn => {
  btn.addEventListener("click", () => {
    filterBtns.forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
    const category = btn.getAttribute("data-category");

    productCards.forEach(card => {
      if (category === "all" || card.getAttribute("data-category") === category) {
        card.style.display = "block";
      } else {
        card.style.display = "none";
      }
    });
  });
});

// Floating button redirect
document.getElementById("view-cart-btn").addEventListener("click", () => {
  window.location.href = "keranjang.html";
});

// Init
window.onload = () => {
  updateCartUI();
};

// Drag-to-scroll kategori filter
const filterContainer = document.querySelector(".category-filter");
let isDown = false;
let startX;
let scrollLeft;

filterContainer.addEventListener("mousedown", (e) => {
  isDown = true;
  filterContainer.classList.add("dragging");
  startX = e.pageX - filterContainer.offsetLeft;
  scrollLeft = filterContainer.scrollLeft;
});
filterContainer.addEventListener("mouseleave", () => {
  isDown = false;
  filterContainer.classList.remove("dragging");
});
filterContainer.addEventListener("mouseup", () => {
  isDown = false;
  filterContainer.classList.remove("dragging");
});
filterContainer.addEventListener("mousemove", (e) => {
  if (!isDown) return;
  e.preventDefault();
  const x = e.pageX - filterContainer.offsetLeft;
  const walk = (x - startX) * 1.5; // kecepatan scroll
  filterContainer.scrollLeft = scrollLeft - walk;
});