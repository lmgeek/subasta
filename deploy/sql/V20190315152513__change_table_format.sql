DELIMITER $$
DROP PROCEDURE IF EXISTS upgrade_database_20190315152513 $$
CREATE PROCEDURE upgrade_database_20190315152513()
BEGIN
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='auctions_offers'
    ) THEN
            ALTER TABLE `auctions_offers` MODIFY COLUMN `auction_id`  int(10) UNSIGNED NOT NULL AFTER `id`;
            ALTER TABLE `auctions_offers` MODIFY COLUMN `user_id`  int(10) UNSIGNED NOT NULL AFTER `auction_id`;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='users_ratings'
    ) THEN
            ALTER TABLE `users_ratings` ENGINE=InnoDB;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='subscriptions'
    ) THEN
            ALTER TABLE `subscriptions` ENGINE=InnoDB;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='auctions_invites'
    ) THEN
            ALTER TABLE `auctions_invites` ENGINE=InnoDB;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas'AND table_name='private_sales'
    ) THEN
            ALTER TABLE `private_sales` ENGINE=InnoDB;
    END IF;
  END $$

CALL upgrade_database_20190315152513() $$

DROP PROCEDURE upgrade_database_20190315152513 $$

DELIMITER ;