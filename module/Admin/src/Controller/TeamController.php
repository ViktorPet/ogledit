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
class TeamController extends BaseController {

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

    public function teamAction() {
        return new ViewModel();
    }

    public function teamDataAction() {
        return $this->getJSONTableGridData($this->adminTable);
    }

    public function teamCreateAction() {
        $teamForm = new TeamForm('teamForm', $this->userStatuses->getTypesArray(), $this->adminTable);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $this->getRequest()->getPost()->toArray();
            $teamForm->setData($post);
            if ($teamForm->isValid()) {
                $data = $teamForm->getData();
                $bcrypt = new Bcrypt();
                $data['password'] = $bcrypt->create($data['password']);
                $admin = new Admin($data);
                $this->adminTable->create($admin);

                return $this->redirect()->toRoute('languageRoute/adminTeam');
            }
        }

        return new ViewModel(['form' => $teamForm]);
    }

    public function teamEditAction() {
        $adminId = $this->params()->fromRoute('id', '');
        $adminData = $this->adminTable->getById($adminId);
        $teamForm = new TeamForm('EditTeam', $this->userStatuses->getTypesArray(), $this->adminTable, $adminId);
        $teamForm->setData($adminData->toArray());
        $request = $this->getRequest();        
        if ($request->isPost()) {
            $post = $this->getRequest()->getPost()->toArray();            
            $editTeamForm = new TeamForm('EditTeam', $this->userStatuses->getTypesArray(), $this->adminTable, $adminId);
            $editTeamForm->setData($post);
            if ($editTeamForm->isValid()) {
                $data = $editTeamForm->getData();                
                $bcrypt = new Bcrypt();

                $data['password'] = $bcrypt->create($data['password']);
                $admin = new Admin($data);
                $admin->setField('id', $adminId);                
                $this->adminTable->edit($admin);

                return $this->redirect()->toRoute('languageRoute/adminTeam');
            } else {
                return new ViewModel(['form' => $editTeamForm]);
            }
        }

        return new ViewModel(['form' => $teamForm]);
    }

    public function teamDeleteAction() {
        $adminId = $this->params()->fromRoute('id', '');
        if($adminId == 1)
        {
            return $this->redirect()->toRoute('languageRoute/adminTeam');
        }        
        try {
            $admin = $this->adminTable->getById($adminId);

            $this->adminTable->delete($adminId);
        } catch (InvalidQueryException $e) {
            $this->flashMessenger()->addErrorMessage('This admin cannot be deleted');
        }

        return $this->redirect()->toRoute('languageRoute/adminTeam');
    }   

}
