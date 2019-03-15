DELIMITER $$
DROP PROCEDURE IF EXISTS upgrade_database_20190315143011 $$
CREATE PROCEDURE upgrade_database_20190315143011()
BEGIN
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='auctions'
    ) THEN
            TRUNCATE TABLE auctions;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='arrives'
    ) THEN
            TRUNCATE TABLE arrives;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='auctions_invites'
    ) THEN
            TRUNCATE TABLE auctions_invites;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='auctions_offers'
    ) THEN
            TRUNCATE TABLE auctions_offers;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='batch_statuses'
    ) THEN
            TRUNCATE TABLE batch_statuses;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='bids'
    ) THEN
            TRUNCATE TABLE bids;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='batches'
    ) THEN
            TRUNCATE TABLE batches;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='boats'
    ) THEN
            TRUNCATE TABLE boats;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='private_sales'
    ) THEN
            TRUNCATE TABLE private_sales;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='products'
    ) THEN
            TRUNCATE TABLE products;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='subscriptions'
    ) THEN
            TRUNCATE TABLE subscriptions;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='users_ratings'
    ) THEN
            TRUNCATE TABLE users_ratings;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='product_detail'
    ) THEN
            TRUNCATE TABLE product_detail;
    END IF;
  END $$

CALL upgrade_database_20190315143011() $$

DROP PROCEDURE upgrade_database_20190315143011 $$

DELIMITER ;