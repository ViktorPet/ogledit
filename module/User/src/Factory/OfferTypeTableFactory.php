<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\OfferType;
use User\Model\OfferTypeTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Offer Type Table
 *
 * Class OfferTypeTableFactory
 * @package User\Factory
 */
class OfferTypeTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return OfferTypeTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new OfferType());
        $tableGateway = new TableGateway('offer_types', $dbAdapter, null, $resultSetPrototype);

        return new OfferTypeTable($tableGateway);
    }
}