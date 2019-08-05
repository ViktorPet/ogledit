<?php
namespace Admin\Factory;

use Admin\Model\Permission;
use Admin\Model\PermissionTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Permission Table
 *
 * Class PermissionTableFactory
 * @package Admin\Factory
 */
class PermissionTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PermissionTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Permission());
        $tableGateway = new TableGateway('permissions', $dbAdapter, null, $resultSetPrototype);

        return new PermissionTable($tableGateway);
    }

}