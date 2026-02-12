-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2026 at 01:45 PM
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
-- Database: `driver_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `pickup_address` text NOT NULL,
  `pickup_city` varchar(50) NOT NULL,
  `drop_address` text NOT NULL,
  `car_type` varchar(50) NOT NULL,
  `trip_type` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `booking_status` enum('Pending','Accepted','Completed','Cancelled') DEFAULT 'Pending',
  `payment_status` enum('Pending','Paid') DEFAULT 'Pending',
  `trip_date` datetime NOT NULL,
  `request_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `car_transmission` varchar(50) DEFAULT 'Manual'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `driver_id`, `pickup_address`, `pickup_city`, `drop_address`, `car_type`, `trip_type`, `amount`, `booking_status`, `payment_status`, `trip_date`, `request_time`, `car_transmission`) VALUES
(1, 1, 2, 'chaitram', 'trichy', 'lalgudi', 'swift', 'One Way', 490.00, 'Completed', 'Paid', '2026-01-21 12:30:00', '2026-01-20 11:40:33', 'Manual'),
(2, 1, NULL, 'chairem', 'trichy', 'lalgudi', 'swift', 'One Way', 500.00, 'Cancelled', 'Pending', '2026-01-19 05:18:00', '2026-01-20 11:47:46', 'Manual'),
(3, 1, NULL, 'wmwoie', 'trichy', 'JNOn', 'swift', 'One Way', 500.00, 'Cancelled', 'Pending', '2026-01-20 17:22:00', '2026-01-20 11:51:47', 'Manual'),
(4, 1, 2, 'trichy', 'trichy', 'lalgudi', 'swift', 'One Way', 600.00, 'Completed', 'Paid', '2026-01-22 17:31:00', '2026-01-21 09:00:39', 'Manual'),
(5, 3, 3, 'chitram', 'trichy', 'lalgudi', 'scorpio', 'One Way', 590.00, 'Completed', 'Paid', '2026-01-27 05:50:00', '2026-01-27 12:10:40', 'Manual'),
(6, 4, 4, 'chthiram bus stand', 'trichy', 'lalgudi bus  stand', 'swift', 'One Way', 540.00, 'Completed', 'Paid', '2026-02-07 05:30:00', '2026-02-07 09:48:39', 'Manual'),
(7, 4, NULL, 'chiram bus stand', 'trichy', 'lalgudi', 'swift', 'Round Trip', 590.00, 'Cancelled', 'Pending', '2026-02-12 16:20:00', '2026-02-12 10:49:15', 'Manual');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `license_no` varchar(50) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `upi_id` varchar(100) DEFAULT NULL,
  `status` enum('Approved','Pending') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `name`, `email`, `mobile`, `address`, `city`, `license_no`, `photo`, `password`, `upi_id`, `status`, `created_at`) VALUES
(1, 'arun', 'arunprakash@gmail.com', '7358813938', 'l11.shathi story,lalgudi', 'trichy', 'ncjhwuihhi1222', 'neam (1).jpg', '1234', NULL, 'Pending', '2026-01-20 11:00:16'),
(2, 'arun', 'arun@gmail.com', '7358813938', 'nwhhuwhshuir', 'trichy', 'ncjhwuihhi1222', 'OIP.jpg', '1234', NULL, 'Approved', '2026-01-20 11:15:03'),
(3, 'dollu', 'dollu@gmail.com', '7358813938', 'lalgudi', 'trichy', 'bcdhdhiwdu', 'na.jpg', '1234', NULL, 'Approved', '2026-01-27 12:04:41'),
(4, 'driver', 'driver@gmail.com', '7358813938', 'trichy', 'trichy', 'ncjhwuihhi1222', 'WhatsApp Image 2025-09-22 at 21.44.56_4026ecfb.jpg', 'driver', NULL, 'Approved', '2026-02-07 09:47:03');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `positive_comments` text DEFAULT NULL,
  `negative_comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `booking_id`, `user_id`, `driver_id`, `rating`, `positive_comments`, `negative_comments`) VALUES
(1, 1, NULL, NULL, 2, 'ewweoij', 'ehheu'),
(2, 5, NULL, NULL, 1, 'no', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `address`, `city`, `password`, `created_at`) VALUES
(1, 'prakash', 'ap3386785@gmail.com', '7358813938', 'lalgudi', 'trichy', '1234', '2026-01-20 11:05:23'),
(2, 'anowcjjcj@gmail.com', 'anowcjjcj@gmail.com', '7358813938', 'c32enohieiw', 'trichy', '1234', '2026-01-21 10:05:43'),
(3, 'BOLLU', 'bollu@gmail.com', '7358813938', 'lalgudi', 'trichy', '1234', '2026-01-27 12:03:56'),
(4, 'user', 'user@gmail.com', '7358813938', 'trichy', 'trichy', 'user', '2026-02-07 09:45:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
