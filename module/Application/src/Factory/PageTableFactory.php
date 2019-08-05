<?php
namespace Application\Factory;

use Application\Model\Page;
use Application\Model\PageTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Page Table
 *
 * Class PageTableFactory
 * @package User\Factory
 */
class PageTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PageTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Page());
        $tableGateway = new TableGateway('pages', $dbAdapter, null, $resultSetPrototype);

        return new PageTable($tableGateway);
    }
}