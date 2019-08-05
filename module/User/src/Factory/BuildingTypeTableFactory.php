<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\BuildingType;
use User\Model\BuildingTypeTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Building Type Table
 *
 * Class BuildingTypeTableFactory
 * @package User\Factory
 */
class BuildingTypeTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return BuildingTypeTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new BuildingType());
        $tableGateway = new TableGateway('building_types', $dbAdapter, null, $resultSetPrototype);

        return new BuildingTypeTable($tableGateway);
    }
}