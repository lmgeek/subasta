DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20181011100123 $$

CREATE PROCEDURE upgrade_database_20181011100123()

BEGIN

  IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='products'
                    AND column_name='weigth'
    ) THEN
    ALTER TABLE `products` ADD COLUMN `weigth` float(10,2) NULL AFTER `unit`;

  END IF;


END $$

CALL upgrade_database_20181011100123() $$

DROP PROCEDURE upgrade_database_20181011100123 $$

DELIMITER ;
