<?php
namespace Application\Factory;

use Application\Helper\AuthenticationHelper;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Authentication Service
 *
 * Class AuthenticationServiceFactory
 * @package Application\Factory
 */
class AuthenticationHelperFactory implements FactoryInterface {
    
    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthenticationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new AuthenticationHelper($container->get(AuthenticationService::class));
    }
}