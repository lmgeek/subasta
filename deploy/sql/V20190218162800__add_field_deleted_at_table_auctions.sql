DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190218162800 $$

CREATE PROCEDURE upgrade_database_20190218162800()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='auctions'
  AND column_name='deleted_at'
) THEN
   ALTER TABLE `auctions` ADD COLUMN `deleted_at`  timestamp NULL AFTER `updated_at`;
END IF;


END $$

CALL upgrade_database_20190218162800() $$

DROP PROCEDURE upgrade_database_20190218162800 $$

DELIMITER ;
