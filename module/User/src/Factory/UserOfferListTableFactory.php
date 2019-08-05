<?php

namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\UserOfferList;
use User\Model\UserOfferListTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of UserOfferListTableFactory
 *
 */
class UserOfferListTableFactory implements FactoryInterface {
   
    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return OfferTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new UserOfferList());
        $tableGateway = new TableGateway('user_offer_lists', $dbAdapter, null, $resultSetPrototype);

        return new UserOfferListTable($tableGateway);
    }
}
