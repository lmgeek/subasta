DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190215140800 $$

CREATE PROCEDURE upgrade_database_20190215140800()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='auctions'
  AND column_name='correlative'
) THEN
   ALTER TABLE `auctions` ADD COLUMN `correlative`  int(10) NOT NULL AFTER `batch_id`;
END IF;


END $$

CALL upgrade_database_20190215140800() $$

DROP PROCEDURE upgrade_database_20190215140800 $$

DELIMITER ;
