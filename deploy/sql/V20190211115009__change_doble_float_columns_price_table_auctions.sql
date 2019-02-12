DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190211115009 $$

CREATE PROCEDURE upgrade_database_20190211115009()

BEGIN

  IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='auctions'
                    AND column_name='start_price'
                    AND column_name='end_price'
    ) THEN
    ALTER TABLE `auctions`
    MODIFY COLUMN `start_price`  float(8,2) NOT NULL AFTER `start`,
    MODIFY COLUMN `end_price`  float(8,2) NOT NULL AFTER `end`;


  END IF;


END $$

CALL upgrade_database_20190211115009() $$

DROP PROCEDURE upgrade_database_20190211115009 $$

DELIMITER ;