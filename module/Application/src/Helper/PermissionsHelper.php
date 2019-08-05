<?php

namespace Application\Helper;

use Zend\Authentication\AuthenticationService;
use Zend\View\Helper\AbstractHelper;
use Zend\Session\Container;
use Admin\Model\PermissionTable;
/**
 * Layout helper for logged user details.
 */
class PermissionsHelper extends AbstractHelper {    

    protected $permissionTable;

    public function __construct(PermissionTable $permissionTable) {
        return $this->permissionTable = $permissionTable;
    }

    public function name() {
        $session = new Container('admin_permissions');
        $adminPermissions = $session->offsetGet('permissions');
        $allPermissions = $this->permissionTable->getPefmissionsDescriptions();        
        
        $adminPefmissionsDescriptions = array_intersect_key(
            $allPermissions, 
            array_flip($adminPermissions)
        );
        
        return $adminPefmissionsDescriptions;        
    }
    
}