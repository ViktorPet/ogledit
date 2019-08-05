<?php
namespace Admin\Factory;

use Admin\Model\Invoice;
use Admin\Model\InvoicesTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Invoices Table
 *
 * Class InvoicesTableFactory
 * @package Admin\Factory
 */
class InvoicesTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return InvoicesTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Invoice());
        $tableGateway = new TableGateway('invoices', $dbAdapter, null, $resultSetPrototype);                
        return new InvoicesTable($tableGateway);
    }

}