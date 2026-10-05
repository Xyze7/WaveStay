-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 05, 2026 at 11:30 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wavestay`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int NOT NULL,
  `unit_id` int NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `total_hours` int DEFAULT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `payment_status` enum('pending','success','cancelled') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `unit_id`, `customer_name`, `customer_phone`, `start_date`, `end_date`, `total_hours`, `total_price`, `payment_status`, `created_at`) VALUES
(1, 12, 'Dei', '087676545', '2027-07-07', '2027-07-08', NULL, '750000.00', 'success', '2026-10-01 00:50:19'),
(2, 13, 'dea', '0887468959', '2027-07-07', '2027-07-09', NULL, '7000000.00', 'success', '2026-10-01 01:08:35');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `category` enum('villa','speedboat','jetski','snorkeling') NOT NULL,
  `capacity` int NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `price_type` enum('per_night','per_hour') NOT NULL,
  `status` enum('available','booked','maintenance') DEFAULT 'available',
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `category`, `capacity`, `price`, `price_type`, `status`, `image`, `created_at`) VALUES
(12, 'Sunset Beach Villa (Affordable)', 'villa', 4, '750000.00', 'per_night', 'available', 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?auto=format&fit=crop&w=800&q=80', '2026-10-01 00:42:20'),
(13, 'Royal Ocean Luxury Villa (Pricey)', 'villa', 10, '3500000.00', 'per_night', 'booked', 'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=800&q=80', '2026-10-01 00:42:20'),
(14, 'Express Wave Runner (Affordable)', 'speedboat', 3, '300000.00', 'per_hour', 'available', 'https://images.unsplash.com/photo-1567899378494-47b22a2ae96a?auto=format&fit=crop&w=800&q=80', '2026-10-01 00:42:20'),
(15, 'VIP Yacht & Speedboat Charter (Pricey)', 'speedboat', 12, '1500000.00', 'per_hour', 'available', 'https://images.unsplash.com/photo-1569263979104-865ab9cd8d5c?auto=format&fit=crop&w=800&q=80', '2026-10-01 00:42:20'),
(16, 'Sea-Doo Standard Jetski (Affordable)', 'jetski', 2, '250000.00', 'per_hour', 'available', 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=800&q=80', '2026-10-01 00:42:20'),
(17, 'Pro Turbo Performance Jetski (Pricey)', 'jetski', 2, '600000.00', 'per_hour', 'available', 'https://images.unsplash.com/photo-1520116468816-95b69f847357?auto=format&fit=crop&w=800&q=80', '2026-10-01 00:42:20'),
(18, 'Coral Reef Snorkeling Fun Pack (Affordable)', 'snorkeling', 5, '150000.00', 'per_hour', 'available', 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=800&q=80', '2026-10-01 00:42:20'),
(19, 'Deep Sea Coral & Marine Safari VIP (Pricey)', 'snorkeling', 8, '450000.00', 'per_hour', 'available', 'https://images.unsplash.com/photo-1682687220063-4742bd7fd538?auto=format&fit=crop&w=800&q=80', '2026-10-01 00:42:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
