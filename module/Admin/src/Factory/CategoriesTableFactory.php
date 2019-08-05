<?php
namespace Admin\Factory;

use Admin\Model\Categories;
use Admin\Model\CategoriesTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Categories Table
 *
 * Class CategoriesTableFactory
 * @package Admin\Factory
 */
class CategoriesTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return CategoriesTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Categories());
        $tableGateway = new TableGateway('categories', $dbAdapter, null, $resultSetPrototype);                
        return new CategoriesTable($tableGateway);
    }

}