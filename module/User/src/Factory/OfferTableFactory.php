<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use Application\Model\Offer;
use User\Model\OfferTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Offer Type Table
 *
 * Class OfferTableFactory
 * @package User\Factory
 */
class OfferTableFactory implements FactoryInterface {

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
        $resultSetPrototype->setArrayObjectPrototype(new Offer());
        $tableGateway = new TableGateway('offers', $dbAdapter, null, $resultSetPrototype);

        return new OfferTable($tableGateway);
    }
}