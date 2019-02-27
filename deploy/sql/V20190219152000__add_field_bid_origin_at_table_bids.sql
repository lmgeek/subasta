DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190219152000 $$

CREATE PROCEDURE upgrade_database_20190219152000()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='bids'
  AND column_name='bid_origin'
) THEN
   ALTER TABLE `bids` ADD COLUMN `bid_origin` enum('auction','offerDirect','privateSold') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'auction' AFTER `bid_date`;
END IF;


END $$

CALL upgrade_database_20190219152000() $$

DROP PROCEDURE upgrade_database_20190219152000 $$

DELIMITER ;
