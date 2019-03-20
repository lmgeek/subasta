DELIMITER $$
DROP PROCEDURE IF EXISTS upgrade_database_20190320090000 $$
CREATE PROCEDURE upgrade_database_20190320090000()
BEGIN
    IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                    WHERE
                        TABLE_SCHEMA='subastas'
                      AND table_name='auctions_offers'
                      AND column_name='auction_id'
      ) THEN
      ALTER TABLE `auctions_offers` MODIFY COLUMN `auction_id`  int(10) UNSIGNED NOT NULL AFTER `id`;
    END IF;
    IF EXISTS ( SELECT * FROM information_schema.`COLUMNS`
                    WHERE
                        TABLE_SCHEMA='subastas'
                      AND table_name='auctions_offers'
                      AND column_name='user_id'
      ) THEN
      ALTER TABLE `auctions_offers` MODIFY COLUMN `user_id`  int(10) UNSIGNED NOT NULL AFTER `auction_id`;
    END IF;
    IF EXISTS (
        SELECT * FROM `TABLES` where TABLE_NAME = 'users_ratings' AND `ENGINE` <> 'InnoDB'
    ) THEN
            ALTER TABLE `users_ratings` ENGINE=InnoDB;
    END IF;
    IF EXISTS (
        SELECT * FROM `TABLES` where TABLE_NAME = 'subscriptions' and `ENGINE` <> 'InnoDB'
    ) THEN
            ALTER TABLE `subscriptions` ENGINE=InnoDB;
    END IF;
    IF EXISTS (
        SELECT * FROM `TABLES` where TABLE_NAME = 'auctions_invites' and `ENGINE` <> 'InnoDB'
    ) THEN
            ALTER TABLE `auctions_invites` ENGINE=InnoDB;
    END IF;
    IF EXISTS (
        SELECT * FROM `TABLES` where TABLE_NAME = 'private_sales' and `ENGINE` <> 'InnoDB'
    ) THEN
            ALTER TABLE `private_sales` ENGINE=InnoDB;
    END IF;
  END $$

CALL upgrade_database_20190320090000() $$

DROP PROCEDURE upgrade_database_20190320090000 $$

DELIMITER ;