DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190218100120 $$

CREATE PROCEDURE upgrade_database_20190218100120()

BEGIN

IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='arrives'
  AND column_name='date'
) THEN
    ALTER TABLE `arrives`
MODIFY COLUMN `date`  datetime NULL AFTER `boat_id`;
END IF;

END $$

CALL upgrade_database_20190218100120() $$

DROP PROCEDURE upgrade_database_20190218100120 $$

DELIMITER ;