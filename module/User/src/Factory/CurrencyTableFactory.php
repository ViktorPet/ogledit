<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\Currency;
use User\Model\CurrencyTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Currencies Table
 *
 * Class CurrencyTableFactory
 * @package User\Factory
 */
class CurrencyTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return CurrencyTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Currency());
        $tableGateway = new TableGateway('currencies', $dbAdapter, null, $resultSetPrototype);

        return new CurrencyTable($tableGateway);
    }
}