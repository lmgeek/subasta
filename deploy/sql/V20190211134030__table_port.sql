DELIMITER $$

DROP PROCEDURE IF EXISTS upgrade_database_20190110123000 $$

CREATE PROCEDURE upgrade_database_20190110123000()

BEGIN

  IF NOT EXISTS ( SELECT * FROM information_schema.`COLUMNS`
  WHERE
      TABLE_SCHEMA='subastas'
      AND table_name='port') THEN
            CREATE TABLE `port` (
            `id`  int(100) NOT NULL AUTO_INCREMENT ,
            `name`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
            PRIMARY KEY (`id`)
            );

            INSERT INTO port (`name`) VALUES ('Mar del Plata');
            INSERT INTO port (`name`) VALUES ('Buenos Aires');
            INSERT INTO port (`name`) VALUES ('Puerto Madryn');
            INSERT INTO port (`name`) VALUES ('General Lavalle');
  END IF;
  END $$

CALL upgrade_database_20190110123000() $$

DROP PROCEDURE upgrade_database_20190110123000 $$

DELIMITER ;