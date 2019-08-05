<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Controller\UserController;
use User\Model\UserTable;
use User\Model\UserTypeTable;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Helper\OglediTranslator;
use Admin\Model\BlogCategoriesTable;
use Admin\Model\NewsCategoriesTable;
use Admin\Model\ServiceCategoriesTable;

/**
 * Factory for User Controller
 *
 * Class UserControllerFactory
 * @package User\Factory
 */
class UserControllerFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new UserController(
            $container->get(UserTable::class),
            $container->get(BlogCategoriesTable::class), 
            $container->get(NewsCategoriesTable::class), 
            $container->get(ServiceCategoriesTable::class),
            $container->get(UserTypeTable::class),
            $container->get(AuthenticationService::class),
            $container->get(OglediTranslator::class)
        );
    }

}