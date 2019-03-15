DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190315165020 $$

CREATE PROCEDURE upgrade_database_20190315165020()

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
IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='batches'
  AND column_name='amount'
) THEN
    ALTER TABLE `batches`
MODIFY COLUMN `amount`  int(11) NULL AFTER `quality`;
END IF;

END $$

CALL upgrade_database_20190315165020() $$

DROP PROCEDURE upgrade_database_20190315165020 $$

DELIMITER ;