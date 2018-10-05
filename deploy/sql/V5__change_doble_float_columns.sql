ALTER TABLE `auctions`
MODIFY COLUMN `start_price`  float(8,2) NOT NULL AFTER `start`,
MODIFY COLUMN `end_price`  float(8,2) NOT NULL AFTER `end`;

ALTER TABLE `bids`
MODIFY COLUMN `price`  float(8,2) NOT NULL AFTER `amount`;

ALTER TABLE `private_sales`
MODIFY COLUMN `price`  float(8,2) NOT NULL AFTER `amount`;