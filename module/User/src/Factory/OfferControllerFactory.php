<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Controller\OfferController;
use User\Model\BuildingTypeTable;
use User\Model\CityTable;
use User\Model\CurrencyTable;
use User\Model\GalleryTable;
use User\Model\HeatingSystemTable;
use User\Model\NeighbourhoodTable;
use User\Model\OfferParcelFeatureTable;
use User\Model\OfferPropertyFeatureTable;
use User\Model\OfferTable;
use User\Model\OfferTypeTable;
use User\Model\UserOfferStatusTable;
use User\Model\ParcelFeatureTable;
use User\Model\ParcelTypeTable;
use User\Model\PriceTable;
use User\Model\PropertyFeatureTable;
use User\Model\PropertyTypeTable;
use User\Model\TransactionTable;
use User\Model\UserTable;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Helper\OglediTranslator;
use Admin\Model\BlogCategoriesTable;
use Admin\Model\NewsCategoriesTable;
use Admin\Model\ServiceCategoriesTable;

/**
 * Factory for Offer Controller
 *
 * Class UserControllerFactory
 * @package User\Factory
 */
class OfferControllerFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return OfferController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new OfferController(
            $container->get(UserTable::class), 
            $container->get(BlogCategoriesTable::class), 
            $container->get(NewsCategoriesTable::class),
            $container->get(ServiceCategoriesTable::class),
            $container->get(OfferTypeTable::class), 
            $container->get(UserOfferStatusTable::class), 
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
            $container->get(OfferPropertyFeatureTable::class), 
            $container->get(ParcelTypeTable::class), 
            $container->get(ParcelFeatureTable::class), 
            $container->get(OfferParcelFeatureTable::class), 
            $container->get(AuthenticationService::class),
            $container->get(OglediTranslator::class),
            $container->get(GalleryTable::class)
        );
    }

}