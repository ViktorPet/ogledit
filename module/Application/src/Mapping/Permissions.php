<?php
namespace Application\Mapping;

/**
 * Mapper for Permissions
 *
 * Class Permissions
 * @package Application\Mapping
 */
class Permissions {
    CONST permissions = [
        'Admin\Controller\AdminController\dashboard' => 1,
        'Admin\Controller\AdminController\offers' => 2,
        'Admin\Controller\AdminController\blog' => 3,
        'Admin\Controller\AdminController\agencies' => 4,
        'Admin\Controller\AdminController\pages' => 5,
        //'Admin\Controller\AdminController\banners' => 6,
        'Admin\Controller\AdminController\team' => 7,
        'Admin\Controller\AdminController\calendar' => 8,
        'Admin\Controller\AdminController\noPanoramas' => 9,
        'Admin\Controller\AdminController\forStopping' => 10,
        'Admin\Controller\AdminController\profile' => 11,
        'Admin\Controller\AdminController\permissions' => 12
    ];
}