DELIMITER $$
DROP PROCEDURE IF EXISTS upgrade_database_20190320093000 $$
CREATE PROCEDURE upgrade_database_20190320093000()
BEGIN
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'arrives_port'
    ) THEN
            ALTER TABLE `arrives` ADD CONSTRAINT `arrives_port` FOREIGN KEY (`port_id`) REFERENCES `port` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'arrive_boat'
    ) THEN
            ALTER TABLE `arrives` ADD CONSTRAINT `arrive_boat` FOREIGN KEY (`boat_id`) REFERENCES `boats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'austions_batch'
    ) THEN
            ALTER TABLE `auctions` ADD  CONSTRAINT `austions_batch` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'invites_auction'
    ) THEN
            ALTER TABLE `auctions_invites` ADD CONSTRAINT `invites_auction` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'invites_users'
    ) THEN
            ALTER TABLE `auctions_invites` ADD CONSTRAINT `invites_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'offers_auction'
    ) THEN
            ALTER TABLE `auctions_offers` ADD CONSTRAINT `offers_auction` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'offers_users'
    ) THEN
            ALTER TABLE `auctions_offers` ADD CONSTRAINT `offers_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'status_batch'
    ) THEN
            ALTER TABLE `batch_statuses` ADD CONSTRAINT `status_batch` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'batches_arrive'
    ) THEN
            ALTER TABLE `batches` ADD CONSTRAINT `batches_arrive` FOREIGN KEY (`arrive_id`) REFERENCES `arrives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF NOT EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'bid_auction'
    ) THEN
            ALTER TABLE `bids` ADD CONSTRAINT `bid_auction` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'bid_user'
    ) THEN
            ALTER TABLE `bids` ADD CONSTRAINT `bid_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'boat_user'
    ) THEN
            ALTER TABLE `boats` ADD CONSTRAINT `boat_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'comprador_user'
    ) THEN
            ALTER TABLE `comprador` ADD CONSTRAINT `comprador_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'sales_batch'
    ) THEN
            ALTER TABLE `private_sales` ADD CONSTRAINT `sales_batch` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'sales_users'
    ) THEN
            ALTER TABLE `private_sales` ADD CONSTRAINT `sales_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'subcription_auction'
    ) THEN
            ALTER TABLE `subscriptions` ADD CONSTRAINT `subcription_auction` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'subcription_users'
    ) THEN
            ALTER TABLE `subscriptions` ADD CONSTRAINT `subcription_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
     IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'ratings_users'
    ) THEN
            ALTER TABLE `users_ratings` ADD CONSTRAINT `ratings_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'vendedor_user'
    ) THEN
            ALTER TABLE `vendedor` ADD CONSTRAINT `vendedor_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'detail_products'
    ) THEN
            ALTER TABLE `product_detail` ADD CONSTRAINT `detail_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        select * from information_schema.`TABLE_CONSTRAINTS` WHERE TABLE_SCHEMA='subastas' AND CONSTRAINT_NAME = 'batches_product_detail'
    ) THEN
            ALTER TABLE `batches` ADD CONSTRAINT `batches_product_detail` FOREIGN KEY (`product_detail_id`) REFERENCES `product_detail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
  END $$

CALL upgrade_database_20190320093000() $$

DROP PROCEDURE upgrade_database_20190320093000 $$

DELIMITER ;