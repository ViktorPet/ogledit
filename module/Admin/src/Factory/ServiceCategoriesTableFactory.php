<?php

namespace Admin\Factory;

use Admin\Model\ServiceCategories;
use Admin\Model\ServiceCategoriesTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of ServiceCategoriesTableFactory
 *
 */
class ServiceCategoriesTableFactory implements FactoryInterface {
    
     /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return CategoriesTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new ServiceCategories());
        $tableGateway = new TableGateway('service_categories', $dbAdapter, null, $resultSetPrototype);
        return new ServiceCategoriesTable($tableGateway);
    }
    
}
