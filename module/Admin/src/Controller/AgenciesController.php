<?php

namespace Admin\Controller;

use Admin\Form\LoginForm;
use Admin\Form\AgenciesForm;
use Admin\Model\AdminPermissionsTable;
use Admin\Model\PermissionTable;
use Admin\Model\AdminTable;
use Admin\Model\Agencies;
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
use User\Model\UserType;
use User\Model\UserTypeTable;
use User\Model\Price;
use User\Model\PriceTable;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use User\Model\UserTable;
use Admin\Model\TransactionTable;
use Admin\Form\AgenciesTransactionsFilterForm;
use Application\Helper\ImageManager;

/**
 * Description of AgenciesController
 *
 */
class AgenciesController extends BaseController {

    private $adminTable;
    private $adminPermissionsTable;
    private $agenciesTable;
    private $articlesTable;
    private $pagesTable;
    private $categories;
    private $languages;
    private $userStatuses;
    private $userTypes;
    private $prices;
    private $permissionTable;
    protected $loginForm;
    protected $pagesForm;
    protected $userTable;
    protected $transactionsTable;

    /**
     * AdminController constructor.
     * @param AdminTable $adminTable
     * @param AdminPermissionsTable $adminPermissionsTable
     * @param AuthenticationService $authService
     */
    public function __construct(
    AdminTable $adminTable, AdminPermissionsTable $adminPermissionsTable, AuthenticationService $authService, AgenciesTable $agenciesTable, ArticlesTable $articlesTable, PagesTable $pagesTable, CategoriesTable $categories, LanguagesTable $languages, UserStatusTable $userStatuses, UserTypeTable $userTypes, PriceTable $prices, PermissionTable $permissionTable, UserTable $userTable, TransactionTable $transactionsTable
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
        $this->userTypes = $userTypes;
        $this->prices = $prices;
        $this->userTable = $userTable;
        $this->transactionsTable = $transactionsTable;
    }

    /**
     * Agencies page.
     *
     * @return ViewModel
     */
    public function agenciesAction() {
        return new ViewModel();
    }

    public function agenciesDataAction() {
        return $this->getJSONTableGridData($this->agenciesTable);
    }

    /**
     * Agents page.
     *
     * @return ViewModel
     */
    public function agentAction() {
        $agencyId = $this->params()->fromRoute('id', '');
        return new ViewModel(['agencyID' => $agencyId]);
    }

    public function agentDataAction() {
        $agencyId = $this->params()->fromRoute('id', '');
        $this->agenciesTable->setAgencyId($agencyId);
        return $this->getJSONTableGridData($this->agenciesTable);
    }

    /**
     * Create new agency
     *
     * @return ViewModel
     */
    public function agenciesCreateAction() {

        $agenciesForm = new AgenciesForm('createAgencies', $this->userTypes->getTypesForAgenciesArray(), $this->prices->getTypesArray(), $this->agenciesTable);
        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
            $agenciesForm->setData($post);

            if ($agenciesForm->isValid()) {
                $agenciesFormData = $agenciesForm->getData();

                $tempName = $agenciesFormData['logo']['tmp_name'];
                $tempNameSave = $tempName;
                $tempName = end(explode("/", $tempName));
                $agenciesFormData['logo']['tmp_name'] = $tempName;

                $bcrypt = new Bcrypt();
                $agenciesFormData['password'] = $bcrypt->create($agenciesFormData['password']);
                $agenciesFormData['password_confirm'] = $bcrypt->create($agenciesFormData['password_confirm']);

                $agency = new Agencies($agenciesFormData);

                $agencyId = $this->agenciesTable->create($agency);

                if (mkdir(PUBLIC_PATH . '/media/agents/' . $agencyId, 0777, TRUE)) {
                    $pathParts = pathinfo($tempNameSave);
                    $newFilePath = $pathParts['dirname'] . '/' . $agencyId . '/' . $pathParts['filename'] . '.' . $pathParts['extension'];
                    ImageManager::resizeImage($tempNameSave, $newFilePath, 300);
                }
                unlink($tempNameSave);

                return $this->redirect()->toRoute('languageRoute/adminAgencies');
            }
        }
        return new ViewModel(['form' => $agenciesForm]);
    }

    /**
     * Edit agency
     *
     * @return ViewModel
     */
    public function agenciesEditAction() {

        $agencyId = $this->params()->fromRoute('id', '');
        $agency = $this->agenciesTable->getAgencyById($agencyId)->toArray();
        $userTypeId = $agency['user_type_id'];
        $agencyForm = new AgenciesForm('EditAgencies', $this->userTypes->getTypesArray(), $this->prices->getTypesArray(), $this->agenciesTable, $agencyId, $userTypeId);

        if ($this->getRequest()->isPost()) {
            $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
            $agencyForm->setData($post);

            if ($agencyForm->isValid()) {
                $agencyFormData = $agencyForm->getData();
                var_dump($agencyFormData);

                $tempName = $agencyFormData['logo']['tmp_name'];
                if($tempName != '') {
                    $tempNameSave = $tempName;
                    $tempName = end(explode("/", $tempName));
                    $agencyFormData['logo']['tmp_name'] = $tempName;
                    if ($agencyFormData['logo']['tmp_name'] != '') {
                        if (is_dir(PUBLIC_PATH . '/media/agents/' . $agencyId) || (mkdir(PUBLIC_PATH . '/media/agents/' . $agencyId, 0777, TRUE))) {
                            $pathParts = pathinfo($tempNameSave);
                            $newFilePath = $pathParts['dirname'] . '/' . $pathParts['filename'] . '.' . $pathParts['extension'];
                            ImageManager::resizeImage($tempNameSave, $newFilePath, 300);
                        }
                    } else {
                        $agencyFormData['logo']['tmp_name'] = $agency['logo'];
                    }
                } else {
                    $agencyFormData['logo']['tmp_name'] = $agency['logo'];
                }


                $bcrypt = new Bcrypt();
                if ($agencyFormData['password'] != '') {
                    $agencyFormData['password'] = $bcrypt->create($agencyFormData['password']);
                    $agencyFormData['password_confirm'] = $bcrypt->create($agencyFormData['password_confirm']);
                }

                $agency = new Agencies($agencyFormData);
                $agency->setField('id', $agencyId);
                $this->agenciesTable->edit($agency);

                return $this->redirect()->toRoute('languageRoute/adminAgencies');
            }
        } else {
            // Loads agency data
            $agency = $this->agenciesTable->getAgencyById($agencyId);
            if ($agency) {
                $agencyForm->setData($agency->toArray());
            } else {
                return $this->redirect()->toRoute('languageRoute/adminAgencies');
            }
        }

        return new ViewModel(['form' => $agencyForm]);
    }

    /**
     * Delete agency
     *
     * @return ViewModel
     */
    public function agenciesDeleteAction() {
        $agencyId = $this->params()->fromRoute('id', '');
        $agents = $this->agenciesTable->getAgentsByAgencyId($agencyId)->toArray();

        try {
            // Set as Deleted all the agents of that agency
            foreach ($agents as $agent) {
                $this->agenciesTable->setAsDeleted($agent['id']);
            }
            // Set as Deleted The Agency
            $this->agenciesTable->setAsDeleted($agencyId);
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage('This agency cannot be deleted');
        }
        return $this->redirect()->toRoute('languageRoute/adminAgencies');
    }

    /**
     * Create agent
     *
     * @return ViewModel
     */
    public function agentCreateAction() {

        $agencyId = $this->params()->fromRoute('id', '');
        $agency = $this->agenciesTable->getAgencyById($agencyId)->toArray();

        $agentForm = new AgenciesForm('createAgent', $this->userTypes->getTypesForAgenciesArray(), $this->prices->getTypesArray(), $this->agenciesTable);
        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
            $agentForm->setData($post);

            if ($agentForm->isValid()) {
                $agentFormData = $agentForm->getData();

                $tempName = $agentFormData['logo']['tmp_name'];
                $tempNameSave = $tempName;
                $tempName = end(explode("/", $tempName));
                $agentFormData['logo']['tmp_name'] = $tempName;

                $bcrypt = new Bcrypt();
                $agentFormData['password'] = $bcrypt->create($agentFormData['password']);
                $agentFormData['password_confirm'] = $bcrypt->create($agentFormData['password_confirm']);

                $agentFormData['user_type_id'] = $agency['user_type_id'];
                $agentFormData['parent_user_id'] = $agency['id'];

                $agent = new Agencies($agentFormData);
                $agentId = $this->agenciesTable->createAgent($agent);

                if (mkdir(PUBLIC_PATH . '/media/agents/' . $agentId, 0777, TRUE)) {
                    $pathParts = pathinfo($tempNameSave);
                    $newFilePath = $pathParts['dirname'] . '/' . $agentId . '/' . $pathParts['filename'] . '.' . $pathParts['extension'];
                    ImageManager::resizeImage($tempNameSave, $newFilePath, 300);
                }
                unlink($tempNameSave);

                return $this->redirect()->toRoute('languageRoute/adminAgencies');
            }
        }
        return new ViewModel(['form' => $agentForm]);
    }

    /**
     * Edit agent
     *
     * @return ViewModel
     */
    public function agentEditAction() {

        $agentId = $this->params()->fromRoute('id', '');
        $agent = $this->agenciesTable->getAgentById($agentId)->toArray();
        $agencyId = $agent['parent_user_id'];
        $agentForm = new AgenciesForm('EditAgent', $this->userTypes->getTypesForAgenciesArray(), $this->prices->getTypesArray(), $this->agenciesTable, $agentId);

        if ($this->getRequest()->isPost()) {
            $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
            $agentForm->setData($post);
            if ($agentForm->isValid()) {
                $agentFormData = $agentForm->getData();

                $tempName = $agentFormData['logo']['tmp_name'];
                $tempNameSave = $tempName;
                $tempName = end(explode("/", $tempName));
                $agentFormData['logo']['tmp_name'] = $tempName;

                if ($agentFormData['logo']['tmp_name'] != '') {
                    if (is_dir(PUBLIC_PATH . '/media/agents/' . $agentId) || (mkdir(PUBLIC_PATH . '/media/agents/' . $agentId, 0777, TRUE))) {
                        $pathParts = pathinfo($tempNameSave);
                        $newFilePath = $pathParts['dirname'] . '/' . $pathParts['filename'] . '.' . $pathParts['extension'];
                        ImageManager::resizeImage($tempNameSave, $newFilePath, 300);
                    }
                } else {
                    $agentFormData['logo']['tmp_name'] = $agent['logo'];
                }

                $bcrypt = new Bcrypt();
                if ($agentFormData['password'] != '') {
                    $agentFormData['password'] = $bcrypt->create($agentFormData['password']);
                    $agentFormData['password_confirm'] = $bcrypt->create($agentFormData['password_confirm']);
                }

                $agentFormData['parent_user_id'] = $agent['parent_user_id'];

                $agent = new Agencies($agentFormData);
                $agent->setField('id', $agentId);
                $this->agenciesTable->editAgent($agent);

                return $this->redirect()->toRoute('languageRoute/adminAgent', array("id" => $agencyId));
            }
        } else {
            // Loads agency data
            $agent = $this->agenciesTable->getAgentById($agentId);
            if ($agent) {
                $agentForm->setData($agent->toArray());
            } else {
                return $this->redirect()->toRoute('languageRoute/adminAgent', array("id" => $agencyId));
            }
        }

        return new ViewModel(['form' => $agentForm, 'agencyId' => $agencyId]);
    }

    /**
     * Delete agent
     *
     * @return ViewModel
     */
    public function agentDeleteAction() {
        $agentId = $this->params()->fromRoute('id', '');
        $agency = $this->agenciesTable->getAgencyByAgentId($agentId)->toArray();
        $agencyId = $agency['parent_user_id'];
        try {
            $this->agenciesTable->setAsDeleted($agentId);
        } catch (InvalidQueryException $e) {
            $this->flashMessenger()->addErrorMessage('This agent cannot be deleted');
        }
        return $this->redirect()->toRoute('languageRoute/adminAgencies');
    }

    /**
     * Agency transaction page
     *
     * @return ViewModel
     */
    public function agenciesTransactionsAction() {
        $agencyId = $this->params()->fromRoute('agencyId', '');
        if ($agencyId == '') {
            return $this->redirect()->toRoute('languageRoute/adminAgencies');
        }
        $agency = $this->userTable->getAgentById($agencyId);
        if (is_null($agency)) {
            return $this->redirect()->toRoute('languageRoute/adminAgencies');
        }
        // get all brokers 
        $brokers = $this->userTable->getBrokersArray($agencyId);
        // create an array with all the ids (brokers and agent)
        $userIds = [
            (int) $agencyId
        ];
        foreach ($brokers as $brokerId => $brokerName) {
            $userIds[] = $brokerId;
        }
        $filters = array(
            'date_from' => '',
            'date_to' => ''
        );
        $request = $this->getRequest();
        if ($request->isPost()) {
            $params = $request->getPost()->toArray();

            if (empty($params['date_from']) && empty($params['date_to'])) {
                
            } else {
                $filters = array(
                    'date_from' => $params['date_from'],
                    'date_to' => $params['date_to']
                );

                $this->transactionsTable->setFilters($filters);
            }

            $transactions = $this->transactionsTable->getUsersTransactionsData($userIds);
            $transactionsForm = new AgenciesTransactionsFilterForm();

            return new ViewModel(array(
                'filters' => $filters,
                'agency' => $agency,
                'transactions' => $transactions,
                'transactionsForm' => $transactionsForm
            ));
        }

        $transactionsForm = new AgenciesTransactionsFilterForm();

        return new ViewModel(array(
            'filters' => $filters,
            'agency' => $agency,
            'transactionsForm' => $transactionsForm
        ));
    }

}
