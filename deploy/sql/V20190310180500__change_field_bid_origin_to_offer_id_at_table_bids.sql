DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190310180500 $$

CREATE PROCEDURE upgrade_database_20190310180500()

BEGIN

IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
WHERE
  TABLE_SCHEMA='subastas'
  AND table_name='bids'
  AND column_name='offer_id'
) THEN
   ALTER TABLE `bids` CHANGE COLUMN `bid_origin` `offer_id` int(10) UNSIGNED NOT NULL DEFAULT 0 AFTER `bid_date`;
   UPDATE bids AS b INNER JOIN (
                SELECT auctions_offers.id FROM auctions_offers
                JOIN bids
                WHERE auctions_offers.`auction_id` = bids.`auction_id`
                ORDER BY auctions_offers.`price` DESC LIMIT 1
                ) AS offerId
   ON b.offer_id > 0 SET offer_id = offerId.id;

END IF;


END $$

CALL upgrade_database_20190310180500() $$

DROP PROCEDURE upgrade_database_20190310180500 $$

DELIMITER ;
