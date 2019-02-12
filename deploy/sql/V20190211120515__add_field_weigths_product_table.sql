DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190211120515 $$

CREATE PROCEDURE upgrade_database_20190211120515()

BEGIN

  IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='products'
                    AND column_name='weigth'
                    AND column_name='weigth_medium'
                    AND column_name='weigth_big'
    ) THEN
    ALTER TABLE `products` CHANGE COLUMN `weigth` `weigth_small` float(10,2) NOT NULL AFTER `unit`,
    ADD COLUMN `weigth_medium` float(10,2) NOT NULL AFTER `weigth_small`,
    ADD COLUMN `weigth_big` float(10,2) NULL AFTER `weigth_medium`;

  END IF;


END $$

CALL upgrade_database_20190211120515() $$

DROP PROCEDURE upgrade_database_20190211120515 $$

DELIMITER ;
