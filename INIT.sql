-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2021 at 06:45 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `--rms--`
--

-- --------------------------------------------------------

--
-- Table structure for table `apartment`
--

CREATE TABLE `apartment` (
  `apartment_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` enum('FLAT','BUNGLOW','MAISONETTE','OTHER') NOT NULL DEFAULT 'FLAT',
  `town` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `owner_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `apartment`
--

INSERT INTO `apartment` (`apartment_id`, `name`, `type`, `town`, `location`, `description`, `owner_id`) VALUES
(2, 'Kailash Boys Hostel', 'BUNGLOW', 'Hamirpur', 'KBH, NIT Hamirpur, Hamirpur, 170005', 'A good place to live for boys studying in NITH.', 111),
(4, 'R. G. bunglow', 'BUNGLOW', 'Rajkot', 'Jubaly garden, Street Avenue Road, 646464', 'Available for lease in affordable price', 111),
(5, 'Apple Villa', 'MAISONETTE', 'Kolkata', '200b, Ahiritolla Street, Kolkata', 'Pleasant Lifestyle with extraordinary vila.', 113),
(7, 'Sanskar evenue', 'BUNGLOW', 'Delhi', 'Subhash Garden, Sector 45, 546412', 'Well settled and crafted bulgalows.', 115),
(8, 'Alex Complex', 'FLAT', 'Jaipur', 'Alex Complex, Tagore Road, 150feet Road, Jaipur, Rajsthan', '12 story building with 1, 2, 3 BHK flats.', 113),
(10, 'Raviraj Society', 'OTHER', 'Rajkot', 'Sea Link Road, Andheri, Rajkot, Gujarat 123112', 'Tenament houses with lots of amenities.', 114);

-- --------------------------------------------------------

--
-- Table structure for table `assigned`
--

CREATE TABLE `assigned` (
  `tenant_id` int(11) NOT NULL,
  `house_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `deposit_amt` int(11) NOT NULL DEFAULT 0,
  `placement_fee` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `assigned`
--

INSERT INTO `assigned` (`tenant_id`, `house_id`, `date`, `deposit_amt`, `placement_fee`) VALUES
(4, 2, '2021-09-30 18:30:00', 3000, 1200),
(6, 4, '2021-10-01 18:30:00', 5000, 500),
(6, 10, '2021-10-01 18:30:00', 15000, 1000),
(4, 11, '2021-10-03 07:04:26', 20000, 1000),
(7, 12, '2021-10-03 08:13:52', 20000, 1000),
(4, 13, '2021-10-02 07:42:42', 20000, 1000),
(13, 14, '2021-10-03 10:31:11', 20000, 3000),
(8, 15, '2021-10-03 10:30:55', 12000, 1000),
(13, 18, '2021-10-04 07:32:31', 12000, 450);

-- --------------------------------------------------------

--
-- Table structure for table `house`
--

CREATE TABLE `house` (
  `house_id` int(11) NOT NULL,
  `house_no` varchar(50) NOT NULL,
  `type` enum('1BHK','2BHK','3BHK','MAISONETTE','SHOP') NOT NULL,
  `rent` int(11) NOT NULL DEFAULT 0,
  `deposit_amt` int(11) NOT NULL DEFAULT 0,
  `placement_fee` int(11) NOT NULL DEFAULT 0,
  `description` varchar(100) NOT NULL,
  `apartment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `house`
--

INSERT INTO `house` (`house_id`, `house_no`, `type`, `rent`, `deposit_amt`, `placement_fee`, `description`, `apartment_id`) VALUES
(2, 'E-501', 'MAISONETTE', 25000, 3000, 1200, 'Boys hostel room for 4 person', 2),
(4, 'E-201', '1BHK', 25000, 5000, 2000, 'Boys hostel room for 4 person', 2),
(7, 'Block 1', '3BHK', 10000, 20000, 3000, 'Well suited for even large family.', 7),
(10, '101', '3BHK', 25000, 20000, 1000, '3 BHK luxurious flat on 1st floor.', 8),
(11, '1201', '3BHK', 26000, 20000, 1000, 'Sky-touching pant house on the top floor in Alex Complex.', 8),
(12, '201', '3BHK', 25000, 20000, 1000, '3 BHK luxurious flat on 2nd floor.', 8),
(13, '301', '3BHK', 25000, 20000, 1000, '3 BHK luxurious flat on 3rd floor.\r\n', 8),
(14, 'Block 2', '3BHK', 10000, 20000, 3000, 'Well suited for large family', 7),
(15, 'A1', '2BHK', 8000, 12000, 1000, 'Block 1 of R. G. bunglows', 4),
(16, 'A2', '3BHK', 8000, 12000, 1000, 'Block 2 of R. G. bunglows', 4),
(17, 'D/12', '1BHK', 4500, 12000, 500, '540sqft flat area for your 1BHK house', 10),
(18, 'D/11', '1BHK', 5500, 12000, 450, '450sqrt area for 1BHK flat', 10),
(19, 'A-101', 'MAISONETTE', 7000, 5000, 800, 'Available in 3 variant', 5),
(20, 'A-102', 'MAISONETTE', 7000, 5000, 500, 'Available in 3 variant', 5);

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `owner_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `owner`
--

INSERT INTO `owner` (`owner_id`, `name`, `phone`, `email`, `address`, `created_at`) VALUES
(111, 'Mayur Kumar', '1234567890', 'mayur@gmail.com', 'D/203, Shyamal plaza, Kabir Road, Rajkot, Gujarat.', '2021-09-28 08:35:02'),
(113, 'Rohit Acharya', '7897897897', 'rohit@gmail.com', '1623 Hart Street, Watertown, 167955', '2021-09-30 08:24:36'),
(114, 'Ranpariya Amish', '08128167201', 'amish@gmail.com', 'A/403 Sagar Residency,\r\nB/H Astha Residency, 150 feet Ring Road\r\nRajkot, Gujarat, 360004', '2021-09-30 08:52:41'),
(115, 'Aaditya Singh', '9639639639', 'aaditya@gmail.com', 'Ankur Vila, Hospital road, Punjab, 123456', '2021-09-30 09:29:54');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `house_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `payment_type` enum('DEPOSIT','PLACEMENT_FEES','RENT') NOT NULL DEFAULT 'RENT',
  `description` varchar(100) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `tenant_id`, `owner_id`, `house_id`, `amount`, `payment_type`, `description`, `payment_date`) VALUES
(13, 4, 111, 2, 3000, 'DEPOSIT', 'House Deposit', '2021-10-02 04:10:26'),
(14, 4, 111, 2, 1200, 'PLACEMENT_FEES', 'House Placement Fee', '2021-10-02 04:10:26'),
(15, 6, 113, 10, 15000, 'DEPOSIT', 'House Deposit', '2021-10-02 04:10:38'),
(16, 6, 113, 10, 1000, 'PLACEMENT_FEES', 'House Placement Fee', '2021-10-02 04:10:38'),
(17, 6, 111, 4, 5000, 'DEPOSIT', 'House Deposit', '2021-10-02 04:10:56'),
(18, 6, 111, 4, 500, 'PLACEMENT_FEES', 'House Placement Fee', '2021-10-02 04:10:56'),
(21, 4, 111, 2, 75000, 'RENT', 'House Rent for 3 months', '2021-10-03 05:46:15'),
(22, 6, 111, 4, 150000, 'RENT', 'House Rent for 6 months', '2021-10-03 06:33:54'),
(25, 4, 113, 11, 20000, 'DEPOSIT', 'House Deposit', '2021-10-03 07:04:26'),
(26, 4, 113, 11, 1000, 'PLACEMENT_FEES', 'House Placement Fee', '2021-10-03 07:04:26'),
(27, 4, 113, 11, 78000, 'RENT', 'House Rent for 3 months', '2021-10-03 07:05:06'),
(28, 4, 111, 2, 25000, 'RENT', 'House Rent for 1 months', '2021-10-03 07:06:38'),
(29, 4, 113, 13, 20000, 'DEPOSIT', 'House Deposit', '2021-10-03 07:42:42'),
(30, 4, 113, 13, 1000, 'PLACEMENT_FEES', 'House Placement Fee', '2021-10-03 07:42:42'),
(31, 7, 113, 12, 20000, 'DEPOSIT', 'House Deposit', '2021-10-03 08:13:52'),
(32, 7, 113, 12, 1000, 'PLACEMENT_FEES', 'House Placement Fee', '2021-10-03 08:13:52'),
(33, 8, 111, 15, 12000, 'DEPOSIT', 'House Deposit', '2021-10-03 10:30:55'),
(34, 8, 111, 15, 1000, 'PLACEMENT_FEES', 'House Placement Fee', '2021-10-03 10:30:55'),
(35, 13, 115, 14, 20000, 'DEPOSIT', 'House Deposit', '2021-10-03 10:31:11'),
(36, 13, 115, 14, 3000, 'PLACEMENT_FEES', 'House Placement Fee', '2021-10-03 10:31:11'),
(37, 6, 113, 10, 25000, 'RENT', 'House Rent for 1 months', '2021-10-04 07:11:46'),
(38, 13, 114, 18, 12000, 'DEPOSIT', 'House Deposit', '2021-10-04 07:32:31'),
(39, 13, 114, 18, 450, 'PLACEMENT_FEES', 'House Placement Fee', '2021-10-04 07:32:31'),
(40, 7, 113, 12, 25000, 'RENT', 'House Rent for 1 months', '2021-10-04 07:33:36');

-- --------------------------------------------------------

--
-- Table structure for table `tenant`
--

CREATE TABLE `tenant` (
  `tenant_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `occupation` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tenant`
--

INSERT INTO `tenant` (`tenant_id`, `name`, `email`, `phone`, `address`, `occupation`, `created_at`) VALUES
(4, 'Jatin Khatri', 'jatin@gmail.com', '9876543210', '123/E open-air theatre, NITH, Hamirpur, 123456', 'Student', '2021-09-28 08:37:25'),
(6, 'Mohd. Uvesh', 'uv@gmail.com', '4564564564', 'abc, xyd society, qwe road, wer city, rty state, 1', 'Professor', '2021-09-30 05:58:19'),
(7, 'Rahul Singh', 'rahul@gmail.com', '1231231231', '13A Gol street, James Road, Maharashtra, 327898', 'Businessman', '2021-09-30 08:27:10'),
(8, 'Jay Patel', 'jay@gmail.com', '7417417417', 'Figma Chok, Adobe Road, Andheri, Ahmedabad.', 'UX designer', '2021-10-01 02:38:23'),
(13, 'Sagar Tank', 'sagar@gmail.com', '1122334455', 'A8 Shashtri medan, Racecourse road, Rajkot, 235623', 'Lawyer', '2021-10-01 03:03:32');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `email`, `password`, `created_at`) VALUES
(1, 'test@gmail.com', '1234', '2021-09-28 03:29:00'),
(2, 'amishranpariya@gmail.com', '1234', '2021-09-28 06:03:36'),
(4, 'test3@gmail.com', '1234', '2021-09-28 06:09:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apartment`
--
ALTER TABLE `apartment`
  ADD PRIMARY KEY (`apartment_id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `apartment_id` (`apartment_id`);

--
-- Indexes for table `assigned`
--
ALTER TABLE `assigned`
  ADD PRIMARY KEY (`house_id`) USING BTREE,
  ADD KEY `tenant_id` (`tenant_id`),
  ADD KEY `house_id` (`house_id`);

--
-- Indexes for table `house`
--
ALTER TABLE `house`
  ADD PRIMARY KEY (`house_id`),
  ADD KEY `apartment_id` (`apartment_id`),
  ADD KEY `house_id` (`house_id`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`owner_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `owner_id` (`owner_id`) USING BTREE;

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `tenant_id` (`tenant_id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `house_id` (`house_id`);

--
-- Indexes for table `tenant`
--
ALTER TABLE `tenant`
  ADD PRIMARY KEY (`tenant_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apartment`
--
ALTER TABLE `apartment`
  MODIFY `apartment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `house`
--
ALTER TABLE `house`
  MODIFY `house_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `owner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `tenant`
--
ALTER TABLE `tenant`
  MODIFY `tenant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `apartment`
--
ALTER TABLE `apartment`
  ADD CONSTRAINT `apartment_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `owner` (`owner_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `assigned`
--
ALTER TABLE `assigned`
  ADD CONSTRAINT `assigned_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenant` (`tenant_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `assigned_ibfk_2` FOREIGN KEY (`house_id`) REFERENCES `house` (`house_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `house`
--
ALTER TABLE `house`
  ADD CONSTRAINT `house_ibfk_1` FOREIGN KEY (`apartment_id`) REFERENCES `apartment` (`apartment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_5` FOREIGN KEY (`owner_id`) REFERENCES `owner` (`owner_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_ibfk_7` FOREIGN KEY (`tenant_id`) REFERENCES `assigned` (`tenant_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_ibfk_8` FOREIGN KEY (`house_id`) REFERENCES `assigned` (`house_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
