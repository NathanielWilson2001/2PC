DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `getPrice477` (IN `partID` INT, OUT `price` DOUBLE)  BEGIN
SET price = (SELECT currentPrice477 from parts477 WHERE partID = partNo477);
END$$

DELIMITER ;