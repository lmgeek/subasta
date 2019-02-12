DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20181005155800 $$

CREATE PROCEDURE upgrade_database_20181005155800()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='users'
  AND column_name='hash'
  AND data_type='varchar'
  AND IS_NULLABLE='NO'
) THEN
   ALTER TABLE users ADD COLUMN hash VARCHAR(255) NOT NULL DEFAULT '' AFTER `email`;
END IF;

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='users'
  AND column_name='active_mail'
  AND data_type='tinyint'
  AND IS_NULLABLE='NO'
) THEN
   ALTER TABLE users ADD COLUMN active_mail BOOLEAN NOT NULL DEFAULT 1 AFTER `email`;
END IF;

END $$

CALL upgrade_database_20181005155800() $$

DROP PROCEDURE upgrade_database_20181005155800 $$

DELIMITER ;
