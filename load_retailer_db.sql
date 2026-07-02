-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2026 at 05:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `load_retailer_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `created_at`) VALUES
(1, 1, 'Added load to John Cruz', '2026-06-30 09:38:56'),
(2, 1, 'Added load to Maria Santos', '2026-06-30 09:38:56'),
(3, 1, 'Viewed weekly analytics', '2026-06-30 09:38:56'),
(4, 1, 'Created new staff account', '2026-06-30 09:38:56'),
(5, 1, 'Updated staff account', '2026-06-30 09:38:56'),
(6, 1, 'Deleted staff account', '2026-06-30 09:38:56'),
(7, 2, 'Sold Smart load', '2026-06-30 09:38:56'),
(8, 3, 'Sold Globe load', '2026-06-30 09:38:56'),
(9, 4, 'Sold DITO load', '2026-06-30 09:38:56'),
(10, 5, 'Sold TNT load', '2026-06-30 09:38:56');

-- --------------------------------------------------------

--
-- Table structure for table `load_inventory`
--

CREATE TABLE `load_inventory` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `network` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `load_inventory`
--

INSERT INTO `load_inventory` (`id`, `staff_id`, `network`, `amount`, `created_at`) VALUES
(1, 2, 'Smart', 1000.00, '2026-06-30 09:38:56'),
(2, 2, 'Globe', 800.00, '2026-06-30 09:38:56'),
(3, 3, 'Smart', 1200.00, '2026-06-30 09:38:56'),
(4, 3, 'TNT', 500.00, '2026-06-30 09:38:56'),
(5, 4, 'Globe', 1500.00, '2026-06-30 09:38:56'),
(6, 4, 'DITO', 700.00, '2026-06-30 09:38:56'),
(7, 5, 'Smart', 900.00, '2026-06-30 09:38:56'),
(8, 5, 'TNT', 550.00, '2026-06-30 09:38:56'),
(9, 2, 'DITO', 390.00, '2026-06-30 09:38:56'),
(10, 3, 'Globe', 1100.00, '2026-06-30 09:38:56'),
(11, 6, 'TNT', 100.00, '2026-06-30 16:32:17'),
(12, 6, 'TNT', 100.00, '2026-06-30 16:32:26'),
(13, 9, 'Smart', 100.00, '2026-06-30 16:35:47'),
(14, 5, 'Smart', 10.00, '2026-06-30 16:46:58'),
(15, 9, 'Smart', 10.00, '2026-06-30 16:56:23'),
(16, 9, 'TNT', 10.00, '2026-06-30 16:57:01'),
(17, 5, 'Smart', -90.00, '2026-06-30 16:57:15'),
(18, 9, 'Smart', 100.00, '2026-07-02 15:12:16');

-- --------------------------------------------------------

--
-- Table structure for table `load_sales`
--

CREATE TABLE `load_sales` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `network` varchar(50) NOT NULL,
  `load_amount` decimal(10,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `customer_number` varchar(20) NOT NULL,
  `date_sold` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `load_sales`
--

INSERT INTO `load_sales` (`id`, `staff_id`, `network`, `load_amount`, `price`, `customer_number`, `date_sold`) VALUES
(1, 2, 'Smart', 50.00, 50.00, '09123456701', '2026-06-30 17:38:56'),
(2, 2, 'Globe', 100.00, 100.00, '09123456702', '2026-06-30 17:38:56'),
(3, 3, 'TNT', 30.00, 30.00, '09123456703', '2026-06-30 17:38:56'),
(4, 3, 'Smart', 20.00, 20.00, '09123456704', '2026-06-30 17:38:56'),
(5, 4, 'Globe', 50.00, 50.00, '09123456705', '2026-06-30 17:38:56'),
(6, 4, 'DITO', 100.00, 100.00, '09123456706', '2026-06-30 17:38:56'),
(7, 5, 'Smart', 20.00, 20.00, '09123456707', '2026-06-30 17:38:56'),
(8, 5, 'TNT', 50.00, 50.00, '09123456708', '2026-06-30 17:38:56'),
(9, 2, 'DITO', 30.00, 30.00, '09123456709', '2026-06-30 17:38:56'),
(10, 3, 'Globe', 100.00, 100.00, '09123456710', '2026-06-30 17:38:56'),
(11, 2, 'DITO', 10.00, 10.00, '09203189772', '2026-06-30 17:55:54'),
(12, 5, 'Smart', 100.00, 100.00, '096535676', '2026-07-02 19:35:58'),
(13, 5, 'TNT', 50.00, 50.00, '09203186772', '2026-07-02 19:35:58');

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) NOT NULL,
  `receipt_number` varchar(50) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`id`, `receipt_number`, `staff_id`, `total_amount`, `date_created`) VALUES
(1, 'RCPT-1782992158', 5, 150.00, '2026-07-02 11:35:58');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_items`
--

CREATE TABLE `receipt_items` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipt_items`
--

INSERT INTO `receipt_items` (`id`, `receipt_id`, `sale_id`) VALUES
(1, 1, 12),
(2, 1, 13);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'System Administrator', 'admin', '$2y$10$dZMhGFKMLQVE4Vy4f5ZEE.byZ9vBf0Kf/Wx8tz0SovRJ13eI.CrDC', 'admin', '2026-06-30 09:38:56'),
(2, 'John Cruz', 'john', '$2y$10$.oSsbU1MJHaEJXsqzaGpau5X75JYLuenJIIeg4T6bPaMayrrm2Iea', 'staff', '2026-06-30 09:38:56'),
(3, 'Maria Santos', 'maria', '$2y$10$wH8Q9YlQmQ3OZ1kzG7s3s.6Zp8Jqv9Fj4yFQFz0JYJHqYz9f6Jx2W', 'staff', '2026-06-30 09:38:56'),
(4, 'Pedro Reyes', 'pedro', '$2y$10$wH8Q9YlQmQ3OZ1kzG7s3s.6Zp8Jqv9Fj4yFQFz0JYJHqYz9f6Jx2W', 'staff', '2026-06-30 09:38:56'),
(5, 'Ana Lopez', 'ana', '$2y$10$tvX1O7vm8ec79dHtPtbCCOVwQlgUeiD7a44i1jOdx8v9gLzMgebva', 'staff', '2026-06-30 09:38:56'),
(6, 'Angelo', 'gwapo', '$2y$10$nSmGBKz7Jtny3k9LXAj8w.7rOqaG.me38Wz0O4x1fYzVtKzFbmaT.', 'staff', '2026-06-30 16:08:22'),
(8, 'Admin1', 'admin1', '$2y$10$KTbWwtJX8ueRtj/VLAUdMutJOIjuBdcTJIHrf/PrgALD5q28on0UG', 'admin', '2026-06-30 16:12:08'),
(9, 'oskar', 'oskar', '$2y$10$s.K33tpWrvIX.3BXo9cgzeUm5rJTX1h13QanaEjnVZIZOvr.WESXe', 'staff', '2026-06-30 16:17:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `load_inventory`
--
ALTER TABLE `load_inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `load_sales`
--
ALTER TABLE `load_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `receipt_items`
--
ALTER TABLE `receipt_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receipt_id` (`receipt_id`),
  ADD KEY `sale_id` (`sale_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `load_inventory`
--
ALTER TABLE `load_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `load_sales`
--
ALTER TABLE `load_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `receipt_items`
--
ALTER TABLE `receipt_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `load_inventory`
--
ALTER TABLE `load_inventory`
  ADD CONSTRAINT `load_inventory_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `load_sales`
--
ALTER TABLE `load_sales`
  ADD CONSTRAINT `load_sales_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `receipts_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `receipt_items`
--
ALTER TABLE `receipt_items`
  ADD CONSTRAINT `receipt_items_ibfk_1` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `receipt_items_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `load_sales` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
