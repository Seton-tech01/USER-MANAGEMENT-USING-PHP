-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2026 at 09:07 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `management_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log_tab`
--

CREATE TABLE `activity_log_tab` (
  `sn` int(11) NOT NULL COMMENT 'Primary Key',
  `alertId` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `performedBy` varchar(255) NOT NULL,
  `roleId` varchar(255) NOT NULL,
  `userType` varchar(255) NOT NULL,
  `ipAddress` varchar(255) NOT NULL,
  `browserName` varchar(255) NOT NULL,
  `systemName` varchar(255) NOT NULL,
  `viewedBy` varchar(255) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `updated_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `counter_tab`
--

CREATE TABLE `counter_tab` (
  `sn` int(11) NOT NULL,
  `counter_id` varchar(255) NOT NULL,
  `counter_value` varchar(255) NOT NULL,
  `counter_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `counter_tab`
--

INSERT INTO `counter_tab` (`sn`, `counter_id`, `counter_value`, `counter_description`) VALUES
(1, 'STAFF', '6', 'COUNT NUMBER OF STAFF');

-- --------------------------------------------------------

--
-- Table structure for table `otp_tab`
--

CREATE TABLE `otp_tab` (
  `sn` int(11) NOT NULL COMMENT 'Primary Key',
  `userId` varchar(255) NOT NULL,
  `otp_code` varchar(255) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `updated_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `sn` int(11) NOT NULL COMMENT 'Primary Key',
  `device_id` varchar(255) NOT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `abilities` varchar(255) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `last_used_at` datetime DEFAULT NULL,
  `created_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`sn`, `device_id`, `user_type`, `user_id`, `token`, `abilities`, `expires_at`, `last_used_at`, `created_time`) VALUES
(9, 'Windows NT', 'staff', 'STAFF000520260425100051', 'dfcd54144a619c970fa3e229cf0c78f135fee0e93570b046bdced7a230f7b12c', '', NULL, NULL, '2026-04-26 01:17:41'),
(10, 'Windows NT', 'staff', 'STAFF000620260425101746', '6d7792ff46212cff15ecc82cdda2fa75d3b950bcca9d9bd09ddf993466c27667', '', NULL, NULL, '2026-04-27 14:45:10');

-- --------------------------------------------------------

--
-- Table structure for table `reset_password_tab`
--

CREATE TABLE `reset_password_tab` (
  `sn` int(11) NOT NULL COMMENT 'Primary Key',
  `email` varchar(255) NOT NULL,
  `token` int(11) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `setup_class_tab`
--

CREATE TABLE `setup_class_tab` (
  `sn` int(11) NOT NULL COMMENT 'Primary Key',
  `classId` varchar(255) NOT NULL,
  `className` varchar(255) NOT NULL,
  `created_time` datetime DEFAULT NULL,
  `updated_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `setup_class_tab`
--

INSERT INTO `setup_class_tab` (`sn`, `classId`, `className`, `created_time`, `updated_time`) VALUES
(1, '1', 'PLAYGROUP', '2026-04-21 14:12:09', '2026-04-21 13:12:09'),
(2, '2', 'KINDERGATEEN 1', '2026-04-21 14:12:09', '2026-04-21 13:12:09'),
(3, '3', 'KINDERGATEEN 2', '2026-04-21 14:12:09', '2026-04-21 13:12:09'),
(4, '4', 'NURSERY 1', '2026-04-21 14:12:09', '2026-04-21 13:12:09'),
(5, '5', 'NURSERY 2', '2026-04-21 14:12:09', '2026-04-21 13:12:09'),
(6, '6', 'BASIC 1', '2026-04-21 14:12:09', '2026-04-21 13:12:09'),
(7, '7', 'BASIC 2', '2026-04-21 14:12:09', '2026-04-21 13:12:09'),
(8, '8', 'BASIC 3', '2026-04-21 14:12:09', '2026-04-21 13:12:09'),
(9, '9', 'BASIC 4', '2026-04-21 14:12:09', '2026-04-21 13:12:09'),
(10, '10', 'BASIC 5', '2026-04-21 14:12:09', '2026-04-21 13:12:09'),
(11, '11', 'JSS 1', '2026-04-21 14:12:09', '2026-04-21 13:12:09'),
(12, '12', 'JSS 2', '2026-04-21 14:12:09', '2026-04-21 13:12:09'),
(13, '13', 'JSS 3', '2026-04-21 14:12:09', '2026-04-21 13:12:09'),
(14, '14', 'SSS 1', '2026-04-21 14:12:09', '2026-04-21 13:12:09'),
(15, '15', 'SSS 2', '2026-04-21 14:12:09', '2026-04-21 13:12:09'),
(16, '16', 'SSS 3', '2026-04-21 14:12:09', '2026-04-21 13:12:09');

-- --------------------------------------------------------

--
-- Table structure for table `setup_role_tab`
--

CREATE TABLE `setup_role_tab` (
  `sn` int(11) NOT NULL COMMENT 'Primary Key',
  `roleId` varchar(255) NOT NULL,
  `roleName` varchar(255) NOT NULL,
  `created_time` datetime DEFAULT NULL,
  `updated_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `setup_role_tab`
--

INSERT INTO `setup_role_tab` (`sn`, `roleId`, `roleName`, `created_time`, `updated_time`) VALUES
(1, '1', 'PROPRIETOR', '2026-04-21 14:11:56', '2026-04-21 13:11:56'),
(2, '2', 'BURSAR', '2026-04-21 14:11:56', '2026-04-21 13:11:56'),
(3, '3', 'REGISTRAR', '2026-04-21 14:11:56', '2026-04-21 13:11:56'),
(4, '4', 'TEACHER', '2026-04-21 14:11:56', '2026-04-21 13:11:56'),
(5, '5', 'SECRETARY', '2026-04-21 14:11:56', '2026-04-21 13:11:56');

-- --------------------------------------------------------

--
-- Table structure for table `setup_session_tab`
--

CREATE TABLE `setup_session_tab` (
  `sn` int(11) NOT NULL COMMENT 'Primary Key',
  `sessionId` varchar(255) NOT NULL,
  `sessionName` varchar(255) NOT NULL,
  `created_time` datetime DEFAULT NULL,
  `updated_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `setup_session_tab`
--

INSERT INTO `setup_session_tab` (`sn`, `sessionId`, `sessionName`, `created_time`, `updated_time`) VALUES
(1, '2025/2026', '2025/2026', '2026-04-21 14:12:17', '2026-04-21 13:12:17'),
(2, '2026/2027', '2026/2027', '2026-04-21 14:12:17', '2026-04-21 13:12:17'),
(3, '2027/2028', '2027/2028', '2026-04-21 14:12:17', '2026-04-21 13:12:17');

-- --------------------------------------------------------

--
-- Table structure for table `setup_status_tab`
--

CREATE TABLE `setup_status_tab` (
  `sn` int(11) NOT NULL COMMENT 'Primary Key',
  `statusId` varchar(255) NOT NULL,
  `statusName` varchar(255) NOT NULL,
  `created_time` datetime DEFAULT NULL,
  `updated_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `setup_status_tab`
--

INSERT INTO `setup_status_tab` (`sn`, `statusId`, `statusName`, `created_time`, `updated_time`) VALUES
(1, '1', 'ACTIVE', '2026-04-21 14:13:05', '2026-04-21 13:13:05'),
(2, '2', 'SUSPENDED', '2026-04-21 14:13:05', '2026-04-21 13:13:05'),
(3, '3', 'RESIGNED', '2026-04-21 14:13:05', '2026-04-21 13:13:05');

-- --------------------------------------------------------

--
-- Table structure for table `setup_title_tab`
--

CREATE TABLE `setup_title_tab` (
  `sn` int(11) NOT NULL COMMENT 'Primary Key',
  `titleId` varchar(255) NOT NULL,
  `titleName` varchar(255) NOT NULL,
  `created_time` datetime DEFAULT NULL,
  `updated_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `setup_title_tab`
--

INSERT INTO `setup_title_tab` (`sn`, `titleId`, `titleName`, `created_time`, `updated_time`) VALUES
(1, '1', 'MR', '2026-04-21 14:12:00', '2026-04-21 13:12:00'),
(2, '2', 'MRS', '2026-04-21 14:12:00', '2026-04-21 13:12:00'),
(3, '3', 'MISS', '2026-04-21 14:12:00', '2026-04-21 13:12:00');

-- --------------------------------------------------------

--
-- Table structure for table `staff_tab`
--

CREATE TABLE `staff_tab` (
  `sn` int(11) NOT NULL COMMENT 'Primary Key',
  `titleId` varchar(255) NOT NULL,
  `staffId` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `middleName` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `role_id` varchar(255) NOT NULL,
  `status_id` varchar(255) DEFAULT '1',
  `qualification_id` varchar(255) DEFAULT NULL,
  `home_address` varchar(255) DEFAULT NULL,
  `gender_id` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `passport` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `updated_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff_tab`
--

INSERT INTO `staff_tab` (`sn`, `titleId`, `staffId`, `firstName`, `lastName`, `middleName`, `email`, `phone_number`, `role_id`, `status_id`, `qualification_id`, `home_address`, `gender_id`, `password`, `passport`, `last_login`, `created_time`, `updated_time`) VALUES
(1, '', 'STAFF000520260425100051', 'OLAPEJU', 'SETON', 'EMMANUEL', 'seton111@gmail.com', '09112345678', '1', '1', '', 'Ode Remo', '1', '83e04e5bdee9a2b3400e4d744e480e06', 'STAFF000520260425100051download (1).jpeg', '2026-04-26 00:17:41', '2026-04-25 21:00:51', '2026-05-08 18:52:46'),
(2, '3', 'STAFF000620260425101746', 'EMMANUEL', 'EMMANUEL', 'EMMANUEL', 'seton1@gmail.com', '09112345678', '1', '1', '', 'Ode Remo', '1', '83e04e5bdee9a2b3400e4d744e480e06', 'STAFF000620260425101746download (1).jpeg', '2026-04-27 13:45:10', '2026-04-25 21:17:46', '2026-04-27 18:21:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log_tab`
--
ALTER TABLE `activity_log_tab`
  ADD PRIMARY KEY (`sn`);

--
-- Indexes for table `counter_tab`
--
ALTER TABLE `counter_tab`
  ADD PRIMARY KEY (`sn`);

--
-- Indexes for table `otp_tab`
--
ALTER TABLE `otp_tab`
  ADD PRIMARY KEY (`sn`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`sn`);

--
-- Indexes for table `reset_password_tab`
--
ALTER TABLE `reset_password_tab`
  ADD PRIMARY KEY (`sn`);

--
-- Indexes for table `setup_class_tab`
--
ALTER TABLE `setup_class_tab`
  ADD PRIMARY KEY (`sn`);

--
-- Indexes for table `setup_role_tab`
--
ALTER TABLE `setup_role_tab`
  ADD PRIMARY KEY (`sn`);

--
-- Indexes for table `setup_session_tab`
--
ALTER TABLE `setup_session_tab`
  ADD PRIMARY KEY (`sn`);

--
-- Indexes for table `setup_status_tab`
--
ALTER TABLE `setup_status_tab`
  ADD PRIMARY KEY (`sn`);

--
-- Indexes for table `setup_title_tab`
--
ALTER TABLE `setup_title_tab`
  ADD PRIMARY KEY (`sn`);

--
-- Indexes for table `staff_tab`
--
ALTER TABLE `staff_tab`
  ADD PRIMARY KEY (`sn`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log_tab`
--
ALTER TABLE `activity_log_tab`
  MODIFY `sn` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `counter_tab`
--
ALTER TABLE `counter_tab`
  MODIFY `sn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `otp_tab`
--
ALTER TABLE `otp_tab`
  MODIFY `sn` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `sn` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reset_password_tab`
--
ALTER TABLE `reset_password_tab`
  MODIFY `sn` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `setup_class_tab`
--
ALTER TABLE `setup_class_tab`
  MODIFY `sn` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key', AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `setup_role_tab`
--
ALTER TABLE `setup_role_tab`
  MODIFY `sn` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `setup_session_tab`
--
ALTER TABLE `setup_session_tab`
  MODIFY `sn` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `setup_status_tab`
--
ALTER TABLE `setup_status_tab`
  MODIFY `sn` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `setup_title_tab`
--
ALTER TABLE `setup_title_tab`
  MODIFY `sn` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `staff_tab`
--
ALTER TABLE `staff_tab`
  MODIFY `sn` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key', AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
