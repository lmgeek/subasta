DELIMITER $$

DROP PROCEDURE IF EXISTS afterMigrate $$

CREATE PROCEDURE afterMigrate()

BEGIN

  IF NOT EXISTS ( SELECT * FROM users
    WHERE password != '$2y$10$chZ5pITb5Q7e/HQIo6qjeudB.QQ8.Zby.vpOgSjdqjNDbVasz4Rze' ) THEN
      UPDATE users 
      SET password = '$2y$10$chZ5pITb5Q7e/HQIo6qjeudB.QQ8.Zby.vpOgSjdqjNDbVasz4Rze';
  END IF;

  IF NOT EXISTS ( SELECT * FROM users
    WHERE email = 'diegow@netlabs.com.ar' ) THEN
      INSERT INTO users (`name`,`lastname`,`email`,`password`,`type`,`status`,`hash`,`active_mail`,`nickname`)
      VALUES ('Diego','Weinstein','diegow@netlabs.com.ar','$2y$10$chZ5pITb5Q7e/HQIo6qjeudB.QQ8.Zby.vpOgSjdqjNDbVasz4Rze','internal','approved','NO_aplica',1,'admin');
  END IF;

END $$

CALL afterMigrate() $$

DROP PROCEDURE afterMigrate $$

DELIMITER ;
