DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190314093000 $$

CREATE PROCEDURE upgrade_database_20190314093000()

BEGIN

  IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='batches'
                    AND column_name='sale_unit'
    ) THEN
    ALTER TABLE batches DROP COLUMN sale_unit;

END $$

CALL upgrade_database_20190314093000() $$

DROP PROCEDURE upgrade_database_20190314093000 $$

DELIMITER ;