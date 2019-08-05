<?php
namespace Admin\Factory;

use Admin\Model\Agencies;
use Admin\Model\AgenciesTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Agencies Table
 *
 * Class AgenciesTableFactory
 * @package Admin\Factory
 */
class AgenciesTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AgenciesTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Agencies());
        $tableGateway = new TableGateway('users', $dbAdapter, null, $resultSetPrototype);                
        return new AgenciesTable($tableGateway);
    }

}