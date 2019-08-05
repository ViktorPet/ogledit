<?php

namespace Admin\Factory;

use Admin\Model\Price;
use Admin\Model\PricesTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;


/**
 * Description of PricesTableFactory
 *
 */
class PricesTableFactory implements FactoryInterface{
    
    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PricesTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Price());
        $tableGateway = new TableGateway('prices', $dbAdapter, null, $resultSetPrototype);                
        return new PricesTable($tableGateway);
    }
    
}
