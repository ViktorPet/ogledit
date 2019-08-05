CREATE TABLE `services` (
    `id` tinyint(4) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) CHARACTER SET utf8 NOT NULL,
    `description` text  NULL,
    `image` varchar(255) CHARACTER SET utf8 NOT NULL,
    `panorama_file` varchar(32) CHARACTER SET utf8 NOT NULL,
    `url` varchar(255) CHARACTER SET utf8 NOT NULL,
    `date_published` datetime NOT NULL,
    `date_created` datetime NOT NULL,
    `date_updated` datetime NOT NULL,
    `service_category_id` tinyint(4) NOT NULL,
        PRIMARY KEY (`id`)
)

ALTER TABLE `services` ADD INDEX(` service_category_id `);

ALTER TABLE `services` ADD FOREIGN KEY (`service_category_id`) REFERENCES `service_categories`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;