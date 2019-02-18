DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190218100023 $$

CREATE PROCEDURE upgrade_database_20190218100023()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='arrives'
  AND column_name='date'
) THEN
    ALTER TABLE `arrives`
MODIFY COLUMN `date`  datetime NULL AFTER `boat_id`;
END IF;

END $$

CALL upgrade_database_20190218100023() $$

DROP PROCEDURE upgrade_database_20190218100023 $$

DELIMITER ;