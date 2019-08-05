ALTER TABLE `users` ADD `facebook_id` BIGINT UNSIGNED NULL DEFAULT NULL AFTER `id`;
ALTER TABLE `users` ADD `is_fb_reg_complete` TINYINT NULL DEFAULT NULL AFTER `facebook_id`;