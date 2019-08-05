<?php

namespace Admin\Factory;

use Admin\Model\Pages;
use Admin\Model\PagesTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of PagesTableFactory
 *
 */
class PagesTableFactory implements FactoryInterface {
    
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
        $resultSetPrototype->setArrayObjectPrototype(new Pages());
        $tableGateway = new TableGateway('pages', $dbAdapter, null, $resultSetPrototype);                
        return new PagesTable($tableGateway);
    }
    
}
