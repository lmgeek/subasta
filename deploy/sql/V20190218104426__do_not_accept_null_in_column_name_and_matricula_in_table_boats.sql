DROP PROCEDURE IF EXISTS upgrade_database_20190218104426 $$

CREATE PROCEDURE upgrade_database_20190218104426()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='boats'
  AND column_name='name'
  AND column_name='matricula'
) THEN
  ALTER TABLE `boats`
	MODIFY COLUMN `name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `user_id`,
	MODIFY COLUMN `matricula`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `name`;
END IF;

END $$

CALL upgrade_database_20190218104426() $$

DROP PROCEDURE upgrade_database_20190218104426 $$

DELIMITER ;