DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190307152430 $$

CREATE PROCEDURE upgrade_database_20190307152430()

BEGIN

  IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
  WHERE
      TABLE_SCHEMA='subastas'
      AND table_name='boats'
      AND column_name='reference_port') THEN
      ALTER TABLE `boats`
      CHANGE COLUMN `reference_port` `preference_port`  int(10) NOT NULL AFTER `nickname`;
  END IF;

END $$

CALL upgrade_database_20190307152430() $$

DROP PROCEDURE upgrade_database_20190307152430 $$

DELIMITER ;
