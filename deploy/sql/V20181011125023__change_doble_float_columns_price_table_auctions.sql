DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20181011125023 $$

CREATE PROCEDURE upgrade_database_20181011125023()

BEGIN

  IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='auctions'
                    AND column_name='start_price'
    ) THEN
    ALTER TABLE `auctions`
    MODIFY COLUMN `start_price`  float(8,2) NOT NULL AFTER `start`;

  END IF;

  IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='auctions'
                    AND column_name='end_price'
    ) THEN
    ALTER TABLE `auctions`
      MODIFY COLUMN `end_price`  float(8,2) NOT NULL AFTER `end`;

  END IF;


END $$

CALL upgrade_database_20181011125023() $$

DROP PROCEDURE upgrade_database_20181011125023 $$

DELIMITER ;