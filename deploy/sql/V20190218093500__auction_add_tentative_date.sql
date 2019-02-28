DELIMITER $$
DROP PROCEDURE IF EXISTS upgrade_database_20190218090000 $$

CREATE PROCEDURE upgrade_database_20190218090000()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE TABLE_SCHEMA='subastas' AND table_name='auctions' AND column_name='tentative_date')
THEN ALTER TABLE auctions ADD COLUMN tentative_date datetime;
END IF;

END $$

CALL upgrade_database_20190218090000() $$

DROP PROCEDURE upgrade_database_20190218090000 $$

DELIMITER ;