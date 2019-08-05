<?php
namespace Admin\Factory;

use Admin\Model\Languages;
use Admin\Model\LanguagesTable;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Categories Table
 *
 * Class LanguagesTableFactory
 * @package Admin\Factory
 */
class LanguagesTableFactory implements FactoryInterface {

    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return LanguagesTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Languages());
        $tableGateway = new TableGateway('languages', $dbAdapter, null, $resultSetPrototype);                
        return new LanguagesTable($tableGateway);
    }

}