DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190218103000 $$

CREATE PROCEDURE upgrade_database_20190218103000()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='batches'
  AND column_name='amount'
) THEN
    ALTER TABLE `batches`
MODIFY COLUMN `amount`  int(11) NULL AFTER `quality`;
END IF;

END $$

CALL upgrade_database_20190218103000() $$

DROP PROCEDURE upgrade_database_20190218103000 $$

DELIMITER ;