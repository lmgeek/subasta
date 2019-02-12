DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20181005155700 $$

CREATE PROCEDURE upgrade_database_20181005155700()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='comprador'
  AND column_name='bid_limit'
  AND data_type='float'
) THEN
   ALTER TABLE `comprador` CHANGE bid_limit intrate INTEGER;
   ALTER TABLE `comprador` ADD bid_limit float(10,2) NOT NULL DEFAULT 100;
   UPDATE `comprador` SET bid_limit=intrate;
   ALTER TABLE `comprador` DROP intrate;
END IF;


END $$

CALL upgrade_database_20181005155700() $$

DROP PROCEDURE upgrade_database_20181005155700 $$

DELIMITER ;
