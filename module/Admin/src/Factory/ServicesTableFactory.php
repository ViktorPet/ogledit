<?php

namespace Admin\Factory;

use Admin\Model\Service;
use Admin\Model\ServicesTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of ServicesTableFactory
 *
 * @author Krasimira Evgenieva
 */
class ServicesTableFactory implements FactoryInterface{
    
     /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ArticlesTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Service());
        $tableGateway = new TableGateway('services', $dbAdapter, null, $resultSetPrototype);                
        return new ServicesTable($tableGateway);
    }
    
}
