DELIMITER $$
DROP PROCEDURE IF EXISTS upgrade_database_20190315165200 $$
CREATE PROCEDURE upgrade_database_20190315165200()
BEGIN
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('arrives', 'port')
    ) THEN
            ALTER TABLE `arrives` ADD CONSTRAINT `arrives_port` FOREIGN KEY (`port_id`) REFERENCES `port` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('arrives', 'boats')
    ) THEN
            ALTER TABLE `arrives` ADD CONSTRAINT `arrive_boat` FOREIGN KEY (`boat_id`) REFERENCES `boats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('auctions', 'batches')
    ) THEN
            ALTER TABLE `auctions` ADD  CONSTRAINT `austions_batch` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('auctions_invites', 'auctions')
    ) THEN
            ALTER TABLE `auctions_invites` ADD CONSTRAINT `invites_auction` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('auctions_invites', 'users')
    ) THEN
            ALTER TABLE `auctions_invites` ADD CONSTRAINT `invites_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('auctions_offers', 'auctions')
    ) THEN
            ALTER TABLE `auctions_offers` ADD CONSTRAINT `offers_auction` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('auctions_offers', 'users')
    ) THEN
            ALTER TABLE `auctions_offers` ADD CONSTRAINT `offers_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('batch_statuses', 'batches')
    ) THEN
            ALTER TABLE `batch_statuses` ADD CONSTRAINT `status_batch` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('batches', 'arrives')
    ) THEN
            ALTER TABLE `batches` ADD CONSTRAINT `batches_arrive` FOREIGN KEY (`arrive_id`) REFERENCES `arrives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('bids', 'auctions')
    ) THEN
            ALTER TABLE `bids` ADD CONSTRAINT `bid_auction` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('bids', 'users')
    ) THEN
            ALTER TABLE `bids` ADD CONSTRAINT `bid_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('boats', 'users')
    ) THEN
            ALTER TABLE `boats` ADD CONSTRAINT `boat_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('comprador', 'users')
    ) THEN
            ALTER TABLE `comprador` ADD CONSTRAINT `comprador_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('private_sales', 'batches')
    ) THEN
            ALTER TABLE `private_sales` ADD CONSTRAINT `sales_batch` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('private_sales', 'users')
    ) THEN
            ALTER TABLE `private_sales` ADD CONSTRAINT `sales_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('subscriptions', 'auctions')
    ) THEN
            ALTER TABLE `subscriptions` ADD CONSTRAINT `subcription_auction` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('subscriptions', 'users')
    ) THEN
            ALTER TABLE `subscriptions` ADD CONSTRAINT `subcription_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
     IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('users_ratings', 'users')
    ) THEN
            ALTER TABLE `users_ratings` ADD CONSTRAINT `ratings_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('vendedor', 'users')
    ) THEN
            ALTER TABLE `vendedor` ADD CONSTRAINT `vendedor_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('product_detail', 'products')
    ) THEN
            ALTER TABLE `product_detail` ADD CONSTRAINT `detail_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
    IF EXISTS (
        SELECT * FROM information_schema.`TABLES` WHERE TABLE_SCHEMA='subastas' AND table_name IN ('batches', 'product_detail')
    ) THEN
            ALTER TABLE `batches` ADD CONSTRAINT `batches_product_detail` FOREIGN KEY (`product_detail_id`) REFERENCES `product_detail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
    END IF;
  END $$

CALL upgrade_database_20190315165200() $$

DROP PROCEDURE upgrade_database_20190315165200 $$

DELIMITER ;