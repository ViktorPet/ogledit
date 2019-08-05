<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\Neighbourhood;
use User\Model\NeighbourhoodTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for City Table
 *
 * Class NeighbourhoodTableFactory
 * @package User\Factory
 */
class NeighbourhoodTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return NeighbourhoodTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Neighbourhood());
        $tableGateway = new TableGateway('neighbourhoods', $dbAdapter, null, $resultSetPrototype);

        return new NeighbourhoodTable($tableGateway);
    }
}