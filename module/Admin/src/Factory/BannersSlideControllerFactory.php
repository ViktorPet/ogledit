<?php
namespace Admin\Factory;

use Admin\Controller\BannersSlideController;
use Admin\Model\AdminPermissionsTable;
use Admin\Model\AdminTable;
use Admin\Model\AgenciesTable;
use Admin\Model\ArticlesTable;
use Admin\Model\CategoriesTable;
use Admin\Model\BlogCategoriesTable;
use Admin\Model\NewsCategoriesTable;
use Admin\Model\LanguagesTable;
use Admin\Model\SlidersTable;
use User\Model\UserStatusTable;
use Admin\Model\PermissionTable;
use Admin\Model\PagesTable;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for BannersSlide ControllerFactory
 *
 * Class BannersSlideControllerFactory
 * @package Banners\Factory
 */
class BannersSlideControllerFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return BannersController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {        
        return new BannersSlideController(
                $container->get(AdminTable::class),
                $container->get(AdminPermissionsTable::class),
                $container->get(AuthenticationService::class),
                $container->get(AgenciesTable::class),
                $container->get(PagesTable::class),
                $container->get(ArticlesTable::class),
                $container->get(CategoriesTable::class),
                $container->get(BlogCategoriesTable::class),
                $container->get(NewsCategoriesTable::class),
                $container->get(LanguagesTable::class),
                $container->get(UserStatusTable::class),
                $container->get(PermissionTable::class),
                $container->get(SlidersTable::class)
        );
    }
}