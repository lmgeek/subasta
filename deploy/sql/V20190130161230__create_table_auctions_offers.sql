DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190130161230 $$

CREATE PROCEDURE upgrade_database_20190130161230()

BEGIN

  IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
  WHERE
      TABLE_SCHEMA='subastas'
      AND table_name='auctions_offers') THEN
        CREATE TABLE `auctions_offers` (
              `id`  int(10) NOT NULL AUTO_INCREMENT,
              `auction_id`  int(10) NOT NULL ,
              `user_id`  int(10) NOT NULL ,
              `amount`  int(11),
              `price`  float(8,2) NOT NULL ,
              `status`  enum('accepted','pending','rejected') NOT NULL DEFAULT 'pending' ,
              `created_at`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at`  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        )
        ENGINE=InnoDB
        DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
        AUTO_INCREMENT=1
        ROW_FORMAT=COMPACT;
  END IF;

END $$

CALL upgrade_database_20190130161230() $$

DROP PROCEDURE upgrade_database_20190130161230 $$

DELIMITER ;
