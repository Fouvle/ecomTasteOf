-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

USE `ecommerce_2025A_nana_nkrumah`;

-- --------------------------------------------------------
-- Table structure for table `customer`
-- --------------------------------------------------------
CREATE TABLE `customer` (
  `customer_id` INT(11) NOT NULL AUTO_INCREMENT,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_email` VARCHAR(50) NOT NULL UNIQUE,
  `customer_pass` VARCHAR(150) NOT NULL,
  `customer_country` VARCHAR(30) NOT NULL,
  `customer_city` VARCHAR(30) NOT NULL,
  `customer_contact` VARCHAR(15) NOT NULL,
  `customer_image` VARCHAR(100) DEFAULT NULL,
  `user_role` INT(11) NOT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--------------------------------------------------------
-- Table structure for table 'brands'
-- --------------------------------------------------------
CREATE TABLE `brands` (
  `brand_id` INT(11) NOT NULL AUTO_INCREMENT,
  `brand_name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- Table structure for table `categories`
-- --------------------------------------------------------
CREATE TABLE `categories` (
  `cat_id` INT(11) NOT NULL AUTO_INCREMENT,
  `cat_name` VARCHAR(100) NOT NULL,
  `created_by` INT(11) NOT NULL,
  `is_approved` TINYINT(1) DEFAULT 0,
  `cat_type` VARCHAR(50) DEFAULT 'V' NOT NULL, -- 'V' for Venue/Review Tag(Default), 'P' for Product/Experience Tag
  `parent_cat_id` INT(11) DEFAULT NULL,
  PRIMARY KEY (`cat_id`),
  FOREIGN KEY (`parent_cat_id`) REFERENCES `categories` (`cat_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- Table structure for table `products`
-- --------------------------------------------------------
CREATE TABLE `products` (
  `product_id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_cat` INT(11) NOT NULL,
  `product_brand` INT(11) NOT NULL,
  `product_title` VARCHAR(200) NOT NULL,
  `product_price` DOUBLE NOT NULL,
  `product_desc` VARCHAR(500) DEFAULT NULL,
  `product_image` VARCHAR(100) DEFAULT NULL,
  `product_keywords` VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- Table structure for table `orders`
-- --------------------------------------------------------
CREATE TABLE `orders` (
  `order_id` INT(11) NOT NULL AUTO_INCREMENT,
  `customer_id` INT(11) NOT NULL,
  `invoice_no` INT(11) NOT NULL,
  `order_date` DATE NOT NULL,
  `order_status` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- Table structure for table `cart`
-- --------------------------------------------------------
CREATE TABLE `cart` (
  `p_id` INT(11) NOT NULL,
  `ip_add` VARCHAR(50) NOT NULL,
  `c_id` INT(11) DEFAULT NULL,
  `qty` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- Table structure for table `orderdetails`
-- --------------------------------------------------------
CREATE TABLE `orderdetails` (
  `order_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `qty` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- Table structure for table `payment`
-- --------------------------------------------------------
CREATE TABLE `payment` (
  `pay_id` INT(11) NOT NULL AUTO_INCREMENT,
  `amt` DOUBLE NOT NULL,
  `customer_id` INT(11) NOT NULL,
  `order_id` INT(11) NOT NULL,
  `currency` TEXT NOT NULL,
  `payment_date` DATE NOT NULL,
  PRIMARY KEY (`pay_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
-- Now add the foreign key constraints
-- --------------------------------------------------------

-- Add foreign key to categories table
ALTER TABLE `categories` 
ADD CONSTRAINT `categories_ibfk_1` 
FOREIGN KEY (`created_by`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE;

-- Add foreign keys to products table
ALTER TABLE `products` 
ADD CONSTRAINT `products_ibfk_1` 
FOREIGN KEY (`product_cat`) REFERENCES `categories` (`cat_id`);

ALTER TABLE `products` 
ADD CONSTRAINT `products_ibfk_2` 
FOREIGN KEY (`product_brand`) REFERENCES `brands` (`brand_id`);

-- Add foreign keys to cart table
ALTER TABLE `cart` 
ADD CONSTRAINT `cart_ibfk_1` 
FOREIGN KEY (`p_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `cart` 
ADD CONSTRAINT `cart_ibfk_2` 
FOREIGN KEY (`c_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Add foreign key to orders table
ALTER TABLE `orders` 
ADD CONSTRAINT `orders_ibfk_1` 
FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

-- Add foreign keys to orderdetails table
ALTER TABLE `orderdetails` 
ADD CONSTRAINT `orderdetails_ibfk_1` 
FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

ALTER TABLE `orderdetails` 
ADD CONSTRAINT `orderdetails_ibfk_2` 
FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

-- Add foreign keys to payment table
ALTER TABLE `payment` 
ADD CONSTRAINT `payment_ibfk_1` 
FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

ALTER TABLE `payment` 
ADD CONSTRAINT `payment_ibfk_2` 
FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

COMMIT;