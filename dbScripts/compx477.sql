-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 04, 2022 at 12:15 AM
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
-- Database: `compx`
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
(1, 'Jim', 'Halifax', 'password', 0, 595.1, 'approved'),
(2, 'Claire', 'Toronto', 'password', 0, 1030.2, 'approved'),
(3, 'Bob', 'New York', 'password', 0, 140, 'approved'),
(4, 'CompanyZ', 'New York', 'password', 0, 880, 'approved');

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
('1', 15, 9, 15, 2),
('1', 16, 1, 15, 2),
('1', 20, 3, 15, 2),
('1', 68, 1, 15, 2),
('2', 1, 6, 20, 2),
('2', 68, 1, 50, 3),
('3', 2, 3, 35, 1),
('4', 3, 4, 10, 3);

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
(1, 'Begin Transaction PONum:15', '2022-12-03 23:46:28'),
(3, 'globally aborted for PONum:15', '2022-12-03 23:46:28'),
(4, 'globally aborted for PONum:15', '2022-12-03 23:46:28'),
(5, 'Begin Transaction PONum:15', '2022-12-03 23:47:24'),
(10, 'inserted 15, , incomplete, 4 into purchaseOrder477', '2022-12-03 23:47:24'),
(11, 'inserted 1, 15, 9, 15, 2 into lines477', '2022-12-03 23:47:24'),
(12, 'globally aborted for PONum:15', '2022-12-03 23:47:24'),
(13, 'Begin Transaction PONum:16', '2022-12-03 23:51:07'),
(18, 'inserted 16, , incomplete, 4 into purchaseOrder477', '2022-12-03 23:51:08'),
(19, 'inserted 1, 16, 1, 15, 2 into lines477', '2022-12-03 23:51:08'),
(20, 'globally com for PONum:16', '2022-12-03 23:51:08'),
(21, 'CHECKPOINT', '2022-12-03 23:51:08'),
(22, 'Begin Transaction PONum:20', '2022-12-03 23:52:21'),
(27, 'inserted 20, , incomplete, 4 into purchaseOrder477', '2022-12-03 23:52:21'),
(28, 'inserted 1, 20, 3, 15, 2 into lines477', '2022-12-03 23:52:21'),
(29, 'globally com for PONum:20', '2022-12-03 23:52:21'),
(30, 'CHECKPOINT', '2022-12-03 23:52:21');

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
(1, 'screwdriver', 'phillips, grey', 100, 31),
(2, 'screws', 'philips, 1 inch, pack of 10', 15, 120),
(3, 'saw', 'steel, 12 inches', 50, 147);

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
(15, '2022-12-03 00:00:00', 'incomplete', 4),
(16, '2022-12-03 00:00:00', 'incomplete', 4),
(20, '2022-12-03 00:00:00', 'incomplete', 4),
(68, '2022-12-03 00:00:00', 'incomplete', 4);

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
  MODIFY `recordNum477` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lines477`
--
ALTER TABLE `lines477`
  ADD CONSTRAINT `fk_lines477_parts4771` FOREIGN KEY (`parts477_partNo477`) REFERENCES `parts477` (`partNo477`) ON DELETE NO ACTION ON UPDATE NO ACTION,
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
