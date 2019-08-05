<?php
namespace Admin\Factory;

use Admin\Model\AdminPermissions;
use Admin\Model\AdminPermissionsTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Admin Permission Table
 *
 * Class AdminPermissionsTableFactory
 * @package Admin\Factory
 */
class AdminPermissionsTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AdminPermissionsTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new AdminPermissions());
        $tableGateway = new TableGateway('admin_permissions', $dbAdapter, null, $resultSetPrototype);

        return new AdminPermissionsTable($tableGateway);
    }

}