<?php

namespace Application\Helper;

use Zend\Authentication\AuthenticationService;
use Zend\View\Helper\AbstractHelper;

/**
 * Layout helper for logged user details.
 */
class AuthenticationHelper extends AbstractHelper {

    private $service;

    function __construct(AuthenticationService $authenticationService) {
        $this->service = $authenticationService;
    }

    public function isUserLogged() {
        return '234234';
    }

    public function isAdminLogged() {
        return '234234';
    }

    /**
     * @return AuthenticationService
     */
    public function getService() {
        return $this->service;
    }

    /**
     * @param AuthenticationService $service
     */
    public function setService($service) {
        $this->service = $service;
    }
    
}