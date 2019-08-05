<?php

namespace Admin\Factory;

use Admin\Controller\NewsletterController;
use Admin\Model\AdminPermissionsTable;
use Admin\Model\AdminTable;
use Admin\Model\LanguagesTable;
use Admin\Model\PermissionTable;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Model\PricesTable;
use User\Model\NewsletterTable;

/**
 * Description of NewsletterControllerFactory
 *
 */
class NewsletterControllerFactory implements FactoryInterface{
    
    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AdminController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {        
        return new NewsletterController(
                $container->get(AdminTable::class), 
                $container->get(AdminPermissionsTable::class), 
                $container->get(AuthenticationService::class),   
                $container->get(LanguagesTable::class),
                $container->get(PermissionTable::class),
                $container->get(NewsletterTable::class)

        );
    }
    
}
