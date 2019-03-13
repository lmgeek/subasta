DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20181011125023 $$

CREATE PROCEDURE upgrade_database_20181011125023()

BEGIN

  IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='product_detail'
    ) THEN
                CREATE TABLE `product_detail` (
            `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
            `product_id`  int(10) UNSIGNED NOT NULL ,
            `caliber`  varchar(100) NOT NULL ,
            `presentation_unit`  varchar(20) NOT NULL ,
            `weight`  float(10,2) NOT NULL ,
            `sale_unit`  varchar(20) NOT NULL ,
            `deleted_at`  timestamp NULL DEFAULT NULL ,
            `created_at`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
            `updated_at`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
            PRIMARY KEY (`id`)
            )
            ENGINE=InnoDB
                    DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
                    AUTO_INCREMENT=1
                    ROW_FORMAT=COMPACT;

            INSERT INTO product_detail (`caliber`, `product_id`, `presentation_unit`, `weight`,`sale_unit`)
            select l.caliber, l.product_id, p.unit, p.weigth_small, p.unit FROM batches as l inner join products as p where l.product_id = p.id and l.caliber = 'small';
            INSERT INTO product_detail (`caliber`, `product_id`, `presentation_unit`, `weight`,`sale_unit`)
            select l.caliber, l.product_id, p.unit, p.weigth_medium, p.unit FROM batches as l inner join products as p where l.product_id = p.id and l.caliber = 'medium';
            INSERT INTO product_detail (`caliber`, `product_id`, `presentation_unit`, `weight`,`sale_unit`)
            select l.caliber, l.product_id, p.unit, p.weigth_big, p.unit FROM batches as l inner join products as p where l.product_id = p.id and l.caliber = 'big';
  END IF;

 IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='batches'
    ) THEN
      RENAME TABLE batches TO batches_002;
  END IF;

  IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='batches'
    ) THEN
              CREATE TABLE `batches` (
          `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
          `arrive_id`  int(10) UNSIGNED NOT NULL ,
          `product_detail_id`  int(10) UNSIGNED NOT NULL ,
          `quality`  int(11) NOT NULL ,
          `amount`  int(11) NOT NULL ,
          `created_at`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
          `updated_at`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
          PRIMARY KEY (`id`)
          )
          ENGINE=InnoDB
                  DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
                  AUTO_INCREMENT=1
                  ROW_FORMAT=COMPACT;
      INSERT INTO batches (`id`, `arrive_id`, `product_detail_id`, `quality`,`amount`,`created_at`,`updated_at`)
      SELECT l.id, l.arrive_id, p.id , l.quality, l.amount, l.created_at, l.updated_at FROM product_detail AS p INNER JOIN batches_002 as l WHERE l.product_id = p.product_id AND l.caliber = 'small' AND p.caliber = 'small';

      INSERT INTO batches (`id`, `arrive_id`, `product_detail_id`, `quality`,`amount`,`created_at`,`updated_at`)
      SELECT l.id, l.arrive_id, p.id , l.quality, l.amount, l.created_at, l.updated_at FROM product_detail AS p INNER JOIN batches_002 as l WHERE l.product_id = p.product_id AND l.caliber = 'medium' AND p.caliber = 'medium';

      INSERT INTO batches (`id`, `arrive_id`, `product_detail_id`, `quality`,`amount`,`created_at`,`updated_at`)
      SELECT l.id, l.arrive_id, p.id , l.quality, l.amount, l.created_at, l.updated_at FROM product_detail AS p INNER JOIN batches_002 as l WHERE l.product_id = p.product_id AND l.caliber = 'big' AND p.caliber = 'big';

  END IF;
  IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='batches_002'
    ) THEN
      DROP TABLE batches_002;

  END IF;

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
                    AND table_name='products'
                    AND column_name='weigth_small'
    ) THEN
    ALTER TABLE products DROP COLUMN weigth_small;

  END IF;
  IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='products'
                    AND column_name='weigth_medium'
    ) THEN
    ALTER TABLE products DROP COLUMN weigth_medium;

  END IF;
  IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='products'
                    AND column_name='weigth_big'
    ) THEN
    ALTER TABLE products DROP COLUMN weigth_big;

  END IF;
  IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='products'
                    AND column_name='sale_unit_small'
    ) THEN
    ALTER TABLE products DROP COLUMN sale_unit_small;

  END IF;
  IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='products'
                    AND column_name='sale_unit_medium'
    ) THEN
    ALTER TABLE products DROP COLUMN sale_unit_medium;

  END IF;
  IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='products'
                    AND column_name='sale_unit_big'
    ) THEN
    ALTER TABLE products DROP COLUMN sale_unit_big;

  END IF;
  IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='products'
                    AND column_name='unit'
    ) THEN
    ALTER TABLE products DROP COLUMN unit;

  END IF;
  IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='products'
                    AND column_name='presentation_unit'
    ) THEN
    ALTER TABLE products DROP COLUMN presentation_unit;

  END IF;


END $$

CALL upgrade_database_20181011125023() $$

DROP PROCEDURE upgrade_database_20181011125023 $$

DELIMITER ;