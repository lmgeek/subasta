DELIMITER $$
DROP PROCEDURE IF EXISTS upgrade_database_20190315165100 $$
CREATE PROCEDURE upgrade_database_20190315165100()
BEGIN
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='comprador'
    ) THEN
            TRUNCATE TABLE comprador;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='vendedor'
    ) THEN
            TRUNCATE TABLE vendedor;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='users'
    ) THEN
            TRUNCATE TABLE users;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='password_resets'
    ) THEN
            TRUNCATE TABLE password_resets;
    END IF;
  END $$

CALL upgrade_database_20190315165100() $$

DROP PROCEDURE upgrade_database_20190315165100 $$

DELIMITER ;