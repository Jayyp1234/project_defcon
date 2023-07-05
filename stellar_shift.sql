-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 06, 2023 at 12:27 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stellar_shift`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(1000) NOT NULL,
  `username` varchar(1000) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `status` varchar(1000) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `adminpubkey` varchar(100) NOT NULL,
  `userlevel` varchar(100) NOT NULL DEFAULT '0',
  `superadmin` int(11) NOT NULL DEFAULT 0,
  `peer_balance` varchar(1000) NOT NULL DEFAULT '0',
  `lastonline` int(11) NOT NULL DEFAULT 0,
  `telegrampass` varchar(100) NOT NULL DEFAULT '0ewfererg54445tt45e',
  `telegrampeerchatid` varchar(100) NOT NULL DEFAULT '0',
  `telegramchatid` varchar(100) NOT NULL DEFAULT '0',
  `mypin` varchar(100) NOT NULL DEFAULT '',
  `activatepin` int(11) NOT NULL DEFAULT 0,
  `adminpno` varchar(100) NOT NULL DEFAULT '',
  `telegramcsschatid` int(11) NOT NULL DEFAULT 0,
  `telegramswapchatid` varchar(100) NOT NULL DEFAULT '',
  `telegramvc_sys_code` int(11) NOT NULL DEFAULT 0,
  `telegram_notification_bot` varchar(100) NOT NULL DEFAULT '0',
  `telegram_notification_bot2` varchar(100) NOT NULL DEFAULT '0',
  `telegram_crash_noti_bot` varchar(100) NOT NULL DEFAULT '0',
  `telegramcashbackchatid` int(11) NOT NULL,
  `telegram_notification_bills` int(11) NOT NULL DEFAULT 0,
  `telegram_phone_call` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `username`, `password`, `name`, `status`, `created_at`, `updated_at`, `adminpubkey`, `userlevel`, `superadmin`, `peer_balance`, `lastonline`, `telegrampass`, `telegrampeerchatid`, `telegramchatid`, `mypin`, `activatepin`, `adminpno`, `telegramcsschatid`, `telegramswapchatid`, `telegramvc_sys_code`, `telegram_notification_bot`, `telegram_notification_bot2`, `telegram_crash_noti_bot`, `telegramcashbackchatid`, `telegram_notification_bills`, `telegram_phone_call`) VALUES
(2, 'habnarmtech@gmail.com', 'Habnarm', '$2y$10$ZjJiNTRhN2NhZWYxZjFhZOZ10JSOdE61Ql9TPiPBOSWG8ZowSWIsW', 'Okeke Johnpaul', '1', '2022-09-13 19:06:56', '2023-07-04 13:08:13', 'CardifykhxesDb93hSsSBWbOtWCMQpGtUFQG', 'WYF1', 0, '150', 1688472493, '1234efefe', '-800527516', '1482313546', '', 0, '09061962412', -800527516, '-905688669', -866214870, '-1001559267801', '-966957510', '-993877147', -918516865, -996930461, -851953336),
(3, 'idris@cardify.co', 'Idris', '$2y$10$ZDdmMWFmZTgxOTQ1NDQ4Zexx9BeIx1F93aJmSVcO9951jWOjABA22', 'Idris', '1', '2022-09-13 19:06:56', '2023-03-22 10:52:22', 'CardifykhxesDb93hSsSBWbOtWCMQpGtUFwwqqQG', 'BLOG23', 0, '0', 0, '0ewfererg54445tt45e', '0', '0', '', 0, '', 0, '0', 0, '0', '0', '0', 0, 0, 0),
(4, 'adebayo@savertech.co', 'Adebayo', '$2y$10$ZDdmMWFmZTgxOTQ1NDQ4Zexx9BeIx1F93aJmSVcO9951jWOjABA22', 'Adebayo Abdulbaqi', '0', '2022-11-05 01:31:11', '2023-03-22 10:52:22', 'CardifykhxesDb93hSsSBWbOtWCMQpdvytybsqdwa990we', 'BLOG23', 0, '0', 0, '0ewfererg54445tt45e', '0', '0', '', 0, '', 0, '0', 0, '0', '0', '0', 0, 0, 0),
(5, 'bisola@savertech.co', 'Bisola', '$2y$10$ZDdmMWFmZTgxOTQ1NDQ4Zexx9BeIx1F93aJmSVcO9951jWOjABA22', 'Bisola Aderemi', '1', '2022-11-05 01:31:11', '2023-06-25 18:29:49', 'CardifykhxesDb93hSsSIUYWJHUEVVdvytybsqdwaeee', 'WYF1', 0, '0', 1769366340, 'YIUJUI88w', '-800527516', '5002815600', '', 0, '08106839271', 0, '0', 0, '0', '0', '0', -918516865, -996930461, -851953336),
(6, 'idris@savertech.co', 'Idris', '$2y$10$ZDdmMWFmZTgxOTQ1NDQ4Zexx9BeIx1F93aJmSVcO9951jWOjABA22', 'Idris Taiwo', '1', '2022-11-05 01:31:11', '2023-03-22 10:52:22', 'CardifykhxesDb93hSsSBWbOtWJDIDJIFOEOtybsqdwawe', 'BLOG23', 0, '0', 0, '0ewfererg54445tt45e', '0', '0', '', 0, '', 0, '0', 0, '0', '0', '0', 0, 0, 0),
(7, 'imran@savertech.co\r\n', 'Imran', '$2y$10$ZDdmMWFmZTgxOTQ1NDQ4Zexx9BeIx1F93aJmSVcO9951jWOjABA22', 'Imran Babs babajide', '1', '2022-11-05 01:31:11', '2023-03-22 10:52:22', 'CardifykhxesDbJDJNFHJBJDtWCMQpdvytybsqdwav', 'BLOG23', 0, '0', 0, '0ewfererg54445tt45e', '0', '0', '', 0, '', 0, '0', 0, '0', '0', '0', 0, 0, 0),
(8, 'lawal@savertech.co', 'Lawal', '$2y$10$ZDdmMWFmZTgxOTQ1NDQ4Zexx9BeIx1F93aJmSVcO9951jWOjABA22', 'Lawal Adeniyi', '1', '2022-11-05 01:31:11', '2023-03-22 10:52:22', 'CardifykhxesDb93hSsSBWbOtWCMQpdvytybsqdwa4', 'BLOG23', 0, '0', 0, '0ewfererg54445tt45e', '0', '0', '', 0, '', 0, '0', 0, '0', '0', '0', 0, 0, 0),
(9, 'medinat@savertech.co', 'Medinat', '$2y$10$ZDdmMWFmZTgxOTQ1NDQ4Zexx9BeIx1F93aJmSVcO9951jWOjABA22', 'Medinat Adelaja', '1', '2022-11-05 01:31:11', '2023-03-22 10:52:22', 'CardifykhxesDb93DKFKKEJRdlfktybsqfksjrjSKDFfg', 'BLOG23', 0, '0', 0, '0ewfererg54445tt45e', '0', '0', '', 0, '', 0, '0', 0, '0', '0', '0', 0, 0, 0),
(10, 'olawale@savertech.co', 'Olawale', '$2y$10$ZDdmMWFmZTgxOTQ1NDQ4Zexx9BeIx1F93aJmSVcO9951jWOjABA22', 'Olawale Ajakaye', '1', '2022-11-05 01:31:11', '2023-03-22 10:52:22', 'CardifykhxesIDHIRgkrjenDJKEKfkgkgdsqdwaofkfg', 'BLOG23', 0, '0', 0, '0ewfererg54445tt45e', '0', '0', '', 0, '', 0, '0', 0, '0', '0', '0', 0, 0, 0),
(11, 'paul@savertech.co', 'Paul', '$2y$10$ZDdmMWFmZTgxOTQ1NDQ4Zexx9BeIx1F93aJmSVcO9951jWOjABA22', 'Okeke Johnpaul', '1', '2022-11-05 01:31:11', '2023-03-22 10:52:22', 'CardifykhxesDb93UDHUHEIJofkkfkgdgfvytyfghdwa', 'BLOG23', 0, '0', 0, '0ewfererg54445tt45e', '0', '0', '', 0, '', 0, '0', 0, '0', '0', '0', 0, 0, 0),
(12, 'rahman@savertech.co', 'Rahman', '$2y$10$ZDdmMWFmZTgxOTQ1NDQ4Zexx9BeIx1F93aJmSVcO9951jWOjABA22', 'Rahman', '1', '2022-11-05 01:31:11', '2023-03-22 10:52:22', 'CardifykhxesDb93hSsSBWbOtWCMQpdvytybsqdwa', 'BLOG23', 0, '0', 0, '0ewfererg54445tt45e', '0', '0', '', 0, '', 0, '0', 0, '0', '0', '0', 0, 0, 0),
(13, 'roqeeb@savertech.co', 'Roqeeb', '$2y$10$ZDdmMWFmZTgxOTQ1NDQ4Zexx9BeIx1F93aJmSVcO9951jWOjABA22', 'Roqeeb', '1', '2022-11-05 01:31:11', '2023-03-22 10:52:22', 'CardifykhxesDb93hSsSBWbOtWCMQpdvytybsqdwauu', 'BLOG23', 0, '0', 0, '0ewfererg54445tt45e', '0', '0', '', 0, '', 0, '0', 0, '0', '0', '0', 0, 0, 0),
(14, 'career@savertech.co', 'Career', '$2y$10$ZDdmMWFmZTgxOTQ1NDQ4Zexx9BeIx1F93aJmSVcO9951jWOjABA22', 'Savertech Career', '1', '2022-11-05 01:31:11', '2023-03-22 10:52:22', 'CardifykhxesDb93hSsUEJKFNRRJKSNkkldljrnflybsqdwa', 'BLOG23', 0, '0', 0, '0ewfererg54445tt45e', '0', '0', '', 0, '', 0, '0', 0, '0', '0', '0', 0, 0, 0),
(15, 'admin@savertech.co', 'Admin', '$2y$10$ZDdmMWFmZTgxOTQ1NDQ4Zexx9BeIx1F93aJmSVcO9951jWOjABA22', 'Savertech Limited', '1', '2022-11-05 01:31:11', '2023-03-22 10:52:22', 'CardifykhxesDb93hSsDJKNDJKENKKJJKRufhniromrko', 'BLOG23', 0, '0', 0, '0ewfererg54445tt45e', '0', '0', '', 0, '', 0, '0', 0, '0', '0', '0', 0, 0, 0),
(16, 'segun@savertech.co', 'Segun', '$2y$10$ZDdmMWFmZTgxOTQ1NDQ4Zexx9BeIx1F93aJmSVcO9951jWOjABA22', 'Segun Adepoju', '1', '2022-11-05 01:31:11', '2023-03-22 10:52:22', 'CardifykhxesDb93hSsSBUDJNOWKFJJKGLDLybsqdwa', 'BLOG23', 0, '0', 0, '0ewfererg54445tt45e', '0', '0', '', 0, '', 0, '0', 0, '0', '0', '0', 0, 0, 0),
(17, 'tunde@savertech.co', 'Tunde', '$2y$10$ZjJiNTRhN2NhZWYxZjFhZOZ10JSOdE61Ql9TPiPBOSWG8ZowSWIsW', 'Tunde Aderemi Ibrahim', '1', '2022-11-05 01:31:11', '2023-07-01 05:21:02', 'CardifykhxesDb93hSsSIUDHFIEJFIWJFPEKiijojowa', 'WYF1', 1, '0', 1688185262, '1234wfwfw', '-800527516', '2089218258', '', 0, '0806 874 5750', -800527516, '0', 0, '0', '0', '0', 0, 0, 0),
(18, 'yemisi@savertech.co', 'Yemisi', '$2y$10$ZDdmMWFmZTgxOTQ1NDQ4Zexx9BeIx1F93aJmSVcO9951jWOjABA22', 'Solaja Yemisi Musilimat', '1', '2022-11-05 01:31:11', '2023-03-22 10:52:22', 'CardifykhxesDb93hSsSBJNDIFOOWISvytybsqdwawdwd_oieofp', 'BLOG23', 0, '0', 0, '0ewfererg54445tt45e', '0', '0', '', 0, '', 0, '0', 0, '0', '0', '0', 0, 0, 0),
(19, 'hr@savertech.co', 'HR', '$2y$10$ZDdmMWFmZTgxOTQ1NDQ4Zexx9BeIx1F93aJmSVcO9951jWOjABA22', 'HR Department', '1', '2022-11-05 01:31:11', '2023-03-22 10:52:22', 'CardifykhxesDb93hSsSBUDJNOWKFJJKGLDLybsqdwaww', 'BLOG23', 0, '0', 0, '0ewfererg54445tt45e', '0', '0', '', 0, '', 0, '0', 0, '0', '0', '0', 0, 0, 0),
(20, 'mukthar@savertech.co', 'HR', '$2y$10$ZDdmMWFmZTgxOTQ1NDQ4Zexx9BeIx1F93aJmSVcO9951jWOjABA22', 'Mukthar', '1', '2022-11-05 01:31:11', '2023-03-22 10:52:22', 'CardifykhxesDb93hSsSBUDJNOWKFJJKGLDwdwLybsqdwa', 'BLOG23', 0, '0', 0, '0ewfererg54445tt45e', '0', '0', '', 0, '', 0, '0', 0, '0', '0', '0', 0, 0, 0),
(21, 'tayo@savertech.co', 'Tot', '$2y$10$Y2EyOGMxOTRhMDNkZjIyO.FCSPWrO3QYvK0uU.2zDjwyDFkVfvVli', 'Mr Tayo', '1', '2022-11-05 01:31:11', '2023-07-03 18:51:19', 'CardifykhxesDbJDJNFHJBJDtWCMQpdvytybsqdwavwdwffwf', 'BASEAD', 0, '0', 1688406679, '0ewfererg54445tt45eff', '0', '0', '', 0, '', 0, '0', 0, '0', '0', '0', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `apidatatable`
--

CREATE TABLE `apidatatable` (
  `id` int(11) NOT NULL,
  `privatekey` varchar(1000) NOT NULL,
  `tokenexpiremin` varchar(1000) NOT NULL,
  `servername` varchar(1000) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `apidatatable`
--

INSERT INTO `apidatatable` (`id`, `privatekey`, `tokenexpiremin`, `servername`, `created_at`, `updated_at`) VALUES
(1, 'comodfkfdfq', '300', 'LOG', '2022-08-16 14:06:07', '2023-06-18 22:58:06');

-- --------------------------------------------------------

--
-- Table structure for table `systemsettings`
--

CREATE TABLE `systemsettings` (
  `id` int(11) NOT NULL,
  `name` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iosversion` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `androidversion` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `webversion` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activesmssystem` int(11) NOT NULL,
  `activemailsystem` int(11) NOT NULL,
  `activebillsystem` int(11) NOT NULL COMMENT '1-1app,2-clibkonnec,3-SH',
  `emailfrom` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `baseurl` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `appshortdetail` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activepaysystem` int(11) NOT NULL,
  `activebanksystem` int(11) DEFAULT NULL COMMENT '1-paystack 2- monify 3-1app 4-SH',
  `activebvnsystem` int(11) NOT NULL,
  `supportemail` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `support_pno` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `appimgurl` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referalpointforusers` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_referall_withdraw` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `maxngn_auto` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `bitavgtoken` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `peer_deposit_bonus` double NOT NULL DEFAULT 0,
  `peer_withdrawal_bonus` double NOT NULL DEFAULT 0,
  `allow_ngn_fund_vc` int(11) NOT NULL DEFAULT 1,
  `amountto_add_ngnrate` int(11) NOT NULL DEFAULT 10,
  `allow_ngn_unload_vc` int(11) NOT NULL,
  `ngn_unload_rate` int(11) NOT NULL COMMENT 'Virtual card rate to unload',
  `ngn_load_rate` int(11) NOT NULL COMMENT 'Virtual card rate to load up a card',
  `intercomecode` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `marketer_1_point_cost` int(11) NOT NULL DEFAULT 10,
  `activate_rate_flaunt` int(11) NOT NULL COMMENT 'flautaute rate with provider'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `systemsettings`
--

INSERT INTO `systemsettings` (`id`, `name`, `iosversion`, `androidversion`, `webversion`, `activesmssystem`, `activemailsystem`, `activebillsystem`, `emailfrom`, `baseurl`, `location`, `appshortdetail`, `activepaysystem`, `activebanksystem`, `activebvnsystem`, `supportemail`, `support_pno`, `appimgurl`, `referalpointforusers`, `min_referall_withdraw`, `maxngn_auto`, `created_at`, `updated_at`, `bitavgtoken`, `peer_deposit_bonus`, `peer_withdrawal_bonus`, `allow_ngn_fund_vc`, `amountto_add_ngnrate`, `allow_ngn_unload_vc`, `ngn_unload_rate`, `ngn_load_rate`, `intercomecode`, `marketer_1_point_cost`, `activate_rate_flaunt`) VALUES
(1, 'StellarShift', '1.0', '1.0', '1.0', 1, 1, 1, 'no-reply@cardify.co', 'https://app.cardify.co/', '', '', 3, 4, 1, 'support@cardify.co', '01 229 2722', 'https://app.cardify.co/assets/images/Cardifylogo.png', '100', '1000', '2000', '2022-08-17 17:26:50', '2023-07-05 15:39:03', 'MTkwZDYwMGIxZjliNGM1NzkxNzAzM2I5YWVjZDI3MjY', 10, 15, 1, 6, 1, 700, 760, 'D_temJm4qju6APoPl_xVhPdAwGNsGg6xFZDrBbAj', 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE `token` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `useridentity` varchar(200) NOT NULL,
  `token` varchar(200) NOT NULL,
  `time` varchar(200) NOT NULL,
  `verifytype` varchar(200) NOT NULL,
  `otp` varchar(1000) NOT NULL,
  `timeinserted` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `usernotification`
--

CREATE TABLE `usernotification` (
  `id` int(11) NOT NULL,
  `userid` varchar(1000) NOT NULL COMMENT 'The user that owns the notification id',
  `notificationtext` varchar(1000) NOT NULL COMMENT 'The notification text',
  `notificationtitle` varchar(1000) NOT NULL DEFAULT 'Notification' COMMENT 'The notification title',
  `notificationtype` varchar(1000) NOT NULL COMMENT 'The type of notification, 1 means normal notification and 2 means transaction notification',
  `orderrefid` varchar(1000) NOT NULL COMMENT 'order id of the transaction if its trans notification',
  `notificationstatus` varchar(1000) NOT NULL COMMENT 'if its success or fail, 1 means success 0 means failed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `notificationcode` varchar(1000) NOT NULL COMMENT 'the track id unique code for notification',
  `seenbyuser` int(11) NOT NULL DEFAULT 0 COMMENT '1 seen by user 0 not seen'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `pin` varchar(100) NOT NULL DEFAULT ' ',
  `phone_number` varchar(100) DEFAULT NULL,
  `bal` double NOT NULL,
  `refcode` varchar(100) NOT NULL,
  `referby` varchar(100) NOT NULL DEFAULT ' ',
  `status` int(11) NOT NULL DEFAULT 0,
  `adminseen` int(11) NOT NULL DEFAULT 0,
  `userpubkey` varchar(1000) NOT NULL DEFAULT ' ',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `emailverified` int(11) NOT NULL DEFAULT 0,
  `phoneverified` int(11) NOT NULL DEFAULT 0,
  `address1` varchar(1000) NOT NULL DEFAULT ' ',
  `lastpassupdate` varchar(30) NOT NULL DEFAULT ' ',
  `lastpinupdate` varchar(100) NOT NULL DEFAULT ' ',
  `referalredeem` int(11) NOT NULL DEFAULT 0,
  `2fa` varchar(3) DEFAULT NULL COMMENT 'empty - Not Set,',
  `login_2fa` int(5) NOT NULL DEFAULT 0,
  `google_secret_key` varchar(100) DEFAULT NULL,
  `pinadded` int(11) NOT NULL DEFAULT 0,
  `profile_pic` varchar(100) NOT NULL DEFAULT '',
  `email_noti` int(11) NOT NULL DEFAULT 1,
  `sms_noti` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `firstname`, `lastname`, `password`, `pin`, `phone_number`, `bal`, `refcode`, `referby`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `emailverified`, `phoneverified`, `address1`, `lastpassupdate`, `lastpinupdate`, `referalredeem`, `2fa`, `login_2fa`, `google_secret_key`, `pinadded`, `profile_pic`, `email_noti`, `sms_noti`) VALUES
(1, 'okekejohnpaul12@gmail.com', 'Okeke', 'Johnpaul', '$2y$10$NDRmOTc5ZWI0MzMyZDkyOOCB2bE4rohBVoPXB5jJBTm36t/KtIli2', ' ', NULL, 0, '859Q1', '', 1, 0, 'StellarShift36KMovuGRFqAMT9EezjwMdKDVGFsd', '2023-07-05 20:47:48', '2023-07-05 20:47:48', 0, 0, ' ', ' ', ' ', 0, NULL, 0, NULL, 0, '', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `apidatatable`
--
ALTER TABLE `apidatatable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `systemsettings`
--
ALTER TABLE `systemsettings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usernotification`
--
ALTER TABLE `usernotification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
