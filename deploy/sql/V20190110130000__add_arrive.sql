DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190110130000 $$

CREATE PROCEDURE upgrade_database_20190110130000()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='arrives'
  AND column_name='port_id') THEN
  ALTER TABLE arrives ADD COLUMN port_id int(10)  not NULL DEFAULT '';
END IF;

END $$

CALL upgrade_database_20190110130000() $$

DROP PROCEDURE upgrade_database_20190110130000 $$

DELIMITER ;