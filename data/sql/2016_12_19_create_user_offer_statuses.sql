CREATE TABLE `user_offer_statuses` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
)

--
-- Dumping data for table `user_statuses`
--

INSERT INTO `user_offer_statuses` (`id`, `name`) VALUES
(1, 'Архивирана'),
(2, 'Продадена'),
(3, 'Депозирана');