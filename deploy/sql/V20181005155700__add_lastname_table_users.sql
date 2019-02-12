DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20181005155700 $$

CREATE PROCEDURE upgrade_database_20181005155700()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='users'
  AND column_name='lastname'
) THEN
   ALTER TABLE users ADD COLUMN lastname VARCHAR(45) NOT NULL DEFAULT '' AFTER `name`;
END IF;


END $$

CALL upgrade_database_20181005155700() $$

DROP PROCEDURE upgrade_database_20181005155700 $$

DELIMITER ;
