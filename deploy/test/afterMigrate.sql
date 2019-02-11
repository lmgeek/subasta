INSERT INTO users (`name`,`lastname`,`email`,`password`,`type`,`status`,`hash`,`active_mail`,`nickname`)
VALUES ('Maria','Crer','mariac@netlabs.com.ar','$2y$10$chZ5pITb5Q7e/HQIo6qjeudB.QQ8.Zby.vpOgSjdqjNDbVasz4Rze','seller','approved','NO_aplica_prueba',1,'Mcrer'),
('luis','marin','luismarin@netlabs.com.ar','$2y$10$chZ5pITb5Q7e/HQIo6qjeudB.QQ8.Zby.vpOgSjdqjNDbVasz4Rze','seller','pending','NO_aplica_prueba',1,'luism'),
('rodolfo','oquendo','rodolfor@netlabs.com.ar','$2y$10$chZ5pITb5Q7e/HQIo6qjeudB.QQ8.Zby.vpOgSjdqjNDbVasz4Rze','seller','pending','NO_aplica_prueba',0,'rodolfo05'),
('Karla','Hernández','Karlahernandez@netlabs.com.ar','$2y$10$chZ5pITb5Q7e/HQIo6qjeudB.QQ8.Zby.vpOgSjdqjNDbVasz4Rze','seller','rejected','NO_aplica_prueba',1,'Karla12'),
('rafael','alvarez','rafaela@netlabs.com.ar','$2y$10$chZ5pITb5Q7e/HQIo6qjeudB.QQ8.Zby.vpOgSjdqjNDbVasz4Rze','buyer','approved','NO_aplica_prueba',1,'rafa'),
('german','barrio','germanb@netlabs.com.ar','$2y$10$chZ5pITb5Q7e/HQIo6qjeudB.QQ8.Zby.vpOgSjdqjNDbVasz4Rze','buyer','approved','NO_aplica_prueba',1,'germanb'),
('ezequiel','bikiel','ezequielb@netlabs.com.ar','$2y$10$chZ5pITb5Q7e/HQIo6qjeudB.QQ8.Zby.vpOgSjdqjNDbVasz4Rze','buyer','pending','NO_aplica_prueba',1,'elbiki'),
('lorena','perez','lorenaperez@netlabs.com.ar','$2y$10$chZ5pITb5Q7e/HQIo6qjeudB.QQ8.Zby.vpOgSjdqjNDbVasz4Rze','buyer','rejected','NO_aplica_prueba',1,'lorena1'),
('diego','weinstein','desarrollo@netlabs.com.ar','$2y$10$chZ5pITb5Q7e/HQIo6qjeudB.QQ8.Zby.vpOgSjdqjNDbVasz4Rze','internal','approved','NO_aplica',1,'admin');

INSERT INTO vendedor (`user_id`,`cuit`) VALUES ( 1, '20-95756293-1'), ( 2, '20-95756293-2'), ( 3, '20-95756293-3'), ( 4, '20-95756293-4');

INSERT INTO comprador (`user_id`,`dni`,`bid_limit`) VALUES ( 5, '12345678', '10000'), ( 6, '14567823', '1000'), ( 7, '12783456', '20000'), ( 8, '45678123', '100');

INSERT INTO boats (`user_id`, `name`, `matricula`, `status`, `nickname`) VALUES ( 1, 'titanic','WS-10-4-A','approved','Barco I'), ( 1, 'magallanes','VE-10-4-A','rejected','Barco II'),
( 1, 'la perla','BO-10-4-A','pending','Barco III'), ( 1, 'caribe','AR-10-4-A','approved','Barco IV');

INSERT INTO products (`name`,`unit`,`weigth_small`,`weigth_medium`,`weigth_big`)  VALUES ('Pulpo','Cajones',10,20,30),('Camarón','Cajones',15,20,25),('Tiburón','Unidad',5,10,15);

INSERT INTO arrives (`boat_id`, `date`, `port_id`) VALUES (1, '2019-01-24 10:30:00', '3'), (1, '2019-01-30 10:35:00', '3'),
( 4, '2019-01-25 10:30:00', '2');

INSERT INTO batches (`arrive_id`, `product_id`, `caliber`, `quality`, `amount`) VALUES (1, 1, 'big', 4, 100000 ), (2, 2, 'big', 5, 10000), ( 2, 3, 'big', 5, 100000);

INSERT INTO batch_statuses (`batch_id`, `assigned_auction`, `auction_sold`, `private_sold`, `remainder`, `created_at`, `updated_at`)
VALUES (1, 90,1,0,99910,'2019-01-24 10:30:00','2019-01-24 10:30:00'), (2, 90,1,0,9910,'2019-01-24 10:30:00','2019-01-24 10:30:00'),
(3, 90,1,0,99910,'2019-01-24 10:30:00','2019-01-24 10:30:00');

INSERT INTO auctions (`batch_id`, `start`, `start_price`, `end`, `end_price`, `interval`, `amount`, `active`, `notification_status`, `type`, `description`, `target_price`)
VALUES (1, '2019-01-24 10:30:00', 10000, '2019-01-30 10:30:00', 5000, 1, 90,  1, 0, 'public','producto a la mejor calidad' ,5050),
(2, '2019-01-30 10:30:00', 1000, '2019-02-06 10:30:00', 500, 1, 90, 1, 0,'public','producto de alta calidad' ,510),
(3, '2019-01-31 10:30:00', 100, '2019-02-03 10:30:00', 50, 1, 90, 1, 0,'public','producto de calidad' ,52);

INSERT INTO bids (`auction_id`, `user_id`, `amount`, `price`, `bid_date`, `status`, `user_calification`, `user_calification_comments`, `seller_calification_comments`, `reason`)
VALUES (3, 5, 90, 100, '2019-02-06 10:32:00', 'concretized', 'positive',  'positive', 'positive','positive');

INSERT INTO batch_statuses (`batch_id`, `assigned_auction`, `auction_sold`, `private_sold`, `remainder`, `created_at`, `updated_at`)
VALUES (3, 0,90,0,0,'2019-02-02 10:32:00','2019-02-03 10:32:00');

# Dejar esto al final ya que lo uso para verificar que termino el deploy
INSERT INTO users (id,`name`,`lastname`,`email`,`password`,`type`,`status`,`hash`,`active_mail`,`nickname`)
VALUES (999,'findeploy','deploy','diegow@netlabs.com.ar','$2y$10$chZ5pITb5Q7e/HQIo6qjeudB.QQ8.Zby.vpOgSjdqjNDbVasz4Rze','internal','approved','NO_aplica',1,'findeploy');
