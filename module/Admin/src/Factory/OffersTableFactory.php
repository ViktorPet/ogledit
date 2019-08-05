<?php

namespace Admin\Factory;

use Application\Model\Offer;
use Admin\Model\OffersTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of OffersTableFactory
 *
 */
class OffersTableFactory implements FactoryInterface{
    
    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PagesTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Offer());
        $tableGateway = new TableGateway('offers', $dbAdapter, null, $resultSetPrototype);                
        return new OffersTable($tableGateway);
    }
    
}
