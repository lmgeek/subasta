DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190129153323 $$

CREATE PROCEDURE upgrade_database_20190129153323()

BEGIN

  IF NOT EXISTS ( SELECT * FROM information_schema.`TABLE_CONSTRAINTS`
  WHERE
      TABLE_SCHEMA='subastas'
      AND table_name='users'
      AND CONSTRAINT_NAME='user_nickname'
      AND CONSTRAINT_TYPE='UNIQUE') THEN
      ALTER TABLE users ADD CONSTRAINT user_nickname UNIQUE (nickname);
  END IF;

END $$

CALL upgrade_database_20190129153323() $$

DROP PROCEDURE upgrade_database_20190129153323 $$

DELIMITER ;
