<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\User;
use User\Model\UserTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for User Table
 *
 * Class UserTableFactory
 * @package User\Factory
 */
class UserTableFactory implements FactoryInterface {

    /**
     * Inject services
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new User());
        $tableGateway = new TableGateway('users', $dbAdapter, null, $resultSetPrototype);

        return new UserTable($tableGateway);
    }

}