ALTER TABLE `boats`
MODIFY COLUMN `name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `user_id`,
MODIFY COLUMN `matricula`  varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `name`;
