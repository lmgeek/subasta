DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20191001104823 $$

CREATE PROCEDURE upgrade_database_20191001104823()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='users'
  AND column_name='nickname'
) THEN
  Alter TABLE users ADD nickname VARCHAR(255) not NULL DEFAULT '';
END IF;

END $$

CALL upgrade_database_20191001104823() $$

DROP PROCEDURE upgrade_database_20191001104823 $$

DELIMITER ;
