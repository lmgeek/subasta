DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190205120000 $$

CREATE PROCEDURE upgrade_database_20190205120000()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='auctions'
  AND column_name='target_price'
  AND COLUMN_TYPE='float(8,0)'
  AND COLUMN_DEFAULT='0'
  AND DATA_TYPE='float'
) THEN
   ALTER TABLE `auctions` ADD COLUMN `target_price`  float(8,0) NOT NULL DEFAULT 0 AFTER `description`;
END IF;


END $$

CALL upgrade_database_20190205120000() $$

DROP PROCEDURE upgrade_database_20190205120000 $$

DELIMITER ;
