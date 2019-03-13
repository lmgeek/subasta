DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190313183000 $$

CREATE PROCEDURE upgrade_database_20190313183000()

BEGIN

  IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='batches'
                    AND column_name='caliber'
    ) THEN
    ALTER TABLE batches DROP COLUMN caliber;

  END IF;
  IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='batches'
                    AND column_name='product_id'
    ) THEN
    ALTER TABLE batches DROP COLUMN product_id;

  END IF;
  IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='batches'
                    AND column_name='product_detail_id'
    ) THEN
    ALTER TABLE `batches` ADD COLUMN `product_detail_id`  int(10) UNSIGNED NOT NULL DEFAULT 1 AFTER `updated_at`;

  END IF;

END $$

CALL upgrade_database_20190313183000() $$

DROP PROCEDURE upgrade_database_20190313183000 $$

DELIMITER ;