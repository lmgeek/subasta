DELIMITER $$
DROP PROCEDURE IF EXISTS upgrade_database_2019032501100000 $$
CREATE PROCEDURE upgrade_database_2019032501100000()
BEGIN
    IF NOT EXISTS ( SELECT * FROM information_schema.`TABLE_CONSTRAINTS`
  WHERE
      TABLE_SCHEMA='subastas'
      AND table_name='products'
      AND CONSTRAINT_NAME='product_fishing_code'
      AND CONSTRAINT_TYPE='UNIQUE') THEN
      ALTER TABLE products ADD CONSTRAINT product_fishing_code UNIQUE (fishing_code);
  END IF;
  IF NOT EXISTS ( SELECT * FROM information_schema.`TABLE_CONSTRAINTS`
  WHERE
      TABLE_SCHEMA='subastas'
      AND table_name='products'
      AND CONSTRAINT_NAME='product_name'
      AND CONSTRAINT_TYPE='UNIQUE') THEN
      ALTER TABLE products ADD CONSTRAINT product_name UNIQUE (name);
  END IF;
END $$

CALL upgrade_database_2019032501100000() $$

DROP PROCEDURE upgrade_database_2019032501100000 $$

DELIMITER ;