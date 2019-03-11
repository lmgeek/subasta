DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190311150700 $$

CREATE PROCEDURE upgrade_database_20190311150700()

BEGIN

IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='auctions'
  AND column_name='correlative'
) THEN
   ALTER TABLE `auctions` MODIFY COLUMN `correlative`  int(10) NULL AFTER `batch_id`;
END IF;


END $$

CALL upgrade_database_20190311150700() $$

DROP PROCEDURE upgrade_database_20190311150700 $$

DELIMITER ;
