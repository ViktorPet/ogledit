<?php
namespace Application\Factory;

use Application\Mapping\UserStatuses;
use Interop\Container\ContainerInterface;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as Storage;
use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for Authentication Service
 *
 * Class AuthenticationServiceFactory
 * @package Application\Factory
 */
class AuthenticationServiceFactory implements FactoryInterface {
    
    /**
     * Inject services
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AuthenticationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $config = $container->get('configuration');
        $dbAdapter = new DbAdapter($config['db']);

        $authAdapter = new AuthAdapter($dbAdapter);
        $authAdapter->setTableName('users')->setIdentityColumn('email')->setCredentialColumn('password');

        $select = $authAdapter->getDbSelect();
        $select->where('user_status_id = ' . UserStatuses::APPROVED);

        $storage = new Storage();

        return new AuthenticationService($storage, $authAdapter);
    }
}