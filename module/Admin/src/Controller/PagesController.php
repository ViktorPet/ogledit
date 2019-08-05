<?php

namespace Admin\Controller;

use Admin\Form\LoginForm;
use Admin\Form\BlogForm;
use Admin\Form\TeamForm;
use Admin\Model\AdminPermissionsTable;
use Admin\Model\PermissionTable;
use Admin\Model\AdminTable;
use Admin\Model\AgenciesTable;
use Admin\Model\ArticlesTable;
use Admin\Model\CategoriesTable;
use Admin\Model\LanguagesTable;
use Admin\Model\Articles;
use Admin\Model\Admin;
use Admin\Form\PagesForm;
use Admin\Model\PagesTable;
use Admin\Model\Pages;
use Application\Mapping\UserStatuses;
use User\Model\UserStatus;
use User\Model\UserStatusTable;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Class AdminController
 * @package Admin\Controller
 */
class PagesController extends BaseController {

    private $adminTable;
    private $adminPermissionsTable;
    private $agenciesTable;
    private $articlesTable;
    private $pagesTable;
    private $categories;
    private $languages;
    private $userStatuses;
    private $permissionTable;
    protected $loginForm;
    protected $pagesForm;

    /**
     * AdminController constructor.
     * @param AdminTable $adminTable
     * @param AdminPermissionsTable $adminPermissionsTable
     * @param AuthenticationService $authService
     */
    public function __construct(
            AdminTable $adminTable, 
            AdminPermissionsTable $adminPermissionsTable, 
            AuthenticationService $authService, 
            AgenciesTable $agenciesTable, 
            PagesTable $pagesTable, 
            ArticlesTable $articlesTable, 
            CategoriesTable $categories, 
            LanguagesTable $languages, 
            UserStatusTable $userStatuses,
            PermissionTable $permissionTable
    ) {
        parent::__construct($authService, $permissionTable);
        $this->adminTable = $adminTable;
        $this->adminPermissionsTable = $adminPermissionsTable;
        $this->authService = $authService;
        $this->agenciesTable = $agenciesTable;
        $this->articlesTable = $articlesTable;
        $this->pagesTable = $pagesTable;
        $this->categories = $categories;
        $this->languages = $languages;
        $this->userStatuses = $userStatuses;
    }   

    public function pagesAction() {
        return new ViewModel();
    }

    public function pagesDataAction() {
        return $this->getJSONTableGridData($this->pagesTable);
    }

    public function pagesCreateAction() {

        $pagesForm = new PagesForm($this->languages->getTypesArray());
        $request = $this->getRequest();

        if ($request->isPost()) {
            $pagesForm->setData($request->getPost());

            if ($pagesForm->isValid()) {
                $pagesFormData = $pagesForm->getData();
                $pagesFormData['url'] = str_replace(' ', '-', $pagesFormData['title']);       
                $page = new Pages($pagesFormData);
                $this->pagesTable->create($page);

                return $this->redirect()->toRoute('languageRoute/adminPages');
            }
        }
        return new ViewModel(['form' => $pagesForm]);
    }

    public function pagesEditAction() {

        $pageId = $this->params()->fromRoute('id', '');
        $pagesForm = new PagesForm($this->languages->getTypesArray());

        if ($this->getRequest()->isPost()) {
            $pagesForm->setData($this->getRequest()->getPost()->toArray());

            if ($pagesForm->isValid()) {
                $pagesFormData = $pagesForm->getData();
                $pagesFormData['url'] = str_replace(' ', '-', $pagesFormData['title']);   
                $page = new Pages($pagesFormData);
                $page->setField('id', $pageId);
                $this->pagesTable->edit($page);

                return $this->redirect()->toRoute('languageRoute/adminPages');
            }
        } else {
            // Loads page data
            $page = $this->pagesTable->getPageById($pageId);
            if ($page) {
                $pagesForm->setData($page->toArray());
            } else {
                return $this->redirect()->toRoute('languageRoute/adminPages');
            }
        }

        return new ViewModel(['form' => $pagesForm]);
    }

    public function pagesDeleteAction() {

        $pageId = $this->params()->fromRoute('id', '');

        try {
            $this->pagesTable->delete($pageId);
        } catch (InvalidQueryException $e) {
            $this->flashMessenger()->addErrorMessage('This page cannot be deleted');
        }

        return $this->redirect()->toRoute('languageRoute/adminPages');
    }
}
