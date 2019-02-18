DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190124105021 $$

CREATE PROCEDURE upgrade_database_20190124105021()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='auctions'
  AND column_name='description') THEN
  ALTER TABLE auctions ADD COLUMN description varchar(1000) NOT NULL;
END IF;

END $$

CALL upgrade_database_20190124105021() $$

DROP PROCEDURE upgrade_database_20190124105021 $$

DELIMITER ;