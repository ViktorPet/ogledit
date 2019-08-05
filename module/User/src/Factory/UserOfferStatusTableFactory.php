<?php

namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\UserOfferStatus;
use User\Model\UserOfferStatusTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of UserOfferStatusTableFactory
 *
 */
class UserOfferStatusTableFactory implements FactoryInterface{
    
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
        $resultSetPrototype->setArrayObjectPrototype(new UserOfferStatus());
        $tableGateway = new TableGateway('user_offer_statuses', $dbAdapter, null, $resultSetPrototype);

        return new UserOfferStatusTable($tableGateway);
    }
    
}
