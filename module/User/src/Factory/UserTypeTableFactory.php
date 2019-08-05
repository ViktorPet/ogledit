<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\UserType;
use User\Model\UserTypeTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for User Type Table
 *
 * Class UserStatusTableFactory
 * @package User\Factory
 */
class UserTypeTableFactory implements FactoryInterface {

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
        $resultSetPrototype->setArrayObjectPrototype(new UserType());
        $tableGateway = new TableGateway('user_types', $dbAdapter, null, $resultSetPrototype);

        return new UserTypeTable($tableGateway);
    }
}