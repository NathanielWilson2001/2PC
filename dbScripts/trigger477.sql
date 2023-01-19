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