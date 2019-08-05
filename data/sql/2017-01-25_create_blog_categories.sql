CREATE TABLE `blog_categories` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `user_statuses`
--

INSERT INTO `blog_categories` (`id`, `name`) VALUES
(1, 'Архитектура'),
(2, 'Интериор'),
(3, 'Дизайн'),
(4, 'Изкуство'),
(5, 'Иновации'),
(6, 'Пътешествия'),
(7, 'Как да'),
(8, 'Полезно за потребителя'),
(9, 'Брокерски истории');

ALTER TABLE `articles` ADD CONSTRAINT `fk_articles_blog_categories` FOREIGN KEY (`position`) REFERENCES `blog_categories`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;