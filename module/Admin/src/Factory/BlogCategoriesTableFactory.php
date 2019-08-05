<?php

namespace Admin\Factory;

use Admin\Model\BlogCategories;
use Admin\Model\BlogCategoriesTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of BlogCategoriesTableFactory
 *
 */
class BlogCategoriesTableFactory implements FactoryInterface {

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
        $resultSetPrototype->setArrayObjectPrototype(new BlogCategories());
        $tableGateway = new TableGateway('blog_categories', $dbAdapter, null, $resultSetPrototype);
        return new BlogCategoriesTable($tableGateway);
    }

}
