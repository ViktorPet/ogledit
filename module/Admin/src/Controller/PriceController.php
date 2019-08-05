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
use Admin\Form\PricesForm;
use Admin\Model\PricesTable;
use Admin\Model\Price;
use User\Model\UserType;
use User\Model\UserTypeTable;
use Application\Mapping\UserStatuses;
use User\Model\UserStatus;
use User\Model\UserStatusTable;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Description of PriceController
 *
 */
class PriceController extends BaseController {

    private $adminTable;
    private $adminPermissionsTable;
    private $agenciesTable;
    private $articlesTable;
    private $pagesTable;
    private $pricesTable;
    private $categories;
    private $languages;
    private $userStatuses;
    private $userTypes;
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
            PricesTable $pricesTable,
            ArticlesTable $articlesTable, 
            CategoriesTable $categories, 
            LanguagesTable $languages, 
            UserStatusTable $userStatuses, 
            UserTypeTable $userTypes,
            PermissionTable $permissionTable
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
        $this->userTypes = $userTypes;       
    }

    public function pricesAction() {
        return new ViewModel();
    }

    public function pricesDataAction() {
        return $this->getJSONTableGridData($this->pricesTable);
    }

    public function pricesCreateAction() {

        $pricesForm = new PricesForm($this->userTypes->getTypesArray());
        $request = $this->getRequest();

        if ($request->isPost()) {
            $pricesForm->setData($request->getPost());

            if ($pricesForm->isValid()) {
                $pricesFormData = $pricesForm->getData();
                $price = new Price($pricesFormData);
                $this->pricesTable->create($price);

                return $this->redirect()->toRoute('languageRoute/adminPrices');
            }
        }
        return new ViewModel(['form' => $pricesForm]);
    }

    public function pricesEditAction() {
        
        $priceId = $this->params()->fromRoute('id', '');
        $price = $this->pricesTable->getPriceById($priceId);
               
        $pricesForm = new PricesForm($this->userTypes->getTypesArray());

        if ($this->getRequest()->isPost()) {
            $pricesForm->setData($this->getRequest()->getPost()->toArray());

            if ($pricesForm->isValid()) {
                $pricesFormData = $pricesForm->getData();
                $price = new Price($pricesFormData);
                $price->setField('id', $priceId);
                $this->pricesTable->edit($price);

                return $this->redirect()->toRoute('languageRoute/adminPrices');
            }
        } else {
            // Loads page data
            $price = $this->pricesTable->getPriceById($priceId);
            if ($price) {
                $pricesForm->setData($price->toArray());
            } else {
                return $this->redirect()->toRoute('languageRoute/adminPrices');
            }
        }

        return new ViewModel(['form' => $pricesForm]);
        
        
    }

    public function pricesDeleteAction() {
        
        $priceId = $this->params()->fromRoute('id', '');

        try {
            $this->pricesTable->delete($priceId);
        } catch (InvalidQueryException $e) {
            $this->flashMessenger()->addErrorMessage('This price cannot be deleted');
        }

        return $this->redirect()->toRoute('languageRoute/adminPrices');
        
        
    }

}
