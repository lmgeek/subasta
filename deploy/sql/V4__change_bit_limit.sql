ALTER TABLE `comprador` CHANGE bid_limit intrate INTEGER;
ALTER TABLE `comprador` ADD bid_limit float(10,2) NOT NULL DEFAULT 100;
UPDATE `comprador` SET bid_limit=intrate;
ALTER TABLE `comprador` DROP intrate;