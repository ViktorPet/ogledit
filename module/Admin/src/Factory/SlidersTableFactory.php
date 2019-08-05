<?php

namespace Admin\Factory;

use Admin\Model\Sliders;
use Admin\Model\SlidersTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of OffersTableFactory
 *
 */
class SlidersTableFactory implements FactoryInterface{
    
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
        $resultSetPrototype->setArrayObjectPrototype(new Sliders());
        $tableGateway = new TableGateway('sliders', $dbAdapter, null, $resultSetPrototype);
        return new SlidersTable($tableGateway);
    }
    
}
