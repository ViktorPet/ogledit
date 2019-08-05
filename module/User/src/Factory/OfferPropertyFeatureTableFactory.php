<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\OfferPropertyFeature;
use User\Model\OfferPropertyFeatureTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Offer Property Feature Table
 *
 * Class OfferPropertyFeatureTableFactory
 * @package User\Factory
 */
class OfferPropertyFeatureTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return OfferPropertyFeatureTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new OfferPropertyFeature());
        $tableGateway = new TableGateway('offer_property_features', $dbAdapter, null, $resultSetPrototype);

        return new OfferPropertyFeatureTable($tableGateway);
    }
}