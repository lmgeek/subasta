DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190307152438 $$

CREATE PROCEDURE upgrade_database_20190307152438()

BEGIN

IF  EXISTS ( SELECT * FROM information_schema.`COLUMNS`
  WHERE TABLE_SCHEMA='subastas' AND table_name='boats'
      AND column_name='reference_port') THEN
      update boats set reference_port = 1 where reference_port = '';
  END IF;

IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
  WHERE
      TABLE_SCHEMA='subastas'
      AND table_name='boats'
      AND column_name='reference_port') THEN
      ALTER TABLE `boats`
      CHANGE COLUMN `reference_port` `preference_port` int(10) NOT NULL default 1 AFTER `nickname`;
  END IF;

END $$

CALL upgrade_database_20190307152438() $$

DROP PROCEDURE upgrade_database_20190307152430 $$

DELIMITER ;
