<?php

namespace Admin\Factory;

use Admin\Controller\AgenciesController;
use Admin\Controller\InvoicesController;
use Admin\Model\AdminPermissionsTable;
use Admin\Model\AdminTable;
use Admin\Model\AgenciesTable;
use Admin\Model\ArticlesTable;
use Admin\Model\CategoriesTable;
use Admin\Model\InvoicesTable;
use Admin\Model\TransactionTable;
use Admin\Model\OffersTable;
use Admin\Model\LanguagesTable;
use User\Model\UserStatusTable;
use User\Model\UserTypeTable;
use User\Model\PriceTable;
use Admin\Model\PermissionTable;
use Admin\Model\PagesTable;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Helper\LanguageHelper;

/**
 * Description of InvoicesControllerFactory
 *
 * @author Krasimira Evgenieva
 */
class InvoicesControllerFactory implements FactoryInterface{
    
    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AgenciesController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {        
        return new InvoicesController(
                $container->get(AdminTable::class), 
                $container->get(AdminPermissionsTable::class), 
                $container->get(AuthenticationService::class), 
                $container->get(AgenciesTable::class), 
                $container->get(PagesTable::class), 
                $container->get(ArticlesTable::class), 
                $container->get(CategoriesTable::class),
                $container->get(LanguagesTable::class),
                $container->get(UserStatusTable::class),
                $container->get(UserTypeTable::class),
                $container->get(PriceTable::class),
                $container->get(PermissionTable::class),
                $container->get(InvoicesTable::class),
                $container->get(TransactionTable::class),
                $container->get(OffersTable::class),
                $container->get(LanguageHelper::class)
        );
    }
    
    
}
