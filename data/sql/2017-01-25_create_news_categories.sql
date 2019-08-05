CREATE TABLE `news_categories` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `user_statuses`
--

INSERT INTO `news_categories` (`id`, `name`) VALUES
(1, 'Новина на деня'),
(2, 'Имоти'),
(3, 'Иновации'),
(4, 'По света'),
(5, 'У нас'),
(6, 'Закони'),
(7, 'Бизнес'),
(8, 'Обществени поръчки'),
(9, 'В помощ на');

ALTER TABLE `articles` ADD CONSTRAINT `fk_articles_news_categories` FOREIGN KEY (`position`) REFERENCES `news_categories`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
