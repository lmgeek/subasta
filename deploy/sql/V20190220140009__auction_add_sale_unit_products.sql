DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190220140009 $$

CREATE PROCEDURE upgrade_database_20190220140009()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE TABLE_SCHEMA='subastas' AND table_name='products' AND column_name='presentation_unit')
THEN ALTER TABLE `products` ADD COLUMN `sale_unit`  varchar(255) NOT NULL AFTER `fishing_code`;
END IF;

END $$

CALL upgrade_database_20190220140009() $$

DROP PROCEDURE upgrade_database_20190220140009 $$

DELIMITER ;