DELIMITER $$
DROP PROCEDURE IF EXISTS upgrade_database_20190315150012 $$
CREATE PROCEDURE upgrade_database_20190315150012()
BEGIN
    IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                  WHERE
                      TABLE_SCHEMA='subastas'
                    AND table_name='batches_002'
    ) THEN
      DROP TABLE batches_002;

  END IF;
  END $$

CALL upgrade_database_20190315150012() $$

DROP PROCEDURE upgrade_database_20190315150012 $$

DELIMITER ;