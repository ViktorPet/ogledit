<?php
namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\Gallery;
use User\Model\GalleryTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Gallery Table
 *
 * Class GalleryTableFactory
 * @package User\Factory
 */
class GalleryTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return GalleryTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Gallery());
        $tableGateway = new TableGateway('gallery', $dbAdapter, null, $resultSetPrototype);

        return new GalleryTable($tableGateway);
    }
}