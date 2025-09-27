-- migration_seed.sql
-- Isi data contoh untuk database bakery_db

-- ========================
-- OUTLETS
-- ========================
INSERT INTO outlets (name, address) VALUES
('Outlet Pusat', 'Jl. Pemuda No. 1, Semarang'),
('Outlet Barat', 'Jl. Siliwangi No. 99, Semarang'),
('Outlet Timur', 'Jl. Majapahit No. 23, Semarang'),
('Outlet Selatan', 'Jl. Setiabudi No. 45, Semarang');

-- ========================
-- VOUCHERS
-- ========================
INSERT INTO vouchers (code, description, discount_type, discount_value, min_purchase) VALUES
('DISKON10', 'Diskon 10% tanpa minimal belanja', 'percent', 10, 0),
('PROMO50K', 'Potongan Rp50.000 tanpa minimal belanja', 'fixed', 50000, 0),
('HEMAT20', 'Diskon 20% dengan minimal belanja Rp100.000', 'percent', 20, 100000);


-- ========================
-- CATEGORIES (contoh)
-- ========================
INSERT INTO categories (name) VALUES
('Chiffon'),
('Tart'),
('Mini Bread'),
('Pudding'),
('Pastry'),
('Cookies'),
('Snack Box'),
('Roti Besar'),
('Normal Bread'),
('Jajan Pasar'),
('Roti Tawar'),
('Nasi Box');

-- ========================
-- PRODUCTS (contoh minimal)
-- ========================
INSERT INTO products (name, description, price, stock, category_id)
VALUES
('Chiffon Cake', '<p>Chiffon lembut dengan rasa vanilla</p><img src="uploads/products/chiffon.jpg">', 50000, 20, 1),
('Tart Coklat', '<p>Tart coklat premium untuk ulang tahun</p><img src="uploads/products/tart.jpg">', 150000, 10, 2),
('Mini Roti Keju', '<p>Roti mini isi keju</p><img src="uploads/products/mini-bread.jpg">', 7000, 50, 3);
