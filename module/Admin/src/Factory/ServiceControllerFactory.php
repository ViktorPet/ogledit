<?php

namespace Admin\Factory;

use Admin\Controller\ServiceController;
use Admin\Model\AdminPermissionsTable;
use Admin\Model\AdminTable;
use Admin\Model\AgenciesTable;
use Admin\Model\ServicesTable;
use Admin\Model\ServiceCategoriesTable;
use Admin\Model\LanguagesTable;
use User\Model\UserStatusTable;
use Admin\Model\PermissionTable;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of ServiceControllerFactory
 *
 */
class ServiceControllerFactory implements FactoryInterface{
    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AdminController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {        
        return new ServiceController(
                $container->get(AdminTable::class), 
                $container->get(AdminPermissionsTable::class), 
                $container->get(AuthenticationService::class), 
                $container->get(AgenciesTable::class), 
                $container->get(ServicesTable::class), 
                $container->get(ServiceCategoriesTable::class),              
                $container->get(LanguagesTable::class),
                $container->get(UserStatusTable::class),
                $container->get(PermissionTable::class)
        );
    }
}
