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
-- Database: `compz`
--

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
(1, 'Jim', 'Halifax', 'password', 0, 3375.1, 'approved'),
(2, 'Claire', 'Toronto', 'password', 0, 2470.2, 'approved'),
(3, 'Bob', 'New York', 'password', 0, 500, 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `lines477`
--

CREATE TABLE `lines477` (
  `lineNo477` varchar(45) NOT NULL,
  `purchaseOrder477_poNo477` int(11) NOT NULL,
  `quantityOnOrder477` int(11) DEFAULT NULL,
  `purchasePrice477` double DEFAULT NULL,
  `parts477_partNo477` int(11) DEFAULT NULL,
  `company477` varchar(145) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lines477`
--

INSERT INTO `lines477` (`lineNo477`, `purchaseOrder477_poNo477`, `quantityOnOrder477`, `purchasePrice477`, `parts477_partNo477`, `company477`) VALUES
('1', 1, 2, 10, 1, 'X'),
('1', 5, 10, 20, 1, 'Y'),
('1', 16, 2, 20, 1, 'Y'),
('1', 20, 2, 20, 1, 'Y'),
('2', 1, 6, 20, 4, 'Y'),
('2', 5, 2, 20, 4, 'Y'),
('2', 16, 1, 100, 2, 'X'),
('2', 20, 3, 100, 2, 'X'),
('3', 2, 3, 35, 1, 'X'),
('3', 5, 1, 20, 6, 'Y'),
('3', 16, 1, 20, 4, 'Y'),
('3', 20, 1, 20, 6, 'Y'),
('4', 3, 4, 10, 6, 'Y');

--
-- Triggers `lines477`
--
DELIMITER $$
CREATE TRIGGER `updateMoneyOwed477` AFTER INSERT ON `lines477` FOR EACH ROW BEGIN
	DECLARE partPrice double; 
	DECLARE clientID int;
	SET clientID = (SELECT clientID477 FROM purchaseorder477   WHERE NEW.purchaseOrder477_poNo477 = poNo477);
    UPDATE client477
    SET moneyOwed477 = moneyOwed477 + NEW.quantityOnOrder477 * NEW.purchasePrice477
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
(1, 'BEGIN PONum:19', '2022-12-03 23:44:44'),
(2, 'inserted 19, 2022-12-03, incomplete, 2 into purchaseOrder477', '2022-12-03 23:44:44'),
(3, 'inserted 1, 19, 2,  20.00, 1 into lines477', '2022-12-03 23:44:44'),
(4, 'sent glob com for PONum:', '2022-12-03 23:44:44'),
(5, 'CHECKPOINT', '2022-12-03 23:44:44'),
(14, 'Aborted', '2022-12-03 23:46:28'),
(23, 'Aborted', '2022-12-03 23:47:24'),
(24, 'BEGIN PONum:16', '2022-12-03 23:51:07'),
(25, 'Send BEGIN to X and Y', '2022-12-03 23:51:07'),
(26, 'In Wait State', '2022-12-03 23:51:07'),
(27, 'inserted 16, 2022-12-03, incomplete, 2 into purchaseOrder477', '2022-12-03 23:51:07'),
(28, 'inserted 1, 16, 2,  20.00, 1 into lines477', '2022-12-03 23:51:07'),
(29, 'inserted 2, 16, 1,  100.00, 2 into lines477', '2022-12-03 23:51:07'),
(30, 'inserted 3, 16, 1,  20.00, 4 into lines477', '2022-12-03 23:51:07'),
(31, 'sent glob com for PONum:', '2022-12-03 23:51:08'),
(32, 'CHECKPOINT', '2022-12-03 23:51:08'),
(33, 'BEGIN PONum:20', '2022-12-03 23:52:21'),
(34, 'Send BEGIN to X and Y', '2022-12-03 23:52:21'),
(35, 'In Wait State', '2022-12-03 23:52:21'),
(36, 'inserted 20, 2022-12-03, incomplete, 3 into purchaseOrder477', '2022-12-03 23:52:21'),
(37, 'inserted 1, 20, 2,  20.00, 1 into lines477', '2022-12-03 23:52:21'),
(38, 'inserted 2, 20, 3,  100.00, 2 into lines477', '2022-12-03 23:52:21'),
(39, 'inserted 3, 20, 1,  20.00, 6 into lines477', '2022-12-03 23:52:21'),
(40, 'sent glob com for PONum:', '2022-12-03 23:52:21'),
(41, 'CHECKPOINT', '2022-12-03 23:52:21');

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
(5, '2022-11-12 00:00:00', 'incomplete', 1),
(16, '2022-12-03 00:00:00', 'incomplete', 2),
(20, '2022-12-03 00:00:00', 'incomplete', 3);

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
  MODIFY `recordNum477` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lines477`
--
ALTER TABLE `lines477`
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
