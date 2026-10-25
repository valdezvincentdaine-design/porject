CREATE DATABASE IF NOT EXISTS phinma_canteen CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE phinma_canteen;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  student_id VARCHAR(60) DEFAULT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('student','admin') NOT NULL DEFAULT 'student',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  category VARCHAR(60) NOT NULL,
  description VARCHAR(255) NOT NULL DEFAULT '',
  emoji VARCHAR(16) NOT NULL DEFAULT '🍽️',
  price DECIMAL(10,2) NOT NULL,
  is_available TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  pickup_method VARCHAR(40) NOT NULL DEFAULT 'Pickup at canteen',
  payment_method VARCHAR(40) NOT NULL DEFAULT 'Cash on pickup',
  instructions VARCHAR(500) DEFAULT NULL,
  status ENUM('Pending','Preparing','Ready for pickup','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NULL,
  product_name VARCHAR(120) NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  CONSTRAINT fk_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO products (name,category,description,emoji,price) VALUES
('Pork Sisig w/ Rice','Rice Meals','Savory pork sisig served with steamed rice.','🍛',65.00),
('Chicken Pastil','Rice Meals','Flavorful shredded chicken with rice.','🍗',45.00),
('Chicken Adobo Rice','Rice Meals','Classic adobo served with warm rice.','🍱',60.00),
('French Fries','Snacks','Crispy golden fries.','🍟',30.00),
('Siomai','Snacks','Steamed dumplings with dipping sauce.','🥟',25.00),
('Cheese Sandwich','Snacks','Toasted sandwich with creamy cheese.','🥪',35.00),
('Iced Tea','Drinks','Refreshing chilled iced tea.','🧋',15.00),
('Bottled Water','Drinks','Cold drinking water.','💧',15.00),
('Chocolate Milk','Drinks','Smooth chocolate drink.','🥤',25.00),
('Banana Cue','Desserts','Sweet caramelized banana snack.','🍌',20.00),
('Puto','Desserts','Soft Filipino rice cake.','🧁',15.00),
('Buko Salad Cup','Desserts','Creamy chilled buko dessert.','🍨',35.00);
