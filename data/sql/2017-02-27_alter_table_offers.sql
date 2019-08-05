INSERT INTO `permissions` (`id`, `module`, `controller`, `action`, `description`) VALUES
(46, 'Admin\\Controller', 'BannersController', 'index', 'parallax'),
(47, 'Admin\\Controller', 'BannersController', 'edit', 'parallax edit');

INSERT INTO `admin_permissions` (`admin_id`, `permission_id`) VALUES
(1, 46),
(1, 47);