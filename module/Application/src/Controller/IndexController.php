<?php

namespace Application\Controller;

use Admin\Controller\BaseController;
use Admin\Model\SlidersTable;
use Application\Controller\PublicBaseController;
use Admin\Model\ArticlesTable;
use Application\Helper\Mail;
use Application\Model\PageTable;
use User\Form\ContactForm;
use User\Model\BuildingTypeTable;
use User\Model\CityTable;
use User\Model\CurrencyTable;
use User\Model\GalleryTable;
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
use User\Model\UserOfferListTable;
use User\Model\User;
use User\Model\UserTable;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Mvc\I18n\Translator;
use Zend\View\HelperPluginManager;
use Application\Helper\Captcha;
use User\Model\NewsletterTable;
use Admin\Model\BlogCategoriesTable;
use Admin\Model\NewsCategoriesTable;
use Admin\Model\ServicesTable;
use Admin\Model\ServiceCategoriesTable;
use Application\Helper\FunctionsHelper;

/**
 * Class IndexController
 * @package Application\Controller
 */
class IndexController extends PublicBaseController
{

    CONST ITEMS_PER_PAGE = 18;

    private $userTable;
    protected $blogCategories;
    protected $newsCategories;
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
    private $galleryTable;
    private $pagesTable;
    private $translator;
    private $userOfferListTable;
    private $newsletterTable;
    private $servicesTable;
    private $slidersTable;

    public function __construct(
        UserTable $userTable, BlogCategoriesTable $blogCategories, NewsCategoriesTable $newsCategories, ServiceCategoriesTable $serviceCategories, OfferTypeTable $offerTypeTable, CityTable $cityTable, NeighbourhoodTable $neighbourhoodTable, PropertyTypeTable $propertyTypeTable, BuildingTypeTable $buildingTypeTable, HeatingSystemTable $heatingSystemTable, CurrencyTable $currencyTable, PropertyFeatureTable $propertyFeatureTable, OfferTable $offerTable, TransactionTable $transactionTable, PriceTable $priceTable, OfferPropertyFeatureTable $offerPropertyFeatureTable, ParcelTypeTable $parcelTypeTable, ParcelFeatureTable $parcelFeatureTablee, OfferParcelFeatureTable $offerParcelFeatureTable, AuthenticationService $authService, ArticlesTable $articlesTable, GalleryTable $galleryTable, PageTable $pageTable, Translator $translator, UserOfferListTable $userOfferListTable, NewsletterTable $newsletterTable, ServicesTable $servicesTable, SlidersTable $slidersTable
    )
    {
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
        $this->galleryTable = $galleryTable;
        $this->pagesTable = $pageTable;
        $this->translator = $translator;
        $this->userOfferListTable = $userOfferListTable;
        $this->newsletterTable = $newsletterTable;
        $this->servicesTable = $servicesTable;
        $this->slidersTable = $slidersTable;
    }

    /**
     * Show all Agencies
     *
     * @return ViewModel
     */
    public function allAgenciesAction()
    {
        $agencies = $this->userTable->getAllAgenciesAllData();

        return new ViewModel(array(
            'agencies' => $agencies,
        ));
    }

    public function getLanguageAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            if (!isset($_SESSION['lang'])) {
                $_SESSION['lang'] = 'bg';
                $_SESSION['language_id'] = 1;
            }
            return new JsonModel(array('return' => 'success', 'lang' => $_SESSION['lang']));
        } else {
            return $this->redirect()->toRoute('home');
        }
    }

    public function changeLanguageAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $_SESSION['lang'] = $_POST['lang'];
            if ($_SESSION['lang'] == 'bg') {
                $_SESSION['language_id'] = 1;
            } else {
                $_SESSION['language_id'] = 2;
            }
            return new JsonModel(array('return' => 'success', 'lang' => $_SESSION['lang']));
        } else {
            return $this->redirect()->toRoute('home');
        }
    }

    /**
     * Index public page.
     *
     * @return ViewModel
     */
    public function indexAction()
    {

        // Initializes the small search form.
        $searchFormData = array(
            'offer_type_id' => '',
            'property_type_id' => '',
            'city_id' => '',
            'available_neighbourhood_id' => array(),
            'neighbourhood_id' => array(),
            'keyword' => '',
            'minprice' => '',
            'maxprice' => '',
            'minsqm' => '',
            'maxsqm' => '',
            'floor_from' => '',
            'floor_to' => '',
            'construction_year_from' => '',
            'construction_year_to' => '',
            'yard_from' => '',
            'yard_to' => '',
            'heating_system_id' => '',
            'building_type_id' => '',
            'parcel_type_id' => '',
            'is_regulated' => '',
            'agency_id' => '',
            'property_features' => array(),
            'parcel_features' => array(),
            'user_id' => ''
        );


        // Initializes Top, Vip and News/Blog
        $lastnewsCount = 3;
        $lastnews = $this->articlesTable->getLastArticles($lastnewsCount)->toArray();
        $topOffers = array();
        $allTopOffers = $this->offerTable->getTopOffers()->toArray();
        $vipOffers = array();
        $allVipOffers = $this->offerTable->getVipOffers()->toArray();

        $numLastOffers = 20;
        $showLastOffers = 4;
        $lastOffers = array();
        $allLastOffers = $this->offerTable->getLastOffers($numLastOffers)->toArray();
        $countLastOffers = count($allLastOffers);
        if ($countLastOffers >= $showLastOffers) {
            while (count($lastOffers) < $showLastOffers) {
                $random = $allLastOffers[rand(0, $countLastOffers - 1)];
                if (!in_array($random, $lastOffers)) {
                    array_push($lastOffers, $random);
                }
            }
        } else {
            $lastOffers = $allLastOffers;
        }

        $numTopRandoms = 3;
        $countTop = count($allTopOffers);
        if ($countTop >= $numTopRandoms) {
            while (count($topOffers) < $numTopRandoms) {
                $random = $allTopOffers[rand(0, $countTop - 1)];
                if (!in_array($random, $topOffers)) {
                    array_push($topOffers, $random);
                }
            }
        } else {
            $topOffers = $allTopOffers;
        }

        $numVipRandoms = 4;
        $countVip = count($allVipOffers);
        if ($countVip >= $numVipRandoms) {
            while (count($vipOffers) < $numVipRandoms) {
                $random = $allVipOffers[rand(0, $countVip - 1)];
                if (!in_array($random, $vipOffers)) {
                    array_push($vipOffers, $random);
                }
            }
        } else {
            $vipOffers = $allVipOffers;
        }

        $agenciesCount = 6;
        $agenciesWithLogo = $this->userTable->getAgenciesForLogo($agenciesCount);

        return new ViewModel(array(
            'topOffers' => $topOffers,
            'vipOffers' => $vipOffers,
            'lastOffers' => $lastOffers,
            'lastNews' => $lastnews,
            'agenciesWithLogo' => $agenciesWithLogo,
            'searchFormData' => $searchFormData,
            'propertyFeatures' => $this->propertyFeatureTable->getTypesArray(),
            'parcelFeatures' => $this->parcelFeatureTable->getTypesArray(),
            'offerTypes' => $this->offerTypeTable->getTypesArray(),
            'cities' => $this->cityTable->getTypesArray(),
            'neighbourhoods' => $this->neighbourhoodTable->getTypesArray(),
            'propertyTypes' => $this->propertyTypeTable->getTypesArray(),
        ));
    }

    public function getSlidersAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $type = $this->params()->fromRoute('type');

            $sliders = $this->slidersTable->getByType($type, $_SESSION['lang']);
            $sliders = $sliders->toArray();

            return new JsonModel(array('return' => 'success', 'sliders' => $sliders));
        } else {
            return $this->redirect()->toRoute('home');
        }
    }

    /**
     * Shows a preview of an offer.
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function previewOfferAction()
    {
        $offerId = $this->params()->fromRoute('offerId');
        $userEmail = $this->authService->getIdentity();
        $user = $this->userTable->findByEmail($userEmail);

        $url = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        if (!is_numeric($offerId)) {
            $offerId = explode('-', $offerId);
            $offerId = end($offerId);
        }

        if (is_numeric($offerId)) {
            $offer = $this->offerTable->getPublicOfferById($offerId);
            if ($offer) {
                $counter = $offer->getCounter();
                $counter++;
                $this->offerTable->setCounterById($offerId, $counter);
                $offer->setCounter($counter);

                $propertyOfferFeatures = $this->offerPropertyFeatureTable->getPropertyFeaturesInfoById($offerId);

                $parcelOfferFeatures = $this->offerParcelFeatureTable->getParcelFeaturesInfoById($offerId);
                $broker = $this->userTable->getAgentById($offer->getUserId());
                $agency = new User();
                if ($broker->getParentUserId() == null) {
                    $agency = $broker;
                } else {
                    $agency = $this->userTable->getAgencyIdByParentUserId($broker->parentUserId);
                }
                $images = $this->galleryTable->getImagesByOfferId($offerId);
                if ($user != null) {
                    $offerList = $this->userOfferListTable->getOfferInListById($offerId, $user->getId())->toArray();
                } else {
                    $offerList = array();
                }

                $facebookImage = $offer->getFacebookImage();
                if (is_null($facebookImage)) {
                    $mainImage = $this->galleryTable->getMainImage($offerId);
                    $facebookImage = $mainImage->getImage();
                }

                $metaTags = array(
                    'og:title' => $offer->getMetaTitle(),
                    'og:description' => $offer->getMetaDescription(),
                    'og:image' => 'http://www.ogledi.bg/media/offers/' . $offer->getId() . '/' . $facebookImage,
                    'og:image:width' => 1050,
                    'og:image:height' => 550,
                    'og:type' => 'article',
                    'og:site_name' => 'OglediBG',
                    'og:url' => $url,
                    'article:author' => 'Огледи БГ',
                    'article:publisher' => 'Огледи БГ'
                );

                $this->layout()->setVariable('metaTags', $metaTags);
                $this->layout()->setVariable('metaTitle', $offer->getMetaTitle());
                $this->layout()->setVariable('metaKeywords', $offer->getMetaKeywords());
                $this->layout()->setVariable('metaDescription', $offer->getMetaDescription());
                return new ViewModel(array(
                    'offer' => $offer,
                    'propertyOfferFeatures' => $propertyOfferFeatures,
                    'parcelOfferFeatures' => $parcelOfferFeatures,
                    'agency' => $agency,
                    'broker' => $broker,
                    'images' => $images,
                    'offerList' => $offerList
                ));
            } else {
                $offerNotActive = $this->offerTable->getNotPublicOfferById($offerId);
                if ($offerNotActive) {
                    return $this->redirect()->toRoute('languageRoute/offerNotActive');
                } else {
                    return $this->redirect()->toRoute('home');
                }
            }
        } else {
            return $this->redirect()->toRoute('home');
        }
    }

    public function notActiveOfferAction()
    {
        return new ViewModel();
    }

    /**
     * Shows the search page.
     *
     * @return ViewModel
     */
    public function searchAction()
    {
        $constructionYears = [];
        $maxConstructionYear = date('Y');
        for ($i = $maxConstructionYear; $i >= 1900; $i--) {
            $constructionYears[$i] = $i;
        }

        $searchFormData = array(
            'offer_type_id' => '',
            'property_type_id' => '',
            'city_id' => '',
            'available_neighbourhood_id' => array(),
            'neighbourhood_id' => array(),
            'keyword' => '',
            'minprice' => '',
            'maxprice' => '',
            'minsqm' => '',
            'maxsqm' => '',
            'floor_from' => '',
            'floor_to' => '',
            'construction_year_from' => '',
            'construction_year_to' => '',
            'yard_from' => '',
            'yard_to' => '',
            'heating_system_id' => '',
            'building_type_id' => '',
            'parcel_type_id' => '',
            'is_regulated' => '',
            'agency_id' => '',
            'property_features' => array(),
            'parcel_features' => array(),
            'user_id' => ''
        );

        // Checks if we have submitted parameters already and loads them.
        $paramsContainer = new Container('searchParams');
        if (($paramsContainer->params) && (is_array($paramsContainer->params))) {
            foreach ($paramsContainer->params as $key => $value) {
                $searchFormData[$key] = $value;
            }
        }

        return new ViewModel(array(
            'searchFormData' => $searchFormData,
            'propertyFeatures' => $this->propertyFeatureTable->getTypesArray(),
            'parcelFeatures' => $this->parcelFeatureTable->getTypesArray(),
            'offerTypes' => $this->offerTypeTable->getTypesArray(),
            'cities' => $this->cityTable->getTypesArray(),
            'neighbourhoods' => (is_numeric($searchFormData['city_id']) ? $this->neighbourhoodTable->getTypesArray($searchFormData['city_id']) : $this->neighbourhoodTable->getTypesArray()),
            'propertyTypes' => $this->propertyTypeTable->getTypesArray(),
            'buildingTypes' => $this->buildingTypeTable->getTypesArray(),
            'heatingSystems' => $this->heatingSystemTable->getTypesArray(),
            'constructionYears' => $constructionYears,
            'currencies' => $this->currencyTable->getTypesArray(),
            'parcelTypes' => $this->parcelTypeTable->getTypesArray(),
            'agencies' => $this->userTable->getAllAgenciesWithActiveOffers()
        ));
    }

    /**
     * Performs search by POST parameters and returns JSON object.
     *
     * @return JsonModel
     */
    public function searchDataAction()
    {
        $result = array(
            'count' => 0,
            'data' => array()
        );

        $request = $this->getRequest();
        if ($request->isPost()) {
            $params = $request->getPost()->toArray();
            $result['count'] = $this->offerTable->searchOffersCount($params);
            if ($result['count'] > 0) {
                $result['data'] = $this->offerTable->searchOffersData($params);
            }
        }

        return new JsonModel($result);
    }

    /**
     * Displays search results page.
     */
    public function searchResultsAction()
    {
        $itemsPerPage = 24;
        $request = $this->getRequest();

        $page = ((is_numeric($request->getQuery('page')) && ($request->getQuery('page') > 1)) ? ($request->getQuery('page') - 1) : 0);
        $searchFormData = array(
            'offer_type_id' => '',
            'property_type_id' => '',
            'city_id' => '',
            'available_neighbourhood_id' => array(),
            'neighbourhood_id' => array(),
            'keyword' => '',
            'minprice' => '',
            'maxprice' => '',
            'minsqm' => '',
            'maxsqm' => '',
            'floor_from' => '',
            'floor_to' => '',
            'construction_year_from' => '',
            'construction_year_to' => '',
            'yard_from' => '',
            'yard_to' => '',
            'heating_system_id' => '',
            'building_type_id' => '',
            'parcel_type_id' => '',
            'is_regulated' => '',
            'agency_id' => '',
            'property_features' => array(),
            'parcel_features' => array(),
            'user_id' => ''
        );
        if ($request->isPost()) {
            foreach ($request->getPost()->toArray() as $key => $value) {
                if ($key == 'neighbourhood_id') {
                    if ((is_array($value)) && (!is_numeric($value[0]))) {
                        $value = '';
                    }
                }
                $searchFormData[$key] = $value;
            }
            $paramsContainer = new Container('searchParams');
            $paramsContainer->params = $searchFormData;
        } else {
            $paramsContainer = new Container('searchParams');
            if ($paramsContainer->params) {
                foreach ($paramsContainer->params as $key => $value) {
                    $searchFormData[$key] = $value;
                }
            }
        }

        if ($searchFormData != null) {
            $result['count'] = $this->offerTable->searchOffersCount($searchFormData);
            if ($result['count'] > 0) {
                $result['data'] = $this->offerTable->searchOffersData($searchFormData, $itemsPerPage, $page * $itemsPerPage);
            } else {
                $result['data'] = '';
            }
            return new ViewModel(array(
                'count' => $result['count'],
                'offers' => $result['data'],
                'page' => $page + 1,
                'maxPages' => ceil(($result['count'] / $itemsPerPage))
            ));
        } else {
            return $this->redirect()->toRoute('languageRoute/search', array('lang' => $_SESSION['lang']));
        }
    }

    public function newsAction()
    {
        // TODO temporarily blocked
        return $this->redirect()->toRoute('home');
        die;
        $itemsPerPage = IndexController::ITEMS_PER_PAGE;
        $request = $this->getRequest();
        $page = ((is_numeric($request->getQuery('page')) && ($request->getQuery('page') > 1)) ? ($request->getQuery('page') - 1) : 0);

        $result['count'] = $this->articlesTable->getNewsCount();
        if ($result['count'] > 0) {
            $result['data'] = $this->articlesTable->getNewsItems($itemsPerPage, $page * $itemsPerPage)->toArray();
        } else {
            $result['data'] = '';
        }
        return new ViewModel(array(
            'count' => $result['count'],
            'newsArticles' => $result['data'],
            'page' => $page + 1,
            'maxPages' => ceil(($result['count'] / $itemsPerPage))
        ));
    }

    public function blogAction()
    {

        $itemsPerPage = IndexController::ITEMS_PER_PAGE;
        $request = $this->getRequest();
        $page = ((is_numeric($request->getQuery('page')) && ($request->getQuery('page') > 1)) ? ($request->getQuery('page') - 1) : 0);

        $result['count'] = $this->articlesTable->getBlogCount();
        if ($result['count'] > 0) {
            $result['data'] = $this->articlesTable->getBlogItems($itemsPerPage, $page * $itemsPerPage)->toArray();
        } else {
            $result['data'] = '';
        }
        return new ViewModel(array(
            'count' => $result['count'],
            'blogArticles' => $result['data'],
            'page' => $page + 1,
            'maxPages' => ceil(($result['count'] / $itemsPerPage))
        ));
    }

    public function blogCategoryAction()
    {

        $itemsPerPage = 18;
        $request = $this->getRequest();
        $page = ((is_numeric($request->getQuery('page')) && ($request->getQuery('page') > 1)) ? ($request->getQuery('page') - 1) : 0);

        $categoryId = $this->params()->fromRoute('id');
        $categoryName = $this->blogCategories->getBlogCategoryNameById($categoryId)->toArray();
        $blogArticles = $this->articlesTable->getBlogsByCategoryId($categoryId)->toArray();

        if ($blogArticles != null) {
            $result['count'] = count($blogArticles);
            if ($result['count'] > 0) {
                $result['data'] = $this->articlesTable->getBlogsByCategoryId($categoryId, $itemsPerPage, $page * $itemsPerPage)->toArray();
            } else {
                $result['data'] = '';
            }
            return new ViewModel(array(
                'count' => $result['count'],
                'blogArticles' => $result['data'],
                'page' => $page + 1,
                'maxPages' => ceil(($result['count'] / $itemsPerPage)),
                'categoryName' => $categoryName
            ));
        } else {
            return $this->redirect()->toRoute('home');
        }
    }

    public function serviceAction()
    {

        $services = $this->serviceCategories->getServiceCategories()->toArray();

        return new ViewModel(array(
            'services' => $services,
            'count' => count($services)
        ));
    }

    public function serviceCategoryAction()
    {

        $itemsPerPage = IndexController::ITEMS_PER_PAGE;
        $request = $this->getRequest();
        $page = ((is_numeric($request->getQuery('page')) && ($request->getQuery('page') > 1)) ? ($request->getQuery('page') - 1) : 0);

        $categoryId = $this->params()->fromRoute('id');
        $categoryName = $this->serviceCategories->getServiceCategoryNameById($categoryId)->toArray();

        $result['count'] = $this->servicesTable->getServicesCount($categoryId);
        if ($result['count'] > 0) {
            $result['data'] = $this->servicesTable->getServicesByCategoryId($categoryId, $itemsPerPage, $page * $itemsPerPage)->toArray();
        } else {
            $result['data'] = '';
        }
        return new ViewModel(array(
            'count' => $result['count'],
            'serviceArticles' => $result['data'],
            'page' => $page + 1,
            'maxPages' => ceil(($result['count'] / $itemsPerPage)),
            'categoryName' => $categoryName
        ));
    }

    public function servicePostAction()
    {

        $url = $this->params()->fromRoute('url');
        $service = $this->servicesTable->getServiceById($url);
        if (is_null($service)) {
            return $this->redirect()->toRoute('home');
        }
        $service = $service->toArray();

        $lastSevicesCount = 4;
        $lastServices = $this->servicesTable->getLastServices($lastSevicesCount, $service['service_category_id'])->toArray();

        return new ViewModel(array(
            'service' => $service,
            'lastServices' => $lastServices,
        ));
    }

    public function newsCategoryAction()
    {
        // TODO temporarily blocked
        return $this->redirect()->toRoute('home');
        die;
        $itemsPerPage = IndexController::ITEMS_PER_PAGE;
        $request = $this->getRequest();
        $page = ((is_numeric($request->getQuery('page')) && ($request->getQuery('page') > 1)) ? ($request->getQuery('page') - 1) : 0);

        $categoryId = $this->params()->fromRoute('id');
        $categoryName = $this->newsCategories->getNewsCategoryNameById($categoryId)->toArray();
        $newsArticles = $this->articlesTable->getNewsByCategoryId($categoryId)->toArray();

        if ($newsArticles != null) {
            $result['count'] = count($newsArticles);
            if ($result['count'] > 0) {
                $result['data'] = $this->articlesTable->getNewsByCategoryId($categoryId, $itemsPerPage, $page * $itemsPerPage)->toArray();
            } else {
                $result['data'] = '';
            }
            return new ViewModel(array(
                'count' => $result['count'],
                'newsArticles' => $result['data'],
                'page' => $page + 1,
                'maxPages' => ceil(($result['count'] / $itemsPerPage)),
                'categoryName' => $categoryName
            ));
        } else {
            return $this->redirect()->toRoute('home');
        }
    }

    public function newsPostAction()
    {
        $url = $this->params()->fromRoute('url');
        $news = $this->articlesTable->getNewsById($url);
        if (is_null($news)) {
            return $this->redirect()->toRoute('home');
        }
        $news = $news->toArray();
        $lastnewsCount = 4;
        //TODO: $lastNews = $this->articlesTable->getLastNewsByCategory($lastnewsCount, $news['position'])->toArray();
        $lastNews = $this->articlesTable->getLastNews($lastnewsCount)->toArray();

        //Meta tags
        $metaTags = array(
            'og:title' => $news['meta_title'],
            'og:description' => $news['meta_description'],
            'og:image' => 'https://www.ogledi.bg/img/blog-img/' . $news['image'],
            'og:image:width' => 1050,
            'og:image:height' => 550,
            'og:type' => 'article',
            'og:site_name' => 'OglediBG',
            'og:url' => "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            'article:author' => 'Огледи БГ',
            'article:publisher' => 'Огледи БГ'
        );

        $this->layout()->setVariable('metaTags', $metaTags);
        $this->layout()->setVariable('metaTitle', $news['meta_title']);
        $this->layout()->setVariable('metaKeywords', $news['meta_keywords']);
        $this->layout()->setVariable('metaDescription', $news['meta_description']);

        return new ViewModel(array(
            'news' => $news,
            'lastNews' => $lastNews,
        ));
    }

    public function blogPostAction()
    {

        $url = $this->params()->fromRoute('url');
        $blog = $this->articlesTable->getBlogsById($url);
        if (is_null($blog)) {
            return $this->redirect()->toRoute('home');
        }
        $blog = $blog->toArray();
        if ($blog['category_id'] == 2) {
            // it has benn redirected to here by the /post/[:url] route
            // we need to redirect it to newsPostAction
            return $this->redirect()->toRoute('languageRoute/newsPost', array('lang' => $_SESSION['lang'], 'url' => $url));
        }
        $lastBlogsCount = 4;
        $lastBlogs = $this->articlesTable->getLastBlogs($lastBlogsCount, $blog['position'])->toArray();

        //Meta tags
        $metaTags = array(
            'og:title' => $blog['meta_title'],
            'og:description' => $blog['meta_description'],
            'og:image' => 'https://www.ogledi.bg/img/blog-img/' . $blog['image'],
            'og:image:width' => 1050,
            'og:image:height' => 550,
            'og:type' => 'article',
            'og:site_name' => 'OglediBG',
            'og:url' => "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            'article:author' => 'Огледи БГ',
            'article:publisher' => 'Огледи БГ'
        );

        $this->layout()->setVariable('metaTags', $metaTags);
        $this->layout()->setVariable('metaTitle', $blog['meta_title']);
        $this->layout()->setVariable('metaKeywords', $blog['meta_keywords']);
        $this->layout()->setVariable('metaDescription', $blog['meta_description']);

        return new ViewModel(array(
            'blog' => $blog,
            'lastBlogs' => $lastBlogs,
        ));
    }

    public function contactsAction()
    {
        $contactForm = new ContactForm($this->translator);

        $captchas = new Captcha('demo', 'secret', '/tmp/captchasnet-random-strings', '3600', 'abcdefghkmnopqrstuvwxyz', '6', '240', '80', '000000');
        $loginFormError = '';

        $request = $this->getRequest();
        if ($request->isPost()) {
            $contactForm->setData($request->getPost());
            $form = $request->getPost()->toArray();

//            $captcha = $form['captcha'];
//            $random_string = $form['random'];
//
//            if (!$captchas->validate($random_string)) {
//                $loginFormError = 'The session of the captcha is expired.';
//            }
            // Check, that the right CAPTCHA password has been entered and
            // return an error message otherwise.
//            elseif (!$captchas->verify($captcha)) {
//                $loginFormError = 'Captcha mismatch.';
//            } else {
            if ($this->isValidCaptcha($form['g-recaptcha-response'])) {
                if ($contactForm->isValid()) {
                    $contactFormData = $contactForm->getData();

                    //SEND E-MAIL
                    $config['from'] = array(
                        $contactFormData['email'] => $contactFormData['name'],
                    );
                    $config['to'] = array(
                        Mail::OGLEDI_MAIL_1 => 'Ogledi.bg',
                        Mail::OGLEDI_MAIL_2 => 'Ogledi.bg',
                        $contactFormData['email'] => $contactFormData['name'],
                    );

                    $config['subject'] = 'Контакти Ogledi.bg';

                    // 1 with html
                    //$config['html'] = "<h1>Html</h1>\nHello !";
                    // 2 with template
                    $config['template'] = __DIR__ . '/../../../Application/view/emailTemplates/contacts.phtml';

                    // 3 create your own template
                    $config['lineWidth'] = 50;

                    $config['fields'] = array(
                        'fromMail' => 'От',
                        'fromName' => 'Име',
                        'phone' => 'Телефон',
                        'to' => 'До',
                        'message' => 'Съобщение',
                    );

                    $config['post']['fromMail'] = $contactFormData['email'];
                    $config['post']['fromName'] = $contactFormData['name'];
                    $config['post']['phone'] = $contactFormData['phone'] ?? 'Не е посочен';
                    $config['post']['to'] = 'Ogledi.bg';
                    $config['post']['message'] = $contactFormData['frmsmbx'];

                    Mail::send($config);
                    return $this->redirect()->toRoute('languageRoute/contactsResponse', array('lang' => $_SESSION['lang']));
                } else {

                }
            } else {
                $loginFormError = 'Captcha not checked!';
            }
        }

        return new ViewModel([
            'contactForm' => $contactForm,
            'loginFormError' => $loginFormError,
//            'captchas' => $captchas,
        ]);
    }

    public function newsletterAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $email = $this->getRequest()->getPost('suber');
            if ($email) {
                $this->newsletterTable->insertMailToDb($email);
            }
        }
        return $this->redirect()->toRoute('home');
    }

    /**
     * Checks Google reCaptcha to verify user input.
     *
     * @param $response
     * @return bool
     */
    public function isValidCaptcha($response) {
        $data = array(
            'secret' => BaseController::RECAPTCHA_SECRET_KEY,
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        );
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $result = json_decode(file_get_contents(BaseController::RECAPTCHA_URL, false, $context), true);
        return ((isset($result['success'])) && ($result['success'] == 1));
    }

    /**
     * Response for successfull sending mail from contacts
     *
     * @return bool
     */
    public function contactsResponseAction()
    {
        return new ViewModel();
    }

    /**
     * Console route running by cron that updates offers and sends notifications.
     */
    public function checkOffersExpirationAction()
    {

        echo date('Y-m-d H:i') . " Process START\n";
        $offersWithExpiredExtra = $this->offerTable->getAllExpiringTodayWithExtra();
        if ($offersWithExpiredExtra->count() > 0) {
            echo date('Y-m-d H:i') . ' Extra expire now: ';
            foreach ($offersWithExpiredExtra as $offer) {
                echo $offer->getId() . '; ';
            }
            $this->offerTable->updateAllExpiringTodayWithExtra();
            echo "\n";
        }

        $toBeExpiredOffers = $this->offerTable->getAllExpiringTomorrow();
        if ($toBeExpiredOffers->count() > 0) {
            echo date('Y-m-d H:i') . ' To expire tomorrow: ';
            foreach ($toBeExpiredOffers as $offer) {
                echo $offer->getId() . '; ';
            }
            echo "\n";
        }

        $toExpireOffers = $this->offerTable->getAllExpiringToday();
        if ($toExpireOffers->count() > 0) {
            echo date('Y-m-d H:i') . ' Expire now: ';
            foreach ($toExpireOffers as $offer) {

                $offerId = $offer->getId();

                $panoDir = null;
                if (is_null($offer->getAlternativeIdFile())) {
                    do {
                        $panoDir = FunctionsHelper::randomString(20);
                    } while (file_exists(BASE_PATH . '/public/media/pano/' . $panoDir));
                    $this->offerTable->editAlternativIdFile($panoDir, $offerId);
                } else {
                    $panoDir = $offer->getAlternativeIdFile();
                }

                if (file_exists(BASE_PATH . '/public/media/pano/' . $offerId)) {
                    rename(BASE_PATH . '/public//media/pano/' . $offerId, BASE_PATH . '/public/media/pano/' . $panoDir);
                } else {
                    mkdir(BASE_PATH . '/public/media/pano/' . $panoDir, 0777, TRUE);
                }

                echo $offerId . '; ';
            }
            $this->offerTable->updateAllExpiringToday();
            $this->sendOfferExpirationEmail($offer->getEmail(), $offer->getId());
            echo "\n";
        }

        echo date('Y-m-d H:i') . " Process END\n";
    }

    /**
     * Sends email to particular user that particular offer is about to expire.
     *
     * @param $toEmail
     * @param $offerId
     */
    private function sendOfferExpirationEmail($toEmail, $offerId)
    {

        $config['from'] = array(
            Mail::OGLEDI_MAIL_1 => Mail::OGLEDI_MAIL_1,
        );
        $config['to'] = array(
            Mail::OGLEDI_MAIL_1 => 'Ogledi.bg',
            Mail::OGLEDI_MAIL_2 => 'Ogledi.bg',
            $toEmail => $toEmail
        );

        $config['subject'] = 'Напомняне за изтичаща оферта';
        $config['template'] = __DIR__ . '/../../../Application/view/emailTemplates/offer-expiration-email.phtml';
        $config['lineWidth'] = 50;

        $config['fields'] = array(
            'offerId' => 'offerId',
        );
        $config['post']['offerId'] = $offerId;

        Mail::send($config);
    }

}
