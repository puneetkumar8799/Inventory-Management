-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2023 at 02:34 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `automotive_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_alerts`
--

CREATE TABLE `tbl_alerts` (
  `id` int(255) NOT NULL,
  `send_to` int(255) NOT NULL,
  `product_id` int(255) NOT NULL,
  `alert_send` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_alerts`
--

INSERT INTO `tbl_alerts` (`id`, `send_to`, `product_id`, `alert_send`) VALUES
(1, 4, 4, '2023-11-18 14:53:10.000000'),
(2, 4, 4, '2023-11-18 15:04:34.000000'),
(3, 4, 4, '2023-11-18 15:05:11.000000'),
(4, 4, 4, '2023-11-18 15:08:48.000000'),
(5, 4, 4, '2023-11-18 15:10:45.000000'),
(6, 4, 4, '2023-11-18 15:13:49.000000'),
(7, 4, 4, '2023-11-18 15:15:46.000000'),
(8, 4, 4, '2023-11-18 15:16:42.000000');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE `tbl_products` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `variant` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `mileage` int(255) NOT NULL,
  `price` int(255) NOT NULL,
  `transmission` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `count` int(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `added_by` int(255) NOT NULL,
  `date_added` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`id`, `name`, `variant`, `color`, `mileage`, `price`, `transmission`, `description`, `count`, `file`, `added_by`, `date_added`) VALUES
(1, 'Corolla', 'Xli 2011', 'white', 120000, 1500000, 'auto', 'This is beautiful car with a lot of inner space you will love it', 3, 'http://localhost/inventory_management/web/assets/productImage/xli.png', 2, '2023-11-15 11:02:04.000000'),
(2, 'Swift updated', 'Szuaki 2021  updated', 'violet', 133022, 123222, 'manual', 'This is an amazing car to drive within the city and a very comfortable one as well updated', 3, 'http://localhost/inventory_management/web/assets/productImage/xli.png', 2, '2023-11-17 21:36:47.000000'),
(3, 'Vitz', '2013 hybrid', 'silver', 112222, 22224, 'manual', 'This is a manual transmission car with very good petrol average..', 2, 'http://localhost/inventory_management/web/assets/productImage/558_1_94445.jpg', 2, '2023-11-15 11:02:07.000000'),
(4, 'Toyota Hilux', '2012', 'white', 12121212, 2550000, 'manual', 'Total genuine Brandnew Thai model 2012/2016 Brandnew tyres\r\nMention PakWheels.com when calling Seller to get a good deal', 1, 'http://localhost/inventory_management/web/assets/productImage/download.jpeg', 4, '2023-11-17 21:47:43.000000'),
(5, 'Audi Etron', '2023', 'silver', 12, 104900, 'auto', 'The e-tron GT is based on the brilliant Porsche Taycan, which means you get a very low center of gravity thanks to the placement of the batteries and the electric motors mounted to the axles.', 2, 'http://localhost/inventory_management/web/assets/productImage/maxresdefault.jpg', 4, '2023-11-18 15:09:01.000000');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `date_added` datetime(6) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `name`, `email`, `role`, `date_added`, `password`) VALUES
(2, 'Naveed', 'navidml6453@gmail.com', 'admin', '2023-11-13 18:35:35.000000', 'naveed'),
(4, 'Robert', 'naveed.ahmed6453@gmail.com', 'client', '2023-11-13 21:37:18.000000', 'robert');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_alerts`
--
ALTER TABLE `tbl_alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_alerts`
--
ALTER TABLE `tbl_alerts`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
