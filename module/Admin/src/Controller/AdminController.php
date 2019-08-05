<?php

namespace Admin\Controller;

use Admin\Form\LoginForm;
use Admin\Form\PhotographsForm;
use Admin\Form\ProfileForm;
use Admin\Form\BlogForm;
use Admin\Form\TeamForm;
use Admin\Model\AdminPermissionsTable;
use Admin\Model\PermissionTable;
use Admin\Model\Admin;
use Admin\Model\AdminTable;
use Admin\Model\AgenciesTable;
use Admin\Model\Articles;
use User\Model\City;
use User\Model\CityTable;
use Admin\Model\ArticlesTable;
/*use Admin\Model\City;
use Admin\Model\CityTable;*/
use Zend\Authentication\Adapter;
use Admin\Model\CategoriesTable;
use Admin\Model\LanguagesTable;
use Admin\Form\PagesForm;
use Admin\Model\PagesTable;
use Admin\Model\Pages;
use Admin\Form\PricesForm;
use Admin\Model\PricesTable;
use Admin\Model\Price;
use Application\Mapping\UserStatuses;
use User\Model\UserStatus;
use User\Model\UserStatusTable;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Debug\Debug;
use Application\Model\BaseTableModel;
use Zend\Db\Sql\Select;


/**
 * Class AdminController
 * @package Admin\Controller
 */
class AdminController extends BaseController {

    private $adminTable;
    private $adminPermissionsTable;
    private $agenciesTable;
    private $articlesTable;
    private $pagesTable;
    private $cityTable;
    private $pricesTable;
    private $categories;
    private $languages;
    private $userStatuses;
    private $permissionTable;
    protected $loginForm;
    protected $profileForm;
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
        PricesTable $pricesTable,
        ArticlesTable $articlesTable,
        CategoriesTable $categories,
        LanguagesTable $languages,
        UserStatusTable $userStatuses,
        PermissionTable $permissionTable,
        CityTable $cityTable
    ) {
        parent::__construct($authService, $permissionTable);
        $this->adminTable = $adminTable;
        $this->adminPermissionsTable = $adminPermissionsTable;
        $this->authService = $authService;
        $this->agenciesTable = $agenciesTable;
        $this->pagesTable = $pagesTable;
        $this->pricesTable = $pricesTable;
        $this->articlesTable = $articlesTable;
        $this->categories = $categories;
        $this->languages = $languages;
        $this->userStatuses = $userStatuses;
        $this->cityTable = $cityTable;
    }

    /**
     * Execute admin login
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function loginAction() {
        if ($this->authService->hasIdentity()) {
            return $this->redirect()->toRoute('languageRoute/adminDashboard');
        }

        $loginForm = new LoginForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $loginForm->setData($request->getPost());
            $form = $request->getPost()->toArray();

            /*   if ($this->isValidCaptcha($form['g-recaptcha-response'])) {  */
            if ($loginForm->isValid()) {
                $loginFormData = $loginForm->getData();
                $admin = $this->adminTable->findByEmail($loginFormData['username']);
                if ($admin) {
                    $passwordProvided = $loginFormData['password'];
                    $passwordInDb = $admin->getField('password');
                    $credentialCallback = function ($passwordInDb, $passwordProvided) {
                        $bcrypt = new Bcrypt();
                        return $bcrypt->verify($passwordProvided, $passwordInDb);
                    };

                    $authAdapter = $this->authService->getAdapter();
                    $authAdapter
                        ->setTableName('admins')
                        ->setIdentityColumn('email')
                        ->setCredentialColumn('password');
                    $authAdapter
                        ->setIdentity($loginFormData['username'])
                        ->setCredential($passwordProvided);

                    $authAdapter->setCredentialValidationCallback($credentialCallback);

                    $authenticate = $this->authService->authenticate($authAdapter);
                    if ($authenticate->isValid()) {
                        $adminPermissions = $this->adminPermissionsTable->findPermissionsByAdminId($admin->getField('id'));
                        $permissions = [];

                        foreach ($adminPermissions as $adminPermission) {
//                                echo '-';
                            $permissionId = $adminPermission->getField('permission_id');
                            $permissions[$permissionId] = $permissionId;
                        }
                        $session = new Container('user_type');
                        $session['user_type'] = 'admin';

                        $session = new Container('admin_permissions');
                        $session['adminType'] = 'admin';
                        $session['permissions'] = $permissions;

                        $this->adminTable->resetInvalidLoginCount($admin->getField('id'));

                        // Redirect to admin dashboard.
                        return $this->redirect()->toRoute('languageRoute/adminDashboard');
                    } else {
                        $this->adminTable->addInvalidLoginCount($admin->getField('id'));
                        if ($admin->getInvalidLoginCount() >= 2) {
                            $this->adminTable->changeAdminStatus($admin->getField('id'), UserStatuses::BLOCKED);
                        }
                    }
                }
                /*}*/
            }
        }

        $this->layout('admin/login');
        return new ViewModel(['form' => $loginForm]);
    }

    /**
     * Log out user
     *
     * @return \Zend\Http\Response
     */
    public function logoutAction() {
        $this->authService->clearIdentity();
        $session = new Container('admin_permissions');
        $session['adminType'] = null;
        $session['permissions'] = null;
        return $this->redirect()->toRoute('languageRoute/adminLogin');
    }

    public function calendarAction() {
        return new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function photographAction() {


        $cities = $this->cityTable->getTypesArray2();
        /* echo '<pre>';
         print_r($cities);

         echo '</pre>';*/

        //  Debug::dump($cities[0]);

        // $cityId = $city->getId();
        //  $cityData = $this->cityTable->getById($cityId);

        $photograpsForm = new PhotographsForm();

        $request = $this->getRequest();

        if ($request->isPost()){

            $city = new City();
            $posts = $this->getRequest()->getPost()->toArray();

            echo '<pre>';
            echo print_r ($posts, true);
            echo '</pre>';

            $photograpsForm->bind($city);

            $photograpsForm->setData($posts);

            $city = array();
            $post1 = array();
            $i =1;
            foreach ($posts as $post){



                    $post1[$i] = $post;
                    $i++;


            }


            for($i = 1; $i < 5; $i++) {

                $city[$i] = new City();
                $city[$i]->setId($i);
                $city[$i]->setNumber($post1[$i]);
                $this->cityTable->edit($city[$i]);

                //   $city[]

            }







             echo '<pre>';
             echo print_r ($post1, true);
             echo '</pre>';


            echo '<pre>';

            echo print_r ($city, true);
            echo '</pre>';
            // exit;
            // EDITED
           // $this->cityTable->edit(new City());

            if($photograpsForm->isValid()){
              /*  $city = array();
                for($i = 1; $i < 6; $i++) {*/

                    /* $city[$i] = new City();
                     $city[$i]->setId($i);*/

                    /*echo '<pre>';

                    echo print_r ($city[$i], true);
                    echo '</pre>';*/
                    //   $city[]
                   // $this->cityTable->edit($city[$i], $post1[$i]);
                }





            } else {

                // return $this->redirect()->toRoute('languageRoute/adminDashboard');
            }





        //Debug::dump($request);

        return new ViewModel( array(

            'form' => $photograpsForm,
            'cities' => $cities

        ));
    }
    public function editphotographAction() {


        //Debug::dump($request);

        return new ViewModel();
    }

    public function profileAction() {

        if ($this->authService->hasIdentity()) {
            $adminEmail = $this->authService->getIdentity();
            $admin = $this->adminTable->getIdByEmail($adminEmail);

            $adminId = $admin->getField('id');
            $adminData = $this->adminTable->getById($adminId);
            $profileForm = new ProfileForm();
            $profileForm->setData($adminData->toArray());

            $request = $this->getRequest();

            // Debug::dump($request);
            if ($request->isPost()) {
                $post = $this->getRequest()->getPost()->toArray();



                $editProfileForm = new ProfileForm();

                $editProfileForm->setData($post);
                if ($editProfileForm->isValid()) {
                    $data = $editProfileForm->getData();



                    $bcrypt = new Bcrypt();

                    if ($data['password'] != '') {
                        $data['password'] = $bcrypt->create($data['password']);
                        $data['password_confirm'] = $bcrypt->create($data['password_confirm']);
                    }

                    $admin = new Admin($data);
                    $admin->setField('id', $adminId);
                    $this->adminTable->updateProfile($admin);

                    return $this->redirect()->toRoute('languageRoute/adminDashboard');
                } else {
                    return new ViewModel(['form' => $editProfileForm]);
                }
            }

            return new ViewModel(['form' => $profileForm]);
        } else {
            return $this->redirect()->toRoute('languageRoute/adminDashboard');
        }
    }

}
