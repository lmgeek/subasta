CREATE TABLE `auctions_offers` (
`id`  int(10) NOT NULL AUTO_INCREMENT,
`auction_id`  int(10) NOT NULL ,
`user_id`  int(10) NOT NULL ,
`amount`  int(11),
`price`  float(8,2) NOT NULL ,
`status`  enum('accepted','pending','rejected') NOT NULL DEFAULT 'pending' ,
`created_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
`updated_at`  timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1
ROW_FORMAT=COMPACT
;
