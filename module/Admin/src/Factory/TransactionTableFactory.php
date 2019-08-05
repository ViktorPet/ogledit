<?php

namespace Admin\Factory;

use Admin\Model\Transaction;
use Admin\Model\TransactionTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Transaction Table
 *
 * Class OfferTypeTableFactory
 * @package Admin\Factory
 */
class TransactionTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PricesTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Transaction());
        $tableGateway = new TableGateway('transactions', $dbAdapter, null, $resultSetPrototype);                
        return new TransactionTable($tableGateway);
    }
    
}