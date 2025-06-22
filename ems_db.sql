-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2024 at 03:25 PM
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
-- Database: `ems_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `a_id` varchar(50) NOT NULL,
  `a_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`a_id`, `a_password`) VALUES
('admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `c_id` varchar(50) NOT NULL,
  `c_password` varchar(255) NOT NULL,
  `c_fname` varchar(100) NOT NULL,
  `c_lname` varchar(100) NOT NULL,
  `c_email` varchar(150) NOT NULL,
  `c_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`c_id`, `c_password`, `c_fname`, `c_lname`, `c_email`, `c_address`) VALUES
('customer', 'customer', '', '', '', ''),
('customer1', 'password1', '', '', '', ''),
('customer12', '$2y$10$0eeWJyyh7fEuY4YHVB0i6uxjlY4hN1I9rB5CyesbS9guUnliiXnrC', 'cus', 'tomer', 'cusomer@g.com', 'sdfsa'),
('customer12asdf', '32asd', 'asdfasdfasd', 'asdfasdfasdf', 'cusomer@g.com', 'asdfas'),
('customer3', 'password3', '', '', '', ''),
('dfaf', '$2y$10$ucPCNEQFuczBmovoMvYvV.bV1Cftty2Bd.WAlZx6Ur4Gcdo/WDyyO', 'asdfasd', 'asdfas', 'fsdf@f.com', 'sdfas'),
('fasdfa', '3kdfjaskld', 'asdfasdf', 'asdfasdfasd', 'sdfa@ds.com', 'jsadlfkja');

-- --------------------------------------------------------

--
-- Table structure for table `customerphone`
--

CREATE TABLE `customerphone` (
  `c_id` varchar(50) NOT NULL,
  `c_phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customerphone`
--

INSERT INTO `customerphone` (`c_id`, `c_phone`) VALUES
('customer12', '23465465'),
('customer12asdf', '23424'),
('dfaf', '2316984'),
('fasdfa', '234720');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `e_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `no_of_guests` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_amount` int(15) DEFAULT NULL,
  `payment_status` bit(1) NOT NULL DEFAULT b'0',
  `o_id` varchar(50) DEFAULT NULL,
  `c_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`e_id`, `type`, `no_of_guests`, `start_date`, `end_date`, `total_amount`, `payment_status`, `o_id`, `c_id`) VALUES
(1, 'Wedding', 100, '2024-06-15', '2024-06-16', 12312, b'0', 'dfasdfasd', 'customer1'),
(4, 'ewee', 1231, '2024-12-31', '2024-12-31', 12312, b'1', NULL, 'customer'),
(5, 'fdasdf', 322, '2024-12-05', '2024-12-06', 332, b'0', 'dfasdfasd', 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `organizer`
--

CREATE TABLE `organizer` (
  `o_id` varchar(50) NOT NULL,
  `o_password` varchar(255) NOT NULL,
  `o_fname` varchar(100) NOT NULL,
  `o_lname` varchar(100) NOT NULL,
  `o_email` varchar(150) NOT NULL,
  `o_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizer`
--

INSERT INTO `organizer` (`o_id`, `o_password`, `o_fname`, `o_lname`, `o_email`, `o_address`) VALUES
('asdfsdaf', 'sdfas', 'asdfsadf', 'asdfasdf', 'sadfsd@g.com', 'asdfsadf'),
('dfasdfasd', '$2y$10$faKbnFsd82loKQg0rb.b4uiqraSwcxK5y6e56e3cV0nzjPoWLpSDK', 'asdfasdf', 'asdfasdf', 'asdfasdfa@gmail.com', 'asdfasdfsad');

-- --------------------------------------------------------

--
-- Table structure for table `organizerphone`
--

CREATE TABLE `organizerphone` (
  `o_id` varchar(50) NOT NULL,
  `o_phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizerphone`
--

INSERT INTO `organizerphone` (`o_id`, `o_phone`) VALUES
('asdfsdaf', '32423423'),
('asdfsdaf', '435242'),
('dfasdfasd', '324234'),
('dfasdfasd', '32423423'),
('dfasdfasd', '4234234');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`a_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `customerphone`
--
ALTER TABLE `customerphone`
  ADD PRIMARY KEY (`c_id`,`c_phone`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`e_id`),
  ADD KEY `o_id` (`o_id`),
  ADD KEY `c_id` (`c_id`);

--
-- Indexes for table `organizer`
--
ALTER TABLE `organizer`
  ADD PRIMARY KEY (`o_id`);

--
-- Indexes for table `organizerphone`
--
ALTER TABLE `organizerphone`
  ADD PRIMARY KEY (`o_id`,`o_phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `e_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customerphone`
--
ALTER TABLE `customerphone`
  ADD CONSTRAINT `customerphone_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `customer` (`c_id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `customer` (`c_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`o_id`) REFERENCES `organizer` (`o_id`) ON DELETE CASCADE;

--
-- Constraints for table `organizerphone`
--
ALTER TABLE `organizerphone`
  ADD CONSTRAINT `organizerphone_ibfk_1` FOREIGN KEY (`o_id`) REFERENCES `organizer` (`o_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
