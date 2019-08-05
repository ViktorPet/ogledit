<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\OfferParcelFeature;
use User\Model\OfferParcelFeatureTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Offer Parcel Feature Table
 *
 * Class OfferParcelFeatureTableFactory
 * @package User\Factory
 */
class OfferParcelFeatureTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return OfferParcelFeatureTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new OfferParcelFeature());
        $tableGateway = new TableGateway('offer_parcel_features', $dbAdapter, null, $resultSetPrototype);

        return new OfferParcelFeatureTable($tableGateway);
    }
}