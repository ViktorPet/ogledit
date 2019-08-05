<?php
namespace Admin\Factory;

use Admin\Controller\TeamController;
use Admin\Model\AdminPermissionsTable;
use Admin\Model\AdminTable;
use Admin\Model\AgenciesTable;
use Admin\Model\ArticlesTable;
use Admin\Model\CategoriesTable;
use Admin\Model\LanguagesTable;
use User\Model\UserStatusTable;
use Admin\Model\PermissionTable;
use Admin\Model\PagesTable;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Admin Controller
 *
 * Class AdminControllerFactory
 * @package Admin\Factory
 */
class TeamControllerFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AdminController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {        
        return new TeamController(
                $container->get(AdminTable::class), 
                $container->get(AdminPermissionsTable::class), 
                $container->get(AuthenticationService::class), 
                $container->get(AgenciesTable::class), 
                $container->get(PagesTable::class), 
                $container->get(ArticlesTable::class), 
                $container->get(CategoriesTable::class),
                $container->get(LanguagesTable::class),
                $container->get(UserStatusTable::class),
                $container->get(PermissionTable::class)
        );
    }
}