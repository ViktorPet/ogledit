<?php

namespace Application\Controller;

use Application\Controller\PublicBaseController;
use Application\Model\PageTable;
use User\Model\BuildingTypeTable;
use User\Model\CityTable;
use User\Model\CurrencyTable;
use User\Model\HeatingSystemTable;
use User\Model\NeighbourhoodTable;
use User\Model\OfferParcelFeatureTable;
use User\Model\OfferPropertyFeatureTable;
use User\Model\OfferTable;
use User\Model\OfferTypeTable;
use User\Model\ParcelFeatureTable;
use User\Model\ParcelTypeTable;
use User\Model\PriceTable;
use User\Model\PropertyFeatureTable;
use User\Model\PropertyTypeTable;
use User\Model\TransactionTable;
use User\Model\UserTable;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\ArticlesTable;
use Admin\Model\BlogCategoriesTable;
use Admin\Model\NewsCategoriesTable;
use Admin\Model\ServiceCategoriesTable;

/**
 * Class IndexController
 * @package Application\Controller
 */
class InfoController extends PublicBaseController {

    private $userTable;
    protected $blogCategories;
    protected $newsCategories;
    protected $serviceCategories;
    private $offerTypeTable;
    private $cityTable;
    private $neighbourhoodTable;
    private $propertyTypeTable;
    private $buildingTypeTable;
    private $heatingSystemTable;
    private $currencyTable;
    private $propertyFeatureTable;
    private $offerTable;
    private $transactionTable;
    private $priceTable;
    private $offerPropertyFeatureTable;
    private $parcelTypeTable;
    private $parcelFeatureTable;
    private $offerParcelFeatureTable;
    protected $authService;
    private $articlesTable;
    private $pagesTable;

    public function __construct(
    UserTable $userTable, BlogCategoriesTable $blogCategories, NewsCategoriesTable $newsCategories, ServiceCategoriesTable $serviceCategories, OfferTypeTable $offerTypeTable, CityTable $cityTable, NeighbourhoodTable $neighbourhoodTable, PropertyTypeTable $propertyTypeTable, BuildingTypeTable $buildingTypeTable, HeatingSystemTable $heatingSystemTable, CurrencyTable $currencyTable, PropertyFeatureTable $propertyFeatureTable, OfferTable $offerTable, TransactionTable $transactionTable, PriceTable $priceTable, OfferPropertyFeatureTable $offerPropertyFeatureTable, ParcelTypeTable $parcelTypeTable, ParcelFeatureTable $parcelFeatureTablee, OfferParcelFeatureTable $offerParcelFeatureTable, AuthenticationService $authService, ArticlesTable $articlesTable, PageTable $pageTable
    ) {
        parent::__construct($authService, $blogCategories, $newsCategories, $serviceCategories);
        $this->userTable = $userTable;
        $this->offerTypeTable = $offerTypeTable;
        $this->cityTable = $cityTable;
        $this->neighbourhoodTable = $neighbourhoodTable;
        $this->propertyTypeTable = $propertyTypeTable;
        $this->buildingTypeTable = $buildingTypeTable;
        $this->heatingSystemTable = $heatingSystemTable;
        $this->currencyTable = $currencyTable;
        $this->propertyFeatureTable = $propertyFeatureTable;
        $this->offerTable = $offerTable;
        $this->transactionTable = $transactionTable;
        $this->priceTable = $priceTable;
        $this->offerPropertyFeatureTable = $offerPropertyFeatureTable;
        $this->parcelTypeTable = $parcelTypeTable;
        $this->parcelFeatureTable = $parcelFeatureTablee;
        $this->offerParcelFeatureTable = $offerParcelFeatureTable;
        $this->authService = $authService;
        $this->articlesTable = $articlesTable;
        $this->pagesTable = $pageTable;
    }

    /**
     * Shows selected page.
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function indexAction() {
        $url = $this->params()->fromRoute('url');

        $page = $this->pagesTable->getPageByUrl($url, $_SESSION['language_id']);
        if ($page) {
            $this->layout()->setVariable('metaTitle', $page->getMetaTitle());
            $this->layout()->setVariable('metaKeywords', $page->getMetaKeywords());
            $this->layout()->setVariable('metaDescription', $page->getMetaDescription());
            return new ViewModel(array(
                'page' => $page
            ));
        } else {
            return $this->redirect()->toRoute('home');
        }
    }
}
