-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 04, 2022 at 12:17 AM
-- Server version: 5.7.24
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `compy`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `getPrice477` (IN `partID` INT, OUT `price` DOUBLE)  BEGIN
SET price = (SELECT currentPrice477 from parts477 WHERE partID = partNo477);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `client477`
--

CREATE TABLE `client477` (
  `clientID477` int(11) NOT NULL,
  `clientName477` varchar(45) DEFAULT NULL,
  `clientCity477` varchar(45) DEFAULT NULL,
  `clientCompPassword477` varchar(45) DEFAULT NULL,
  `dollarsOnOrder477` double DEFAULT NULL,
  `moneyOwed477` double DEFAULT NULL,
  `clientStatus477` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `client477`
--

INSERT INTO `client477` (`clientID477`, `clientName477`, `clientCity477`, `clientCompPassword477`, `dollarsOnOrder477`, `moneyOwed477`, `clientStatus477`) VALUES
(1, 'Jim', 'Halifax', 'password', 0, 195.1, 'approved'),
(2, 'Claire', 'Toronto', 'password', 0, 1030.2, 'approved'),
(3, 'Bob', 'New York', 'password', 0, 140, 'approved'),
(4, 'CompanyZ', 'New York', 'password', 0, 1299, 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `lines477`
--

CREATE TABLE `lines477` (
  `lineNo477` varchar(45) NOT NULL,
  `purchaseOrder477_poNo477` int(11) NOT NULL,
  `quantityOnOrder477` int(11) DEFAULT NULL,
  `purchasePrice477` double DEFAULT NULL,
  `parts477_partNo477` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lines477`
--

INSERT INTO `lines477` (`lineNo477`, `purchaseOrder477_poNo477`, `quantityOnOrder477`, `purchasePrice477`, `parts477_partNo477`) VALUES
('1', 1, 2, 10, 1),
('1', 5, 10, 20, 1),
('1', 15, 2, 20, 1),
('1', 16, 2, 20, 1),
('1', 20, 2, 20, 1),
('2', 1, 6, 20, 4),
('2', 5, 2, 23, 4),
('2', 16, 1, 23, 4),
('2', 20, 1, 10, 6),
('3', 2, 3, 35, 1),
('3', 5, 1, 10, 6),
('4', 3, 4, 10, 6);

--
-- Triggers `lines477`
--
DELIMITER $$
CREATE TRIGGER `updateMoneyOwed477` AFTER INSERT ON `lines477` FOR EACH ROW BEGIN
	DECLARE partPrice double; 
	DECLARE clientID int;
	SET clientID = (SELECT clientID477 FROM purchaseorder477   WHERE NEW.purchaseOrder477_poNo477 = poNo477);
    CALL getPrice477( NEW.parts477_partNo477, partPrice); 
    UPDATE client477
    SET moneyOwed477 = moneyOwed477 + NEW.quantityOnOrder477 * partPrice
    WHERE clientID477 = clientID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `log477`
--

CREATE TABLE `log477` (
  `recordNum477` int(11) NOT NULL,
  `record477` varchar(200) NOT NULL,
  `date477` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `log477`
--

INSERT INTO `log477` (`recordNum477`, `record477`, `date477`) VALUES
(1, 'Begin Transaction PONum:15', '2022-12-03 23:40:52'),
(5, 'Ready', '2022-12-03 23:40:52'),
(6, 'Begin Transaction PONum:15', '2022-12-03 23:41:08'),
(10, 'Ready', '2022-12-03 23:41:08'),
(11, 'Begin Transaction PONum:19', '2022-12-03 23:42:08'),
(15, 'Ready', '2022-12-03 23:42:09'),
(16, 'Begin Transaction PONum:19', '2022-12-03 23:44:44'),
(20, 'Ready', '2022-12-03 23:44:44'),
(21, 'inserted 19, , incomplete, 4 into purchaseOrder477', '2022-12-03 23:44:44'),
(22, 'inserted 1, 19, 2, 20, 1 into lines477', '2022-12-03 23:44:44'),
(23, 'globally com for PONum:19', '2022-12-03 23:44:44'),
(24, 'CHECKPOINT', '2022-12-03 23:44:44'),
(25, 'Begin Transaction PONum:15', '2022-12-03 23:46:28'),
(29, 'Ready', '2022-12-03 23:46:28'),
(30, 'inserted 15, , incomplete, 4 into purchaseOrder477', '2022-12-03 23:46:28'),
(31, 'inserted 1, 15, 2, 20, 1 into lines477', '2022-12-03 23:46:28'),
(32, 'globally aborted for PONum:15', '2022-12-03 23:46:28'),
(33, 'voted abor for PONum:', '2022-12-03 23:47:24'),
(34, 'voted abor for PONum:', '2022-12-03 23:47:24'),
(35, 'globally aborted for PONum:15', '2022-12-03 23:47:24'),
(36, 'Begin Transaction PONum:16', '2022-12-03 23:51:08'),
(41, 'Ready', '2022-12-03 23:51:08'),
(42, 'inserted 16, , incomplete, 4 into purchaseOrder477', '2022-12-03 23:51:08'),
(43, 'inserted 1, 16, 2, 20, 1 into lines477', '2022-12-03 23:51:08'),
(44, 'inserted 2, 16, 1, 23, 4 into lines477', '2022-12-03 23:51:08'),
(45, 'globally com for PONum:16', '2022-12-03 23:51:08'),
(46, 'CHECKPOINT', '2022-12-03 23:51:08'),
(47, 'Begin Transaction PONum:20', '2022-12-03 23:52:21'),
(52, 'Ready', '2022-12-03 23:52:21'),
(53, 'inserted 20, , incomplete, 4 into purchaseOrder477', '2022-12-03 23:52:21'),
(54, 'inserted 1, 20, 2, 20, 1 into lines477', '2022-12-03 23:52:21'),
(55, 'inserted 2, 20, 1, 10, 6 into lines477', '2022-12-03 23:52:21'),
(56, 'globally com for PONum:20', '2022-12-03 23:52:21'),
(57, 'CHECKPOINT', '2022-12-03 23:52:21');

-- --------------------------------------------------------

--
-- Table structure for table `parts477`
--

CREATE TABLE `parts477` (
  `partNo477` int(11) NOT NULL,
  `partName477` varchar(45) DEFAULT NULL,
  `partDescription477` varchar(45) DEFAULT NULL,
  `currentPrice477` double DEFAULT NULL,
  `quantityOnHand477` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `parts477`
--

INSERT INTO `parts477` (`partNo477`, `partName477`, `partDescription477`, `currentPrice477`, `quantityOnHand477`) VALUES
(1, 'screwdriver', 'phillips, grey', 20, 31),
(4, 'screws', 'philips, 1 inch, pack of 30', 23, 120),
(6, 'nails', 'steel, 2 inches, pack fo 10', 10, 401);

-- --------------------------------------------------------

--
-- Table structure for table `purchaseorder477`
--

CREATE TABLE `purchaseorder477` (
  `poNo477` int(11) NOT NULL,
  `datePO477` datetime DEFAULT NULL,
  `status477` varchar(45) DEFAULT NULL,
  `clientID477` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `purchaseorder477`
--

INSERT INTO `purchaseorder477` (`poNo477`, `datePO477`, `status477`, `clientID477`) VALUES
(1, '2021-03-23 00:00:00', 'complete', 1),
(2, '2018-07-14 00:00:00', 'complete', 2),
(3, '2022-09-29 00:00:00', 'incomplete', 3),
(5, '2022-11-12 00:00:00', 'incomplete', 4),
(15, '2022-12-03 00:00:00', 'incomplete', 4),
(16, '2022-12-03 00:00:00', 'incomplete', 4),
(20, '2022-12-03 00:00:00', 'incomplete', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client477`
--
ALTER TABLE `client477`
  ADD PRIMARY KEY (`clientID477`);

--
-- Indexes for table `lines477`
--
ALTER TABLE `lines477`
  ADD PRIMARY KEY (`lineNo477`,`purchaseOrder477_poNo477`),
  ADD KEY `fk_lines477_purchaseOrder4771` (`purchaseOrder477_poNo477`),
  ADD KEY `fk_lines477_parts4471` (`parts477_partNo477`);

--
-- Indexes for table `log477`
--
ALTER TABLE `log477`
  ADD PRIMARY KEY (`recordNum477`);

--
-- Indexes for table `parts477`
--
ALTER TABLE `parts477`
  ADD PRIMARY KEY (`partNo477`);

--
-- Indexes for table `purchaseorder477`
--
ALTER TABLE `purchaseorder477`
  ADD PRIMARY KEY (`poNo477`),
  ADD KEY `fk_purchaseOrder477_client477` (`clientID477`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log477`
--
ALTER TABLE `log477`
  MODIFY `recordNum477` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lines477`
--
ALTER TABLE `lines477`
  ADD CONSTRAINT `fk_lines477_parts4471` FOREIGN KEY (`parts477_partNo477`) REFERENCES `parts477` (`partNo477`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_lines477_purchaseOrder4771` FOREIGN KEY (`purchaseOrder477_poNo477`) REFERENCES `purchaseorder477` (`poNo477`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `purchaseorder477`
--
ALTER TABLE `purchaseorder477`
  ADD CONSTRAINT `fk_purchaseOrder477_client477` FOREIGN KEY (`clientID477`) REFERENCES `client477` (`clientID477`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
