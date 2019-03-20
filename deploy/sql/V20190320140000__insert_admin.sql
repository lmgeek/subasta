DELIMITER $$
DROP PROCEDURE IF EXISTS upgrade_database_20190320140000 $$
CREATE PROCEDURE upgrade_database_20190320140000()
BEGIN
    IF NOT EXISTS (
        select * from users as u WHERE u.email='desarrollo@netlabs.com.ar'
    ) THEN
        INSERT INTO users (`name`,`lastname`,`email`,`password`,`type`,`status`,`hash`,`active_mail`,`nickname`) VALUES ('diego','weinstein','desarrollo@netlabs.com.ar','$2y$10$chZ5pITb5Q7e/HQIo6qjeudB.QQ8.Zby.vpOgSjdqjNDbVasz4Rze','internal','approved','NO_aplica',1,'admin');
    END IF;
 END $$
CALL upgrade_database_20190320140000() $$
DROP PROCEDURE upgrade_database_20190320140000 $$
DELIMITER ;