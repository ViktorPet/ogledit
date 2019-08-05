<?php

namespace Admin\Factory;

use Admin\Model\NewsCategories;
use Admin\Model\NewsCategoriesTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of NewsCategoriesTableFactory
 *
 */
class NewsCategoriesTableFactory implements FactoryInterface {

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
        $resultSetPrototype->setArrayObjectPrototype(new NewsCategories());
        $tableGateway = new TableGateway('news_categories', $dbAdapter, null, $resultSetPrototype);
        return new NewsCategoriesTable($tableGateway);
    }

}
