<?php

namespace Admin\Factory;

use Interop\Container\ContainerInterface;
use Admin\Controller\OffersController;
use Admin\Model\OffersTable;
use User\Model\BuildingTypeTable;
use User\Model\CityTable;
use User\Model\CurrencyTable;
use User\Model\HeatingSystemTable;
use User\Model\NeighbourhoodTable;
use User\Model\OfferTypeTable;
use User\Model\UserOfferStatusTable;
use User\Model\PriceTable;
use User\Model\PropertyFeatureTable;
use User\Model\PropertyTypeTable;
use User\Model\TransactionTable;
use User\Model\OfferTable;
use User\Model\UserTable;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Model\AdminPermissionsTable;
use Admin\Model\PermissionTable;
use User\Model\OfferParcelFeatureTable;
use User\Model\OfferPropertyFeatureTable;
use User\Model\ParcelFeatureTable;
use User\Model\ParcelTypeTable;
use Admin\Model\GalleryTable;
use User\Model\OfferStatusTable;
use Admin\Model\AdminTable;


/**
 * Description of OffersControllerFactory
 *
 */
class OffersControllerFactory implements FactoryInterface{
    
    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return OfferController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new OffersController(
            $container->get(UserTable::class), 
            $container->get(OfferTypeTable::class), 
            $container->get(UserOfferStatusTable::class), 
            $container->get(OfferStatusTable::class), 
            $container->get(CityTable::class), 
            $container->get(NeighbourhoodTable::class), 
            $container->get(PropertyTypeTable::class), 
            $container->get(BuildingTypeTable::class), 
            $container->get(HeatingSystemTable::class), 
            $container->get(CurrencyTable::class), 
            $container->get(PropertyFeatureTable::class), 
            $container->get(OfferTable::class),   
            $container->get(TransactionTable::class), 
            $container->get(PriceTable::class), 
            $container->get(AuthenticationService::class),
            $container->get(OffersTable::class),
            $container->get(AdminPermissionsTable::class),
            $container->get(OfferPropertyFeatureTable::class), 
            $container->get(ParcelTypeTable::class), 
            $container->get(ParcelFeatureTable::class), 
            $container->get(OfferParcelFeatureTable::class), 
            $container->get(PermissionTable::class),
            $container->get(GalleryTable::class),
            $container->get(AdminTable::class)
        );
    }
    
}
