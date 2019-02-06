DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190205115833 $$

CREATE PROCEDURE upgrade_database_20190205115833()

BEGIN

  IF NOT EXISTS ( SELECT * FROM information_schema.`TABLE_CONSTRAINTS`
  WHERE
      TABLE_SCHEMA='subastas'
      AND table_name='boats'
      AND CONSTRAINT_NAME='index_UserAlias'
      AND CONSTRAINT_TYPE='UNIQUE') THEN
        CREATE UNIQUE INDEX index_UserAlias ON boats (user_id,nickname);
  END IF;

END $$

CALL upgrade_database_20190205115833() $$

DROP PROCEDURE upgrade_database_20190205115833 $$

DELIMITER ;
