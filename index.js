// ===== Navbar Toggle =====
function toggleMenu(){
  document.getElementById('menu').classList.toggle('show');
}

// ===== Slider Script =====
const slider = document.querySelector('.slider');
const slides = document.querySelectorAll('.slide');
const prevBtn = document.querySelector('.prev');
const nextBtn = document.querySelector('.next');
const thumbs = document.querySelectorAll('.thumb');

let currentIndex = 0;
const totalSlides = slides.length;

// Update slide position
function showSlide(index) {
  if(index < 0) index = totalSlides - 1;
  if(index >= totalSlides) index = 0;
  currentIndex = index;

  const offset = -index * 100; // persen
  slider.style.transform = `translateX(${offset}%)`;

  // Update thumbnails
  thumbs.forEach((thumb, i) => {
    thumb.classList.toggle('active', i === index);
  });
}

// Prev / Next button
prevBtn.addEventListener('click', () => {
  showSlide(currentIndex - 1);
});

nextBtn.addEventListener('click', () => {
  showSlide(currentIndex + 1);
});

// Click thumbnail
thumbs.forEach((thumb, i) => {
  thumb.addEventListener('click', () => {
    showSlide(i);
  });
});

// Auto-play slider every 5s
setInterval(() => {
  showSlide(currentIndex + 1);
}, 5000);

// Initialize
showSlide(currentIndex);

// Data contoh produk (isi sesuai kebutuhan kamu)
const products = {
  chiffon: [
    { name: "Chiffon Coklat", img: "img/chiffon1.jpg" },
    { name: "Chiffon Pandan", img: "img/chiffon2.jpg" },
    { name: "Chiffon Strawberry", img: "img/chiffon3.jpg" },
    { name: "Chiffon Keju", img: "img/chiffon4.jpg" }
  ],
  tart: [
    { name: "Tart Opera", img: "img/tart1.jpg" },
    { name: "Tart Mini", img: "img/tart2.jpg" },
    { name: "Tart Red Velvet", img: "img/tart3.jpg" },
    { name: "Tart Coklat", img: "img/tart4.jpg" }
  ],
  "mini-bread": [
    { name: "Mini Croissant", img: "img/mini1.jpg" },
    { name: "Mini Chocolate Bread", img: "img/mini2.jpg" },
    { name: "Mini Beef Flosh", img: "img/mini3.jpg" },
    { name: "Mini Banana Bun", img: "img/mini4.jpg" }
  ],
  pudding: [
    { name: "Pudding Coklat", img: "img/pudding1.jpg" },
    { name: "Desser Cup", img: "img/pudding2.jpg" },
    { name: "Pudding Mangga", img: "img/pudding3.jpg" },
    { name: "Pudding Roll", img: "img/pudding4.jpg" }
  ],
  pastry: [
    { name: "Chicken Pastry", img: "img/pastry1.jpg" },
    { name: "Danish Pastry", img: "img/pastry2.jpg" },
    { name: "Donat", img: "img/pastry3.jpg" },
    { name: "Banana Roll Pastry", img: "img/pastry4.jpg" }
  ],
  cookies: [
    { name: "Choco Puff Cookies", img: "img/cookies1.jpg" },
    { name: "Choco Chrunc Cookies", img: "img/cookies2.jpg" },
    { name: "Banalo Cookies", img: "img/cookies3.jpg" },
    { name: "Butter Cookies", img: "img/cookies4.jpg" }
  ],
  "snack-box": [
    { name: "Snack Box A", img: "img/snack1.jpg" },
    { name: "Snack Box B", img: "img/snack2.jpg" },
    { name: "Snack Box C", img: "img/snack3.jpg" },
    { name: "Snack Box D", img: "img/snack4.jpg" }
  ],
  "roti-besar": [
    { name: "Pillow Coklat", img: "img/roti-besar1.jpg" },
    { name: "Ring Konde", img: "img/roti-besar2.jpg" },
    { name: "Sweet Ring", img: "img/roti-besar3.jpg" },
    { name: "Bajul Ayam", img: "img/roti-besar4.jpg" }
  ],
  "normal-bread": [
    { name: "Banana Bread", img: "img/normal1.jpg" },
    { name: "Durian Bread", img: "img/normal2.jpg" },
    { name: "Oreo Bread", img: "img/normal3.jpg" },
    { name: "Chicken Mushroom Bread", img: "img/normal4.jpg" }
  ],
  "jajan-pasar": [
    { name: "Klepon", img: "img/jajan1.jpg" },
    { name: "Cantik Manis", img: "img/jajan2.jpg" },
    { name: "Arem-arem", img: "img/jajan3.jpg" },
    { name: "Lemper", img: "img/jajan4.jpg" }
  ],
  "roti-tawar": [
    { name: "Roti Tawar Putih", img: "img/tawar1.jpg" },
    { name: "Roti Tawar Kulit", img: "img/tawar2.jpg" },
    { name: "Roti Tawar Keju", img: "img/tawar3.jpg" },
    { name: "Roti Tawar Gandum", img: "img/tawar4.jpg" }
  ],
  "nasi-box": [
    { name: "Nasi Box Ayam", img: "img/nasi1.jpg" },
    { name: "Nasi Box Langgi", img: "img/nasi2.jpg" },
    { name: "Nasi Kuning Box ", img: "img/nasi3.jpg" },
    { name: "Nasi Box Pindang", img: "img/nasi4.jpg" }
  ]
};

// ==== Elemen ====
const categoryCards = document.querySelectorAll(".category-card");
const productList = document.getElementById("product-list");
const categoryTitle = document.getElementById("category-title");
const seeMore = document.getElementById("see-more");
const track = document.querySelector(".category-track");

// ==== Klik kategori ====
categoryCards.forEach(card => {
  card.addEventListener("click", () => {
    categoryCards.forEach(c => c.classList.remove("active"));
    card.classList.add("active");

    const category = card.dataset.category;
    const items = products[category] || [];

    categoryTitle.textContent = `Terbaik di Kategori ${card.querySelector("p").textContent}`;

    if (items.length > 0) {
      productList.innerHTML = items.slice(0,4).map(p => `
        <div class="product-card">
          <img src="${p.img}" alt="${p.name}">
          <p>${p.name}</p>
        </div>
      `).join("");
      seeMore.href = `produk.html?kategori=${category}`;
      seeMore.style.display = "inline-block";
    } else {
      productList.innerHTML = "<p>Belum ada produk di kategori ini.</p>";
      seeMore.style.display = "none";
    }
  });
});

// ==== Geser 4 kategori per klik ====
const card = document.querySelector(".category-card");
const cardWidth = card.offsetWidth + 15;
const scrollAmount = cardWidth * 4;

document.querySelector(".cat-prev").addEventListener("click", () => {
  track.scrollBy({ left: -scrollAmount, behavior: "smooth" });
});
document.querySelector(".cat-next").addEventListener("click", () => {
  track.scrollBy({ left: scrollAmount, behavior: "smooth" });
});

// ==== Swipe/Drag ====
let isDown = false;
let startX;
let scrollLeft;

track.addEventListener("mousedown", (e) => {
  e.preventDefault(); // cegah efek seleksi teks
  isDown = true;
  track.classList.add("dragging");
  startX = e.pageX - track.offsetLeft;
  scrollLeft = track.scrollLeft;
});
track.addEventListener("mouseleave", () => {
  isDown = false;
  track.classList.remove("dragging");
});
track.addEventListener("mouseup", () => {
  isDown = false;
  track.classList.remove("dragging");
});
track.addEventListener("mousemove", (e) => {
  if (!isDown) return;
  e.preventDefault();
  const x = e.pageX - track.offsetLeft;
  const walk = (x - startX) * 1.5;
  track.scrollLeft = scrollLeft - walk;
});
track.addEventListener("touchstart", (e) => {
  isDown = true;
  startX = e.touches[0].pageX - track.offsetLeft;
  scrollLeft = track.scrollLeft;
});
track.addEventListener("touchend", () => {
  isDown = false;
});
track.addEventListener("touchmove", (e) => {
  if (!isDown) return;
  const x = e.touches[0].pageX - track.offsetLeft;
  const walk = (x - startX) * 1.5;
  track.scrollLeft = scrollLeft - walk;
});