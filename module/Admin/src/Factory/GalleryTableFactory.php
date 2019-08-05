<?php

namespace Admin\Factory;

use Admin\Model\Gallery;
use Admin\Model\GalleryTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of OffersTableFactory
 *
 */
class GalleryTableFactory implements FactoryInterface{
    
    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PagesTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Gallery());
        $tableGateway = new TableGateway('gallery', $dbAdapter, null, $resultSetPrototype);                
        return new GalleryTable($tableGateway);
    }
    
}
