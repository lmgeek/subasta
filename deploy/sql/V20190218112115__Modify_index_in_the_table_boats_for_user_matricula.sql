DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190218112115 $$

CREATE PROCEDURE upgrade_database_20190218112115()

BEGIN

  IF NOT EXISTS ( SELECT * FROM information_schema.`TABLE_CONSTRAINTS`
  WHERE
      TABLE_SCHEMA='subastas'
      AND table_name='boats'
      AND CONSTRAINT_NAME='index_UserMatriucla'
      AND CONSTRAINT_TYPE='UNIQUE') THEN
        ALTER TABLE `boats`
        MODIFY COLUMN `nickname`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' AFTER `updated_at`,
        DROP INDEX `index_UserAlias` ,
        ADD UNIQUE INDEX `index_UserMatriucla` (`user_id`, `matricula`) USING BTREE ;
  END IF;

END $$

CALL upgrade_database_20190218112115() $$

DROP PROCEDURE upgrade_database_20190218112115 $$

DELIMITER ;