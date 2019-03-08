# Limpio las taablas y los AUTO_INCREMENTs
truncate arrives;
truncate auctions;
truncate auctions_invites;
truncate auctions_offers;
truncate batch_statuses;
truncate batches;
truncate bids;
truncate boats;
truncate comprador;
truncate flyway_schema_history;
truncate jobs;
truncate migrations;
truncate password_resets;
truncate private_sales;
truncate products;
truncate subscriptions;
truncate users;
truncate users_ratings;
truncate vendedor;

# Insertar todo los usuarios de la web en la tabla users
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

# Insertar todo los usuarios en la tabla vendedor
INSERT INTO vendedor (`user_id`,`cuit`) VALUES ( 1, '20-95756293-1'), ( 2, '20-95756293-2'), ( 3, '20-95756293-3'), ( 4, '20-95756293-4');

# Insertar todo los usuarios en la tabla compador
INSERT INTO comprador (`user_id`,`dni`,`bid_limit`) VALUES ( 5, '12345678', '10000'), ( 6, '14567823', '1000'), ( 7, '12783456', '20000'), ( 8, '45678123', '100');

# Insertar todo los barco
INSERT INTO boats (`user_id`, `name`, `matricula`, `status`, `nickname`, `reference_port`) VALUES ( 1, 'titanic','WS-10-4-A','approved','Barco I', 4), ( 1, 'magallanes','VE-10-4-A','rejected','Barco II', 2),
( 1, 'la perla','BO-10-4-A','pending','Barco III', 3), ( 1, 'caribe','AR-10-4-A','approved','Barco IV', 4);

# Insertar todo los producto
INSERT INTO products (`name`,`unit`,`weigth_small`,`weigth_medium`,`weigth_big`,`fishing_code`,`sale_unit`)
VALUES ('Pulpo','Cajones',10,20,30,'PRO-001','Cajones'),('Camarón','Cajones',15,20,25,'PRO-002','Kg'),('Tiburón','Unidad',5,10,15,'PRO-003','Unidades');

# Insertar todo los arribo
INSERT INTO arrives (`boat_id`, `date`, `port_id`) VALUES (1, '2019-01-24 10:30:00', '3'), (1, '2019-01-30 10:35:00', '3'),
( 4, '2019-01-25 10:30:00', '2');

# Insertar todo los lotes
INSERT INTO batches (`arrive_id`, `product_id`, `caliber`, `quality`, `amount`) VALUES (1, 1, 'big', 4, 100000 ), (2, 2, 'big', 5, 10000), ( 2, 3, 'big', 5, 100000);

# Insertar todo los status de los lotes
INSERT INTO batch_statuses (`batch_id`, `assigned_auction`, `auction_sold`, `private_sold`, `remainder`, `created_at`, `updated_at`)
VALUES (1, 40,50,2000,97910,'2019-01-24 10:30:00','2019-02-20 10:45:0'), (2, 40,50,0,9910,'2019-01-24 10:30:00','2019-01-24 10:30:00'),
(3, 0,90,0,99910,'2019-01-24 10:30:00','2019-02-03 10:32:00'), (2,0,60,0,9910,'2019-01-24 10:30:00','2019-01-24 10:30:00');

# Insertar todo las subastas
INSERT INTO auctions (`batch_id`, `correlative`, `start`, `start_price`, `end`, `end_price`, `interval`, `amount`, `active`, `notification_status`, `type`, `description`, `target_price`,`tentative_date`)
VALUES (1, 152, '2019-01-24 10:30:00', 10000, '2019-01-30 10:30:00', 5000, 1, 90,  1, 0, 'public','producto a la mejor calidad', 5050, '2019-01-30 10:30:00'),
(2, 155, '2019-01-30 10:30:00', 1000, '2019-02-06 10:30:00', 500, 1, 90, 1, 0,'public','producto de alta calidad' ,510, '2019-02-06 10:30:00'),
(3, 150, '2019-01-31 10:30:00', 100, '2019-02-03 10:30:00', 50, 1, 90, 1, 0,'public','producto de calidad' ,52, '2019-02-03 10:30:00'),
(2, 154, '2019-01-30 10:34:00', 1000, '2019-02-06 10:35:00', 500, 2, 90, 1, 0,'private','el mejor producto de alta calidad' ,510, '2019-02-06 10:38:00');

# Insertar invitaciones a subasta
INSERT INTO auctions_invites (`auction_id`, `user_id`)
VALUES (4, 5), (4, 6);

# Insertar todo las ventas privadas
INSERT INTO private_sales (`user_id`, `batch_id`, `amount`, `price`, `weight`, `date`, `buyer_name`)
VALUES (1, 1, 1000, 100, 0, '2019-02-02 10:35:00', 'rafael'), (1, 1, 500, 1000, 0, '2019-02-02 10:40:00', 'alex'), (1, 1, 500, 1000, 0, '2019-02-02 10:45:00', 'carlos');

# Insertar evaluacion
INSERT INTO users_ratings (`user_id`, `positive`, `negative`, `neutral`) VALUES (1,2,1,1);

# Insertar todo las compras
INSERT INTO bids (`auction_id`, `user_id`, `amount`, `price`, `bid_date`, `status`, `user_calification`, `user_calification_comments`, `seller_calification_comments`, `reason`,`seller_calification`)
VALUES (3, 5, 90, 100, '2019-02-06 10:32:00','concretized', 'positive','Reponsable','Buen producto','positive','positive'),
(2, 5, 50, 1000, '2019-02-06 10:31:00','concretized','positive','Producto alta calidad','alta calidad','positive','positive'),
(1, 5, 50, 10000, '2019-01-30 10:31:00','concretized','positive','trato agradable','muy responsable','positive','positive'),
(4, 5, 60, 1000, '2019-01-30 10:35:00','concretized','positive','muy atento','confiable','positive','positive');

# Dejar esto al final ya que lo uso para verificar que termino el deploy
INSERT INTO users (id,`name`,`lastname`,`email`,`password`,`type`,`status`,`hash`,`active_mail`,`nickname`)
VALUES (999,'findeploy','deploy','diegow@netlabs.com.ar','$2y$10$chZ5pITb5Q7e/HQIo6qjeudB.QQ8.Zby.vpOgSjdqjNDbVasz4Rze','internal','approved','NO_aplica',1,'findeploy');
