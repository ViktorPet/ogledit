<?php

namespace Application\Factory;

use Admin\Model\OffersTable;
use Application\Helper\LanguageHelper;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of StaticticsHelperFactory
 *
 * @author Krasimira Evgenieva
 */
class LanguageHelperFactory implements FactoryInterface{
    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthenticationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {        
        return new LanguageHelper();
    }
}
