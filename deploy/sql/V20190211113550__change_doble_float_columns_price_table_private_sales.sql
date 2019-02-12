DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190211113550 $$

CREATE PROCEDURE upgrade_database_20190211113550()

BEGIN

  IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='private_sales'
                    AND column_name='price'
    ) THEN
    ALTER TABLE `private_sales` MODIFY COLUMN `price`  float(8,2) NOT NULL AFTER `amount`;

  END IF;


END $$

CALL upgrade_database_20190211113550() $$

DROP PROCEDURE upgrade_database_20190211113550 $$

DELIMITER ;