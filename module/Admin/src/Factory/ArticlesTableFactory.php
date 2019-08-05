<?php
namespace Admin\Factory;

use Admin\Model\Articles;
use Admin\Model\ArticlesTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Articles Table
 *
 * Class ArticlesTableFactory
 * @package Admin\Factory
 */
class ArticlesTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ArticlesTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Articles());
        $tableGateway = new TableGateway('articles', $dbAdapter, null, $resultSetPrototype);                
        return new ArticlesTable($tableGateway);
    }

}