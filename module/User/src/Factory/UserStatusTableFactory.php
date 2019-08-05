<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\UserStatus;
use User\Model\UserStatusTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for User Status Table
 *
 * Class UserStatusTableFactory
 * @package User\Factory
 */
class UserStatusTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserStatusTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new UserStatus());
        $tableGateway = new TableGateway('user_statuses', $dbAdapter, null, $resultSetPrototype);

        return new UserStatusTable($tableGateway);
    }
}