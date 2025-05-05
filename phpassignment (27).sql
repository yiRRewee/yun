-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2025 at 03:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phpassignment`
--
CREATE DATABASE IF NOT EXISTS `yun` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `yun`;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address_line` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postcode` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`address_id`, `user_id`, `full_name`, `address_line`, `city`, `postcode`, `phone`, `is_default`) VALUES
(38, 8, 'YipikSB', '+++', 'Perlis', '14300', '0192829101', 1),
(39, 10, '', 'test', 'Selangor', '11232', '0192829102', 1),
(40, 11, 'weien', 'test', 'Sarawak', '12345', '0192738191', 1),
(41, 12, 'qiheng', 'test', 'Sarawak', '12345', '0192837812', 1),
(43, 1, 'Tan Bo Chen', 'test', 'Pulau Pinang', '12345', '0192883828', 0),
(44, 15, '', 'test', 'Sarawak', '12345', '0192738291', 1),
(45, 16, 'Tan Wei Hong', 'test', 'Perak', '12345', '01827362812', 1),
(46, 17, 'Chan Jean Theng', 'test', 'Pulau Pinang', '12345', '01927327212', 1),
(47, 18, 'Akau', 'test', 'Kelantan', '12345', '01927212121', 1),
(50, 21, 'Lisa', 'test test', 'Terrengganu', '12332', '01928398372', 1),
(51, 1, 'Tan Bo Chen', '77 taman sintar', 'Pulau Pinang', '11111', '0192883828', 1),
(55, 2, 'Yeoh San Lim', 'test', 'Selangor', '14302', '0182937283', 0),
(56, 2, 'Yeoh San Lim', 'test', 'Perak', '32323', '01828128212', 1),
(58, 23, 'Kor Jia Xuan', 'test', 'Perak', '32323', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin_product_edit_logs`
--

CREATE TABLE `admin_product_edit_logs` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `admin_user_id` int(11) NOT NULL,
  `changes` text DEFAULT NULL,
  `edited_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_product_edit_logs`
--

INSERT INTO `admin_product_edit_logs` (`id`, `product_id`, `admin_user_id`, `changes`, `edited_at`) VALUES
(4, 42, 1, 'Brand:  → Bouquet', '2025-05-01 12:06:47'),
(5, 42, 1, 'Brand:  → Bouquet, Stock: 0 → 9', '2025-05-01 12:06:47'),
(6, 42, 1, 'Brand:  → Bouquet, Discount: 0% → 9%', '2025-05-01 12:06:47'),
(7, 42, 1, 'Brand:  → Bouquet, Stock: 0 → 9', '2025-05-01 12:06:47'),
(8, 42, 1, 'Brand:  → Bouquet, Discount: 0% → 9%', '2025-05-01 12:06:47'),
(9, 42, 1, 'Brand:  → Bouquet, Stock: 0 → 9', '2025-05-01 12:06:47'),
(10, 42, 1, 'Brand:  → Bouquet, Stock: 0 → 9', '2025-05-01 12:06:47'),
(12, 42, 1, 'Brand:  → Bouquet, Discount: 9% → 8%', '2025-05-01 12:06:47'),
(13, 42, 1, 'Brand:  → Bouquet, Stock: 9 → 8', '2025-05-01 12:06:47'),
(14, 42, 1, 'Brand:  → Bouquet, Stock: 8 → 7', '2025-05-01 12:07:01'),
(15, 42, 1, 'Brand:  → Bouquet', '2025-05-01 12:59:48'),
(16, 42, 1, 'Brand:  → Bouquet', '2025-05-01 13:04:47'),
(17, 42, 1, 'Brand:  → Bouquet, Price: RM0.00 → RM100', '2025-05-01 13:05:52'),
(19, 47, 1, 'Brand:  → Bouquet', '2025-05-01 13:08:26');

-- --------------------------------------------------------

--
-- Table structure for table `admin_user_edit_logs`
--

CREATE TABLE `admin_user_edit_logs` (
  `email` varchar(255) NOT NULL,
  `log_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) DEFAULT NULL,
  `edited_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_user_edit_logs`
--

INSERT INTO `admin_user_edit_logs` (`email`, `log_id`, `admin_id`, `user_id`, `action`, `edited_at`) VALUES
('yipik123@gmail.com', 1, 1, 8, 'Updated profile for user: Yipik', '2025-04-19 00:54:38'),
('yipik123@gmail.com', 2, 1, 8, 'Updated profile for user: Yipik with changes: Profile Image: /assets/image/uploads/50b65fec6733ac01780b3070086213c1_w500_h300_cp.jpg -> /assets/image/uploads/oYvnRAYD2Q4ACMdePDIAgeGdADSLICPfIAcBe3~tplv-dy-aweme-images_q75.webp, Password: Updated', '2025-04-19 01:00:50'),
('qh123@gmail.com', 3, 1, 12, 'Updated profile for user: qiheng with changes: Phone: 0192837812 -> 0192832112', '2025-04-25 00:28:44'),
('lisa@gmail.com', 4, 1, 21, 'Updated profile for user: Lisa with changes: Email: lisa@gmail.com -> lisa@gmail.c', '2025-04-27 02:58:36'),
('lisa@gmail.com', 5, 1, 21, 'Updated profile for user: Lisa with changes: Phone: 0192736718 -> 01928398372', '2025-04-27 03:17:56'),
('lisa@gmail.com', 6, 1, 21, 'Updated profile for user: Lisa with changes: Profile Image: Updated', '2025-04-27 03:18:58'),
('bochentan0187@gmail.com', 7, 23, 1, 'Updated profile for user: Tan Bo Chen with changes: Password: Updated', '2025-04-29 17:38:51'),
('bochentan0187@gmail.com', 8, 23, 1, 'Updated profile for user: Tan Bo Chen with changes: Password: Updated', '2025-04-29 17:41:10'),
('yp123@gmail.com', 9, 1, 8, 'Updated profile for user: Yipik with changes: Username: Yipik -> YipikSB', '2025-05-01 20:15:03');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` enum('category','all') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `type`) VALUES
(14, 'bouquet', 'category'),
(15, 'key chain', 'category');

-- --------------------------------------------------------

--
-- Table structure for table `home_screen`
--

CREATE TABLE `home_screen` (
  `id` int(11) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `type` enum('video','image') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `home_screen`
--

INSERT INTO `home_screen` (`id`, `path`, `title`, `type`) VALUES
(1, '../assets/image/homeScreen/excited-group-of-college-graduates-throwing-their-royalty-free-image-1646946850.jpg', '\"Blooms & Bling for Grads – Flowers + Keychain Combo\"', 'image');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('Pending','Requested-Cancel','Shipped','Cancelled','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_number` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `address_line` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `is_reviewed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `created_at`, `order_number`, `full_name`, `address_line`, `city`, `postcode`, `phone`, `is_reviewed`) VALUES
(64, 2, 90.00, 'Cancelled', '2025-05-01 07:36:48', 'ORD-20250501-0001', 'Yeoh San Lim', 'test', 'Selangor', '14302', '0182937283', 0),
(65, 2, 38.00, 'Completed', '2025-05-01 12:17:09', 'ORD-20250501-0002', 'Yeoh San Lim', 'test', 'Perak', '32323', '01828128212', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_cancel`
--

CREATE TABLE `order_cancel` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('Requested-Cancel','approved','rejected') DEFAULT 'Requested-Cancel',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_cancel`
--

INSERT INTO `order_cancel` (`id`, `order_id`, `user_id`, `reason`, `status`, `created_at`, `updated_at`) VALUES
(17, 64, 2, 'NOTHING', 'approved', '2025-05-01 07:40:56', '2025-05-01 07:43:54');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_checkout` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price_at_checkout`) VALUES
(1, 64, 42, 1, 100.00),
(2, 65, 41, 2, 20.00);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `reset_token` char(32) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` int(3) DEFAULT 0,
  `sold_count` int(11) NOT NULL DEFAULT 0,
  `stock` int(11) NOT NULL,
  `category` enum('bouquet','key chain') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `discount`, `sold_count`, `stock`, `category`) VALUES
(41, 'Key Chain', 20.00, 5, 3, 8, 'key chain'),
(42, 'Rose bouquet Red', 100.00, 8, 2, 7, 'bouquet'),
(44, 'Rose bouquet Pink', 99.00, 10, 0, 10, 'bouquet'),
(45, 'Rose bouquet Red (White Plastic)', 105.00, 0, 0, 10, 'bouquet'),
(46, 'Graduation Blue Bouquet', 79.00, 0, 0, 10, 'bouquet'),
(47, 'Graduation Orange Bouquet', 79.00, 0, 0, 10, 'bouquet'),
(49, 'Funny KeyChain Set', 30.00, 3, 0, 10, 'key chain'),
(50, 'Beautiful Key Chain Set', 40.00, 10, 0, 10, 'key chain'),
(51, 'Couple Key Chain Set', 50.00, 0, 0, 10, 'key chain'),
(54, 'Valorant Key Chain Set', 100.00, 0, 0, 100, 'key chain'),
(55, 'Graduation Pink Bouquet', 79.00, 0, 0, 10, 'bouquet');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `product_id`, `category_id`) VALUES
(1, 41, 15),
(2, 42, 14),
(4, 54, 15),
(6, 44, 14),
(7, 45, 14),
(8, 46, 14),
(10, 47, 14),
(11, 49, 15),
(12, 50, 15),
(13, 51, 15),
(15, 55, 14);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`) VALUES
(95, 41, '../assets/image/products/key.webp'),
(96, 41, '../assets/image/products/keyChain2.webp'),
(97, 41, '../assets/image/products/keyChain3.webp'),
(98, 42, '../assets/image/products/bouquet4.webp'),
(102, 44, '../assets/image/uploads/681371a57f52b.jpg'),
(103, 45, '../assets/image/uploads/681371d1e5b4c.jpg'),
(104, 46, '../assets/image/uploads/6813720059a94.jpg'),
(105, 47, '../assets/image/uploads/6813721996a5d.jpg'),
(108, 47, '../assets/image/uploads/6813724aabf57.jpg'),
(109, 49, '../assets/image/uploads/681373a107acb.jpg'),
(110, 50, '../assets/image/uploads/681373c56a5da.jpg'),
(111, 51, '../assets/image/uploads/681373ec87ad2.jpg'),
(114, 54, '../assets/image/uploads/6813789317728.jpg'),
(115, 55, '../assets/image/uploads/681378bf535cc.jpg'),
(116, 55, '../assets/image/uploads/681378bf5c045.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `review_text`, `created_at`) VALUES
(98, 41, 2, 1, 'shit shoe bro fk man, fake like shit, is this a real shoe shop\r\n\r\n', '2025-05-01 12:21:04'),
(99, 41, 2, 5, 'men what can i say', '2025-05-01 12:26:11'),
(100, 41, 2, 1, 'dumb ass', '2025-05-01 12:27:31');

-- --------------------------------------------------------

--
-- Table structure for table `review_image`
--

CREATE TABLE `review_image` (
  `id` int(11) NOT NULL,
  `review_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review_image`
--

INSERT INTO `review_image` (`id`, `review_id`, `image_path`) VALUES
(22, 99, '68136863f1b7d.jpg'),
(23, 100, '681368b3ac747.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone_num` varchar(12) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin','manager') NOT NULL DEFAULT 'user',
  `profile_image` varchar(255) DEFAULT 'uploads/default.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone_num`, `password`, `role`, `profile_image`, `created_at`) VALUES
(1, 'Tan Bo Chen', 'bochentan0187@gmail.com', '0192883828', 'bc199aa0ec01235f97c118864f81681cdb8699ea', 'admin', '/assets/image/uploads/1744978364_w700d1q75cms.jpg', '2025-03-06 04:23:00'),
(2, 'Yeoh San Lim', 'ysl123@gmail.com', '0192878921', '7c222fb2927d828af22f592134e8932480637c0d', 'user', '/assets/image/uploads/1745850668_images - Copy.jpeg', '2025-03-06 04:23:46'),
(8, 'YipikSB', 'yp123@gmail.com', '0192829101', '7c222fb2927d828af22f592134e8932480637c0d', 'user', '/assets/image/uploads/oYvnRAYD2Q4ACMdePDIAgeGdADSLICPfIAcBe3~tplv-dy-aweme-images_q75.webp', '2025-04-18 08:13:47'),
(10, 'jaosn', 'jason123@gmail.com', '0192829102', '7c222fb2927d828af22f592134e8932480637c0d', 'user', '/assets/image/uploads/1744975226_LAL_Bryant_Kobe-900x506.jpg', '2025-04-18 11:20:26'),
(11, 'weien', 'weien123@gmail.com', '0192738191', '7c222fb2927d828af22f592134e8932480637c0d', 'user', '/assets/image/uploads/1744975783_lebron.png', '2025-04-18 11:29:43'),
(12, 'qiheng', 'qh123@gmail.com', '0192832112', '7c222fb2927d828af22f592134e8932480637c0d', 'user', '/assets/image/uploads/1744975871_50b65fec6733ac01780b3070086213c1_w500_h300_cp.jpg', '2025-04-18 11:31:11'),
(15, 'ruikang', 'ruikang123@gmail.com', '0192738291', '7c222fb2927d828af22f592134e8932480637c0d', 'user', '/assets/image/uploads/1744984942_oYvnRAYD2Q4ACMdePDIAgeGdADSLICPfIAcBe3~tplv-dy-aweme-images_q75.webp', '2025-04-18 14:02:22'),
(16, 'Tan Wei Hong', 'twh@gmail.com', '01827362812', '7c222fb2927d828af22f592134e8932480637c0d', 'user', '/assets/image/uploads/1745001916_okoeIACjIW9DGHAuRbSJMQkpleAgcn6mxADgAA~tplv-dy-aweme-images_q75.webp', '2025-04-18 18:45:16'),
(17, 'Chan Jean Theng', 'cjt123@gmail.com', '01927327212', '7c222fb2927d828af22f592134e8932480637c0d', 'admin', '/assets/image/uploads/1745001950_okoeIACjIW9DGHAuRbSJMQkpleAgcn6mxADgAA~tplv-dy-aweme-images_q75.webp', '2025-04-18 18:45:50'),
(18, 'Akau', 'akau@gmail.com', '01927212121', '7c222fb2927d828af22f592134e8932480637c0d', 'user', '/assets/image/uploads/1745519878_okoeIACjIW9DGHAuRbSJMQkpleAgcn6mxADgAA~tplv-dy-aweme-images_q75.webp', '2025-04-24 18:37:58'),
(21, 'Lisa', 'lisa@gmail.com', '01928398372', '7c222fb2927d828af22f592134e8932480637c0d', 'user', '/assets/image/uploads/Screenshot 2025-04-27 031836.png', '2025-04-24 18:45:51'),
(23, 'Kor Jia Xuan', 'kjx123@gmail.com', '0105201314', 'bc199aa0ec01235f97c118864f81681cdb8699ea', 'manager', '/assets/image/uploads/1745917441_Screenshot 2025-04-29 162641.png', '2025-04-29 08:26:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admin_product_edit_logs`
--
ALTER TABLE `admin_product_edit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `admin_user_id` (`admin_user_id`);

--
-- Indexes for table `admin_user_edit_logs`
--
ALTER TABLE `admin_user_edit_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`),
  ADD KEY `fk_product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `home_screen`
--
ALTER TABLE `home_screen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_cancel`
--
ALTER TABLE `order_cancel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `review_image`
--
ALTER TABLE `review_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `review_id` (`review_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_num` (`phone_num`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `admin_product_edit_logs`
--
ALTER TABLE `admin_product_edit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `admin_user_edit_logs`
--
ALTER TABLE `admin_user_edit_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `order_cancel`
--
ALTER TABLE `order_cancel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `review_image`
--
ALTER TABLE `review_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_product_edit_logs`
--
ALTER TABLE `admin_product_edit_logs`
  ADD CONSTRAINT `admin_product_edit_logs_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_product_edit_logs_ibfk_2` FOREIGN KEY (`admin_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_user_edit_logs`
--
ALTER TABLE `admin_user_edit_logs`
  ADD CONSTRAINT `admin_user_edit_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `admin_user_edit_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_cancel`
--
ALTER TABLE `order_cancel`
  ADD CONSTRAINT `order_cancel_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_cancel_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `review_image`
--
ALTER TABLE `review_image`
  ADD CONSTRAINT `review_image_ibfk_1` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
