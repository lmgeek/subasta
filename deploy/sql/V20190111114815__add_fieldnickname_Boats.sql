DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20191101114815 $$

CREATE PROCEDURE upgrade_database_20191101114815()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='boats'
  AND column_name='nickname'
) THEN
    Alter TABLE boats ADD nickname VARCHAR(255) not NULL DEFAULT '';
END IF;

END $$

CALL upgrade_database_20191101114815() $$

DROP PROCEDURE upgrade_database_20191101114815 $$

DELIMITER ;