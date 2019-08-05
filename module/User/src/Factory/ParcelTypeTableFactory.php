<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\ParcelType;
use User\Model\ParcelTypeTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Parcel Type Table
 *
 * Class PropertyTypeTableFactory
 * @package User\Factory
 */
class ParcelTypeTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ParcelTypeTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new ParcelType());
        $tableGateway = new TableGateway('parcel_types', $dbAdapter, null, $resultSetPrototype);

        return new ParcelTypeTable($tableGateway);
    }
}