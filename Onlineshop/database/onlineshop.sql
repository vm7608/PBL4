-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 28, 2022 lúc 01:40 PM
-- Phiên bản máy phục vụ: 10.4.27-MariaDB
-- Phiên bản PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `onlineshop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin_info`
--

CREATE TABLE `admin_info` (
  `admin_id` int(10) NOT NULL,
  `admin_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `admin_email` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `admin_password` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `admin_info`
--

INSERT INTO `admin_info` (`admin_id`, `admin_name`, `admin_email`, `admin_password`) VALUES
(1, 'admin', 'admin@gmail.com', '25f9e794323b453885f5181f1b624d0b');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(100) NOT NULL,
  `brand_title` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_title`) VALUES
(1, 'HP'),
(2, 'Samsung'),
(3, 'Apple'),
(4, 'motorolla'),
(5, 'LG'),
(6, 'Cloth Brand'),
(7, 'Khác');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(10) NOT NULL,
  `p_id` int(10) NOT NULL,
  `ip_add` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `qty` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `p_id`, `ip_add`, `user_id`, `qty`) VALUES
(6, 26, '::1', 4, 1),
(9, 10, '::1', 7, 1),
(10, 11, '::1', 7, 1),
(11, 45, '::1', 7, 1),
(44, 5, '::1', 3, 0),
(46, 2, '::1', 3, 0),
(48, 72, '::1', 3, 0),
(49, 60, '::1', 8, 1),
(50, 61, '::1', 8, 1),
(51, 1, '::1', 8, 1),
(52, 5, '::1', 9, 1),
(53, 2, '::1', 14, 1),
(54, 3, '::1', 14, 1),
(55, 5, '::1', 14, 1),
(56, 1, '::1', 9, 1),
(57, 2, '::1', 9, 1),
(71, 61, '127.0.0.1', -1, 1),
(148, 10, '::1', 1, 1),
(162, 6, '::1', 26, 1),
(163, 2, '::1', 29, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(100) NOT NULL,
  `cat_title` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_title`) VALUES
(1, 'Electronics'),
(2, 'Ladies Wears'),
(3, 'Mens Wear'),
(4, 'Kids Wear'),
(5, 'Furnitures'),
(6, 'Home Appliances'),
(7, 'Electronics Gadgets');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comment`
--

CREATE TABLE `comment` (
  `Id` int(11) NOT NULL,
  `User_id` int(11) DEFAULT NULL,
  `Product_id` int(11) DEFAULT NULL,
  `Content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `comment`
--

INSERT INTO `comment` (`Id`, `User_id`, `Product_id`, `Content`) VALUES
(1, 26, 71, 'Điện thoại đẹp, xịn'),
(3, 26, 1, 'Phong cách, thời trang'),
(4, 26, 1, 'Đẹp'),
(5, 26, 3, 'Đẹp và tốt <:>'),
(24, 26, 1, 'Test'),
(25, 16, 4, 'hehehehe'),
(26, 29, 4, 'wwao');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders_info`
--

CREATE TABLE `orders_info` (
  `order_id` int(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  `f_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `zip` int(10) NOT NULL,
  `cardname` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cardnumber` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `expdate` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `prod_count` int(15) DEFAULT NULL,
  `total_amt` int(15) DEFAULT NULL,
  `cvv` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `orders_info`
--

INSERT INTO `orders_info` (`order_id`, `user_id`, `f_name`, `email`, `address`, `city`, `state`, `zip`, `cardname`, `cardnumber`, `expdate`, `prod_count`, `total_amt`, `cvv`) VALUES
(1, 12, 'Puneeth', 'puneethreddy951@gmail.com', 'Kiet 9 Lac Long Quan', 'Da Nang', 'Viet Nam', 560074, 'pokjhgfcxc', '4321 2345 6788 7654', '12/90', 3, 77000, 1234),
(2, 26, 'Luong Thien', 'luongthiendn1@gmail.com', 'to 2 tuy hoa', 'Da nang', 'Viet Nam', 560074, 'hfndchsgrggg', '4321 2345 6788 7654', '12/22', 3, 60000, 1111);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_products`
--

CREATE TABLE `order_products` (
  `order_pro_id` int(10) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(15) DEFAULT NULL,
  `amt` int(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `order_products`
--

INSERT INTO `order_products` (`order_pro_id`, `order_id`, `product_id`, `qty`, `amt`) VALUES
(73, 1, 1, 1, 5000),
(74, 1, 4, 2, 64000),
(75, 1, 8, 1, 40000),
(91, 2, 1, 3, 15000),
(92, 2, 2, 1, 25000),
(93, 2, 3, 1, 30000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `product_id` int(100) NOT NULL,
  `product_cat` int(100) NOT NULL,
  `product_brand` int(100) NOT NULL,
  `product_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `product_price` int(100) NOT NULL,
  `product_desc` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `product_image` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `product_keywords` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`product_id`, `product_cat`, `product_brand`, `product_title`, `product_price`, `product_desc`, `product_image`, `product_keywords`) VALUES
(1, 1, 2, 'Samsung galaxy s10', 5000, 'Samsung galaxy s10', 'product10.png', 'samsung mobile electronics'),
(2, 1, 3, 'iPhone 5s', 25000, 'iphone 5s', 'http___pluspng.com_img-png_iphone-hd-png-iphone-apple-png-file-550.png', 'mobile iphone apple'),
(3, 1, 3, 'iPad air 2', 30000, 'ipad apple brand', 'da4371ffa192a115f922b1c0dff88193.png', 'apple ipad tablet'),
(4, 1, 3, 'iPhone 6s', 32000, 'Apple iPhone ', 'http___pluspng.com_img-png_iphone-6s-png-iphone-6s-gold-64gb-1000.png', 'iphone apple mobile'),
(5, 1, 2, 'iPad 2', 10000, 'samsung ipad', 'iPad-air.png', 'ipad tablet samsung'),
(6, 1, 1, 'HP Laptop r series', 35000, 'HP Black combination Laptop', 'laptop_PNG5939.png', 'Hp laptop '),
(7, 1, 1, 'Laptop Pavillion', 50000, 'Laptop Hp Pavillion', 'laptop_PNG5930.png', 'Laptop Hp Pavillion'),
(8, 1, 3, 'iPhone 9 Pro Max', 10000, 'iphone', 'Iphone-9.png', 'iphone apple mobile'),
(9, 1, 3, 'iPhone 11 Pro Max', 12000, 'iphone', 'iphone-hd-png-iphone-apple-png-file-550.png', 'iphone apple mobile'),
(10, 2, 6, 'Red Ladies dress', 1000, 'red dress for girls', 'red dress.jpg', 'red dress '),
(12, 2, 6, 'Ladies Casual Cloths', 1500, 'ladies casual summer two colors pleted', '7475-ladies-casual-dresses-summer-two-colors-pleated.jpg', 'girl dress cloths casual'),
(13, 2, 6, 'SpringAutumnDress', 1200, 'girls dress', 'Spring-Autumn-Winter-Young-Ladies-Casual-Wool-Dress-Women-s-One-Piece-Dresse-Dating-Clothes-Medium.jpg_640x640.jpg', 'girl dress'),
(14, 2, 6, 'Casual Dress', 400, 'girl dress', 'download.jpg', 'ladies cloths girl'),
(15, 2, 6, 'Formal Look', 1500, 'girl dress', 'shutterstock_203611819.jpg', 'ladies wears dress girl'),
(16, 3, 6, 'Sweter for men', 600, '2012-Winter-Sweater-for-Men-for-better-outlook', '2012-Winter-Sweater-for-Men-for-better-outlook.jpg', 'black sweter cloth winter'),
(17, 3, 6, 'Gents formal', 1000, 'gents formal look', 'gents-formal-250x250.jpg', 'gents wear cloths'),
(18, 3, 6, 'Formal Coat', 3000, 'ad', 'images (1).jpg', 'coat blazer gents'),
(19, 3, 6, 'Mens Sweeter', 1600, 'jg', 'Winter-fashion-men-burst-sweater.png', 'sweeter gents '),
(20, 3, 6, 'T shirt', 800, 'ssds', 'IN-Mens-Apparel-Voodoo-Tiles-09._V333872612_.jpg', 'formal t shirt black'),
(21, 4, 6, 'Yellow T shirt ', 1300, 'yello t shirt with pant', '1.0x0.jpg', 'kids yellow t shirt'),
(22, 4, 6, 'Girls cloths', 1900, 'sadsf', 'GirlsClothing_Widgets.jpg', 'formal kids wear dress'),
(24, 4, 6, 'Blue T shirt', 700, 'g', 'images.jpg', 'kids dress'),
(25, 2, 6, 'Yellow girls dress', 750, 'as', 'images (3).jpg', 'yellow kids dress'),
(26, 6, 5, 'Washing Machine', 25000, 'LG Inverter Direct Drive Technology? Front Load Washing Machine 7KG, 6 Motion DD?, Smart Diagnosis?', 'lauchy.png', 'book shelf furniture'),
(27, 4, 6, 'Formal look', 690, 'sd', 'image28.jpg', 'formal kids dress'),
(32, 5, 7, 'Book Shelf', 2500, 'book shelf', 'furniture-book-shelf-250x250.jpg', 'book shelf furniture'),
(33, 6, 2, 'Refrigerator', 35000, 'Refrigerator', 'CT_WM_BTS-BTC-AppliancesHome_20150723.jpg', 'refrigerator samsung'),
(34, 6, 4, 'Emergency Light', 1000, 'Emergency Light', 'emergency light.jpg', 'emergency light'),
(35, 6, 7, 'Vaccum Cleaner', 6000, 'LG CordZero Kompressor Universal Power ThinQ Stick Vacuum', 'images (2).jpg', 'Vaccum Cleaner'),
(36, 6, 5, 'Iron', 1500, 'gj', 'iron.jpg', 'iron'),
(37, 6, 5, 'Smart Tivi LG', 20000, 'Smart Tivi LG NanoCell 4K 65 inch 65NANO76SQA', 'images (4).jpg', 'tv lg'),
(39, 6, 5, 'Mixer Grinder', 2500, 'Mixer Grinder', 'singer-mixer-grinder-mg-46-medium_4bfa018096c25dec7ba0af40662856ef.jpg', 'Mixer Grinder'),
(40, 2, 6, 'Formal girls dress', 3000, 'Formal girls dress', 'girl-walking.jpg', 'ladies'),
(45, 1, 2, 'Samsung Galaxy Note 3', 10000, '0', 'samsung_galaxy_note3_note3neo.jpg', 'samsung galaxy Note 3 neo'),
(46, 1, 2, 'Samsung Galaxy Note 5', 11200, '', 'samsung_galaxy_note5_note5.jpg', 'samsung galxaxy Note 5'),
(47, 1, 2, 'Laptop', 650, 'nbk', 'product01.png', 'Dell Laptop'),
(50, 3, 6, 'boys shirts', 350, 'shirts', 'pm1.jpg', 'suit boys shirts'),
(51, 3, 6, 'boys shirts', 270, 'shirts', 'pm2.jpg', 'suit boys shirts'),
(52, 3, 6, 'boys shirts', 453, 'shirts', 'pm3.jpg', 'suit boys shirts'),
(53, 3, 6, 'boys shirts', 220, 'shirts', 'ms1.jpg', 'suit boys shirts'),
(54, 3, 6, 'boys shirts', 290, 'shirts', 'ms2.jpg', 'suit boys shirts'),
(56, 3, 6, 'boys shirts', 299, 'shirts', 'pm7.jpg', 'suit boys shirts'),
(57, 3, 6, 'boys shirts', 260, 'shirts', 'i3.jpg', 'suit boys shirts'),
(58, 3, 6, 'boys shirts', 350, 'shirts', 'pm9.jpg', 'suit boys shirts'),
(59, 3, 6, 'boys shirts', 275, 'shirts', 'a2.jpg', 'suit boys shirts'),
(60, 3, 6, 'boys shirts', 150, 'shirts', 'pm11.jpg', 'suit boys shirts'),
(61, 3, 6, 'boys shirts', 215, 'shirts', 'pm12.jpg', 'suit boys shirts'),
(62, 3, 6, 'boys shirts', 299, 'shirts', 'pm13.jpg', 'suit boys shirts'),
(63, 3, 6, 'boys Jeans Pant', 550, 'Pants', 'pt1.jpg', 'boys Jeans Pant'),
(64, 3, 6, 'boys Jeans Pant', 460, 'pants', 'pt2.jpg', 'boys Jeans Pant'),
(65, 3, 6, 'boys Jeans Pant', 470, 'pants', 'pt3.jpg', 'boys Jeans Pant'),
(66, 3, 6, 'boys Jeans Pant', 480, 'pants', 'pt4.jpg', 'boys Jeans Pant'),
(67, 3, 6, 'boys Jeans Pant', 360, 'pants', 'pt5.jpg', 'boys Jeans Pant'),
(68, 3, 6, 'boys Jeans Pant', 550, 'pants', 'pt6.jpg', 'boys Jeans Pant'),
(69, 3, 6, 'boys Jeans Pant', 390, 'pants', 'pt7.jpg', 'boys Jeans Pant'),
(70, 3, 6, 'boys Jeans Pant', 399, 'pants', 'pt8.jpg', 'boys Jeans Pant'),
(71, 1, 2, 'Samsung galaxy s7', 5000, 'Samsung galaxy s7', 'product07.png', 'samsung mobile electronics'),
(72, 6, 5, 'Refrigerator LG', 30000, 'Tủ lạnh LG Inverter InstaView Door-in-Door 601 L GR-X247JS', 'tu-lanh-samsung_main_66_1020.png', 'samsung Headphones electronics gadgets'),
(73, 7, 2, 'samsung Headphones', 3500, 'samsung Headphones', 'product05.png', 'samsung Headphones electronics gadgets'),
(74, 1, 1, 'HP i5 laptop', 5500, 'HP i5 laptop', 'product01.png', 'HP i5 laptop electronics'),
(75, 1, 1, 'HP i7 laptop 8gb ram', 5500, 'HP i7 laptop 8gb ram', 'product03.png', 'HP i7 laptop 8gb ram electronics'),
(76, 1, 5, 'sony note 6gb ram', 4500, 'sony note 6gb ram', 'product04.png', 'sony note 6gb ram mobile electronics'),
(77, 1, 4, 'MSV laptop 16gb ram NVIDEA Graphics', 5499, 'MSV laptop 16gb ram', 'product06.png', 'MSV laptop 16gb ram NVIDEA Graphics electronics'),
(78, 1, 5, 'Laptop LG Gram 2021', 4579, 'Laptop LG Gram 2021 - 14ZD90P - G.AX56A5 - i5-1135G7/16GB/512GB/Intel Iris Xe/14.0 inch WUXGA/FreeDos/B?c', 'product08.png', 'dell laptop 8gb ram intel integerated Graphics electronics'),
(79, 7, 2, 'Camera Samsung', 2569, 'camera with 3D pixels', '1672230703_product09.png', 'Camera samsung '),
(82, 5, 7, 'Dinner Table', 1000, 'Woolean dinning table', '1672230465_amer-furniture.jpg', 'eat'),
(83, 5, 7, 'Twin Bed', 2000, 'Glorious Twin Bed', '1672230542_bedroom-furniture-250x250.jpg', 'giường'),
(84, 5, 7, 'Decorative Bookshelf', 700, 'Decorative Bookshelf', '1672230605_asian-furniture-250x250.jpg', 'ke sach');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_info`
--

CREATE TABLE `user_info` (
  `user_id` int(10) NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address1` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address2` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `user_info`
--

INSERT INTO `user_info` (`user_id`, `first_name`, `last_name`, `email`, `password`, `mobile`, `address1`, `address2`) VALUES
(12, 'puneeth', 'Reddy', 'puneethreddy951@gmail.com', '25f9e794323b453885f5181f1b624d0b', '9448121558', 'Nha Trang', 'abc'),
(16, 'venky', 'vs', 'venkey@gmail.com', '25f9e794323b453885f5181f1b624d0b', '9877654334', 'Da Nang', 'Hoa Khanh'),
(19, 'abhishek', 'bs', 'abhishekbs@gmail.com', '25f9e794323b453885f5181f1b624d0b', '9871236534', 'Da Nang', 'Hoa Khanh'),
(21, 'prajval', 'mcta', 'prajvalmcta@gmail.com', '25f9e794323b453885f5181f1b624d0b', '202-555-01', 'Da Nang', 'Hoa Khanh'),
(23, 'hemanth', 'reddy', 'hemanth@gmail.com', '25f9e794323b453885f5181f1b624d0b', '9876543234', 'Da Nang', 'Hoa Khanh'),
(24, 'newuser', 'user', 'newuser@gmail.com', '25f9e794323b453885f5181f1b624d0b', '9535688928', 'Da Nang', 'Hoa Khanh'),
(25, 'otheruser', 'user', 'otheruser@gmail.com', '25f9e794323b453885f5181f1b624d0b', '9535688928', 'Da Nang', 'Hoa Khanh'),
(26, 'Luong', 'Thien', 'luongthiendn1@gmail.com', '25f9e794323b453885f5181f1b624d0b', '0905718777', 'Da Nang', 'Hoa Vang'),
(28, 'Go', 'Test', 'test@gmail.com', '25f9e794323b453885f5181f1b624d0b', '0909222333', 'Da Nang', 'Hai Chau'),
(29, 'manh', 'cao', 'manh@gmail.com', '87bbd88248ee8ab59bfd9c92faf03264', '1234567890', 'Da Nang', 'Hoa Khanh');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin_info`
--
ALTER TABLE `admin_info`
  ADD PRIMARY KEY (`admin_id`);

--
-- Chỉ mục cho bảng `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Chỉ mục cho bảng `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `User_id` (`User_id`),
  ADD KEY `Product_id` (`Product_id`);

--
-- Chỉ mục cho bảng `orders_info`
--
ALTER TABLE `orders_info`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`order_pro_id`),
  ADD KEY `order_products` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Chỉ mục cho bảng `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin_info`
--
ALTER TABLE `admin_info`
  MODIFY `admin_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `comment`
--
ALTER TABLE `comment`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `orders_info`
--
ALTER TABLE `orders_info`
  MODIFY `order_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `order_products`
--
ALTER TABLE `order_products`
  MODIFY `order_pro_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT cho bảng `user_info`
--
ALTER TABLE `user_info`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `user_info` (`user_id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`Product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `orders_info`
--
ALTER TABLE `orders_info`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user_info` (`user_id`);

--
-- Các ràng buộc cho bảng `order_products`
--
ALTER TABLE `order_products`
  ADD CONSTRAINT `order_products` FOREIGN KEY (`order_id`) REFERENCES `orders_info` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
