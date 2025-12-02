-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 02, 2025 at 06:48 PM
-- Server version: 8.0.44-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce_2025A_nana_nkrumah`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `vendor_id` int NOT NULL,
  `booking_datetime` datetime NOT NULL,
  `number_of_people` int NOT NULL,
  `booking_status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `customer_id`, `vendor_id`, `booking_datetime`, `number_of_people`, `booking_status`) VALUES
(1, 7, 3, '2025-12-19 21:31:00', 2, 'confirmed'),
(2, 7, 3, '2025-12-06 19:30:00', 1, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int NOT NULL,
  `brand_name` varchar(100) NOT NULL,
  `cat_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`, `cat_id`) VALUES
(1, 'Home Style', 1),
(2, 'Spicy Corner', 2),
(3, 'Chef Special', 3),
(4, 'Refreshing', 4);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int NOT NULL,
  `cat_name` varchar(100) NOT NULL,
  `cat_type` enum('Food Service','Booking') NOT NULL DEFAULT 'Food Service',
  `created_by` int NOT NULL,
  `parent_cat_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_name`, `cat_type`, `created_by`, `parent_cat_id`) VALUES
(1, 'Traditional', 'Food Service', 1, NULL),
(2, 'Street Food', 'Food Service', 1, NULL),
(3, 'Continental', 'Food Service', 1, NULL),
(4, 'Drinks', 'Food Service', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(50) NOT NULL,
  `customer_pass` varchar(150) NOT NULL,
  `customer_country` varchar(30) NOT NULL,
  `customer_city` varchar(30) NOT NULL,
  `customer_contact` varchar(15) NOT NULL,
  `customer_image` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_name`, `customer_email`, `customer_pass`, `customer_country`, `customer_city`, `customer_contact`, `customer_image`) VALUES
(1, 'Mama Esi', 'mamaesi@tasteconnect.com', '$2y$10$abcdefghijklmnopqrstuv', 'Ghana', 'Accra', '0241234567', 'uploads/vendors/mama_esi.jpg'),
(2, 'Kwame Jollof', 'kwame@jollofparadise.com', '$2y$10$abcdefghijklmnopqrstuv', 'Ghana', 'Kumasi', '0209876543', 'uploads/vendors/jollof_guy.jpg'),
(3, 'Adjoa Bites', 'adjoa@streetbites.com', '$2y$10$abcdefghijklmnopqrstuv', 'Ghana', 'Accra', '0501122334', 'uploads/vendors/street_bites.jpg'),
(4, 'Yaaba Nkrumah', 'yaaba.nkrumah@gmail.com', '$2y$10$Lh7kgr6kVfMZLUBbf6DHQuGk48AQLt45yoxz3K7eqXmMe9Ngo.Y9a', 'Ghana', 'Accra', '0548093944', NULL),
(5, 'Tasty Treats', 'tastytreats@tasteconnect.com', '$2y$10$UsXGIoBD5Q82It7/FUOodO5SV82FwtUwRX3ralRn7HuNoOm.6kQdW', 'Ghana', 'Accra', '078886716819', NULL),
(6, 'Esi Hutton', 'mamaesikitchen@gmail.com', '$2y$10$4qDtVZWknvSQeUg7GWW4demarC8YSlVcGBSK58J0Pwfv.BatY6WGW', 'Ghana', 'Accra', '0538057822', NULL),
(7, 'Ato Hummer', 'hummer.ato@gmail.com', '$2y$10$S5jCNCSQWGolBMEFKe5qxuePoxVYEt9PNtykd23cw/bud9LSNSTHi', 'Ghana', 'Accra', '05367897622', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int NOT NULL,
  `vendor_id` int NOT NULL,
  `event_title` varchar(200) NOT NULL,
  `event_description` text,
  `event_date` datetime NOT NULL,
  `price` double NOT NULL,
  `max_participants` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `vendor_id`, `event_title`, `event_description`, `event_date`, `price`, `max_participants`) VALUES
(1, 4, 'Cake and Sip', 'Decorate your own cake and have fun', '2025-12-17 17:18:00', 100, 20);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `fav_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `vendor_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `event_id` int DEFAULT NULL,
  `booking_id` int DEFAULT NULL,
  `invoice_no` varchar(50) NOT NULL,
  `order_date` date NOT NULL,
  `order_status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `event_id`, `booking_id`, `invoice_no`, `order_date`, `order_status`) VALUES
(6, 7, NULL, 1, 'INV-1764617830', '2025-12-01', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `pay_id` int NOT NULL,
  `order_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `amt` double NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'GHS',
  `payment_date` datetime NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL COMMENT 'Payment method: paystack, cash, bank_transfer, etc.',
  `transaction_ref` varchar(100) DEFAULT NULL COMMENT 'Paystack transaction reference',
  `authorization_code` varchar(100) DEFAULT NULL COMMENT 'Authorization code from payment gateway',
  `payment_channel` varchar(50) DEFAULT NULL COMMENT 'Payment channel: card, mobile_money, etc.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`pay_id`, `order_id`, `customer_id`, `amt`, `currency`, `payment_date`, `payment_method`, `transaction_ref`, `authorization_code`, `payment_channel`) VALUES
(1, 6, 7, 50, 'GHS', '2025-12-01 19:37:05', 'mobile_money', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `vendor_id` int NOT NULL,
  `product_cat` int NOT NULL,
  `product_brand` int NOT NULL,
  `product_title` varchar(200) NOT NULL,
  `product_price` double NOT NULL,
  `product_desc` varchar(500) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `product_keywords` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `vendor_id`, `product_cat`, `product_brand`, `product_title`, `product_price`, `product_desc`, `product_image`, `product_keywords`) VALUES
(1, 1, 1, 1, 'Signature Waakye', 45, 'Rice and beans cooked with millet leaves, served with spaghetti, gari, boiled eggs, and wele.', 'uploads/signature waakye.jpg', 'waakye, rice, beans, traditional'),
(2, 1, 1, 1, 'Fufu & Light Soup', 60, 'Smooth pounded cassava and plantain served with spicy goat meat light soup.', 'uploads/fufu and light soup.jpg', 'fufu, soup, goat, traditional'),
(3, 1, 1, 1, 'Red Red (Gob3)', 35, 'Fried plantains with savory black-eyed pea stew, topped with gari and palm oil.', 'uploads/red red.jpg', 'beans, plantain, red red, gob3'),
(4, 2, 1, 3, 'Assorted Jollof Rice', 85, 'Smoky Ghana jollof rice served with grilled chicken, beef strips, and sausages.', 'uploads/assorted jollof.jpg', 'jollof, rice, spicy, meat'),
(5, 2, 3, 3, 'Grilled Tilapia & Banku', 75, 'Fresh grilled tilapia marinated in spices, served with soft fermented corn dough.', 'uploads/grilled tilapia with banku.jpg', 'fish, tilapia, banku, grilled'),
(6, 2, 3, 3, 'Fried Rice & Chicken', 50, 'Classic fried rice with veggies and crispy fried chicken thigh.', 'uploads/fried rice and chicken.jpg', 'fried rice, chicken, continental'),
(7, 3, 2, 2, 'Spicy Kelewele', 25, 'Ripe plantain cubes seasoned with ginger, onions, and chili, fried to perfection.', 'uploads/spicy kelewele.jpg', 'plantain, spicy, snack, street food'),
(8, 3, 2, 2, 'Suya Beef Khebabs', 15, 'Thinly sliced beef skewers coated in spicy peanut powder and grilled.', 'uploads/suya beef kebab.jpg', 'beef, meat, spicy, khebab, suya'),
(9, 3, 4, 4, 'Sobolo (Bissap)', 10, 'Chilled hibiscus tea infused with ginger and pineapple.', 'uploads/sobolo.jpg', 'drink, juice, sobolo, hibiscus');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `vendor_id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `rating` int NOT NULL,
  `review_text` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `customer_id`, `vendor_id`, `product_id`, `rating`, `review_text`, `created_at`) VALUES
(1, 7, 3, NULL, 4, 'vyikb', '2025-12-01 19:45:47');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `vendor_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `business_name` varchar(150) NOT NULL,
  `business_address` varchar(255) DEFAULT NULL,
  `business_city` varchar(50) DEFAULT NULL,
  `business_phone` varchar(20) DEFAULT NULL,
  `business_email` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `seating_capacity` int DEFAULT NULL,
  `operating_days` varchar(255) DEFAULT NULL,
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `menu_file` varchar(255) DEFAULT NULL,
  `momo_provider` varchar(50) DEFAULT NULL,
  `momo_number` varchar(20) DEFAULT NULL,
  `business_reg_no` varchar(50) DEFAULT NULL,
  `tin` varchar(50) DEFAULT NULL,
  `business_description` text,
  `business_type` varchar(100) DEFAULT NULL,
  `cuisine_type` varchar(255) DEFAULT NULL,
  `price_range` varchar(50) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT '0',
  `admin_privilege` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`vendor_id`, `customer_id`, `business_name`, `business_address`, `business_city`, `business_phone`, `business_email`, `website`, `seating_capacity`, `operating_days`, `opening_time`, `closing_time`, `menu_file`, `momo_provider`, `momo_number`, `business_reg_no`, `tin`, `business_description`, `business_type`, `cuisine_type`, `price_range`, `verified`, `admin_privilege`) VALUES
(1, 1, 'Mama Esi\'s Kitchen', '123 Liberation Road, Osu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Experience authentic Ghanaian home cooking with our signature waakye and red-red.', NULL, NULL, NULL, 1, 1),
(2, 2, 'Jollof Paradise', '45 Bantama High St', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Award-winning jollof rice prepared with our secret family recipe passed down for generations.', NULL, NULL, NULL, 1, 1),
(3, 3, 'Street Bites Accra', 'Spintex Road, near Texpo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Your favorite Ghanaian street foods in one place - kelewele, khebab, and more!', NULL, NULL, NULL, 1, 1),
(4, 5, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, 0, 1),
(5, 6, 'Mama Esi Kitchen', 'P. O. Box GP 2856 Cantonments.', 'Accra', '+233547093844', 'mamaesikitchen@gmail.com', '', 30, 'Monday,Tuesday,Wednesday,Thursday,Friday', '09:00:00', '17:06:00', NULL, 'MTN', '0538057822', 'BN-123456', 'P0012345678', 'Traditional Ghanaian dishes such as fufu, face the wall and so on', 'Chop Bar', 'Traditional Ghanaian,Vegan/Vegetarian', 'Low', 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `parent_cat_id` (`parent_cat_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `customer_email` (`customer_email`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`fav_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`pay_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `idx_transaction_ref` (`transaction_ref`),
  ADD KEY `idx_payment_method` (`payment_method`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `product_cat` (`product_cat`),
  ADD KEY `product_brand` (`product_brand`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`vendor_id`),
  ADD UNIQUE KEY `customer_id` (`customer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `fav_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `pay_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `vendor_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`);

--
-- Constraints for table `brands`
--
ALTER TABLE `brands`
  ADD CONSTRAINT `brands_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`cat_id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `categories_ibfk_2` FOREIGN KEY (`parent_cat_id`) REFERENCES `categories` (`cat_id`) ON DELETE SET NULL;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`),
  ADD CONSTRAINT `favorites_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE SET NULL;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`product_cat`) REFERENCES `categories` (`cat_id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`product_brand`) REFERENCES `brands` (`brand_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`),
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `vendors_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
