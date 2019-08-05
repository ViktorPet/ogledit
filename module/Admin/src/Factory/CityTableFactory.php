<?php
namespace Admin\Factory;

use Interop\Container\ContainerInterface;
use Admin\Model\City;
use Admin\Model\CityTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for City Table
 *
 * Class OfferTypeTableFactory
 * @package Admin\Factory
 */
class CityTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return CityTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new City());
        $tableGateway = new TableGateway('cities', $dbAdapter, null, $resultSetPrototype);

        return new CityTable($tableGateway);
    }
}