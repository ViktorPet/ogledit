<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\PropertyType;
use User\Model\PropertyTypeTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Property Type Table
 *
 * Class PropertyTypeTableFactory
 * @package User\Factory
 */
class PropertyTypeTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PropertyTypeTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new PropertyType());
        $tableGateway = new TableGateway('property_types', $dbAdapter, null, $resultSetPrototype);

        return new PropertyTypeTable($tableGateway);
    }
}