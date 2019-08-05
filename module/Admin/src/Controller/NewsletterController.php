<?php

namespace Admin\Controller;

use Admin\Model\AdminPermissionsTable;
use Admin\Model\PermissionTable;
use Admin\Model\AdminTable;
use Admin\Model\LanguagesTable;
use Admin\Model\Admin;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use User\Model\NewsletterTable;

/**
 * Description of NewsletterController
 *
 */
class NewsletterController extends BaseController {

    private $adminTable;
    private $adminPermissionsTable;
    private $languages;
    private $permissionTable;
    private $newsletterTable;

    /**
     * NewsletterController constructor.
     */
    public function __construct(
    AdminTable $adminTable, AdminPermissionsTable $adminPermissionsTable, AuthenticationService $authService, LanguagesTable $languages, PermissionTable $permissionTable, NewsletterTable $newsletterTable
    ) {
        parent::__construct($authService, $permissionTable);
        $this->adminTable = $adminTable;
        $this->adminPermissionsTable = $adminPermissionsTable;
        $this->authService = $authService;
        $this->languages = $languages;
        $this->permissionTable = $permissionTable;
        $this->newsletterTable = $newsletterTable;
    }

    public function newsletterAction() {
        return new ViewModel();
    }

    public function newsletterDataAction() {
        return $this->getJSONTableGridData($this->newsletterTable);
    }

}
