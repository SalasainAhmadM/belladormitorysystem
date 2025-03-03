-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2025 at 03:37 AM
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
-- Database: `bella`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(300) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updation_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`, `reg_date`, `updation_date`) VALUES
(1, 'admin', 'admin123@gmail.com', '12345', '2016-04-04 20:31:45', '2025-01-30');

-- --------------------------------------------------------

--
-- Table structure for table `adminlog`
--

CREATE TABLE `adminlog` (
  `id` int(11) NOT NULL,
  `adminid` int(11) NOT NULL,
  `ip` varbinary(16) NOT NULL,
  `logintime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contactus`
--

CREATE TABLE `contactus` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contactus`
--

INSERT INTO `contactus` (`id`, `name`, `email`, `subject`, `message`) VALUES
(2, 'Nadzrin Alhari', 'kaizoku902604@gmail.com', 'Inquire', 'Any vacant?');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_code` varchar(255) DEFAULT NULL,
  `course_sn` varchar(255) DEFAULT NULL,
  `course_fn` varchar(255) DEFAULT NULL,
  `posting_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `course_sn`, `course_fn`, `posting_date`) VALUES
(1, 'B10992', 'B.Tech', 'Bachelor  of Technology', '2016-04-11 19:31:42'),
(2, 'BCOM1453', 'B.Com', 'Bachelor Of commerce ', '2016-04-11 19:32:46'),
(3, 'BSC12', 'BSC', 'Bachelor  of Science', '2016-04-11 19:33:23'),
(4, 'BC36356', 'BCA', 'Bachelor Of Computer Application', '2016-04-11 19:34:18'),
(5, 'MCA565', 'MCA', 'Master of Computer Application', '2016-04-11 19:34:40'),
(6, 'MBA75', 'MBA', 'Master of Business Administration', '2016-04-11 19:34:59'),
(7, 'BE765', 'BE', 'Bachelor of Engineering', '2016-04-11 19:35:19');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance`
--

CREATE TABLE `maintenance` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `requested_by` varchar(255) DEFAULT NULL,
  `requested_time` datetime NOT NULL DEFAULT current_timestamp(),
  `room_no` varchar(255) DEFAULT NULL,
  `fee` varchar(255) NOT NULL,
  `status` enum('available','working') DEFAULT NULL,
  `profession` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `issue` varchar(255) DEFAULT NULL,
  `payment` varchar(255) DEFAULT NULL,
  `payment_method` enum('Cash','PayPal') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance`
--

INSERT INTO `maintenance` (`id`, `name`, `requested_by`, `requested_time`, `room_no`, `fee`, `status`, `profession`, `description`, `issue`, `payment`, `payment_method`) VALUES
(1, 'Banny Sins', '19', '2025-01-30 11:10:01', '120', '2000', 'working', 'Plumber', 'responsible for the installation, modification, maintenance, and repair of plumbing fixtures for drainage and water systems.', 'test', NULL, 'Cash'),
(2, 'Janny Sins', NULL, '0000-00-00 00:00:00', NULL, '2100', 'available', 'Electrician', '123', NULL, NULL, NULL),
(3, 'Arnold Sins', NULL, '0000-00-00 00:00:00', NULL, '2100', 'available', 'Electrician', '123', NULL, NULL, NULL),
(4, 'Nadzrin Alhari', NULL, '2025-01-30 17:02:03', NULL, '3000', 'available', 'Bumbero', 'Fire Protection', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `sender_type` enum('user','admin') NOT NULL,
  `is_read` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `timestamp`, `sender_type`, `is_read`) VALUES
(1, 1, 6, 'test', '2025-01-29 10:02:57', 'user', 0),
(2, 6, 1, 'test', '2025-01-29 10:02:57', 'admin', 0),
(3, 1, 6, 'test', '2025-01-29 10:03:30', 'user', 0),
(4, 1, 6, 'test', '2025-01-29 15:28:06', 'user', 0),
(5, 1, 6, '1', '2025-01-29 15:48:09', 'admin', 0),
(6, 1, 6, 'yow', '2025-01-29 15:48:14', 'admin', 0),
(7, 1, 6, 'yow', '2025-01-29 15:48:37', 'admin', 0),
(8, 1, 6, 'lets talk\r\n', '2025-01-29 15:49:14', 'admin', 0),
(9, 1, 6, 'lets talk\r\n', '2025-01-29 15:50:11', 'admin', 0),
(10, 1, 6, 'lets talk\r\n', '2025-01-29 15:50:39', 'admin', 0),
(11, 10, 1, '123', '2025-01-29 16:27:30', 'user', 0),
(12, 10, 1, '123', '2025-01-29 16:27:49', 'user', 0),
(13, 10, 1, '123', '2025-01-29 16:28:40', 'user', 0),
(14, 21, 1, 'yow', '2025-01-29 16:31:48', 'user', 0),
(15, 1, 21, 'yes?\r\n', '2025-01-29 16:37:24', 'admin', 0),
(16, 1, 21, 'yes?\r\n', '2025-01-29 16:38:25', 'admin', 0),
(17, 1, 10, '321', '2025-01-29 21:35:48', 'admin', 0),
(18, 1, 10, 'goods', '2025-01-30 16:36:40', 'admin', 0),
(19, 1, 10, 'goods', '2025-01-30 16:38:22', 'admin', 0),
(20, 23, 1, 'Hello admin', '2025-01-30 16:50:46', 'user', 0);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_payments`
--

CREATE TABLE `monthly_payments` (
  `id` int(11) NOT NULL,
  `regno` int(11) NOT NULL,
  `roomno` int(11) DEFAULT NULL,
  `month` varchar(20) NOT NULL,
  `amount_paid` int(11) NOT NULL,
  `payment_method` enum('Cash','PayPal') DEFAULT NULL,
  `payment_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monthly_payments`
--

INSERT INTO `monthly_payments` (`id`, `regno`, `roomno`, `month`, `amount_paid`, `payment_method`, `payment_date`) VALUES
(5, 102355, 120, 'January 2025', 4500, 'PayPal', '2025-01-30 12:14:03'),
(6, 102355, 120, 'January 2025', 3000, 'Cash', '2025-01-30 12:27:45'),
(7, 123456, 201, 'February 2025', 11500, 'Cash', '2025-01-30 16:48:02');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `id` int(11) NOT NULL,
  `roomno` int(11) DEFAULT NULL,
  `seater` int(11) DEFAULT NULL,
  `feespm` int(11) DEFAULT NULL,
  `foodstatus` int(11) DEFAULT NULL,
  `stayfrom` date DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `course` varchar(500) DEFAULT NULL,
  `regno` int(11) DEFAULT NULL,
  `firstName` varchar(500) DEFAULT NULL,
  `middleName` varchar(500) DEFAULT NULL,
  `lastName` varchar(500) DEFAULT NULL,
  `gender` varchar(250) DEFAULT NULL,
  `contactno` bigint(11) DEFAULT NULL,
  `emailid` varchar(500) DEFAULT NULL,
  `egycontactno` bigint(11) DEFAULT NULL,
  `guardianName` varchar(500) DEFAULT NULL,
  `guardianRelation` varchar(500) DEFAULT NULL,
  `guardianContactno` bigint(11) DEFAULT NULL,
  `corresAddress` varchar(500) DEFAULT NULL,
  `corresCIty` varchar(500) DEFAULT NULL,
  `corresState` varchar(500) DEFAULT NULL,
  `corresPincode` int(11) DEFAULT NULL,
  `pmntAddress` varchar(500) DEFAULT NULL,
  `pmntCity` varchar(500) DEFAULT NULL,
  `pmnatetState` varchar(500) DEFAULT NULL,
  `pmntPincode` int(11) DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT current_timestamp(),
  `updationDate` varchar(500) DEFAULT NULL,
  `waterstatus` int(11) DEFAULT NULL,
  `electricstatus` int(11) DEFAULT NULL,
  `internetstatus` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`id`, `roomno`, `seater`, `feespm`, `foodstatus`, `stayfrom`, `duration`, `course`, `regno`, `firstName`, `middleName`, `lastName`, `gender`, `contactno`, `emailid`, `egycontactno`, `guardianName`, `guardianRelation`, `guardianContactno`, `corresAddress`, `corresCIty`, `corresState`, `corresPincode`, `pmntAddress`, `pmntCity`, `pmnatetState`, `pmntPincode`, `postingDate`, `updationDate`, `waterstatus`, `electricstatus`, `internetstatus`) VALUES
(11, 120, 2, 2000, 1, '2025-01-29', 8, 'N/A', 102355, 'Zoro', 'D', 'Roronoa', 'male', 6786786786, 'zoro@gmail.com', 123, 'qwert', 'dad', 321, 'test', 'Zamboanga City', 'Zamboanga Del Sur', 7000, 'test', 'Zamboanga City', 'Zamboanga Del Sur', 7000, '2025-01-29 13:05:35', NULL, 1, 1, 1),
(14, 201, 2, 6000, 1, '2025-01-30', 10, 'N/A', 123456, 'Roger', 'D', 'Pirate', 'male', 12345678901, 'kaizoku902604@gmail.com', 123, 'Kaido', 'King', 321, 'Kasanynagan', 'Zamboanga City', 'Zamboanga Del Sur', 7000, 'Kasanynagan', 'Zamboanga City', 'Zamboanga Del Sur', 7000, '2025-01-30 08:44:03', NULL, 1, 1, 1),
(15, 100, 2, 6000, 1, '2025-01-30', 4, 'N/A', 108061211, 'Luffy', 'D', 'Monkey', 'male', 1234567890, 'test@gmail.com', 123, 'Kaido', 'king', 321, 'Earth', 'Zamboanga City', 'Zamboanga Del Sur', 7000, 'Earth', 'Zamboanga City', 'Zamboanga Del Sur', 7000, '2025-01-30 11:35:55', NULL, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `seater` int(11) DEFAULT NULL,
  `room_no` int(11) DEFAULT NULL,
  `fees` int(11) DEFAULT NULL,
  `posting_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `seater`, `room_no`, `fees`, `posting_date`) VALUES
(1, 2, 100, 6000, '2016-04-11 22:45:43'),
(2, 2, 201, 6000, '2016-04-12 01:30:47'),
(3, 2, 200, 6000, '2016-04-12 01:30:58'),
(4, 3, 112, 4000, '2016-04-12 01:31:07'),
(5, 5, 132, 2000, '2016-04-12 01:31:15'),
(6, 1, 456, 2000, '2025-01-29 10:34:16'),
(7, 2, 120, 2000, '2025-01-29 10:38:50'),
(8, 1, 234, 3000, '2025-01-30 08:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `State` varchar(150) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `State`) VALUES
(1, 'Andaman and Nicobar Island (UT)'),
(2, 'Andhra Pradesh'),
(3, 'Arunachal Pradesh'),
(4, 'Assam'),
(5, 'Bihar'),
(6, 'Chandigarh (UT)'),
(7, 'Chhattisgarh'),
(8, 'Dadra and Nagar Haveli (UT)'),
(9, 'Daman and Diu (UT)'),
(10, 'Delhi (NCT)'),
(11, 'Goa'),
(12, 'Gujarat'),
(13, 'Haryana'),
(14, 'Himachal Pradesh'),
(15, 'Jammu and Kashmir'),
(16, 'Jharkhand'),
(17, 'Karnataka'),
(18, 'Kerala'),
(19, 'Lakshadweep (UT)'),
(20, 'Madhya Pradesh'),
(21, 'Maharashtra'),
(22, 'Manipur'),
(23, 'Meghalaya'),
(24, 'Mizoram'),
(25, 'Nagaland'),
(26, 'Odisha'),
(27, 'Puducherry (UT)'),
(28, 'Punjab'),
(29, 'Rajastha'),
(30, 'Sikkim'),
(31, 'Tamil Nadu'),
(32, 'Telangana'),
(33, 'Tripura'),
(34, 'Uttarakhand'),
(35, 'Uttar Pradesh'),
(36, 'West Bengal');

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE `userlog` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `userIp` varbinary(16) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `loginTime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `userlog`
--

INSERT INTO `userlog` (`id`, `userId`, `userEmail`, `userIp`, `city`, `country`, `loginTime`) VALUES
(1, 10, 'test@gmail.com', '', '', '', '2016-06-22 06:16:42'),
(2, 10, 'test@gmail.com', '', '', '', '2016-06-24 11:20:28'),
(4, 10, 'test@gmail.com', 0x3a3a31, '', '', '2016-06-24 11:22:47'),
(5, 10, 'test@gmail.com', 0x3a3a31, '', '', '2016-06-26 15:37:40'),
(6, 20, 'ajay@gmail.com', 0x3a3a31, '', '', '2016-06-26 16:40:57'),
(7, 10, 'test@gmail.com', 0x3a3a31, '', '', '2019-06-10 05:02:51'),
(8, 10, 'test@gmail.com', 0x3a3a31, '', '', '2019-06-10 05:49:42'),
(9, 10, 'test@gmail.com', 0x3a3a31, '', '', '2019-06-10 07:17:32'),
(10, 10, 'test@gmail.com', 0x3a3a31, '', '', '2019-06-10 08:08:59'),
(11, 10, 'test@gmail.com', 0x3a3a31, '', '', '2025-01-29 01:26:58'),
(12, 10, 'test@gmail.com', 0x3a3a31, '', '', '2025-01-29 08:04:23'),
(13, 21, 'binimaloi352@gmail.com', 0x3a3a31, '', '', '2025-01-29 08:31:41'),
(14, 10, 'test@gmail.com', 0x3a3a31, '', '', '2025-01-29 12:15:19'),
(15, 19, 'zoro@gmail.com', 0x3a3a31, '', '', '2025-01-29 12:19:03'),
(16, 10, 'test@gmail.com', 0x3a3a31, '', '', '2025-01-29 20:41:08'),
(17, 10, 'test@gmail.com', 0x3a3a31, '', '', '2025-01-29 21:13:04'),
(18, 19, 'zoro@gmail.com', 0x3a3a31, '', '', '2025-01-29 22:51:29'),
(19, 19, 'zoro@gmail.com', 0x3a3a31, '', '', '2025-01-30 03:06:35'),
(20, 19, 'zoro@gmail.com', 0x3a3a31, '', '', '2025-01-30 03:09:54'),
(21, 19, 'zoro@gmail.com', 0x3a3a31, '', '', '2025-01-30 03:10:53'),
(22, 23, 'kaizoku902604@gmail.com', 0x3a3a31, '', '', '2025-01-30 08:41:54'),
(23, 10, 'test@gmail.com', 0x3a3a31, '', '', '2025-01-30 11:35:05'),
(24, 10, 'test@gmail.com', 0x3a3a31, '', '', '2025-01-30 12:28:02');

-- --------------------------------------------------------

--
-- Table structure for table `userregistration`
--

CREATE TABLE `userregistration` (
  `id` int(11) NOT NULL,
  `regNo` varchar(255) DEFAULT NULL,
  `firstName` varchar(255) DEFAULT NULL,
  `middleName` varchar(255) DEFAULT NULL,
  `lastName` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `contactNo` bigint(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `regDate` timestamp NULL DEFAULT current_timestamp(),
  `updationDate` varchar(45) DEFAULT NULL,
  `passUdateDate` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `userregistration`
--

INSERT INTO `userregistration` (`id`, `regNo`, `firstName`, `middleName`, `lastName`, `gender`, `contactNo`, `email`, `password`, `regDate`, `updationDate`, `passUdateDate`) VALUES
(10, '108061211', 'Luffy', 'D', 'Monkey', 'male', 1234567890, 'test@gmail.com', '123', '2016-06-22 04:21:33', '29-01-2025 07:05:16', NULL),
(19, '102355', 'Zoro', 'D', 'Roronoa', 'male', 6786786786, 'zoro@gmail.com', '123', '2016-06-26 16:33:36', '', ''),
(20, '586952', 'Sanji', 'D', 'Vinsmoke', 'male', 8596185625, 'sanji@gmail.com', '8596185625', '2016-06-26 16:40:07', '', ''),
(21, '123', 'Test', 'D', 'Dorm', 'male', 12345678901, 'binimaloi352@gmail.com', '123', '2025-01-29 08:30:20', NULL, NULL),
(23, '123456', 'Roger', 'Dead', 'Pirate', 'male', 12345678901, 'kaizoku902604@gmail.com', '12345', '2025-01-30 08:41:38', '30-01-2025 02:26:11', '30-01-2025 02:25:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contactus`
--
ALTER TABLE `contactus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_payments`
--
ALTER TABLE `monthly_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_no` (`room_no`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userregistration`
--
ALTER TABLE `userregistration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contactus`
--
ALTER TABLE `contactus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `monthly_payments`
--
ALTER TABLE `monthly_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `userregistration`
--
ALTER TABLE `userregistration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
