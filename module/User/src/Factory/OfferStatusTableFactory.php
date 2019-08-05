<?php

namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\OfferStatus;
use User\Model\OfferStatusTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of OfferStatusTableFactory
 */
class OfferStatusTableFactory implements FactoryInterface{
  
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
        $resultSetPrototype->setArrayObjectPrototype(new OfferStatus());
        $tableGateway = new TableGateway('offer_statuses', $dbAdapter, null, $resultSetPrototype);

        return new OfferStatusTable($tableGateway);
    }
    
}
