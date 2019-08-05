<?php

namespace Admin\Factory;

use Admin\Controller\DashboardController;
use Admin\Model\AdminPermissionsTable;
use Admin\Model\AdminTable;
use Admin\Model\OffersTable;
use Admin\Model\CategoriesTable;
use Admin\Model\LanguagesTable;
use User\Model\UserStatusTable;
use User\Model\CityTable;
use User\Model\OfferTypeTable; 
use Admin\Model\PermissionTable;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of DashboardControllerFactory
 *
 * @author Krasimira Evgenieva
 */
class DashboardControllerFactory implements FactoryInterface{
   
    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return DashboardController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {        
        return new DashboardController(
                $container->get(AdminTable::class), 
                $container->get(AdminPermissionsTable::class), 
                $container->get(AuthenticationService::class), 
                $container->get(CategoriesTable::class),
                $container->get(LanguagesTable::class),
                $container->get(UserStatusTable::class),
                $container->get(CityTable::class),
                $container->get(OffersTable::class),
                $container->get(PermissionTable::class),
                $container->get(OfferTypeTable::class)
        );
    }
    
}
