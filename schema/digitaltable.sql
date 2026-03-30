CREATE DATABASE IF NOT EXISTS digitaltable CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE digitaltable;

CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_name VARCHAR(80) NOT NULL,
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE menu_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  item_name VARCHAR(140) NOT NULL,
  description VARCHAR(255) NULL,
  price DECIMAL(10,2) NOT NULL,
  image_path VARCHAR(255) NULL,
  is_veg TINYINT(1) DEFAULT 1,
  is_available TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_menu_category (category_id),
  INDEX idx_menu_available (is_available),
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE tables (
  id INT AUTO_INCREMENT PRIMARY KEY,
  table_name VARCHAR(50) NOT NULL,
  qr_path VARCHAR(255) NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  order_code VARCHAR(40) NOT NULL UNIQUE,
  table_id INT NOT NULL,
  customer_name VARCHAR(120) NOT NULL,
  customer_mobile VARCHAR(15) NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  gst_percent DECIMAL(5,2) NOT NULL,
  gst_amount DECIMAL(10,2) NOT NULL,
  total_amount DECIMAL(10,2) NOT NULL,
  status ENUM('pending','accepted','rejected') DEFAULT 'pending',
  order_date DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_order_date (order_date),
  INDEX idx_order_status (status),
  INDEX idx_order_table (table_id),
  FOREIGN KEY (table_id) REFERENCES tables(id)
);

CREATE TABLE order_items (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT NOT NULL,
  menu_item_id INT NOT NULL,
  item_name VARCHAR(140) NOT NULL,
  qty INT NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  line_total DECIMAL(10,2) NOT NULL,
  INDEX idx_oi_order (order_id),
  INDEX idx_oi_menu (menu_item_id),
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (menu_item_id) REFERENCES menu_items(id)
);

CREATE TABLE invoices (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT NOT NULL UNIQUE,
  invoice_month CHAR(7) NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_inv_month (invoice_month),
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

CREATE TABLE settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(80) NOT NULL UNIQUE,
  setting_value VARCHAR(120) NOT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO admins(name,email,password_hash) VALUES ('Admin','admin@digitaltable.com', '$2y$10$d5t1OkOHZIpIoou9nvtrZOahnr/hYYa3pgxUL9bO/nkbEToBp4.ku');
INSERT INTO categories(category_name,sort_order) VALUES ('Veg',1),('Non-Veg',2),('Drinks',3);
INSERT INTO settings(setting_key,setting_value) VALUES ('gst_percent','5.00');
INSERT INTO tables(table_name,is_active) VALUES ('Table 1',1),('Table 2',1),('Table 3',1);
INSERT INTO menu_items(category_id,item_name,description,price,is_veg,is_available,image_path)
VALUES (1,'Paneer Tikka','Cottage cheese grill',249,1,1,''),(2,'Chicken Biryani','Aromatic dum biryani',299,0,1,''),(3,'Fresh Lime Soda','Mint and lime',99,1,1,'');
