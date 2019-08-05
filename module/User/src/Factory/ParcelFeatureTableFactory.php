<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\ParcelFeature;
use User\Model\ParcelFeatureTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Parcel Feature Table
 *
 * Class PropertyFeatureTableFactory
 * @package User\Factory
 */
class ParcelFeatureTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ParcelFeatureTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new ParcelFeature());
        $tableGateway = new TableGateway('parcel_features', $dbAdapter, null, $resultSetPrototype);

        return new ParcelFeatureTable($tableGateway);
    }
}