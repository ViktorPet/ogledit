INSERT INTO `permissions` (`id`, `module`, `controller`, `action`, `description`) VALUES
(49, 'Admin\\Controller', 'BannersSlideController', 'index', 'Банери'),
(50, 'Admin\\Controller', 'BannersSlideController', 'create', 'Банери създаване'),
(51, 'Admin\\Controller', 'BannersSlideController', 'edit', 'Банери промяна'),
(52, 'Admin\\Controller', 'BannersSlideController', 'delete', 'Банери изтриване');

--6, Admin\\Controller,  AdminController, banners, Банери
DELETE FROM `permissions` WHERE `id` = 6;


UPDATE `permissions` SET `description` = 'Хедъри' WHERE `permissions`.`id` = 46;
UPDATE `permissions` SET `description` = 'Хедъри промяна' WHERE `permissions`.`id` = 47;