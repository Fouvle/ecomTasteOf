SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

USE ecommerce_2025A_nana_nkrumah;

-- =========================================================
-- 1. CUSTOMER TABLE
-- =========================================================
CREATE TABLE customer (
  customer_id INT(11) NOT NULL AUTO_INCREMENT,
  customer_name VARCHAR(100) NOT NULL,
  customer_email VARCHAR(50) NOT NULL UNIQUE,
  customer_pass VARCHAR(150) NOT NULL,
  customer_country VARCHAR(30) NOT NULL,
  customer_city VARCHAR(30) NOT NULL,
  customer_contact VARCHAR(15) NOT NULL,
  customer_image VARCHAR(100),
  PRIMARY KEY (customer_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================================================
-- 2. VENDORS TABLE
-- =========================================================
CREATE TABLE vendors (
  vendor_id INT(11) NOT NULL AUTO_INCREMENT,
  customer_id INT(11) NOT NULL UNIQUE,
  business_name VARCHAR(150) NOT NULL,
  business_address VARCHAR(255),
  business_description TEXT,
  verified TINYINT(1) DEFAULT 0,
  admin_privilege TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (vendor_id),
  FOREIGN KEY (customer_id) REFERENCES customer(customer_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- =========================================================
-- 3. CATEGORIES TABLE
-- =========================================================
CREATE TABLE categories (
  cat_id INT(11) NOT NULL AUTO_INCREMENT,
  cat_name VARCHAR(100) NOT NULL,
  cat_type ENUM('Food Service','Booking') NOT NULL DEFAULT 'Food Service',
  created_by INT(11) NOT NULL,
  parent_cat_id INT(11),
  PRIMARY KEY (cat_id),
  FOREIGN KEY (created_by) REFERENCES customer(customer_id),
  FOREIGN KEY (parent_cat_id) REFERENCES categories(cat_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- =========================================================
-- 4. BRANDS TABLE
-- =========================================================
CREATE TABLE brands (
  brand_id INT(11) NOT NULL AUTO_INCREMENT,
  brand_name VARCHAR(100) NOT NULL,
  cat_id INT(11) NOT NULL,
  PRIMARY KEY (brand_id),
  FOREIGN KEY (cat_id) REFERENCES categories(cat_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================================================
-- 5. PRODUCTS TABLE (Menu Items, Events, Booking Slots)
-- =========================================================
CREATE TABLE products (
  product_id INT(11) NOT NULL AUTO_INCREMENT,
  vendor_id INT(11) NOT NULL,
  product_cat INT(11) NOT NULL,
  product_brand INT(11) NOT NULL,
  product_title VARCHAR(200) NOT NULL,
  product_price DOUBLE NOT NULL,
  product_desc VARCHAR(500),
  product_image VARCHAR(100),
  product_keywords VARCHAR(100),
  PRIMARY KEY (product_id),
  FOREIGN KEY (vendor_id) REFERENCES vendors(vendor_id) ON DELETE CASCADE,
  FOREIGN KEY (product_cat) REFERENCES categories(cat_id),
  FOREIGN KEY (product_brand) REFERENCES brands(brand_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================================================
-- 6. BOOKINGS TABLE
-- =========================================================
CREATE TABLE bookings (
  booking_id INT(11) NOT NULL AUTO_INCREMENT,
  customer_id INT(11) NOT NULL,
  vendor_id INT(11) NOT NULL,
  booking_datetime DATETIME NOT NULL,
  number_of_people INT(11) NOT NULL,
  booking_status ENUM('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  PRIMARY KEY (booking_id),
  FOREIGN KEY (customer_id) REFERENCES customer(customer_id),
  FOREIGN KEY (vendor_id) REFERENCES vendors(vendor_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================================================
-- 7. EVENTS TABLE
-- =========================================================
CREATE TABLE events (
  event_id INT(11) NOT NULL AUTO_INCREMENT,
  vendor_id INT(11) NOT NULL,
  event_title VARCHAR(200) NOT NULL,
  event_description TEXT,
  event_date DATETIME NOT NULL,
  price DOUBLE NOT NULL,
  max_participants INT(11),
  PRIMARY KEY (event_id),
  FOREIGN KEY (vendor_id) REFERENCES vendors(vendor_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================================================
-- 8. REVIEWS TABLE
-- =========================================================
CREATE TABLE reviews (
  review_id INT(11) NOT NULL AUTO_INCREMENT,
  customer_id INT(11) NOT NULL,
  vendor_id INT(11) NOT NULL,
  product_id INT(11) DEFAULT NULL,
  rating INT(1) NOT NULL CHECK (rating BETWEEN 1 AND 5),
  review_text TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (review_id),
  FOREIGN KEY (customer_id) REFERENCES customer(customer_id),
  FOREIGN KEY (vendor_id) REFERENCES vendors(vendor_id),
  FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================================================
-- 9. FAVORITES TABLE
-- =========================================================
CREATE TABLE favorites (
  fav_id INT(11) NOT NULL AUTO_INCREMENT,
  customer_id INT(11) NOT NULL,
  vendor_id INT(11) DEFAULT NULL,
  product_id INT(11) DEFAULT NULL,
  PRIMARY KEY (fav_id),
  FOREIGN KEY (customer_id) REFERENCES customer(customer_id),
  FOREIGN KEY (vendor_id) REFERENCES vendors(vendor_id),
  FOREIGN KEY (product_id) REFERENCES products(product_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================================================
-- 10. ORDERS TABLE (Events & Bookings Payments)
-- =========================================================
CREATE TABLE orders (
  order_id INT(11) NOT NULL AUTO_INCREMENT,
  customer_id INT(11) NOT NULL,
  event_id INT(11) DEFAULT NULL,
  booking_id INT(11) DEFAULT NULL,
  invoice_no VARCHAR(50) NOT NULL,
  order_date DATE NOT NULL,
  order_status VARCHAR(100) NOT NULL,
  PRIMARY KEY (order_id),
  FOREIGN KEY (customer_id) REFERENCES customer(customer_id),
  FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE SET NULL,
  FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =========================================================
-- 11. PAYMENTS TABLE
-- =========================================================
CREATE TABLE payment (
  pay_id INT(11) NOT NULL AUTO_INCREMENT,
  order_id INT(11) NOT NULL,
  customer_id INT(11) NOT NULL,
  amt DOUBLE NOT NULL,
  payment_method ENUM('mtn_momo','telecel_cash','at_money','card') NOT NULL,
  currency VARCHAR(10) NOT NULL DEFAULT 'GHS',
  payment_date DATETIME NOT NULL,
  PRIMARY KEY (pay_id),
  FOREIGN KEY (order_id) REFERENCES orders(order_id),
  FOREIGN KEY (customer_id) REFERENCES customer(customer_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

COMMIT;
