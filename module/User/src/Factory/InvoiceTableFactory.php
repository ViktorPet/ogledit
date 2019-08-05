<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\Invoice;
use User\Model\InvoiceTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Invoice Table
 *
 * Class InvoiceTable
 * @package User\Factory
 */
class InvoiceTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return InvoiceTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Invoice());
        $tableGateway = new TableGateway('invoices', $dbAdapter, null, $resultSetPrototype);

        return new InvoiceTable($tableGateway);
    }
}