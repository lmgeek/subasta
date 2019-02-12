DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20181011110123 $$

CREATE PROCEDURE upgrade_database_20181011110123()

BEGIN

  IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='bids'
                    AND column_name='price'
    ) THEN
    ALTER TABLE `bids` MODIFY COLUMN `price`  float(8,2) NOT NULL AFTER `amount`;

  END IF;


END $$

CALL upgrade_database_20181011110123() $$

DROP PROCEDURE upgrade_database_20181011110123 $$

DELIMITER ;