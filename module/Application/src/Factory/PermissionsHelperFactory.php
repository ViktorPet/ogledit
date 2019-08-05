<?php

namespace Application\Factory;

use Admin\Model\OffersTable;
use Application\Helper\PermissionsHelper;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Model\PermissionTable;
/**
 * Description of PermissionsHelperFactory
 *
 * @author Kiril Tsvetanov
 */
class PermissionsHelperFactory implements FactoryInterface{
    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthenticationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {     
        $pageTable = $container->get(PermissionTable::class);
        return new PermissionsHelper($pageTable);        
    }
}
