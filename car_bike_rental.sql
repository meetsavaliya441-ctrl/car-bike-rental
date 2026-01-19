-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2026 at 10:53 AM
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
-- Database: `car_bike_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', 'password123', '2026-01-08 16:44:31');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'confirmed',
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `vehicle_id`, `from_date`, `to_date`, `total_price`, `status`, `booking_date`) VALUES
(13, 1, 7, '2026-01-14', '2026-02-07', 378250.00, 'confirmed', '2026-01-14 09:17:48');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `brand_name`, `created_at`) VALUES
(1, 'suzuki', '2026-01-09 06:42:55'),
(2, 'Royal Enfield', '2026-01-14 08:49:59'),
(3, 'Toyota', '2026-01-14 09:00:35'),
(4, 'Mahindra', '2026-01-14 09:08:55');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'Meet Savaliya', 'meet@gmail.com', 'road to fine', 'meet', '2026-01-14 09:28:49'),
(2, 'Meet Savaliya', 'meet@gmail.com', 'asdf', 'frrf', '2026-01-14 09:29:04');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','approved') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `user_id`, `vehicle_id`, `rating`, `message`, `status`, `created_at`) VALUES
(1, 1, 2, 5, 'good', 'approved', '2026-01-09 10:10:48'),
(2, 1, 2, 5, 'good', 'pending', '2026-01-09 10:11:34'),
(3, 1, 2, 5, 'good', 'pending', '2026-01-09 10:12:59'),
(4, 1, 2, 5, 'good', 'pending', '2026-01-09 10:13:15'),
(5, 1, 2, 5, 'good', 'pending', '2026-01-09 10:17:16'),
(6, 1, 2, 5, 'hjhhjk', 'pending', '2026-01-09 10:17:20'),
(7, 1, 1, 5, 'ss', 'pending', '2026-01-09 10:24:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `created_at`) VALUES
(1, 'Meet ', 'meet@gmail.com', 'meet123', '6352390909', '2026-01-09 06:48:51');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `price_per_day` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `fuel_type` varchar(50) DEFAULT NULL,
  `transmission` varchar(50) DEFAULT NULL,
  `seats` int(11) DEFAULT NULL,
  `hp` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `brand`, `model`, `type`, `price_per_day`, `image`, `fuel_type`, `transmission`, `seats`, `hp`, `status`) VALUES
(7, 'land rover', 'defender 110', 'car', 15000, '1768379741_Defender_110.jpeg', 'Petrol', NULL, NULL, NULL, 'available'),
(8, 'Ducati', 'Panigale V4', 'bike', 2000, '1768379896_Panigale_V4.jpeg', 'Petrol', NULL, NULL, NULL, 'available'),
(9, 'Kawasaki', 'Ninja ZX-10R', 'bike', 2500, '1768380012_Ninja_ZX_10R.jpeg', 'Petrol', NULL, NULL, NULL, 'available'),
(10, 'BMW', 'M4 Competition', 'car', 15000, '1768380187_M4_Competition.jpeg', 'Petrol', NULL, NULL, NULL, 'available'),
(11, 'Audi', 'RS7 Sportback', 'car', 10000, '1768380289_RS7_Sportback.jpeg', 'Petrol', NULL, NULL, NULL, 'available'),
(12, 'Mercedes-Benz', 'G-Wagon AMG', 'car', 30000, '1768380465_G_Wagon_AMG.jpeg', 'Petrol', NULL, NULL, NULL, 'available'),
(13, 'Royal Enfield', 'Classic 350', 'bike', 1500, '1768380840_Classic_350.jpeg', 'Petrol', NULL, NULL, NULL, 'available'),
(15, 'Yamaha', 'R15 V4', 'bike', 1000, '1768381010_R15_V4.jpeg', 'Petrol', NULL, NULL, NULL, 'available'),
(16, 'Yamaha', 'MT-15', 'bike', 1000, '1768381062_MT_15.jpeg', 'Petrol', NULL, NULL, NULL, 'available'),
(17, 'Yamaha', 'FZ-S', 'bike', 1200, '1768381113_FZ_S.jpeg', 'Petrol', NULL, NULL, NULL, 'available'),
(18, 'Toyota ', 'Glanza', 'car', 1300, '1768381318_Toyota_Glanza.jpeg', 'Petrol', NULL, NULL, NULL, 'available'),
(19, 'Toyota', ' Innova Crysta', 'car', 5500, '1768381383_Toyota_Innova_Crysta.jpeg', 'Petrol', NULL, NULL, NULL, 'available'),
(20, 'Toyota ', 'Urban Cruiser Hyryder', 'car', 6500, '1768381439_Toyota_Urban_Cruiser_Hyryder.jpeg', 'Petrol', NULL, NULL, NULL, 'available'),
(21, 'Toyota ', 'Fortuner', 'car', 7500, '1768381504_Toyota_Fortuner.jpeg', 'Petrol', NULL, NULL, NULL, 'available'),
(22, 'Mahindra ', 'Scorpio N', 'car', 3500, '1768381586_Mahindra_Scorpio_N.jpeg', 'Diesel', NULL, NULL, NULL, 'available'),
(23, 'Mahindra ', 'Thar', 'car', 2000, '1768381714_Mahindra Thar.jpeg', 'Diesel', NULL, NULL, NULL, 'available'),
(24, 'TVS', ' Apache RR 310', 'bike', 3500, '1768381977_TVS_Apache_RR_310.jpeg', 'Petrol', NULL, NULL, NULL, 'available'),
(25, 'Bajaj ', 'Dominar 400', 'bike', 3600, '1768382054_Bajaj_Dominar_400.jpeg', 'Petrol', NULL, NULL, NULL, 'available');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
