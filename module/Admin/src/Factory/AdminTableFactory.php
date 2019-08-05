<?php
namespace Admin\Factory;

use Admin\Model\Admin;
use Admin\Model\AdminTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Admin Table
 *
 * Class AdminTableFactory
 * @package Admin\Factory
 */
class AdminTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AdminTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Admin());
        $tableGateway = new TableGateway('admins', $dbAdapter, null, $resultSetPrototype);

        return new AdminTable($tableGateway);
    }

}