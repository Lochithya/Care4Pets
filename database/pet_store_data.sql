-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2025 at 09:18 PM
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
-- Database: `pet_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'chinthana', '$2y$10$JLq9TEzP9sHmL9HoW974aO5Gbh.QIK4lLt2XEYRGxSzsvEQjW/hBG');

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

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(103, 3, 9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `firstname`, `lastname`, `email`, `subject`, `message`, `created_at`) VALUES
(11, 3, 'chinthana', 'sandeepa', 'lochithya12@gmail.com', 'good', 'very good', '2025-10-08 05:02:56'),
(12, 3, 'Lochithya', 'Hettiarachchi', 'lochithya12@gmail.com', 'quality', 'very good', '2025-10-08 06:04:55');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `order_date` date DEFAULT curdate(),
  `order_time` time DEFAULT curtime(),
  `delivery_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `order_date`, `order_time`, `delivery_date`) VALUES
(37, 3, 218.85, 'shipped', '2025-10-08', '11:41:30', '2025-10-29');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(34, 36, 10, 1, 8.99),
(35, 36, 2, 2, 49.98),
(36, 37, 1, 4, 119.96),
(37, 37, 10, 11, 98.89);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_type` enum('cash','card') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `masked_card_number` char(16) DEFAULT NULL,
  `cardholder_name` varchar(100) DEFAULT NULL,
  `expiry_date` char(5) DEFAULT NULL,
  `card_type` varchar(30) DEFAULT NULL,
  `transaction_status` varchar(20) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `payment_type`, `amount`, `masked_card_number`, `cardholder_name`, `expiry_date`, `card_type`, `transaction_status`, `payment_date`, `payment_time`) VALUES
(17, 36, 'cash', 58.97, NULL, NULL, NULL, NULL, 'Pending', '0000-00-00', '00:00:00'),
(18, 37, 'cash', 218.85, NULL, NULL, NULL, NULL, 'Pending', '0000-00-00', '00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `pet_types`
--

CREATE TABLE `pet_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pet_types`
--

INSERT INTO `pet_types` (`id`, `name`) VALUES
(3, 'Birds'),
(2, 'Cats'),
(1, 'Dogs'),
(9, 'rabbits'),
(4, 'Small Pets');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `pet_type_id` int(11) DEFAULT NULL,
  `product_type_id` int(11) DEFAULT NULL,
  `ratings` decimal(10,1) DEFAULT NULL,
  `img1` varchar(100) DEFAULT NULL,
  `img2` varchar(100) DEFAULT NULL,
  `img3` varchar(100) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock_quantity`, `image_url`, `pet_type_id`, `product_type_id`, `ratings`, `img1`, `img2`, `img3`, `supplier_id`) VALUES
(1, 'Premium Dog Food', 'High-quality dry dog food with natural ingredients for optimal nutrition', 29.99, 4, '../images/products/Product 01/img 01.jpg', 1, 2, 4.0, '../images/products/Product 01/img 02.avif', '../images/products/Product 01/img 03.webp', '../images/products/Product 01/img 04.jpg', 2),
(2, 'Natural Cat Food', 'Nutritious cat food made with real meat and vegetables', 24.99, 39, '../images/products/Product 02/img 01.jpg', 2, 2, 4.2, '../images/products/Product 02/img 02.webp', '../images/products/Product 02/img 03.jpg', '../images/products/Product 02/img 04.webp', 4),
(3, 'Waterproof Dog Collar', 'Durable and comfortable waterproof collar for dogs of all sizes', 15.99, 22, '../images/products/Product 03/img 01.jpg', 1, 4, 4.3, '../images/products/Product 03/img 02.webp', '../images/products/Product 03/img 03.webp', '../images/products/Product 03/img 04.jpg', 1),
(4, 'Premium Dog Leash', 'Strong and stylish leash perfect for daily walks', 19.99, 23, '../images/products/Product 04/img 01.jpg', 1, 4, 4.9, '../images/products/Product 04/img 02.png', '../images/products/Product 04/img 03.webp', '../images/products/Product 04/img 04.jpg', 6),
(5, 'Cat Scratching Post', 'Multi-level scratching post to keep your cat entertained', 39.99, 18, '../images/products/Product 05/img 01.jpg', 2, 3, 4.6, '../images/products/Product 05/img 02.webp', '../images/products/Product 05/img 03.jpg', '../images/products/Product 05/img 04.jpg', 1),
(7, 'Hamster Wheel', 'Silent running exercise wheel designed for hamsters and other small pets', 12.99, 39, '../images/products/Product 07/img 01.jpg', 4, 3, 4.9, '../images/products/Product 07/img 02.jpg', '../images/products/Product 07/img 03.webp', '../images/products/Product 07/img 04.jpg', 3),
(8, 'Dog Toy Set', 'Set of 5 interactive toys to keep your dog engaged', 22.99, 35, '../images/products/Product 08/img 01.webp', 1, 3, 4.6, '../images/products/Product 08/img 02.jpg', '../images/products/Product 08/img 03.jpg', '../images/products/Product 08/img 04.jpg', 2),
(9, 'Cat Litter Premium', 'Odor-control cat litter that clumps for easy cleaning', 16.99, 59, '../images/products/Product 09/img 01.webp', 2, 4, 5.0, '../images/products/Product 09/img 02.webp', '../images/products/Product 09/img 03.jpg', '../images/products/Product 09/img 04.webp', 1),
(10, 'Bird Food Mix', 'Nutritious seed mix for various bird species', 8.99, 43, '../images/products/Product 10/img 01.avif', 3, 2, 4.8, '../images/products/Product 10/img 02.jpg', '../images/products/Product 10/img 03.webp', '../images/products/Product 10/img 04.jpg', 5);

-- --------------------------------------------------------

--
-- Table structure for table `product_types`
--

CREATE TABLE `product_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_types`
--

INSERT INTO `product_types` (`id`, `name`) VALUES
(4, 'Accessories'),
(5, 'birds crr'),
(2, 'Food'),
(1, 'Pets'),
(3, 'Toys');

-- --------------------------------------------------------

--
-- Table structure for table `shipping`
--

CREATE TABLE `shipping` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `add_phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping`
--

INSERT INTO `shipping` (`id`, `order_id`, `first_name`, `last_name`, `address_line1`, `address_line2`, `city`, `state`, `postal_code`, `country`, `phone`, `add_phone`) VALUES
(27, 36, 'Lochithya', 'Hettiarachchi', 'No:483/1/2 ,Veedi Road , Enderamulla', 'paniyaambalangoda', 'Wattala', 'Western', '11300', 'Sri Lanka', '0705100823', ''),
(28, 37, 'Lochithya', 'Hettiarachchi', 'No:483/1/2 ,Veedi Road , Enderamulla', '', 'Wattala', 'Western', '11300', 'Sri Lanka', '0705100823', '');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `sup_name` varchar(100) NOT NULL,
  `sup_phone` varchar(100) NOT NULL,
  `sup_email` varchar(100) NOT NULL,
  `sup_address` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `sup_name`, `sup_phone`, `sup_email`, `sup_address`) VALUES
(1, 'Global Pet Supplies Ltd.', '011-2456789', 'contact@globalpetsupplies.com', 'No. 120, Union Place, Colombo 02'),
(2, 'Elite Pet Distributors Pvt. Ltd.', '011-2987654', 'info@elitepetdistributors.com', '45/3 Main Street, Kandy'),
(3, 'PetCare International', '011-3344556', 'support@petcareintl.com', '78 Galle Road, Galle'),
(4, 'Animal Essentials Pvt. Ltd.', '011-2765432', 'sales@animalessentials.com', '22 Central Road, Jaffna'),
(5, 'Premium Pet Traders', '011-2233445', 'enquiries@premiumpettraders.com', '56 Negombo Road, Negombo'),
(6, 'United Pet Products Co.', '011-2121212', 'service@unitedpetproducts.com', '89 Matara Road, Matara'),
(7, 'GlodenPet.PVT.LTD', '0777006582', 'chinthanasandeepa123@gmail.com', 'Ranasooriya Mawatha,paniyana'),
(8, 'chi', '', '', ''),
(9, 'Lochithya', '0705100823', 'lochithya12@gmail.com', 'fgdb');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avtar` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`, `first_name`, `last_name`, `phone`, `avtar`, `avatar`) VALUES
(3, 'Lochithya', '$2y$10$BvlE3josCP.XSrSpk9KrHuIyS3lJOX05br7S0jqqE1ygqOYJab7L6', 'lochithya12@gmail.com', '2025-10-07 16:44:39', 'Lochithya', 'Hettiarachchi', '0705100823', NULL, '../images/attachments/avatar_68e543773594d.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `pet_types`
--
ALTER TABLE `pet_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_products_pet_type` (`pet_type_id`),
  ADD KEY `fk_products_product_type` (`product_type_id`),
  ADD KEY `fk_supplier` (`supplier_id`);

--
-- Indexes for table `product_types`
--
ALTER TABLE `product_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_type` (`name`);

--
-- Indexes for table `shipping`
--
ALTER TABLE `shipping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pet_types`
--
ALTER TABLE `pet_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `product_types`
--
ALTER TABLE `product_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `shipping`
--
ALTER TABLE `shipping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_pet_type` FOREIGN KEY (`pet_type_id`) REFERENCES `pet_types` (`id`),
  ADD CONSTRAINT `fk_products_product_type` FOREIGN KEY (`product_type_id`) REFERENCES `product_types` (`id`),
  ADD CONSTRAINT `fk_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`);

--
-- Constraints for table `shipping`
--
ALTER TABLE `shipping`
  ADD CONSTRAINT `shipping_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
