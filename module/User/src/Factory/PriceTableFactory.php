<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\Price;
use User\Model\PriceTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Price Table
 *
 * Class OfferTableFactory
 * @package User\Factory
 */
class PriceTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PriceTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Price());
        $tableGateway = new TableGateway('prices', $dbAdapter, null, $resultSetPrototype);

        return new PriceTable($tableGateway);
    }
}