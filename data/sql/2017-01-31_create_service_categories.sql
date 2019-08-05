CREATE TABLE `service_categories` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `image_link` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Dumping data for table `user_statuses`
--

INSERT INTO `service_categories` (`id`, `name`, `image_link`) VALUES
(1, 'Търговски обекти', 'turgovski_obekti.jpg'),
(2, 'Спортни зали', 'sportni_zali.jpg'),
(3, 'Заведения', 'zavedenie.jpg'),
(4, 'Изкуство', 'izkustvo.jpg'),
(5, 'Забавление', 'zabavlenie.jpg'),
(6, 'Други', 'drugo.jpg');