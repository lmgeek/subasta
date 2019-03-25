DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190322090000 $$

CREATE PROCEDURE upgrade_database_20190322090000()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='product_detail'
  AND column_name='status'
) THEN
   ALTER TABLE `product_detail` ADD COLUMN `status`  numeric(1,0) NOT NULL DEFAULT 1 AFTER `updated_at`;
END IF;


END $$

CALL upgrade_database_20190322090000() $$

DROP PROCEDURE upgrade_database_20190322090000 $$

DELIMITER ;
