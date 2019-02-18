DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190218090000 $$

CREATE PROCEDURE upgrade_database_20190218090000()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE TABLE_SCHEMA='subastas' AND table_name='products' AND column_name='fishing_code')
THEN ALTER TABLE products ADD COLUMN fishing_code varchar (10) NOT NULL;
END IF;

END $$

CALL upgrade_database_20190218090000() $$

DROP PROCEDURE upgrade_database_20190218090000 $$

DELIMITER ;