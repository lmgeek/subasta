DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20181012135023 $$

CREATE PROCEDURE upgrade_database_20181012135023()

BEGIN

  IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='products'
                    AND column_name='weigth'
    ) THEN
    ALTER TABLE `products` CHANGE COLUMN `weigth` `weigth_small` float(10,2) NOT NULL AFTER `unit`,
    ADD COLUMN `weigth_medium` float(10,2) NOT NULL AFTER `weigth_small`,
    ADD COLUMN `weigth_big` float(10,2) NULL AFTER `weigth_medium`;

  END IF;

  IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='products'
                    AND column_name='weigth_medium'
    ) THEN
  ALTER TABLE `products` ADD COLUMN `weigth_medium` float(10,2) NOT NULL AFTER `weigth_small`;

  END IF ;

  IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='products'
                    AND column_name='weigth_big'
    ) THEN
  ALTER TABLE `products` ADD COLUMN `weigth_big` float(10,2) NULL AFTER `weigth_medium`;

  END IF ;

END $$

CALL upgrade_database_20181012135023() $$

DROP PROCEDURE upgrade_database_20181012135023 $$

DELIMITER ;
