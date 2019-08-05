<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\HeatingSystem;
use User\Model\HeatingSystemTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Heating System Table
 *
 * Class HeatingSystemTableFactory
 * @package User\Factory
 */
class HeatingSystemTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return HeatingSystemTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new HeatingSystem());
        $tableGateway = new TableGateway('heating_systems', $dbAdapter, null, $resultSetPrototype);

        return new HeatingSystemTable($tableGateway);
    }
}