-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 25, 2024 at 10:20 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Company`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'level 0',
  `email` varchar(200) NOT NULL,
  `password` varchar(500) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `role`, `email`, `password`, `date`) VALUES
(5, 'Obed Nyakundi', 'level-0', 'obed@example.com', '$2y$12$EcXNA8UHc53gWrW0suAB2.b028O7ItuHyLqXrQN6j00ax7OHVBF3i', '2021-05-22 10:56:20'),
(8, 'Ace One', 'level-1', 'ace@nyumbani.com', '$2y$12$CLdOW9clSRIYAnLzicI97.J5VJt6KPVwnSOeR0TT8u/uZCC/mxgoq', '2021-05-24 16:01:01');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `blogid` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `name`, `comment`, `blogid`, `date`) VALUES
(1, 'Jaden', 'Awesome post guys!!', 6, '2018-07-28 00:15:59'),
(6, 'Cliff', 'I really relate to this', 5, '2018-07-28 01:00:14');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `names` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `names`, `email`, `message`, `date`) VALUES
(1, 'Ethredah', 'ethredah@gmail.com', 'Hello there Ushauri team.', '2018-07-27 16:57:59'),
(2, 'Chao', 'chao@gmail.com', 'Hi there!!', '2018-07-27 16:57:59'),
(4, 'James Mlamba', 'jaymo@gmail.com', 'I am interested in a meeting.', '2018-07-28 01:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `houses`
--

CREATE TABLE `houses` (
  `houseID` int(11) NOT NULL,
  `house_name` text NOT NULL,
  `number_of_rooms` int(10) NOT NULL DEFAULT 1,
  `rent_amount` double NOT NULL,
  `location` text NOT NULL,
  `num_of_bedrooms` int(10) NOT NULL,
  `house_status` varchar(50) NOT NULL DEFAULT 'Vacant'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `houses`
--

INSERT INTO `houses` (`houseID`, `house_name`, `number_of_rooms`, `rent_amount`, `location`, `num_of_bedrooms`, `house_status`) VALUES
(11, 'A Blue House', 2, 10000, 'Nairobi Cbd', 2, 'Vacant'),
(12, 'The Palatial House', 0, 70000, 'Nairobi Cbd', 5, 'Occupied'),
(13, 'Telposta House', 12, 3000, 'Mvita', 2, 'Vacant');

-- --------------------------------------------------------

--
-- Table structure for table `house_pics`
--

CREATE TABLE `house_pics` (
  `pic_id` int(11) NOT NULL,
  `pic_name` text NOT NULL,
  `house_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoiceNumber` varchar(50) NOT NULL,
  `tenantID` int(11) NOT NULL,
  `dateOfInvoice` text NOT NULL,
  `dateDue` text NOT NULL,
  `amountDue` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'unpaid',
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoiceNumber`, `tenantID`, `dateOfInvoice`, `dateDue`, `amountDue`, `status`, `comment`) VALUES
('INV20231019132349', 18, '2023-10-19', '2023-10-26', -4500, 'paid', 'Please pay rent for this month'),
('INV20231019133401', 19, '2023-10-19', '2023-10-25', 7000, 'unpaid', 'This is the rent invoice for this month'),
('INV20231019134119', 20, '2023-10-19', '2023-10-31', 0, 'paid', 'This is the rent invoice for this month');

-- --------------------------------------------------------

--
-- Stand-in structure for view `invoicesView`
-- (See below for the actual view)
--
CREATE TABLE `invoicesView` (
`invoiceNumber` varchar(50)
,`tenant_name` text
,`tenantID` int(11)
,`phone_number` varchar(13)
,`amountDue` int(11)
,`dateOfInvoice` text
,`dateDue` text
,`status` varchar(50)
,`comment` text
);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `location_name` text NOT NULL,
  `geo_id` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location_name`, `geo_id`) VALUES
(1, 'Shika Adabu', ''),
(2, 'Mtongwe', ''),
(3, 'Mvita', ''),
(4, 'Nyali', ''),
(5, 'Nairobi Cbd', 'undefined'),
(6, 'Tanga', 'undefined');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `paymentID` int(11) NOT NULL,
  `tenantID` int(11) NOT NULL,
  `invoiceNumber` varchar(50) NOT NULL,
  `expectedAmount` int(11) NOT NULL,
  `amountPaid` int(11) NOT NULL,
  `balance` int(11) NOT NULL,
  `mpesaCode` varchar(30) NOT NULL DEFAULT 'None',
  `dateofPayment` text NOT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`paymentID`, `tenantID`, `invoiceNumber`, `expectedAmount`, `amountPaid`, `balance`, `mpesaCode`, `dateofPayment`, `comment`) VALUES
(13, 18, 'INV20231019132349', 10000, 7000, 3000, 'gh3423h', '2023-10-19', 'partial payment for the invoice'),
(14, 18, 'INV20231019132349', 3000, 7500, -4500, 'ty28393io', '2023-10-19', 'another payment'),
(15, 19, 'INV20231019133401', 10000, 3000, 7000, 'sdsd434', '2023-10-19', 'partial payment'),
(16, 20, 'INV20231019134119', 70000, 70000, 0, 'er23456', '2023-10-19', 'rent settlement');

-- --------------------------------------------------------

--
-- Stand-in structure for view `paymentsView`
-- (See below for the actual view)
--
CREATE TABLE `paymentsView` (
`paymentID` int(11)
,`tenantID` int(11)
,`tenant_name` text
,`house_name` text
,`invoiceNumber` varchar(50)
,`expectedAmount` int(11)
,`amountPaid` int(11)
,`balance` int(11)
,`mpesaCode` varchar(30)
,`dateofPayment` text
,`comment` text
);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `author` varchar(200) NOT NULL,
  `title` varchar(400) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `author`, `title`, `content`, `date`) VALUES
(5, 'Ethredah', 'MENTAL HEALTH IS REAL', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis,csem.Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. enean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar,\r\n\r\n', '2018-07-27 15:28:31'),
(6, 'Derick', 'Ushauri Lending a hand', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. enean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar,\r\n\r\n', '2018-07-27 15:50:04');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `date`) VALUES
(3, 'ethredah@gmail.com', '2018-07-27 18:21:30'),
(4, 'james@hack3.io', '2018-07-27 18:21:30'),
(6, 'admin@pikash.sales', '2018-07-28 01:49:21');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `tenantID` int(11) NOT NULL,
  `houseNumber` int(10) NOT NULL,
  `tenant_name` text NOT NULL,
  `email` text NOT NULL,
  `ID_number` int(10) NOT NULL,
  `profession` text NOT NULL,
  `phone_number` varchar(13) NOT NULL,
  `agreement_file` text DEFAULT NULL,
  `dateAdmitted` text DEFAULT NULL,
  `account` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`tenantID`, `houseNumber`, `tenant_name`, `email`, `ID_number`, `profession`, `phone_number`, `agreement_file`, `dateAdmitted`, `account`) VALUES
(18, 11, 'Obed Paul', 'paulnyaxx@gmail.com', 32160902, 'Programmer', '+254706748162', NULL, '2023-10-19', 1500),
(19, 11, 'Ann Tenant', 'ann@localhost.com', 32160902, 'Teacher', '+254789489393', NULL, '2023-10-19', -7000),
(20, 12, 'Someone Watching', 'someone@localhost.com', 12345623, 'Being Rich.', '+25478934756', NULL, '2023-10-19', -70000);

-- --------------------------------------------------------

--
-- Stand-in structure for view `tenantsView`
-- (See below for the actual view)
--
CREATE TABLE `tenantsView` (
`tenantID` int(11)
,`houseNumber` int(10)
,`tenant_name` text
,`email` text
,`ID_number` int(10)
,`profession` text
,`phone_number` varchar(13)
,`dateAdmitted` text
,`agreement_file` text
,`house_name` text
,`number_of_rooms` int(10)
,`house_status` varchar(50)
,`rent_amount` double
,`houseID` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `actor` text DEFAULT NULL,
  `time` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `seen` varchar(10) NOT NULL DEFAULT 'NO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `actor`, `time`, `description`, `seen`) VALUES
(21, 'Admin (obed)', '2023-10-19 : 13:18:23', 'obed added a new house (A Blue House) with 4 rentable units, and 2 bedrooms per unit located in Nairobi Cbd', 'YES'),
(22, 'Admin (obed)', '2023-10-19 : 13:20:16', 'obed admitted a new tenant (Obed Paul) at 2023-10-19 : 13:20:16', 'YES'),
(23, 'Admin (obed)', '2023-10-19 : 13:23:49', 'obed added a new rental invoice (INV20231019132349) for tenant (Obed Paul) at 2023-10-19 : 13:23:49.', 'YES'),
(24, 'Admin (obed)', '2023-10-19 : 13:25:50', 'obed added payment of 7000 for Obed Paul, under invoice ID: INV20231019132349', 'YES'),
(25, 'Admin (obed)', '2023-10-19 : 13:27:25', 'obed added payment of 7500 for Obed Paul, under invoice ID: INV20231019132349', 'YES'),
(26, 'Admin (obed)', '2023-10-19 : 13:31:52', 'obed admitted a new tenant (Ann Tenant) at 2023-10-19 : 13:31:52', 'YES'),
(27, 'Admin (obed)', '2023-10-19 : 13:34:01', 'obed added a new rental invoice (INV20231019133401) for tenant (Ann Tenant) at 2023-10-19 : 13:34:01.', 'YES'),
(28, 'Admin (obed)', '2023-10-19 : 13:35:21', 'obed added payment of 3000 for Ann Tenant, under invoice ID: INV20231019133401', 'YES'),
(29, 'Admin (obed)', '2023-10-19 : 13:37:33', 'obed added a new house (The Palatial House) with 1 rentable units, and 5 bedrooms per unit located in Nairobi Cbd', 'YES'),
(30, 'Admin (obed)', '2023-10-19 : 13:39:10', 'obed admitted a new tenant (Someone Watching) at 2023-10-19 : 13:39:10', 'YES'),
(31, 'Admin (obed)', '2023-10-19 : 13:41:19', 'obed added a new rental invoice (INV20231019134119) for tenant (Someone Watching) at 2023-10-19 : 13:41:19.', 'YES'),
(32, 'Admin (obed)', '2023-10-19 : 13:42:34', 'obed added payment of 70000 for Someone Watching, under invoice ID: INV20231019134119', 'YES'),
(33, 'Admin (obed)', '2024-01-25 : 10:17:45', 'obed added a new house (Telposta House) with 12 rentable units, and 2 bedrooms per unit located in Mvita', 'NO');

-- --------------------------------------------------------

--
-- Structure for view `invoicesView`
--
DROP TABLE IF EXISTS `invoicesView`;

CREATE VIEW `invoicesView`  AS SELECT `invoices`.`invoiceNumber` AS `invoiceNumber`, `tenants`.`tenant_name` AS `tenant_name`, `invoices`.`tenantID` AS `tenantID`, `tenants`.`phone_number` AS `phone_number`, `invoices`.`amountDue` AS `amountDue`, `invoices`.`dateOfInvoice` AS `dateOfInvoice`, `invoices`.`dateDue` AS `dateDue`, `invoices`.`status` AS `status`, `invoices`.`comment` AS `comment` FROM (`invoices` left join `tenants` on(`invoices`.`tenantID` = `tenants`.`tenantID`)) ;

-- --------------------------------------------------------

--
-- Structure for view `paymentsView`
--
DROP TABLE IF EXISTS `paymentsView`;

CREATE   VIEW `paymentsView`  AS SELECT `payments`.`paymentID` AS `paymentID`, `payments`.`tenantID` AS `tenantID`, `tenantsView`.`tenant_name` AS `tenant_name`, `tenantsView`.`house_name` AS `house_name`, `payments`.`invoiceNumber` AS `invoiceNumber`, `payments`.`expectedAmount` AS `expectedAmount`, `payments`.`amountPaid` AS `amountPaid`, `payments`.`balance` AS `balance`, `payments`.`mpesaCode` AS `mpesaCode`, `payments`.`dateofPayment` AS `dateofPayment`, `payments`.`comment` AS `comment` FROM (`payments` left join `tenantsView` on(`payments`.`tenantID` = `tenantsView`.`tenantID`)) ;

-- --------------------------------------------------------

--
-- Structure for view `tenantsView`
--
DROP TABLE IF EXISTS `tenantsView`;

CREATE   VIEW `tenantsView`  AS SELECT `tenants`.`tenantID` AS `tenantID`, `tenants`.`houseNumber` AS `houseNumber`, `tenants`.`tenant_name` AS `tenant_name`, `tenants`.`email` AS `email`, `tenants`.`ID_number` AS `ID_number`, `tenants`.`profession` AS `profession`, `tenants`.`phone_number` AS `phone_number`, `tenants`.`dateAdmitted` AS `dateAdmitted`, `tenants`.`agreement_file` AS `agreement_file`, `houses`.`house_name` AS `house_name`, `houses`.`number_of_rooms` AS `number_of_rooms`, `houses`.`house_status` AS `house_status`, `houses`.`rent_amount` AS `rent_amount`, `houses`.`houseID` AS `houseID` FROM (`tenants` left join `houses` on(`tenants`.`houseNumber` = `houses`.`houseID`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogid` (`blogid`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `houses`
--
ALTER TABLE `houses`
  ADD PRIMARY KEY (`houseID`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoiceNumber`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`paymentID`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`tenantID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `houseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `paymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `tenantID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
