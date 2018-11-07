-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2018 at 05:24 AM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `padi_hr`
--

-- --------------------------------------------------------

--
-- Table structure for table `approval`
--

CREATE TABLE `approval` (
  `id_approval` int(11) NOT NULL,
  `id_employee` int(11) DEFAULT NULL,
  `id_leave` int(11) DEFAULT NULL,
  `reason_reject` text,
  `status` int(11) DEFAULT NULL,
  `id_user_approval` int(11) DEFAULT NULL,
  `level_user_approval` varchar(10) DEFAULT NULL,
  `approval_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `approval`
--

INSERT INTO `approval` (`id_approval`, `id_employee`, `id_leave`, `reason_reject`, `status`, `id_user_approval`, `level_user_approval`, `approval_time`) VALUES
(40, 9, 26, '', 1, 4, 'spv', '2018-11-06 07:19:50'),
(41, 9, 26, '', 1, 8, 'hr', '2018-11-06 07:20:18'),
(42, 9, 27, '', 1, 4, 'spv', '2018-11-06 07:32:43'),
(43, 9, 27, '', 1, 8, 'hr', '2018-11-06 07:33:07');

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `id_city` int(11) NOT NULL,
  `name_city` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`id_city`, `name_city`) VALUES
(1, 'Surabaya'),
(2, 'Jakarta'),
(3, 'Malang'),
(4, 'Bali');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id_employee` int(11) NOT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `nickname` varchar(20) DEFAULT NULL,
  `nik_employee` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(300) DEFAULT NULL,
  `password_asli` varchar(300) DEFAULT NULL,
  `address` text,
  `address_2` text,
  `birthdate` date DEFAULT NULL,
  `npwp` varchar(50) DEFAULT NULL,
  `identity_number` varchar(50) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `id_role` int(11) DEFAULT NULL,
  `photo` varchar(200) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id_employee`, `fullname`, `nickname`, `nik_employee`, `email`, `password`, `password_asli`, `address`, `address_2`, `birthdate`, `npwp`, `identity_number`, `phone_number`, `id_role`, `photo`, `status`) VALUES
(1, 'Willis Yudha', 'willis', 'PADI-8924759247', 'willis.yudha@padi.net.id', '$2y$10$Cq4YYlQoLv6RSojkj3zLTuzQFje4WZ8KOGK6WqIpG1KxmhDBc9qnO', 'admin345', '', NULL, '2014-02-22', '', '', '', 1, '', 1),
(2, 'Dwi Utomo', 'Dwi', 'PADI-89247544444', 'dwi@padi.net.id', '$2y$10$W0OgbargHMAE54ig9vijn.b.hbgfEGurgdhKYCVn5WsXja9AP4ZsS', 'Gjmlcl', '', NULL, '0000-00-00', '', '', '', 5, '', 1),
(3, 'Dhita M', 'Dhita', 'PADI-02', 'dhita@padi.net.id', '$2y$10$d9OVWCL/.u7oOrjSv0tLVeNhqDLJI99iTl6ovE2DxwrrW939Uf5Cq', 'YWb7O7', '', NULL, '0000-00-00', '', '', '', 5, '', 1),
(4, 'Ketut A', 'Ketut', 'PADI-25', 'ketut@padi.net.id', '$2y$10$oUpbmbmcNM99ejcCS6505.jUqdpcjklPC/XEcOtEWw0efdtRLcaq6', '4WcPmY', '', NULL, '0000-00-00', '', '', '', 4, '', 1),
(5, 'Ardi Y', 'Ardi', 'PADI-03', 'tri@padi.net.id', '$2y$10$l2TiRHkbW5aQ3IqZg1FG7Obn6gVxOt4R/SdW9UNSbXoIZx9cZLV6K', 'Z52WxA', '', NULL, '0000-00-00', '', '', '', 5, '', 1),
(6, 'Alif', 'Alif', 'PADI-07', 'alif@padi.net.id', '$2y$10$j2Rkc8sCX8IZVYD1NE3qTufC579b8Jmz1s.hWwqUXVN75LsUvOrCe', 'v1QbZK', '', NULL, '0000-00-00', '', '', '', 5, '', 1),
(7, 'M Zamroni', 'Zamroni', 'PADI-54', 'zamroni@gmail.com', '$2y$10$2nB0pbYsSs5DAN6tUrdZhup88ebTtvDDjnHWEYsCbYyZbLowai7yO', 'ejSdth', '', NULL, '0000-00-00', '', '', '', 4, '', 1),
(8, 'Diana Chandra', 'Diana', 'PADI-12', 'diana@padi.net.id', '$2y$10$hnpbJIdAe6v.kwz2ti55K.2Tdl.gKP8d33fip3ehKzi9Nu67.dvY.', 'UcftWC', '', NULL, '0000-00-00', '', '', '', 2, '', 1),
(9, 'Puji ', 'Puji', '201101-072', 'puji@padi.net.id', '$2y$10$Wbg0UKaOrB50klKDLUAHGunsFv66M2jJoERTrn65aWocsYOdBMiAW', 'coTuCm', '', NULL, '0000-00-00', '', '', '', 5, '', 1),
(10, 'Shera Lia', 'Shera', 'PADI-45', 'shera@padi.net.id', '$2y$10$ilujbonfInyQHw3eUq1iu.u5DBJjq5ef7BLgB6kQ1fYwLpZbeZI7O', 'J7CRoc', '', '', '0000-00-00', '', '', '', 5, '', 1),
(11, 'Yudi', 'Yudi', 'PADI00099', 'yudi@padi.net.id', '$2y$10$UV1MJ7hFRWU/7G43.4H2IOP9tV9oukzDq7EZWpIE1rSot1fC4Tswy', 'MJwB9j', '', '', '0000-00-00', '', '', '', 5, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `employment`
--

CREATE TABLE `employment` (
  `id_employment` int(11) NOT NULL,
  `id_employee` int(11) NOT NULL DEFAULT '0',
  `tgl_mulai` date DEFAULT NULL,
  `tgl_berakhir` date DEFAULT NULL,
  `id_level` int(11) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `division` varchar(50) DEFAULT NULL,
  `id_city` int(11) DEFAULT NULL,
  `leave_quota` int(11) DEFAULT NULL,
  `leave_quota_ext` int(11) DEFAULT '3',
  `supervisor` int(11) DEFAULT NULL,
  `description` text,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employment`
--

INSERT INTO `employment` (`id_employment`, `id_employee`, `tgl_mulai`, `tgl_berakhir`, `id_level`, `position`, `division`, `id_city`, `leave_quota`, `leave_quota_ext`, `supervisor`, `description`, `status`) VALUES
(1, 1, '2018-08-02', '0000-00-00', 4, 'webdev', 'marketing', 1, 12, 3, 1, NULL, 1),
(18, 8, '2018-01-01', NULL, 3, 'HR', NULL, 1, 15, 3, 1, '', 1),
(19, 9, '2018-01-01', '2018-11-01', 3, 'Programmer', NULL, 1, 15, 3, 4, '', 0),
(22, 4, '2018-01-01', NULL, 4, 'Sales Manager', NULL, 1, 18, 3, 1, '', 1),
(23, 9, '2018-11-01', NULL, 4, 'Programmer', NULL, 1, 16, 3, 4, '', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `employment_active`
-- (See below for the actual view)
--
CREATE TABLE `employment_active` (
`id_employment` int(11)
,`id_employee` int(11)
,`tgl_mulai` date
,`tgl_berakhir` date
,`id_level` int(11)
,`position` varchar(100)
,`division` varchar(50)
,`id_city` int(11)
,`leave_quota` int(11)
,`supervisor` int(11)
,`description` text
,`status` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `joint_holiday`
--

CREATE TABLE `joint_holiday` (
  `id_joint_holiday` int(11) NOT NULL,
  `date_holiday` date DEFAULT NULL,
  `description` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `joint_holiday`
--

INSERT INTO `joint_holiday` (`id_joint_holiday`, `date_holiday`, `description`) VALUES
(1, '2018-07-25', 'bosnya ultah'),
(2, '2018-07-31', 'rekreasi kantor'),
(3, '2018-10-22', 'hari malas sedunia'),
(4, '2018-07-26', 'coba');

-- --------------------------------------------------------

--
-- Table structure for table `leave_adjustment`
--

CREATE TABLE `leave_adjustment` (
  `id_leave_adjustment` int(11) NOT NULL,
  `id_employee` int(11) NOT NULL DEFAULT '0',
  `adjustment_type` varchar(50) DEFAULT NULL,
  `quota` int(11) DEFAULT NULL,
  `description` text,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `leave_history`
--

CREATE TABLE `leave_history` (
  `id_leave_history` int(11) NOT NULL,
  `year_leave_history` varchar(50) DEFAULT NULL,
  `id_employee` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `leave_staff`
--

CREATE TABLE `leave_staff` (
  `id_leave` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `submission_date` datetime DEFAULT NULL,
  `days` int(11) DEFAULT NULL,
  `description` text,
  `payroll_deduction` int(11) DEFAULT '0',
  `dispensation_quota` int(11) DEFAULT '0',
  `leave_quota_ext` int(11) DEFAULT '0',
  `cancel_status` int(11) DEFAULT '0',
  `id_employee` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leave_staff`
--

INSERT INTO `leave_staff` (`id_leave`, `start_date`, `end_date`, `submission_date`, `days`, `description`, `payroll_deduction`, `dispensation_quota`, `leave_quota_ext`, `cancel_status`, `id_employee`) VALUES
(26, '2018-11-05', '2018-11-08', '2018-11-06 07:19:06', 4, 'coba mas puji', 0, 0, 0, 1, 9),
(27, '2018-11-08', '2018-11-16', '2018-11-06 07:32:11', 7, 'lagi', 0, 0, 0, 1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `level_staff`
--

CREATE TABLE `level_staff` (
  `id_level` int(11) NOT NULL,
  `level_name` int(11) DEFAULT NULL,
  `level_quota` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `level_staff`
--

INSERT INTO `level_staff` (`id_level`, `level_name`, `level_quota`) VALUES
(1, 1, 12),
(2, 2, 12),
(3, 3, 15),
(4, 4, 18),
(5, 5, 24);

-- --------------------------------------------------------

--
-- Table structure for table `reset_leave`
--

CREATE TABLE `reset_leave` (
  `id_reset_leave` int(11) NOT NULL,
  `year_reset_leave` int(11) DEFAULT NULL,
  `id_employee` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `name_role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id_role`, `name_role`) VALUES
(1, 'Super Admin'),
(2, 'HR'),
(3, 'HR Staff'),
(4, 'Supervisor'),
(5, 'Member');

-- --------------------------------------------------------

--
-- Table structure for table `user_history`
--

CREATE TABLE `user_history` (
  `id_user_history` int(11) NOT NULL,
  `id_employee` int(11) DEFAULT NULL,
  `time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_history`
--

INSERT INTO `user_history` (`id_user_history`, `id_employee`, `time`) VALUES
(1, 1, '2018-05-31 02:45:17'),
(2, 1, '2018-05-31 07:54:30'),
(3, 1, '2018-06-04 02:02:39'),
(4, 1, '2018-06-05 02:07:00'),
(5, 1, '2018-06-05 07:33:44'),
(6, 1, '2018-06-06 07:00:20'),
(7, 1, '2018-06-20 03:16:36'),
(8, 1, '2018-06-20 07:53:02'),
(9, 1, '2018-06-22 02:02:47'),
(10, 1, '2018-06-25 02:57:53'),
(11, 1, '2018-06-28 02:57:04'),
(12, 1, '2018-06-28 08:13:39'),
(13, 1, '2018-06-29 01:33:36'),
(14, 1, '2018-06-29 06:51:28'),
(15, 1, '2018-07-02 02:27:13'),
(16, 1, '2018-07-04 02:34:08'),
(17, 1, '2018-07-05 01:40:41'),
(18, 1, '2018-07-05 09:45:49'),
(19, 1, '2018-07-09 01:57:25'),
(20, 1, '2018-07-17 02:48:53'),
(21, 1, '2018-07-17 07:33:36'),
(22, 1, '2018-07-23 02:11:23'),
(23, 1, '2018-07-24 02:57:35'),
(24, 1, '2018-07-24 08:11:02'),
(25, 1, '2018-07-25 01:56:37'),
(26, 1, '2018-07-25 04:02:50'),
(27, 1, '2018-07-25 07:04:15'),
(28, 1, '2018-07-26 06:57:00'),
(29, 1, '2018-07-27 02:14:24'),
(30, 1, '2018-07-27 07:17:37'),
(31, 1, '2018-07-30 01:42:35'),
(32, 1, '2018-07-30 08:42:30'),
(33, 1, '2018-07-31 01:42:00'),
(34, 1, '2018-07-31 07:28:05'),
(35, 1, '2018-07-31 07:28:18'),
(36, 1, '2018-07-31 07:28:26'),
(37, 1, '2018-07-31 07:28:36'),
(38, 1, '2018-07-31 07:29:29'),
(39, 1, '2018-07-31 07:34:43'),
(40, 1, '2018-07-31 07:36:58'),
(41, 1, '2018-07-31 07:42:58'),
(42, 1, '2018-07-31 07:43:54'),
(43, 1, '2018-07-31 07:57:31'),
(44, 1, '2018-07-31 08:55:17'),
(45, 1, '2018-07-31 08:58:25'),
(46, 1, '2018-07-31 09:04:11'),
(47, 1, '2018-07-31 09:05:22'),
(48, 1, '2018-07-31 09:06:00'),
(49, 1, '2018-07-31 09:45:23'),
(50, 1, '2018-07-31 09:46:38'),
(51, 1, '2018-08-02 02:22:23'),
(52, 1, '2018-08-02 02:27:50'),
(53, 1, '2018-08-02 02:28:18'),
(54, 1, '2018-08-02 02:30:30'),
(55, 1, '2018-08-02 02:31:46'),
(56, 1, '2018-08-02 03:42:10'),
(57, 1, '2018-08-02 03:42:58'),
(58, 1, '2018-08-02 03:53:55'),
(59, 1, '2018-08-03 02:01:55'),
(60, 1, '2018-08-03 02:10:47'),
(61, 1, '2018-08-03 02:17:40'),
(62, 1, '2018-08-03 02:20:04'),
(63, 1, '2018-08-03 02:21:13'),
(64, 1, '2018-08-03 02:23:54'),
(65, 1, '2018-08-03 02:24:11'),
(66, 1, '2018-08-03 02:25:10'),
(67, 1, '2018-08-03 02:30:53'),
(68, 1, '2018-08-03 02:30:58'),
(69, 1, '2018-08-03 02:32:53'),
(70, 1, '2018-08-03 02:33:43'),
(71, 1, '2018-08-03 02:34:25'),
(72, 1, '2018-08-03 02:34:44'),
(73, 1, '2018-08-03 02:37:40'),
(74, 1, '2018-08-03 07:15:31'),
(75, 1, '2018-08-06 06:41:43'),
(76, 1, '2018-08-06 07:50:07'),
(77, 1, '2018-08-07 04:02:21'),
(78, 1, '2018-08-07 07:28:05'),
(79, 1, '2018-08-08 01:44:55'),
(80, 1, '2018-08-08 03:31:43'),
(81, 1, '2018-08-08 07:00:45'),
(82, 1, '2018-08-09 02:23:24'),
(83, 1, '2018-08-09 08:44:01'),
(84, 1, '2018-08-13 03:39:05'),
(85, 1, '2018-08-13 08:20:51'),
(86, 1, '2018-08-14 02:55:50'),
(87, 1, '2018-08-14 09:33:05'),
(88, 1, '2018-08-15 01:59:51'),
(89, 1, '2018-08-21 02:53:59'),
(90, 1, '2018-08-21 07:24:49'),
(91, 1, '2018-08-27 03:11:10'),
(92, 1, '2018-08-27 07:11:35'),
(93, 1, '2018-08-28 02:53:34'),
(94, 1, '2018-08-28 08:16:20'),
(95, 1, '2018-08-29 02:23:50'),
(96, 1, '2018-08-29 03:28:22'),
(97, 1, '2018-08-29 06:58:00'),
(98, 1, '2018-08-29 06:59:45'),
(99, 1, '2018-08-30 02:16:25'),
(100, 1, '2018-08-30 08:16:54'),
(101, 4, '2018-08-30 08:17:29'),
(102, 4, '2018-08-30 08:21:15'),
(103, 2, '2018-08-30 08:40:10'),
(104, 2, '2018-08-30 08:42:55'),
(105, 3, '2018-08-30 08:43:10'),
(106, 6, '2018-08-30 09:44:05'),
(107, 4, '2018-08-30 09:44:56'),
(108, 7, '2018-08-30 09:57:11'),
(109, 1, '2018-08-31 01:52:47'),
(110, 1, '2018-08-31 07:50:35'),
(111, 4, '2018-09-03 02:20:52'),
(112, 8, '2018-09-03 02:44:29'),
(113, 1, '2018-09-03 05:14:02'),
(114, 8, '2018-09-03 05:31:45'),
(115, 1, '2018-09-04 02:18:23'),
(116, 1, '2018-09-04 04:55:07'),
(117, 8, '2018-09-04 06:41:00'),
(118, 1, '2018-09-05 01:50:12'),
(119, 1, '2018-09-05 06:48:10'),
(120, 1, '2018-09-05 09:22:53'),
(121, 8, '2018-09-05 09:26:59'),
(122, 4, '2018-09-05 09:30:07'),
(123, 8, '2018-09-05 09:30:37'),
(124, 3, '2018-09-05 09:34:36'),
(125, 1, '2018-09-06 02:51:55'),
(126, 1, '2018-09-06 06:53:30'),
(127, 1, '2018-09-07 02:15:01'),
(128, 1, '2018-09-07 06:38:36'),
(129, 1, '2018-09-10 03:48:08'),
(130, 1, '2018-09-10 07:42:09'),
(131, 1, '2018-09-12 01:46:30'),
(132, 1, '2018-09-12 08:27:34'),
(133, 1, '2018-09-13 04:25:46'),
(134, 1, '2018-09-13 07:01:51'),
(135, 1, '2018-09-14 01:52:07'),
(136, 8, '2018-09-14 04:25:21'),
(137, 1, '2018-09-14 04:27:28'),
(138, 1, '2018-09-14 09:10:36'),
(139, 1, '2018-09-17 02:08:33'),
(140, 8, '2018-09-17 03:53:02'),
(141, 1, '2018-09-18 03:25:17'),
(142, 3, '2018-09-18 04:15:07'),
(143, 1, '2018-09-18 04:17:31'),
(144, 3, '2018-09-18 04:26:28'),
(145, 3, '2018-09-18 06:33:19'),
(146, 1, '2018-09-19 06:52:37'),
(147, 1, '2018-09-19 07:56:30'),
(148, 1, '2018-09-20 01:37:01'),
(149, 1, '2018-09-20 03:09:44'),
(150, 1, '2018-09-21 09:27:53'),
(151, 1, '2018-09-24 05:20:22'),
(152, 1, '2018-09-25 03:21:03'),
(153, 2, '2018-09-25 05:51:36'),
(154, 8, '2018-09-25 06:06:44'),
(155, 2, '2018-09-25 06:18:39'),
(156, 4, '2018-09-25 06:19:52'),
(157, 8, '2018-09-25 06:58:28'),
(158, 8, '2018-09-25 09:32:43'),
(159, 2, '2018-09-25 09:33:45'),
(160, 1, '2018-09-25 09:39:25'),
(161, 8, '2018-09-25 09:39:49'),
(162, 2, '2018-09-25 09:49:52'),
(163, 1, '2018-09-26 02:49:34'),
(164, 1, '2018-09-26 06:27:23'),
(165, 2, '2018-09-26 06:45:38'),
(166, 4, '2018-09-26 06:46:37'),
(167, 8, '2018-09-26 07:12:02'),
(168, 2, '2018-09-26 08:53:17'),
(169, 1, '2018-09-26 09:01:38'),
(170, 1, '2018-09-28 02:37:06'),
(171, 1, '2018-09-28 07:13:59'),
(172, 1, '2018-10-01 02:21:42'),
(173, 1, '2018-10-01 06:57:07'),
(174, 1, '2018-10-02 03:25:28'),
(175, 1, '2018-10-03 07:14:56'),
(176, 1, '2018-10-04 05:01:41'),
(177, 1, '2018-10-04 07:27:33'),
(178, 1, '2018-10-04 08:42:00'),
(179, 1, '2018-10-08 10:00:50'),
(180, 1, '2018-10-09 04:12:28'),
(181, 1, '2018-10-10 02:33:10'),
(182, 1, '2018-10-11 03:19:55'),
(183, 1, '2018-10-15 04:18:44'),
(184, 1, '2018-10-15 07:00:08'),
(185, 1, '2018-10-15 07:01:02'),
(186, 1, '2018-10-15 07:13:17'),
(187, 2, '2018-10-15 08:15:30'),
(188, 1, '2018-10-15 08:17:36'),
(189, 1, '2018-10-16 06:52:18'),
(190, 1, '2018-10-18 02:54:50'),
(191, 1, '2018-10-22 02:17:36'),
(192, 1, '2018-10-23 04:48:06'),
(193, 1, '2018-10-24 06:57:11'),
(194, 9, '2018-10-24 07:07:22'),
(195, 1, '2018-10-24 07:09:26'),
(196, 9, '2018-10-24 07:11:58'),
(197, 1, '2018-10-24 07:14:31'),
(198, 9, '2018-10-24 07:22:41'),
(199, 8, '2018-10-24 07:23:46'),
(200, 4, '2018-10-24 07:25:11'),
(201, 9, '2018-10-24 07:38:37'),
(202, 8, '2018-10-24 07:39:43'),
(203, 4, '2018-10-24 07:42:39'),
(204, 8, '2018-10-24 07:47:12'),
(205, 9, '2018-10-24 07:48:23'),
(206, 1, '2018-10-24 07:49:12'),
(207, 9, '2018-10-24 08:13:43'),
(208, 4, '2018-10-24 08:16:20'),
(209, 8, '2018-10-24 08:17:17'),
(210, 1, '2018-10-25 01:25:47'),
(211, 10, '2018-10-25 01:58:43'),
(212, 1, '2018-10-25 02:17:07'),
(213, 10, '2018-10-25 02:18:22'),
(214, 1, '2018-10-25 02:41:53'),
(215, 1, '2018-10-25 02:44:27'),
(216, 10, '2018-10-25 02:44:58'),
(217, 1, '2018-10-25 03:01:11'),
(218, 10, '2018-10-25 03:33:07'),
(219, 1, '2018-10-25 03:47:53'),
(220, 10, '2018-10-25 04:05:43'),
(221, 1, '2018-10-25 04:07:04'),
(222, 8, '2018-10-25 04:44:13'),
(223, 10, '2018-10-25 05:59:57'),
(224, 1, '2018-10-25 07:17:31'),
(225, 1, '2018-10-29 01:25:03'),
(226, 1, '2018-10-30 08:47:27'),
(227, 1, '2018-10-31 01:34:25'),
(228, 1, '2018-10-31 06:28:06'),
(229, 8, '2018-10-31 08:13:28'),
(230, 1, '2018-10-31 08:13:51'),
(231, 8, '2018-10-31 08:22:33'),
(232, 1, '2018-10-31 09:51:43'),
(233, 1, '2018-11-02 02:21:24'),
(234, 1, '2018-11-05 02:43:20'),
(235, 1, '2018-11-05 05:12:57'),
(236, 1, '2018-11-06 02:48:46'),
(237, 2, '2018-11-06 05:00:02'),
(238, 4, '2018-11-06 05:01:17'),
(239, 8, '2018-11-06 05:02:00'),
(240, 2, '2018-11-06 05:02:54'),
(241, 4, '2018-11-06 05:16:08'),
(242, 8, '2018-11-06 05:17:58'),
(243, 2, '2018-11-06 05:50:14'),
(244, 1, '2018-11-06 05:50:47'),
(245, 9, '2018-11-06 07:17:46'),
(246, 4, '2018-11-06 07:19:39'),
(247, 8, '2018-11-06 07:20:07'),
(248, 9, '2018-11-06 07:20:30'),
(249, 4, '2018-11-06 07:32:33'),
(250, 8, '2018-11-06 07:32:58'),
(251, 9, '2018-11-06 07:33:25'),
(252, 1, '2018-11-06 07:34:06'),
(253, 1, '2018-11-07 02:11:28'),
(254, 1, '2018-11-07 05:16:37');

-- --------------------------------------------------------

--
-- Structure for view `employment_active`
--
DROP TABLE IF EXISTS `employment_active`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `employment_active`  AS  select `employment`.`id_employment` AS `id_employment`,`employment`.`id_employee` AS `id_employee`,`employment`.`tgl_mulai` AS `tgl_mulai`,`employment`.`tgl_berakhir` AS `tgl_berakhir`,`employment`.`id_level` AS `id_level`,`employment`.`position` AS `position`,`employment`.`division` AS `division`,`employment`.`id_city` AS `id_city`,`employment`.`leave_quota` AS `leave_quota`,`employment`.`supervisor` AS `supervisor`,`employment`.`description` AS `description`,`employment`.`status` AS `status` from `employment` where (`employment`.`status` = 1) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approval`
--
ALTER TABLE `approval`
  ADD PRIMARY KEY (`id_approval`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id_city`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id_employee`);

--
-- Indexes for table `employment`
--
ALTER TABLE `employment`
  ADD PRIMARY KEY (`id_employment`);

--
-- Indexes for table `joint_holiday`
--
ALTER TABLE `joint_holiday`
  ADD PRIMARY KEY (`id_joint_holiday`);

--
-- Indexes for table `leave_adjustment`
--
ALTER TABLE `leave_adjustment`
  ADD PRIMARY KEY (`id_leave_adjustment`);

--
-- Indexes for table `leave_history`
--
ALTER TABLE `leave_history`
  ADD PRIMARY KEY (`id_leave_history`);

--
-- Indexes for table `leave_staff`
--
ALTER TABLE `leave_staff`
  ADD PRIMARY KEY (`id_leave`);

--
-- Indexes for table `level_staff`
--
ALTER TABLE `level_staff`
  ADD PRIMARY KEY (`id_level`);

--
-- Indexes for table `reset_leave`
--
ALTER TABLE `reset_leave`
  ADD PRIMARY KEY (`id_reset_leave`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `user_history`
--
ALTER TABLE `user_history`
  ADD PRIMARY KEY (`id_user_history`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approval`
--
ALTER TABLE `approval`
  MODIFY `id_approval` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id_city` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id_employee` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `employment`
--
ALTER TABLE `employment`
  MODIFY `id_employment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `joint_holiday`
--
ALTER TABLE `joint_holiday`
  MODIFY `id_joint_holiday` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `leave_adjustment`
--
ALTER TABLE `leave_adjustment`
  MODIFY `id_leave_adjustment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `leave_history`
--
ALTER TABLE `leave_history`
  MODIFY `id_leave_history` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `leave_staff`
--
ALTER TABLE `leave_staff`
  MODIFY `id_leave` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `level_staff`
--
ALTER TABLE `level_staff`
  MODIFY `id_level` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `reset_leave`
--
ALTER TABLE `reset_leave`
  MODIFY `id_reset_leave` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `user_history`
--
ALTER TABLE `user_history`
  MODIFY `id_user_history` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=255;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
