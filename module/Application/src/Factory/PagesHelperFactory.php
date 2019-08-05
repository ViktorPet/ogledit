<?php

namespace Application\Factory;

use Application\Helper\PagesHelper;
use Application\Helper\StatisticsHelper;
use Application\Model\PageTable;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of PagesHelperFactory
 */
class PagesHelperFactory implements FactoryInterface {
    
    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthenticationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $pageTable = $container->get(PageTable::class);
        return new PagesHelper($pageTable);
    }
}
