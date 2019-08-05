<?php

namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Controller\ListController;
use User\Model\UserOfferListTable;
use User\Model\OfferTable;
use User\Model\UserTable;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Helper\OglediTranslator;
use Admin\Model\BlogCategoriesTable;
use Admin\Model\NewsCategoriesTable;
use Admin\Model\ServiceCategoriesTable;

/**
 * Description of ListControllerFactory
 *
 */
class ListControllerFactory implements FactoryInterface{
   
    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return OfferController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new ListController(
            $container->get(UserTable::class), 
            $container->get(BlogCategoriesTable::class), 
            $container->get(NewsCategoriesTable::class),
            $container->get(ServiceCategoriesTable::class),
            $container->get(OfferTable::class), 
            $container->get(AuthenticationService::class),
            $container->get(OglediTranslator::class),
            $container->get(UserOfferListTable::class)   
        );
    }
}
