<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\PropertyFeature;
use User\Model\PropertyFeatureTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Property Feature Type Table
 *
 * Class PropertyFeatureTableFactory
 * @package User\Factory
 */
class PropertyFeatureTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PropertyFeatureTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new PropertyFeature());
        $tableGateway = new TableGateway('property_features', $dbAdapter, null, $resultSetPrototype);

        return new PropertyFeatureTable($tableGateway);
    }
}