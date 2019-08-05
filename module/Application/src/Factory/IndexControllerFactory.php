<?php
namespace Application\Factory;

use Admin\Model\SlidersTable;
use Application\Controller\IndexController;
use Application\Model\PageTable;
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
use User\Model\ParcelFeatureTable;
use User\Model\ParcelTypeTable;
use User\Model\PriceTable;
use User\Model\PropertyFeatureTable;
use User\Model\UserOfferListTable;
use User\Model\PropertyTypeTable;
use User\Model\TransactionTable;
use User\Model\UserTable;
use Admin\Model\ArticlesTable;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Helper\OglediTranslator;
use User\Model\NewsletterTable;
use Admin\Model\BlogCategoriesTable;
use Admin\Model\NewsCategoriesTable;
use Admin\Model\ServicesTable;
use Admin\Model\ServiceCategoriesTable;

/**
 * Factory for Index Controller
 *
 * Class UserControllerFactory
 * @package User\Factory
 */
class IndexControllerFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return OfferController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new IndexController(
            $container->get(UserTable::class), 
            $container->get(BlogCategoriesTable::class), 
            $container->get(NewsCategoriesTable::class), 
            $container->get(ServiceCategoriesTable::class),
            $container->get(OfferTypeTable::class), 
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
            $container->get(ArticlesTable::class),
            $container->get(GalleryTable::class),
            $container->get(PageTable::class),
            $container->get(OglediTranslator::class),
            $container->get(UserOfferListTable::class),
            $container->get(NewsletterTable::class),
            $container->get(ServicesTable::class),
            $container->get(SlidersTable::class)
        );
    }

}