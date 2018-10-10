ALTER TABLE `products`
CHANGE COLUMN `weigth` `weigth_small` float(10,2) NOT NULL AFTER `unit`,
ADD COLUMN `weigth_medium` float(10,2) NOT NULL AFTER `weigth_small`,
ADD COLUMN `weigth_big` float(10,2) NULL AFTER `weigth_medium`;