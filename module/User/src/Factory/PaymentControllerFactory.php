<?php
namespace User\Factory;

use Application\Helper\LanguageHelper;
use Interop\Container\ContainerInterface;
use User\Controller\PaymentController;
use User\Model\InvoiceTable;
use User\Model\OfferTable;
use User\Model\PriceTable;
use User\Model\TransactionTable;
use User\Model\UserTable;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Model\BlogCategoriesTable;
use Admin\Model\NewsCategoriesTable;
use Admin\Model\ServiceCategoriesTable;

/**
 * Factory for Payment Controller
 *
 * Class UserControllerFactory
 * @package User\Factory
 */
class PaymentControllerFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PaymentControllerFactory
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new PaymentController(
            $container->get(UserTable::class), 
            $container->get(BlogCategoriesTable::class), 
            $container->get(NewsCategoriesTable::class),
            $container->get(ServiceCategoriesTable::class),
            $container->get(OfferTable::class), 
            $container->get(TransactionTable::class), 
            $container->get(PriceTable::class), 
            $container->get(InvoiceTable::class), 
            $container->get(AuthenticationService::class),
            $container->get(LanguageHelper::class));
    }

}