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

use Zend\View\Model\JsonModel;
use Application\Model\Base\BaseGridSettings;
/**
 * Class AdminController
 * @package Admin\Controller
 */
class AdminPermissionsController extends BaseController {

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
        $this->permissionTable = $permissionTable;
    }   

    public function permissionsAction() {
        return new ViewModel();
    }

    public function adminPermissionsTestDataAction() {
       $permissions = $this->permissionTable->getPermissionsForGridArray();
       if ($this->getRequest()->isXmlHttpRequest()) {                             
            return new JsonModel(array('permissions' => $permissions));
        } else {
            return $this->redirect()->toRoute('languageRoute/adminPermissions');
        }
    }
    
    public function permissionsDataAction() {
        $gridSettings = new BaseGridSettings($this->params()->fromQuery());                        
        $admins = $this->adminTable->getData($gridSettings, 'undefined', FALSE)->toArray();
        
        // REMOVE THE ADMIN WITH ID 1 FROM THE GRID        
        foreach ($admins as $key => $admin) {  
            if($admin['id'] == 1)
            {
                unset($admins[$key]);
                break;
            }
        }
        
        $count = $items = $this->adminTable->getCount($gridSettings);
        $data = $adminIDs = [];
        if ($count > 0) {
            $permissions = $this->permissionTable->getPermissionsForGridArray();
            
            foreach ($admins as $admin) {                
                $adminIDs[] = $admin['id'];
                $data[$admin['id']]['admin_id'] = $admin['id'];                
                $data[$admin['id']]['first_name'] = $admin['first_name'];
                $data[$admin['id']]['last_name'] = $admin['last_name'];        
                
                foreach ($permissions as $key => $permission) { 
                    $data[$admin['id']][$permission] = 0;        
                }
            }            
            $adminPermissions = $this->adminPermissionsTable->getData($gridSettings, $adminIDs, FALSE)->toArray();

            foreach ($adminPermissions as $adminPermission) {                
                $data[$adminPermission['admin_id']][$permissions[$adminPermission['permission_id']]] = 1;
            }
        }

        return new JsonModel(array(
            array(
                'TotalRows' => $count,
                'Rows' => array_values($data)
            )
        ));             
    }

    public function permissionsEditAction() {
        $id = $this->params()->fromRoute('id', '');
        $columnfield = $this->params()->fromRoute('columnfield', '');
        $value = $this->params()->fromRoute('value', '');

        $permission = $this->permissionTable->getPermissionByDescription($columnfield);

        if(!is_null($permission)) {
            $adminPermission = $this->adminPermissionsTable->checkIfExist($id, $permission->getId());
            if(!is_null($adminPermission)) {
                // DELETE
                $this->adminPermissionsTable->deleteByAdminIdAndPermissionId($id, $permission->getId());
            }
            else {
                // CREATE
                $this->adminPermissionsTable->createByAdminIdAndPermissionId($id, $permission->getId());
            }
        }
        
        if ($this->getRequest()->isXmlHttpRequest()) {                             
            return new JsonModel();
        } else {
            return $this->redirect()->toRoute('languageRoute/adminPermissions');
        }
    }
    
}
