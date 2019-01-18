ALTER TABLE `auctions`
ADD COLUMN `target_price`  float(8,0) NOT NULL DEFAULT 0 AFTER `description`;