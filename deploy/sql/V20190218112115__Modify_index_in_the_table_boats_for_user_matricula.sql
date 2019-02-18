DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190218101620 $$

CREATE PROCEDURE upgrade_database_20190218101620()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='boats'
  AND column_name='reference_port'
) THEN
  alter table boats add reference_port varchar(255) not null default '';
END IF;

END $$

CALL upgrade_database_20190218101620() $$

DROP PROCEDURE upgrade_database_20190218101620 $$

DELIMITER ;