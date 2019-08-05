<?php

namespace Admin\Controller;

use Admin\Model\OffersTable;
use Admin\Form\DashboardFilterForm;
use Admin\Model\AdminPermissionsTable;
use Admin\Model\PermissionTable;
use Admin\Model\AdminTable;
use Admin\Model\CategoriesTable;
use Admin\Model\LanguagesTable;
use User\Model\UserStatusTable;
use User\Model\CityTable;
use User\Model\OfferTypeTable;
use Zend\View\Model\JsonModel;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;

/**
 * Description of DashboardController
 *
 * @author Krasimira Evgenieva
 */
class DashboardController extends BaseController {

    private $adminTable;
    private $adminPermissionsTable;
    private $categories;
    private $languages;
    private $userStatuses;
    private $cities;
    private $offersTable;
    private $offerTypeTable;

    /**
     * DashboardController constructor.
     * @param AdminTable $adminTable
     * @param AdminPermissionsTable $adminPermissionsTable
     * @param AuthenticationService $authService
     * @param CategoriesTable $categories
     * @param LanguagesTable $languages
     * @param UserStatusTable $userStatuses
     * @param CityTable $cities
     * @param OffersTable $offersTable
     * @param PermissionTable $permissionTable
     * @param OfferTypeTable $offerTypeTable
     */
    public function __construct(
    AdminTable $adminTable, AdminPermissionsTable $adminPermissionsTable, AuthenticationService $authService, CategoriesTable $categories, LanguagesTable $languages, UserStatusTable $userStatuses, CityTable $cities, OffersTable $offersTable, PermissionTable $permissionTable, OfferTypeTable $offerTypeTable) {
        parent::__construct($authService, $permissionTable);
        $this->adminTable = $adminTable;
        $this->adminPermissionsTable = $adminPermissionsTable;
        $this->authService = $authService;
        $this->categories = $categories;
        $this->languages = $languages;
        $this->userStatuses = $userStatuses;
        $this->cities = $cities;
        $this->offersTable = $offersTable;
        $this->offerTypeTable = $offerTypeTable;
    }

    /**
     * Admin panel dashboard
     *
     * @return ViewModel
     */
    public function dashboardAction() {
        return new ViewModel();
    }

    /**
     * Datas for dashboard results.
     *
     * @return JsonModel
     */
    public function dashboardDataAction() {

        $params = $this->params()->fromQuery();

        $filters = array(
            'city_id' => $params['city_id'],
            'period_type' => $params['period_type']
        );

        if ($params['period_type'] === "date") {           
                $filters['date_from'] = $params['date_from'];
                $filters['date_to'] = $params['date_to'];
        } else {
            if ($params['period_id'] === "1") { // this month
                $filters['date_from'] = date('Y-m-d', strtotime('first day of this month'));
                $filters['date_to'] = date('Y-m-d', strtotime('first day of next month'));
            } elseif ($params['period_id'] === "2") { // last month
                $filters['date_from'] = date('Y-m-d', strtotime('first day of last month'));
                $filters['date_to'] = date('Y-m-d', strtotime('first day of this month'));
            }
        }

        $this->offerTypeTable->setFilters($filters);
        $data = $this->offerTypeTable->getDashboardData();

        return new JsonModel(array(
            array(
                'TotalRows' => count($data),
                'Rows' => $data
            )
        ));
    }

    /**
     * filter data
     *
     * @return ViewModel
     */
    public function filterAction() {
        $dashboardForm = new DashboardFilterForm($this->cities->getTypesArray());
        $viewModel = new ViewModel(array('dashboardFilterForm' => $dashboardForm));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

}
