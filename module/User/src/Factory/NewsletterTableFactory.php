<?php

namespace User\Factory;

use Interop\Container\ContainerInterface;
use User\Model\Newsletter;
use User\Model\NewsletterTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of MailHistoryTableFactory
 *
 */
class NewsletterTableFactory implements FactoryInterface {
    
    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return OfferTypeTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Newsletter());
        $tableGateway = new TableGateway('newsletter', $dbAdapter, null, $resultSetPrototype);

        return new NewsletterTable($tableGateway);
    }
}
