<?php

namespace User\Controller;

use Application\Controller\PublicBaseController;
use Application\Helper\ImageManager;
use Application\Model\Offer;
use Application\Helper\Calendar;
use Google_Service_Calendar;
use User\Form\OfferCreateForm;
use User\Form\OfferEditForm;
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
use User\Model\UserOfferStatusTable;
use User\Model\ParcelFeatureTable;
use User\Model\ParcelTypeTable;
use User\Model\PriceTable;
use User\Model\PropertyFeatureTable;
use User\Model\PropertyTypeTable;
use User\Model\Transaction;
use User\Model\TransactionTable;
use User\Model\UserTable;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\I18n\Translator;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
//use Application\Helper\Calendar;
//use Application\Helper\FunctionsHelper;
//use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use User\Utillity;
use Admin\Model\BlogCategoriesTable;
use Admin\Model\NewsCategoriesTable;
use Admin\Model\ServiceCategoriesTable;
use User\Form\MediaForm;
use ZipArchive;
use User\Model\Gallery;
use User\Form\ChangeImageForm;

/**
 * Class OfferController
 * @package User\Controller
 */
class OfferController extends PublicBaseController
{

    const MAX_NUM_SHOTS_ON_DATE_TIME = 2;

    private $userTable;
    protected $blogCategories;
    protected $newsCategories;
    private $offerTypeTable;
    private $userOfferStatusTable;
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
    private $translator;
    protected $galleryTable;

    public function __construct(
        UserTable $userTable, BlogCategoriesTable $blogCategories, NewsCategoriesTable $newsCategories, ServiceCategoriesTable $serviceCategories, OfferTypeTable $offerTypeTable, UserOfferStatusTable $userOfferStatusTable, CityTable $cityTable, NeighbourhoodTable $neighbourhoodTable, PropertyTypeTable $propertyTypeTable, BuildingTypeTable $buildingTypeTable, HeatingSystemTable $heatingSystemTable, CurrencyTable $currencyTable, PropertyFeatureTable $propertyFeatureTable, OfferTable $offerTable, TransactionTable $transactionTable, PriceTable $priceTable, OfferPropertyFeatureTable $offerPropertyFeatureTable, ParcelTypeTable $parcelTypeTable, ParcelFeatureTable $parcelFeatureTablee, OfferParcelFeatureTable $offerParcelFeatureTable, AuthenticationService $authService, Translator $translator, GalleryTable $galleryTable
    )
    {
        parent::__construct($authService, $blogCategories, $newsCategories, $serviceCategories);
        $this->userTable = $userTable;
        $this->offerTypeTable = $offerTypeTable;
        $this->userOfferStatusTable = $userOfferStatusTable;
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
        $this->translator = $translator;
        $this->galleryTable = $galleryTable;
    }

    public function offerBrokerAction()
    {
        $brokerId = $this->params()->fromRoute('brokerId', '');
        $offerId = $this->params()->fromRoute('offerId', '');
        $user = $this->userTable->findByEmail($this->authService->getIdentity());

        if ($brokerId == '' || $offerId == '') {
            return $this->redirect()->toRoute('languageRoute/myOffers');
        }

        // edit the broker for the offer
        $this->offerTable->editOfferBroker($offerId, $brokerId, $user->getId());

        if ($this->getRequest()->isXmlHttpRequest()) {
            return new JsonModel(array('return' => 'success'));
        } else {
            return $this->redirect()->toRoute('languageRoute/myOffers');
        }
    }

    /**
     * My Offers page.
     *
     * @return ViewModel
     */
    public function offersAction()
    {
        if ($this->authService->hasIdentity()) {

            $filterType = $this->params()->fromRoute('filterType');
            $user = $this->userTable->findByEmail($this->authService->getIdentity());
            $brokers = $this->userTable->getBrokersArray($user->getId());

            $offers = array();
            $offersLastTransaction = array();
            if ($filterType == 'rent') {
                $offers = $this->offerTable->getUserOffersRent($user->getId());
            } else if ($filterType == 'expired') {
                $offers = $this->offerTable->getUserOffersExpired($user->getId());

                $offerIds = [];
                foreach ($offers->buffer() as $offer) {
                    $offerIds[] = $offer->getId();
                }

                $offersLastTransaction = $this->transactionTable->getOffersLastSuccessfulTransactions($offerIds, $user->getId());
            } else if ($filterType == 'archive') {
                $offers = $this->offerTable->getUserOffersArchive($user->getId());
            } else if ($filterType == 'nights') {
                $offers = $this->offerTable->getUserOffersNights($user->getId());
            } else {
                $offers = $this->offerTable->getUserOffersSell($user->getId());
            }

            $errors = [];
            if (!empty($_SESSION['errorIds'])) {
                $errors = $_SESSION['errorIds'];
            }
            unset($_SESSION['errorIds']);

            return new ViewModel(array(
                'offers' => $offers,
                'offersLastTransaction' => $offersLastTransaction,
                'filterType' => $filterType,
                'logo' => $user->getLogo(),
                'userType' => $user->getUserTypeId(),
                'userId' => $user->getId(),
                'brokers' => $brokers,
                'errors' => $errors
            ));
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    /**
     * Creates new offer.
     *
     * @return ViewModel
     */
    public function createAction()
    {
        if ($this->authService->hasIdentity()) {
            $hasOldOffer = false;
            $constructionYears = [];
            $maxConstructionYear = date('Y');
            for ($i = $maxConstructionYear; $i >= 1900; $i--) {
                $constructionYears[$i] = $i;
            }

            $weeks = [];
            $weeks[1] = '1 седмица';
            for ($i = 2; $i <= 10; $i++) {
                $weeks[$i] = $i . ' седмици';
            }

            $propertyFeatures = $this->propertyFeatureTable->getTypesArray();
            $parcelFeatures = $this->parcelFeatureTable->getTypesArray();

            $user = $this->userTable->findByEmail($this->authService->getIdentity());
            $numActiveOffers = $this->offerTable->getCountActiveOffers($user->getId());
            $offersWithPanorama = $this->offerTable->getOffersWithPanorama($user->getId())->toArray();

            $userPriceId = $user->priceId;
            $userTypeId = $user->userTypeId;
            $parentUserId = $user->parentUserId;

            if ($user->parentUserId == null) {
                //Agency
                //check if agency has price_id
                if ($userPriceId != '') {
                    $prices = $this->priceTable->getPriceByUserPriceId($userPriceId);
                } else {
                    //agency has not price_id
                    // get prices for active offers and user_type_id
                    $prices = $this->priceTable->getPriceByActiveOffersAndTypeId($numActiveOffers, $userTypeId);

                    if ($prices == null) {
                        $prices = $this->priceTable->getPriceByActiveOffers($numActiveOffers);
                    }
                }
            } else {
                // Broker
                $agency = $this->userTable->getAgencyIdByParentUserId($parentUserId);
                $agencyPriceId = $agency->priceId;

                //check if agency has price_id
                if ($agencyPriceId != '') {
                    $prices = $this->priceTable->getPriceByUserPriceId($agencyPriceId);
                } else {
                    //agency has not price_id
                    // get prices for active offers and user_type_id
                    $prices = $this->priceTable->getPriceByActiveOffersAndTypeId($numActiveOffers, $userTypeId);

                    if ($prices == null) {
                        $prices = $this->priceTable->getPriceByActiveOffers($numActiveOffers);
                    }
                }
            }

            $createFormData = array(
                'vip_offer' => '',
                'top_offer' => '',
                'chat_offer' => '',
                'schema_offer' => '',
                'photography_collision_err' => '',
                'external_panorama' => ''
            );

            $request = $this->getRequest();
            if ($request->isPost()) {
                $hasOldOffer = false;
                $hasExternalPanorama = false;
                $createFormData = $request->getPost();

                if ($createFormData['old_offer_id'] != '') {
                    $hasOldOffer = true;
                    $createFormData['photographer_appointment'] = '';
                }

                if ($createFormData['external_panorama'] == '1') {
                    $hasExternalPanorama = true;
                    $createFormData['photographer_appointment'] = '';
                }

                $createForm = new OfferCreateForm(
                    $this->offerTypeTable->getTypesArray(),
                    $this->cityTable->getTypesArray(),
                    $this->neighbourhoodTable->getTypesArray($createFormData['city_id']),
                    $this->propertyTypeTable->getTypesArray(),
                    $this->buildingTypeTable->getTypesArray(),
                    $this->heatingSystemTable->getTypesArray(),
                    $constructionYears,
                    $this->currencyTable->getTypesArray(),
                    $weeks,
                    $this->parcelTypeTable->getTypesArray(),
                    $this->translator,
                    $hasOldOffer,
                    $hasExternalPanorama
                );

                $createForm->setData($createFormData);

                // Checks if there is photography collision.
                $numShotsOnDateTime = $this->offerTable->getNumShotsOnDateTime($createFormData['photographer_appointment'], $createFormData['city_id']);
                if ($numShotsOnDateTime >= $this::MAX_NUM_SHOTS_ON_DATE_TIME) {
                    $createFormData['photography_collision_err'] = 'photography_collision_err';

                    // Checks if the form is valid.
                } else if ($createForm->isValid()) {
                    $offer = new Offer();
                    $offer->exchangeArray($request->getPost());

                    // Calculates and prepares the transaction.
                    $transaction = new Transaction();
                    $area = $offer->getArea();

                    if ($offer->getYardShot() == 1) {
                        $area += $offer->getYard();
                    }

                    $oldOfferId = $offer->getOldOfferId();
                    if (is_null($oldOfferId) && !$hasExternalPanorama) {
                        $transaction->setPhotoshootPerSqPrice(
                            (($area * $prices->getPhotoshootPerSqPrice()) > $prices->getPhotoshootMinPrice()) ? ($area * $prices->getPhotoshootPerSqPrice()) : $prices->getPhotoshootMinPrice()
                        );
                    }
                    $transaction->setWeeklyPrice($offer->getWeeks() * $prices->getWeeklyPrice());
                    $transaction->setWeeks($offer->getWeeks());
                    $transaction->setExtraWeeks($offer->getExtraWeeks());

                    if ($offer->getVipOffer() == 1) {
                        $transaction->setVipPrice($prices->getVipPrice() * $offer->getExtraWeeks());
                        $transaction->setIsVip(1);
                    }
                    if ($offer->getTopOffer() == 1) {
                        $transaction->setTopPrice($prices->getTopPrice() * $offer->getExtraWeeks());
                        $transaction->setIsTop(1);
                    }
//                    if ($offer->getChatOffer() == 1) {
//                        $transaction->setChatPrice($prices->getChat() * $offer->getExtraWeeks());
//                        $transaction->setIsChat(1);
//                    }
                    if ($offer->getSchemaOffer() == 1) {
                        $transaction->setSchemaPrice($prices->getPriceSchema());
                        $transaction->setIsSchema(1);
                    }
                    $transaction->setTotalPrice(
                        $transaction->getPhotoshootPerSqPrice() + $transaction->getWeeklyPrice() + $transaction->getVipPrice() + $transaction->getTopPrice() + $transaction->getChatPrice() + $transaction->getSchemaPrice()
                    );
                    $transaction->setUserId($user->getId());

                    // Automatically pays the transaction, if it amounts to zero.
                    if ($transaction->getTotalPrice() == 0) {
                        $transaction->setIsPaid(1);
                    } else {
                        $transaction->setIsPaid(0);
                    }

                    // Inserts the offer.
                    $offer->setUserId($user->getId());

                    // Automatically activates the offer, if the amount is zero.
                    if ($transaction->getTotalPrice() == 0) {
                        if ($hasOldOffer == true || $hasExternalPanorama == true) {
                            $offer->setOfferStatusId(4);
                        } else {
                            $offer->setOfferStatusId(2);
                        }
                    } else {
                        $offer->setOfferStatusId(1);
                    }
                    // get the youtube_code_1 from the $oldOfferId
                    if ($oldOfferId) {
                        $oldOffer = $this->offerTable->getOfferByIdAndUser($oldOfferId, $user->getId());
                        $offer->setYoutubeCode1($oldOffer->getYoutubeCode1());
                    }

                    $offerId = $this->offerTable->createUserOffer($offer);

                    //Get current offer and create meta tags
                    $currentOffer = $this->offerTable->getOffer($user->getId(), $offerId)->toArray();
                    $metaTitle = mb_substr($currentOffer[0]['offerTypeName'] . ' ' . $currentOffer[0]['propertyTypeName'] . ' ' . $currentOffer[0]['cityName'], 0, 60);
                    $metaDescription = strip_tags(mb_substr($currentOffer[0]['information'], 0, 100));
                    $metaKeywords = $currentOffer[0]['offerTypeName'] . ',' . $currentOffer[0]['propertyTypeName'] . ',' . $currentOffer[0]['cityName'];

                    // Inserts property or parcel features.
                    if ($offer->getPropertyTypeId() != 20) {
                        // Inserts property features.
                        if ($createFormData['property_features']) {
                            foreach ($createFormData['property_features'] as $featureId) {
                                $this->offerPropertyFeatureTable->insertFeature($offerId, $featureId);
                                $featureNames[] = $this->propertyFeatureTable->getTypesArrayForOffer($featureId);
                            }
                            foreach ($featureNames as $key => $value) {
                                $metaKeywords = $metaKeywords . ',' . $value[0];
                            }
                        }
                    } else {
                        // Inserts parcel features.
                        if ($createFormData['parcel_features']) {
                            foreach ($createFormData['parcel_features'] as $featureId) {
                                $this->offerParcelFeatureTable->insertFeature($offerId, $featureId);
                                $featureNames[] = $this->parcelFeatureTable->getTypesArrayForOffer($featureId);
                            }
                            foreach ($featureNames as $key => $value) {
                                $metaKeywords = $metaKeywords . ',' . $value[0];
                            }
                        }
                    }

                    // Insert meta tags
                    $this->offerTable->updateMetaTags($offerId, $metaTitle, $metaDescription, mb_substr($metaKeywords, 0, 160));

                    // Inserts the transaction.
                    $transaction->setOfferId($offerId);
                    $transactionId = $this->transactionTable->createTransaction($transaction);

                    // Inserts the Calendar event for unique offers. If the offer is copy of existing offer dont insert event.

                    if (!$hasOldOffer && !$hasExternalPanorama) {
                        var_dump ($this->addGoogleEvent($offerId, $createFormData, $offer, $user, $area));
                    }

                    //Identical panorama
                    $oldOfferId = $offer->getOldOfferId();
                    if (!is_null($oldOfferId)) {

                        if (is_null($oldOffer->getAlternativeIdFile())) {
                            $oldOfferPanoDirectory = $oldOfferId;
                        } else {
                            $oldOfferPanoDirectory = $oldOffer->getAlternativeIdFile();
                        }

                        if ($offer->getOfferStatusId() != Offer::OFFER_STATUS_ACTIVE) {
                            // NOT ACTIVE
                            if (is_null($offer->getAlternativeIdFile())) {
                                do {
                                    $randomString = FunctionsHelper::randomString(20);
                                } while (file_exists(PUBLIC_PATH . '/media/pano/' . $randomString));

                                $offerPanoDirectory = $randomString;

                                if (file_exists(PUBLIC_PATH . '/media/pano/' . $offerId)) {
                                    rename(PUBLIC_PATH . '/media/pano/' . $offerId, PUBLIC_PATH . '/media/pano/' . $offerPanoDirectory);
                                } else {
                                    mkdir(PUBLIC_PATH . '/media/pano/' . $offerPanoDirectory, 0777, TRUE);
                                }

                                $this->offerTable->editAlternativIdFile($offerPanoDirectory, $offerId);
                            } else {
                                $offerPanoDirectory = $offer->getAlternativeIdFile();
                            }
                        } else {
                            // ACTIVE
                            $offerPanoDirectory = $offerId;

                            if (!is_null($offer->getAlternativeIdFile())) {
                                rename(PUBLIC_PATH . '/media/pano/' . $offer->getAlternativeIdFile(), PUBLIC_PATH . '/media/pano/' . $offerId);
                            } else {
                                if (!file_exists(PUBLIC_PATH . '/media/pano/' . $offerId)) {
                                    mkdir(PUBLIC_PATH . '/media/pano/' . $offerId, 0777, TRUE);
                                }

                            }
                            $this->offerTable->editAlternativIdFile(null, $offerId);

                        }

                        if (!file_exists(PUBLIC_PATH . '/media/offers/' . $offerId)) {
                            mkdir(PUBLIC_PATH . '/media/offers/' . $offerId, 0777, TRUE);
                        }

                        Utillity::recurse_copy(PUBLIC_PATH . '/media/pano/' . $oldOfferPanoDirectory, PUBLIC_PATH . '/media/pano/' . $offerPanoDirectory);
                        Utillity::recurse_copy(PUBLIC_PATH . '/media/offers/' . $oldOfferId, PUBLIC_PATH . '/media/offers/' . $offerId);


                        $this->offerTable->copyImages($offerId, $oldOfferId);
                        $this->offerTable->setPanoramaStatus($offerId, 'y');
                    }

                    if ($hasExternalPanorama == false) {
                        // Redirects the user to checkout / cart.
                        return $this->redirect()->toRoute('languageRoute/myCart', array('lang' => $_SESSION['lang']));
                    } else {
                        // Attach offer multimedia
                        return $this->redirect()->toRoute('languageRoute/attachMedia', array('lang' => $_SESSION['lang'], 'offerId' => $offerId));
                    }


                }
            } else {
                $createForm = new OfferCreateForm(
                    $this->offerTypeTable->getTypesArray(),
                    $this->cityTable->getTypesArray(),
                    $this->neighbourhoodTable->getTypesArray(),
                    $this->propertyTypeTable->getTypesArray(),
                    $this->buildingTypeTable->getTypesArray(),
                    $this->heatingSystemTable->getTypesArray(),
                    $constructionYears,
                    $this->currencyTable->getTypesArray(),
                    $weeks,
                    $this->parcelTypeTable->getTypesArray(),
                    $this->translator,
                    false,
                    false
                );
            }

            $createForm->prepare();
            return new ViewModel(
                array(
                    'createForm' => $createForm,
                    'propertyFeatures' => $propertyFeatures,
                    'parcelFeatures' => $parcelFeatures,
                    'createFormData' => $createFormData,
                    'numActiveOffers' => $numActiveOffers,
                    'weeklyPrice' => $prices->getWeeklyPrice(),
                    'vipPrice' => $prices->getVipPrice(),
                    'topPrice' => $prices->getTopPrice(),
                    'areaPrice' => $prices->getPhotoshootPerSqPrice(),
                    'areaMinPrice' => $prices->getPhotoshootMinPrice(),
                    'chat' => $prices->getChat(),
                    'priceSchema' => $prices->getPriceSchema(),
                    'logo' => $user->getLogo(),
                    'userType' => $user->getUserTypeId(),
                    'userId' => $user->getId(),
                    'offersWithPanorama' => $offersWithPanorama,
                    'hasOldOffer' => $hasOldOffer
                )
            );
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    /**
     * Activate Offer page.
     * If the offer has not expired, to recover as active,
     * if expired - to record as expired (for payment).
     * The status that a user has put on notice to be cleared.
     *
     * @return ViewModel
     */
    public function activateAction()
    {
        if ($this->authService->hasIdentity()) {
            $offerId = $this->params()->fromRoute('offerId');
            $user = $this->userTable->findByEmail($this->authService->getIdentity());
            $offer = $this->offerTable->getOfferByIdAndUser($offerId, $user->getId());

            // Change offer status if replace archive with other user_offer_status

            $lastTransaction = $this->transactionTable->getLastTransaction($offer->getId())->toArray();
            $now = date("Y-m-d h:i:s");
            $activeUntilDate = $offer->getActiveUntilDate();

            if ($lastTransaction[0]['isPaid'] == 1) {
                if ($now < $activeUntilDate) {
                    if ($offer->getPanoramaFile() == 'y') {
                        $offer->setOfferStatusId(4);
                    } else {
                        $offer->setOfferStatusId(3);
                    }
                } else {
                    $offer->setOfferStatusId(5);
                }
            } else {
                if ($now < $activeUntilDate) {
                    $offer->setOfferStatusId(1);
                } else {
                    $offer->setOfferStatusId(5);
                }
            }
            $offer->setUserOfferStatusId(0);

            $this->offerTable->activateOffer($offer);

            return $this->redirect()->toRoute('languageRoute/myOffers', array('filterType' => 'archive'));
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    /**
     * Edit Offer page.
     *
     * @return ViewModel
     */
    public function editAction()
    {
        if ($this->authService->hasIdentity()) {
            $offerId = $this->params()->fromRoute('offerId');

            //If user in not attached panorama, he must enter photograph address and apointment
            $hasToFillAddress = $this->params()->fromRoute('fillAddress');

            $user = $this->userTable->findByEmail($this->authService->getIdentity());
            $userPriceId = $user->priceId;
            $userTypeId = $user->userTypeId;
            $parentUserId = $user->parentUserId;

            $offerObj = $this->offerTable->getOfferByIdAndUser($offerId, $user->getId());

            $offerObjRawData = $offerObj->getRawData();
            if ($offerObjRawData['property_type_id'] !== 20 && $offerObjRawData['floor'] == null) {
                $offerObjRawData['floor'] = '0';
            }

            $offerCreator = $this->userTable->getAgentById($offerObj->userId);
            if ($offerCreator->parentUserId != '') {
                $agencyCreator = $this->userTable->getAgencyIdByParentUserId($offerCreator->parentUserId);
                $broker = $offerCreator;
            } else {
                $agencyCreator = $offerCreator;
                $broker = null;
            }

            $constructionYears = [];
            $maxConstructionYear = date('Y');
            for ($i = $maxConstructionYear; $i >= 1900; $i--) {
                $constructionYears[$i] = $i;
            }

            $weeks = [];
            $weeks[0] = 'Без промяна';
            $weeks[1] = '1 седмица';
            for ($i = 2; $i <= 10; $i++) {
                $weeks[$i] = $i . ' седмици';
            }

            $propertyFeatures = $this->propertyFeatureTable->getTypesArray();
            $propertyOfferFeatures = $this->offerPropertyFeatureTable->getPropertyFeaturesById($offerId);
            $parcelFeatures = $this->parcelFeatureTable->getTypesArray();
            $parcelOfferFeatures = $this->offerParcelFeatureTable->getParcelFeaturesById($offerId);
            $numActiveOffers = $this->offerTable->getCountActiveOffers($user->getId());

            if ($user->parentUserId == null) {
                //Agency
                //check if agency has price_id
                if ($userPriceId != '') {
                    $prices = $this->priceTable->getPriceByUserPriceId($userPriceId);
                } else {
                    //agency has not price_id
                    // get prices for active offers and user_type_id
                    $prices = $this->priceTable->getPriceByActiveOffersAndTypeId($numActiveOffers, $userTypeId);

                    if ($prices == null) {
                        $prices = $this->priceTable->getPriceByActiveOffers($numActiveOffers);
                    }
                }
            } else {
                // Broker
                $agency = $this->userTable->getAgencyIdByParentUserId($parentUserId);
                $agencyPriceId = $agency->priceId;

                //check if agency has price_id
                if ($agencyPriceId != '') {
                    $prices = $this->priceTable->getPriceByUserPriceId($agencyPriceId);
                } else {
                    //agency has not price_id
                    // get prices for active offers and user_type_id
                    $prices = $this->priceTable->getPriceByActiveOffersAndTypeId($numActiveOffers, $userTypeId);

                    if ($prices == null) {
                        $prices = $this->priceTable->getPriceByActiveOffers($numActiveOffers);
                    }
                }
            }

            if ($hasToFillAddress != '1') {
                $editFormData = array(
                    'vip_offer' => $offerObj->getVipOffer(),
                    'top_offer' => $offerObj->getTopOffer(),
                    'chat_offer' => $offerObj->getChatOffer(),
                    'schema_offer' => $offerObj->getSchemaOffer(),
                    'property_features' => $propertyOfferFeatures,
                    'parcel_features' => $parcelOfferFeatures,
                    'photography_collision_err' => '',
                    'external_panorama' => ''
                );
            }

            $request = $this->getRequest();
            if ($request->isPost()) {
                $editFormData = $request->getPost();
                $hasExternalPanorama = false;

                if ($editFormData['external_panorama'] == '1') {
                    $hasExternalPanorama = true;
                    $createFormData['photographer_appointment'] = '';
                }

                $editForm = new OfferEditForm(
                    $this->offerTypeTable->getTypesArray(),
                    $this->userOfferStatusTable->getTypesArray(),
                    $this->cityTable->getTypesArray(),
                    $this->neighbourhoodTable->getTypesArray($editFormData['city_id']),
                    $this->propertyTypeTable->getTypesArray(),
                    $this->buildingTypeTable->getTypesArray(),
                    $this->heatingSystemTable->getTypesArray(),
                    $constructionYears,
                    $this->currencyTable->getTypesArray(),
                    $weeks,
                    $this->parcelTypeTable->getTypesArray(),
                    $this->userTable->getBrokersArray($agencyCreator->id),
                    $this->translator,
                    $hasToFillAddress,
                    $hasExternalPanorama
                );

                $editForm->setData($request->getPost());

                $numShotsOnDateTime = $this->offerTable->getNumShotsOnDateTime($editFormData['photographer_appointment'], $editFormData['city_id']);
                if (($numShotsOnDateTime >= $this::MAX_NUM_SHOTS_ON_DATE_TIME) && $hasExternalPanorama == true) {
                    $editFormData['photography_collision_err'] = 'photography_collision_err';
                    // Checks if the form is valid.
                } else if ($editForm->isValid()) {
                    $offer = new Offer();
                    $offer->exchangeArray($request->getPost());
                    $offer->setUserId($offerObj->getUserId());

                    // Calculates and prepares the transaction.
                    if ($offer->getHasAddressFields() == '1') {
                        //If user is not attached panorama file add photoshot per sq price to transaction
                        $transaction = $this->transactionTable->getLastTransactionForUpdate($offerId);
                        $area = $offer->getArea();
                        if ($offer->getYardShot() == 1) {
                            $area += $offer->getYard();
                        }
                        $transaction->setPhotoshootPerSqPrice(
                            (($area * $prices->getPhotoshootPerSqPrice()) > $prices->getPhotoshootMinPrice()) ? ($area * $prices->getPhotoshootPerSqPrice()) : $prices->getPhotoshootMinPrice()
                        );
                    } else {
                        $transaction = new Transaction();
                    }

                    $transaction->setWeeklyPrice($offer->getWeeks() * $prices->getWeeklyPrice());
                    $transaction->setWeeks($offer->getWeeks());
                    $transaction->setExtraWeeks($offer->getExtraWeeks());

                    if ($offer->getVipOffer() == 1) {
                        if ($offerObj->getVipOffer() != 1) {
                            $transaction->setVipPrice($prices->getVipPrice() * $offer->getExtraWeeks());
                        }
                        if ($offer->getHasAddressFields() == '1') {
                            $transaction->setVipPrice($prices->getVipPrice() * $offer->getExtraWeeks());
                        }
                        $transaction->setIsVip(1);
                    }
                    if ($offer->getTopOffer() == 1) {
                        if ($offerObj->getTopOffer() != 1) {
                            $transaction->setTopPrice($prices->getTopPrice() * $offer->getExtraWeeks());
                        }
                        if ($offer->getHasAddressFields() == '1') {
                            $transaction->setTopPrice($prices->getTopPrice() * $offer->getExtraWeeks());
                        }
                        $transaction->setIsTop(1);
                    }
//                    if ($offer->getChatOffer() == 1) {
//                        if ($offerObj->getChatOffer() != 1) {
//                            $transaction->setChatPrice($prices->getChat() * $offer->getExtraWeeks());
//                        }
//                    if($offer->getHasAddressFields() == '1') {
//                        $transaction->setChatPrice($prices->getChat() * $offer->getExtraWeeks());
//                    }
//                        $transaction->setIsChat(1);
//                    }
                    if ($offer->getSchemaOffer() == 1) {
                        if ($offerObj->getSchemaOffer() != 1) {
                            $transaction->setSchemaPrice($prices->getPriceSchema());
                        }
                        if ($offer->getHasAddressFields() == '1') {
                            $transaction->setSchemaPrice($prices->getPriceSchema());
                        }
                        $transaction->setIsSchema(1);
                    }
                    $transaction->setTotalPrice(
                        $transaction->getPhotoshootPerSqPrice() + $transaction->getWeeklyPrice() + $transaction->getVipPrice() + $transaction->getTopPrice() + $transaction->getChatPrice() + $transaction->getSchemaPrice()
                    );
                    $transaction->setUserId($user->getId());

                    // Automatically pays the transaction, if it amounts to zero.
                    if ($transaction->getTotalPrice() == 0) {
                        $transaction->setIsPaid(1);
                    } else {
                        $transaction->setIsPaid(0);
                    }

                    // Automatically activates the offer, if the amount is zero.
                    if ($transaction->getTotalPrice() == 0) {
                        if ($offer->getUserOfferStatusId() == 1) {
                            $offer->setOfferStatusId(6);
                        } else {
                            if ($hasToFillAddress == '1') {
                                $offer->setOfferStatusId(2);
                            } else {
                                $offer->setOfferStatusId($offerObj->getOfferStatusId());
                            }
                        }
                    } else {
                        $offer->setOfferStatusId(1);
                        $offer->setUserOfferStatusId(0);
                    }


                    // Change offer status if replace archive with other user_offer_status
                    if ($offerObj->getUserOfferStatusId() == 1 && $offer->getUserOfferStatusId() != $offerObj->getUserOfferStatusId()) {
                        $lastTransaction = $this->transactionTable->getLastTransaction($offerObj->getId())->toArray();
                        $now = date("Y-m-d h:i:s");
                        $activeUntilDate = $offerObj->getActiveUntilDate();

                        if ($lastTransaction[0]['isPaid'] == 1) {
                            if ($now < $activeUntilDate) {
                                if ($offerObj->getPanoramaFile() == 'y') {
                                    $offer->setOfferStatusId(4);
                                } else {
                                    $offer->setOfferStatusId(3);
                                }
                            } else {
                                $offer->setOfferStatusId(5);
                            }
                        } else {
                            if ($now < $activeUntilDate) {
                                $offer->setOfferStatusId(1);
                            } else {
                                $offer->setOfferStatusId(5);
                            }
                        }
                    }

                    //Define offer expire date
                    $now = date("Y-m-d h:i:s");
                    $activeUntilDate = $offerObj->getActiveUntilDate();
                    if ($now > $activeUntilDate) {
                        $activeUntilDate = $now;
                    }

                    //Define offer extra expire date
                    $extraUntilDate = $offerObj->getExtraUntilDate();
                    if ($now > $extraUntilDate) {
                        $extraUntilDate = $now;
                    }

                    if (is_null($offer->getTopOffer()) && is_null($offer->getVipOffer()) && is_null($offer->getChatOffer())) {
                        $offer->setExtraWeeks(0);
                    }

                    if ($offer->getHasAddressFields() == '1') {
                        // UPDATE offer if user is not attached multimedia
                        $this->offerTable->updateOfferData($offer, $offerObj, $agencyCreator);
                    } else {
                        $this->offerTable->editUserOffer($offer, $offerObj, $agencyCreator, $activeUntilDate, $extraUntilDate);
                    }


                    // Inserts the Calendar event for unique offers. If the offer is copy of existing offer dont insert event.
                    if ($offer->getHasAddressFields() == '1') {
                        $this->addGoogleEvent($offerId, $editFormData, $offer, $user, $area);
                    }

                    // Inserts property or parcel features.
                    if ($offer->getPropertyTypeId() != 20) {
                        // Inserts property features.
                        if ($editFormData['property_features']) {
                            foreach ($propertyFeatures as $featureId => $featureVaue) {
                                if (!in_array($featureId, $propertyOfferFeatures) && in_array($featureId, $editFormData['property_features'])) {
                                    $this->offerPropertyFeatureTable->insertFeature($offerId, $featureId);
                                } elseif (in_array($featureId, $propertyOfferFeatures) && !in_array($featureId, $editFormData['property_features'])) {
                                    $this->offerPropertyFeatureTable->deleteFeature($featureId);
                                }
                            }
                        }
                    } else {
                        // Inserts parcel features.
                        if ($editFormData['parcel_features']) {
                            foreach ($parcelFeatures as $featureId => $featureValue) {
                                if (!in_array($featureId, $parcelOfferFeatures) && in_array($featureId, $editFormData['parcel_features'])) {
                                    $this->offerParcelFeatureTable->insertFeature($offerId, $featureId);
                                } elseif (in_array($featureId, $parcelOfferFeatures) && !in_array($featureId, $editFormData['parcel_features'])) {
                                    $this->offerParcelFeatureTable->deleteFeature($featureId);
                                }
                            }
                        }
                    }

                    //Get current offer and create meta tags
                    $currentOffer = $this->offerTable->getOffer($user->getId(), $offerObj->getId())->toArray();
                    $metaTitle = mb_substr($currentOffer[0]['offerTypeName'] . ' ' . $currentOffer[0]['propertyTypeName'] . ' ' . $currentOffer[0]['cityName'], 0, 60);
                    $metaDescription = strip_tags(mb_substr($currentOffer[0]['information'], 0, 100));
                    $metaKeywords = $currentOffer[0]['offerTypeName'] . ',' . $currentOffer[0]['propertyTypeName'] . ',' . $currentOffer[0]['cityName'];

                    if (($currentOffer[0]['propertyTypeId']) != 20) {
                        $ids = $this->offerPropertyFeatureTable->getPropertyFeaturesById($currentOffer[0]['id']);
                    } else {
                        $ids = $this->offerParcelFeatureTable->getParcelFeaturesById($currentOffer[0]['id']);
                    }

                    foreach ($ids as $key => $featureId) {
                        if (($currentOffer[0]['propertyTypeId']) != 20) {
                            $featureNames[] = $this->propertyFeatureTable->getTypesArrayForOffer($featureId);
                        } else {
                            $featureNames[] = $this->parcelFeatureTable->getTypesArrayForOffer($featureId);
                        }
                    }

                    if(isset($featureNames)){
                        if (!is_null($featureNames)) {
                            foreach ($featureNames as $key => $name) {
                                $metaKeywords = $metaKeywords . ',' . $name[0];
                            }
                        }
                    }

                    // Insert meta tags
                    $this->offerTable->updateMetaTags($offerId, $metaTitle, $metaDescription, mb_substr($metaKeywords, 0, 160));

                    // Inserts the transaction.
                    if ($transaction->getTotalPrice() != null) {
                        $transaction->setOfferId($offerId);
                        if ($offer->getHasAddressFields() == '1') {
                            // UPDATE
                            $transactionId = $this->transactionTable->updateTransaction($transaction);
                        } else {
                            $transactionId = $this->transactionTable->createTransaction($transaction);
                        }
                    }

                    if(isset($transactionId)){
                        if ($transactionId && $hasExternalPanorama != '1') {
                            return $this->redirect()->toRoute('languageRoute/myCart', array('lang' => $_SESSION['lang']));
                        }  else if ($transactionId && $hasExternalPanorama == '1') {
                            // Attach offer multimedia
                            return $this->redirect()->toRoute('languageRoute/attachMedia', array('lang' => $_SESSION['lang'], 'offerId' => $offerId));
                        }
                    }

                    if ($offer->getOfferTypeId() == 2) {
                        return $this->redirect()->toRoute('languageRoute/myOffers', array('lang' => $_SESSION['lang'], 'filterType' => 'rent'));
                    } elseif ($offer->getOfferTypeId() == 3) {
                        return $this->redirect()->toRoute('languageRoute/myOffers', array('lang' => $_SESSION['lang'], 'filterType' => 'nights'));
                    } elseif ($offer->getUserOfferStatusId() == 1) {
                        return $this->redirect()->toRoute('languageRoute/myOffers', array('lang' => $_SESSION['lang'], 'filterType' => 'archive'));
                    }
                    return $this->redirect()->toRoute('languageRoute/myOffers', array('lang' => $_SESSION['lang']));
                }
            } else {
                $editForm = new OfferEditForm(
                    $this->offerTypeTable->getTypesArray(), $this->userOfferStatusTable->getTypesArray(), $this->cityTable->getTypesArray(), $this->neighbourhoodTable->getTypesArray($offerObj->getCityId()), $this->propertyTypeTable->getTypesArray(), $this->buildingTypeTable->getTypesArray(), $this->heatingSystemTable->getTypesArray(), $constructionYears, $this->currencyTable->getTypesArray(), $weeks, $this->parcelTypeTable->getTypesArray(), $this->userTable->getBrokersArray($agencyCreator->id), $this->translator, $hasToFillAddress, false
                );
                $editForm->setData($offerObjRawData);
            }
            if (is_null($editFormData['vip_offer'])) {
                $editFormData['vip_offer'] = 0;
            }
            if (is_null($editFormData['top_offer'])) {
                $editFormData['top_offer'] = 0;
            }
            if (is_null($editFormData['chat_offer'])) {
                $editFormData['chat_offer'] = 0;
            }
            if (is_null($editFormData['schema_offer'])) {
                $editFormData['schema_offer'] = 0;
            }

            $editForm->prepare();
            return new ViewModel(
                array(
                    'editForm' => $editForm,
                    'offerObj' => $offerObj,
                    'propertyFeatures' => $propertyFeatures,
                    'parcelFeatures' => $parcelFeatures,
                    'editFormData' => $editFormData,
                    'numActiveOffers' => $numActiveOffers,
                    'weeklyPrice' => $prices->getWeeklyPrice(),
                    'vipPrice' => $prices->getVipPrice(),
                    'topPrice' => $prices->getTopPrice(),
                    'areaPrice' => $prices->getPhotoshootPerSqPrice(),
                    'areaMinPrice' => $prices->getPhotoshootMinPrice(),
                    'chat' => $prices->getChat(),
                    'priceSchema' => $prices->getPriceSchema(),
                    'logo' => $user->getLogo(),
                    'userType' => $user->getUserTypeId(),
                    'userId' => $user->getId(),
                    'agencyCreator' => $agencyCreator,
                    'broker' => $broker,
                    'user' => $user,
                    'hasToFillAddress' => $hasToFillAddress
                )
            );
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    /**
     * Gets Neighbourhoods by city ID.
     *
     * @return \Zend\Http\Response|JsonModel
     */
    public function neighbourhoodsDataAction()
    {
        $request = $this->getRequest();
        $res = $this->neighbourhoodTable->getTypesObject($request->getQuery('cityId'));
        return new JsonModel($res);
    }

    /**
     * Puts an offer to a stage of stopping.
     *
     * @return \Zend\Http\Response
     */
    public function deleteAction()
    {
        if ($this->authService->hasIdentity()) {
            $user = $this->userTable->findByEmail($this->authService->getIdentity());
            $offerId = $this->params()->fromRoute('offerId');
            $offerObj = $this->offerTable->getOfferById($offerId);
            $redirect = $code = $this->params()->fromRoute('redirect');

            $lastTransaction = $this->transactionTable->getLastTransactionForUpdate($offerId);
            if ($lastTransaction && $lastTransaction->getIsPaid() == 0) {
                $this->transactionTable->deleteCartItem($lastTransaction->getId(), $lastTransaction->getUserId());
            }

            if (file_exists(PUBLIC_PATH . '/media/offers/' . $offerId)) {
                // remove pics for this offer
                $files = glob(PUBLIC_PATH . '/media/offers/' . $offerId . '/*');
                foreach ($files as $file) {
                    if (is_file($file))
                        unlink($file);
                }
                rmdir(PUBLIC_PATH . '/media/offers/' . $offerId);
            }
            // delete images from gallery table
            $this->galleryTable->deleteImages($offerId);

            $dir = PUBLIC_PATH . '/media/pano/' . $offerId;
            if (file_exists($dir) == true) {
                $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
                $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
                foreach ($files as $file) {
                    if ($file->isDir()) {
                        rmdir($file->getRealPath());
                    } else {
                        unlink($file->getRealPath());
                    }
                }
            }

            if (!is_null($offerObj->getAlternativeIdFile())) {
                $dir = PUBLIC_PATH . '/media/pano/' . $offerObj->getAlternativeIdFile();
                if (file_exists($dir) == true) {
                    $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
                    $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
                    foreach ($files as $file) {
                        if ($file->isDir()) {
                            rmdir($file->getRealPath());
                        } else {
                            unlink($file->getRealPath());
                        }
                    }
                }
                rmdir($dir);
            }

            $dir = PUBLIC_PATH . '/media/video/' . $offerId;
            if (file_exists($dir) == true) {
                $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
                $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
                foreach ($files as $file) {
                    if ($file->isDir()) {
                        rmdir($file->getRealPath());
                    } else {
                        unlink($file->getRealPath());
                    }
                }
                rmdir($dir);
            }

            $this->offerTable->editAlternativIdFile(null, $offerId);
            $this->offerTable->setDeletionById($offerId, $user->getId());


            if (is_null($redirect)) {
                return $this->redirect()->toRoute('languageRoute/myOffers', array('lang' => $_SESSION['lang']));
            } else {
                return $this->redirect()->toRoute('languageRoute/myOffers', array('lang' => $_SESSION['lang'], 'filterType' => $redirect));
            }
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    /**
     * Load user media page.
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function mediaAction()
    {

        if ($this->authService->hasIdentity()) {

            $offerId = $this->params()->fromRoute('offerId');
            $offerObj = $this->offerTable->getOfferById($offerId);
            $user = $this->userTable->findByEmail($this->authService->getIdentity());
            $mediaForm = new MediaForm($offerId, $offerObj, $this->translator);

            $mediaForm->setData($offerObj->getRawData());

            $offer = $this->offerTable->getOfferByIdAndUser($offerId, $user->getId());

            $images = $this->galleryTable->getImagesByOfferId($offerId);

            $mediaForm->getInputFilter()->get('panorama_file')->setRequired(false);
            $mediaForm->getInputFilter()->get('image')->setRequired(false);
            $this->processImages($mediaForm, $offerId, $user);

            return new ViewModel(array(
                'mediaForm' => $mediaForm,
                'offer' => $offer,
                'images' => $images,
                'logo' => $user->getLogo(),
                'userType' => $user->getUserTypeId(),
                'userId' => $user->getId(),
                'offerId' => $offerId,
                'offerObj' => $offerObj

            ));
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    /**
     * Download selected images to zip file
     *
     * @return \Zend\Http\Response
     */
    public function imageDownloadAction()
    {

        if ($this->authService->hasIdentity()) {
            $request = $this->getRequest();
            $imageIds = $request->getPost()['image_to_save'];

            $files = array();
            foreach ($imageIds as $key => $id) {
                $imageObj = $this->galleryTable->getImage($id);
                $image = $imageObj->getImage();
                $offerId = $imageObj->getOfferId();

                $file = PUBLIC_PATH . '/media/offers/' . $offerId . '/' . $image;
                array_push($files, $file);
            }

            $zipname = 'Offer_' . $offerId . '.zip';
            $zip = new ZipArchive();
            $zip->open($zipname, ZipArchive::CREATE);
            foreach ($files as $file) {
                $fileContent = file_get_contents($file);
                $zip->addFromString(basename($file), $fileContent);
            }
            $zip->close();

            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename=' . $zipname);
            header('Content-Length: ' . filesize($zipname));
            readfile($zipname);
            unlink($zipname);
            exit();

        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    /**
     * User attach or update multimedia (panorama file, youtube code 1 and images)
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function attachMediaAction()
    {

        if ($this->authService->hasIdentity()) {

            $offerId = $this->params()->fromRoute('offerId');
            $offerObj = $this->offerTable->getOfferById($offerId);
            $user = $this->userTable->findByEmail($this->authService->getIdentity());
            $mediaForm = new MediaForm($offerId, $offerObj, $this->translator);
            $offer = $this->offerTable->getOfferByIdAndUser($offerId, $user->getId());

            $this->processImages($mediaForm, $offerId, $user);

            return new ViewModel(array(
                'mediaForm' => $mediaForm,
                'offer' => $offer,
                'logo' => $user->getLogo(),
                'userType' => $user->getUserTypeId(),
                'userId' => $user->getId(),
                'offerId' => $offerId,
                'offerObj' => $offerObj
            ));
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    private function processImages($mediaForm, $offerId, $user)
    {

        $request = $this->getRequest();

        if ($request->isPost()) {

            if (!file_exists(PUBLIC_PATH . '/media/offers/' . $offerId)) {
                mkdir(PUBLIC_PATH . '/media/offers/' . $offerId, 0777, TRUE);
            }

            $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
            $mediaForm->setData($post);

            if ($mediaForm->isValid()) {
                $mediaFormData = $mediaForm->getData();

                $data = [];

                $imageCount = intval($this->galleryTable->getImageCount($offerId));
                $hasMainImage = !is_null($this->galleryTable->getMainImage($offerId));
                $uploadImageCount = count($mediaFormData['image']);
                if ($imageCount + $uploadImageCount > 20) {
                    $this->flashMessenger()->addErrorMessage('Максималният брой снимки за тази оферта е 20.');

                    foreach ($mediaFormData['image'] as $key => $image) {
                        unlink($image['tmp_name']);
                    }

                    if ($mediaFormData['update_user_media'] == '1') {
                        //The user update media
                        return $this->redirect()->toRoute('languageRoute/myOffersMedia', array('lang' => $_SESSION['lang'], 'offerId' => $offerId));
                    } else {
                        //Attach media
                        return $this->redirect()->toRoute('languageRoute/attachMedia', array('lang' => $_SESSION['lang'], 'offerId' => $offerId));
                    }
                }

                // Load the logo to apply the watermark to
                $logo = imagecreatefromjpeg(PUBLIC_PATH . '/media/agents/' . $user->getId() . '/' . $user->getLogo()) ?? imagecreatefrompng(PUBLIC_PATH . '/media/agents/' . $user->getId() . '/' . $user->getLogo());
                // Get the height/width of the stamp image
                $sx_logo = imagesx($logo);
                $sy_logo = imagesy($logo);

                foreach ($mediaFormData['image'] as $key => $image) {

                    $pathParts = pathinfo($mediaFormData['image'][$key]['tmp_name']);
                    $newFilePath = $pathParts['dirname'] . '/front-' . $pathParts['filename'] . '.' . $pathParts['extension'];
                    $originalFilePath = $pathParts['dirname'] . '/' . $pathParts['filename'] . '.' . $pathParts['extension'];

                    ImageManager::resizeImage($image['tmp_name'], $originalFilePath, 1920);

                    // Load the photo to apply the watermark to
                    $img = imagecreatefromjpeg($image['tmp_name']);

                    // Calculate and Set the margins
                    $marge_right = 20;
                    $marge_bottom = 20;

                    // Copy the logo image onto our photo using the margin offsets and the photo
                    // width to calculate positioning of the logo.
                    imagecopy($img, $logo, $marge_right, $marge_bottom, 0, 0, $sx_logo, $sy_logo);


                    if ($mediaFormData['has_watermark'] == '1') {
                        imagejpeg($img, $originalFilePath);
                    }

                    $data['image'] = $image;
                    $data['image']['tmp_name'] = $pathParts['filename'] . '.' . $pathParts['extension'];

                    $gallery = new Gallery();
                    $gallery->exchangeArray($data);
                    if ($data['image']['name'] != '') {
                        $lastImage = $this->galleryTable->getLastImageOrder($offerId);
                        $imageOrder = 0;

                        if (!is_null($lastImage)) {
                            $imageOrder = $lastImage->getImageOrder();
                        }

                        if ($hasMainImage) {
                            $this->galleryTable->create($gallery, $offerId, $imageOrder);
                        } else {
                            $this->galleryTable->create($gallery, $offerId, $imageOrder, 1);
                            $hasMainImage = true;
                        }
                        ImageManager::resizeImage($mediaFormData['image'][$key]['tmp_name'], $newFilePath);
                    }
                    // Output and free memory
                    imagedestroy($img);
                }

                if ($mediaFormData['youtube_code_1'] == '') {
                    $youTubeCode1 = null;
                } else {
                    $url = $mediaFormData['youtube_code_1'];
                    parse_str(parse_url($url, PHP_URL_QUERY), $my_array_of_vars);
                    $youTubeCode1 = $my_array_of_vars['v'];

                    if (is_null($youTubeCode1) || strlen($youTubeCode1) != 11) {
                        $this->flashMessenger()->addErrorMessage('Моля, въведете валиден youtube линк.');
                        $youTubeCode1 = null;
                    }
                }

                $this->offerTable->updateExternalMedia($mediaFormData['panorama_file'], $youTubeCode1, $offerId);
                if ($mediaFormData['panorama_file'] != '') {
                    $this->offerTable->setPanoramaStatus($offerId, 'y');
                }

                $transaction = $this->transactionTable->getLastTransactionForUpdate($offerId);
                if ($mediaFormData['update_user_media'] == '1' || $transaction->getTotalPrice() == 0) {
                    //After media update load the same page
                    return $this->redirect()->toRoute('languageRoute/myOffersMedia', array('lang' => $_SESSION['lang'], 'offerId' => $offerId));
                } else {
                    //If media is attached- redirect to cart
                    return $this->redirect()->toRoute('languageRoute/myCart', array('lang' => $_SESSION['lang']));
                }
            }
        }
    }


    public function brandAllImagesAction()
    {

        if ($this->authService->hasIdentity()) {
            $offerId = $this->params()->fromRoute('offerId');
            $user = $this->userTable->findByEmail($this->authService->getIdentity());
            $images = $this->galleryTable->getImagesByOfferId($offerId);

            //Load the stamp to apply the watermark to
            $logo = imagecreatefromjpeg(PUBLIC_PATH . '/media/agents/' . $user->getId() . '/' . $user->getLogo()) ?? imagecreatefrompng(PUBLIC_PATH . '/media/agents/' . $user->getId() . '/' . $user->getLogo());

            // Get the height/width of the stamp image
            $sx_logo = imagesx($logo);
            $sy_logo = imagesy($logo);

            foreach ($images as $key => $image) {

                $imageName = $image->getImage();
                $imageFrontName = 'front-' . $image->getImage();

                $imagePath = PUBLIC_PATH . '/media/offers/' . $offerId . '/' . $imageName;
                $imagePathFront = PUBLIC_PATH . '/media/offers/' . $offerId . '/' . $imageFrontName;

                // Load the photo to apply the watermark to
                $img = imagecreatefromjpeg($imagePath);

                // Set the margins
                $marge_right = 20;
                $marge_bottom = 20;

                // Copy the stamp image onto our photo using the margin offsets and the photo
                // width to calculate positioning of the stamp.
                imagecopy($img, $logo, $marge_right, $marge_bottom, 0, 0, $sx_logo, $sy_logo);
                imagejpeg($img, $imagePath);

                ImageManager::resizeImage($imagePath, $imagePathFront);
            }

            return $this->redirect()->toRoute('languageRoute/myOffersMedia', array('lang' => $_SESSION['lang'], 'offerId' => $offerId));
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    /**
     * Update the main image
     *
     * @return \Zend\Http\Response
     */
    public function mainImageAction()
    {
        if ($this->authService->hasIdentity()) {
            $imageId = $this->params()->fromRoute('imageId');
            $offerId = $this->galleryTable->getOfferIdByImage($imageId);
            $image = $this->galleryTable->getImage($imageId);
            $this->galleryTable->updateMainImageForOffer($image->getImage(), $offerId->getOfferId());

            return $this->redirect()->toRoute('languageRoute/myOffersMedia', array('lang' => $_SESSION['lang'], 'offerId' => $offerId->getOfferId()));
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }


    /**
     * Brand image
     *
     * @return \Zend\Http\Response
     */
    public function brandImageAction()
    {
        if ($this->authService->hasIdentity()) {
            $imageId = $this->params()->fromRoute('imageId');
            $offerId = $this->galleryTable->getOfferIdByImage($imageId);
            $user = $this->userTable->findByEmail($this->authService->getIdentity());
            $image = $this->galleryTable->getImage($imageId);
            $imageName = $image->getImage();
            $imageFrontName = 'front-' . $image->getImage();

            $imagePath = PUBLIC_PATH . '/media/offers/' . $offerId->getOfferId() . '/' . $imageName;
            $imagePathFront = PUBLIC_PATH . '/media/offers/' . $offerId->getOfferId() . '/' . $imageFrontName;

            //Load the stamp to apply the watermark to
            $logo = imagecreatefromjpeg(PUBLIC_PATH . '/media/agents/' . $user->getId() . '/' . $user->getLogo()) ?? imagecreatefrompng(PUBLIC_PATH . '/media/agents/' . $user->getId() . '/' . $user->getLogo());

            // Get the height/width of the stamp image
            $sx_logo = imagesx($logo);
            $sy_logo = imagesy($logo);

            // Load the photo to apply the watermark to
            $img = imagecreatefromjpeg($imagePath);

            // Set the margins
            $marge_right = 20;
            $marge_bottom = 20;

            // Copy the stamp image onto our photo using the margin offsets and the photo
            // width to calculate positioning of the stamp.
            imagecopy($img, $logo, $marge_right, $marge_bottom, 0, 0, $sx_logo, $sy_logo);
            imagejpeg($img, $imagePath);

            ImageManager::resizeImage($imagePath, $imagePathFront);

            return $this->redirect()->toRoute('languageRoute/myOffersMedia', array('lang' => $_SESSION['lang'], 'offerId' => $offerId->getOfferId()));
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }


    /**
     * Update image. Remove the old image and insert the new one.
     *
     * @return \Zend\Http\Response
     */
    public function imageUpdateAction()
    {
        if ($this->authService->hasIdentity()) {

            $imageId = $this->params()->fromRoute('id');
            $offerId = $this->galleryTable->getOfferIdByImage($imageId);
            $oldImage = $this->galleryTable->getImage($imageId);
            $user = $this->userTable->findByEmail($this->authService->getIdentity());

            $changeImageForm = new ChangeImageForm($offerId->getOfferId());
            $request = $this->getRequest();
            if ($request->isPost()) {

                $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
                $changeImageForm->setData($post);

                if ($changeImageForm->isValid()) {
                    $changeImageFormData = $changeImageForm->getData();
                    $image = $changeImageFormData['image'];

                    $pathParts = pathinfo($image['tmp_name']);
                    $newFilePath = $pathParts['dirname'] . '/front-' . $pathParts['filename'] . '.' . $pathParts['extension'];
                    $originalFilePath = $pathParts['dirname'] . '/' . $pathParts['filename'] . '.' . $pathParts['extension'];

                    ImageManager::resizeImage($changeImageFormData['image']['tmp_name'], $originalFilePath, 1920);

                    if ($changeImageFormData['has_watermark'] == '1') {
                        //Load the stamp to apply the watermark to
                        $logo = imagecreatefromjpeg(PUBLIC_PATH . '/media/agents/' . $user->getId() . '/' . $user->getLogo()) ?? imagecreatefrompng(PUBLIC_PATH . '/media/agents/' . $user->getId() . '/' . $user->getLogo());
                        // Get the height/width of the stamp image
                        $sx_logo = imagesx($logo);
                        $sy_logo = imagesy($logo);

                        // Load the photo to apply the watermark to
                        $img = imagecreatefromjpeg($image['tmp_name']);

                        // Set the margins
                        $marge_right = 20;
                        $marge_bottom = 20;


                        // Copy the stamp image onto our photo using the margin offsets and the photo
                        // width to calculate positioning of the stamp.
                        imagecopy($img, $logo, $marge_right, $marge_bottom, 0, 0, $sx_logo, $sy_logo);
                        imagejpeg($img, $originalFilePath);
                    }

                    $this->galleryTable->updateImage($imageId, $pathParts['filename'] . '.' . $pathParts['extension']);
                    ImageManager::resizeImage($image['tmp_name'], $newFilePath);
                    ImageManager::resizeImage($img, $newFilePath);
                    rename(PUBLIC_PATH . '/media/offers/' . $pathParts['filename'] . '.' . $pathParts['extension'], PUBLIC_PATH . '/media/offers/' . $offerId->getOfferId() . '/' . $pathParts['filename'] . '.' . $pathParts['extension']);
                    unlink($pathParts['dirname'] . '/' . $oldImage->getImage());
                    unlink($pathParts['dirname'] . '/front-' . $oldImage->getImage());

                    // Output and free memory
                    imagedestroy($img);

                    return $this->redirect()->toRoute('languageRoute/myOffersMedia', array('lang' => $_SESSION['lang'], 'offerId' => $offerId->getOfferId()));
                }
            }
            return $this->redirect()->toRoute('languageRoute/myOffersMedia', array('lang' => $_SESSION['lang'], 'offerId' => $offerId->getOfferId()));

        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    /**
     * Moves image up in the ordering.
     *
     * @return \Zend\Http\Response
     */
    public function imageUpAction()
    {
        if ($this->authService->hasIdentity()) {
            $imageId = $this->params()->fromRoute('id');
            $offerId = $this->galleryTable->getOfferIdByImage($imageId);
            $oldImageOrder = $this->params()->fromRoute('imageOrder');
            $newImageOrder = $oldImageOrder - 1;

            $this->galleryTable->updateImageOrder($imageId, $newImageOrder, $oldImageOrder, $offerId->getOfferId());
            return $this->redirect()->toRoute('languageRoute/myOffersMedia', array('lang' => $_SESSION['lang'], 'offerId' => $offerId->getOfferId()));
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }

    /**
     * Moves image down in the ordering.
     *
     * @return \Zend\Http\Response
     */
    public function imageDownAction()
    {
        if ($this->authService->hasIdentity()) {
            $imageId = $this->params()->fromRoute('id');
            $offerId = $this->galleryTable->getOfferIdByImage($imageId);
            $oldImageOrder = $this->params()->fromRoute('imageOrder');
            $newImageOrder = $oldImageOrder + 1;

            $this->galleryTable->updateImageOrder($imageId, $newImageOrder, $oldImageOrder, $offerId->getOfferId());
            return $this->redirect()->toRoute('languageRoute/myOffersMedia', array('lang' => $_SESSION['lang'], 'offerId' => $offerId->getOfferId()));
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }


    /**
     * Delete offer image from gallery
     *
     * @return \Zend\Http\Response
     */
    public function userImageDeleteAction()
    {
        if ($this->authService->hasIdentity()) {
            $imageId = $this->params()->fromRoute('imageId', '');
            $offer = $this->galleryTable->getOfferIdByImage($imageId);
            $image = $this->galleryTable->getImage($imageId);
            $offerId = $offer->getOfferId();

            try {
                $this->galleryTable->delete($imageId);
                unlink(PUBLIC_PATH . '/media/offers/' . $offerId . '/' . $image->getImage());
                unlink(PUBLIC_PATH . '/media/offers/' . $offerId . '/front-' . $image->getImage());
            } catch (InvalidQueryException $e) {
                $this->flashMessenger()->addErrorMessage('This image cannot be deleted');
            }
            return $this->redirect()->toRoute('languageRoute/myOffersMedia', array('lang' => $_SESSION['lang'], 'offerId' => $offerId));
        } else {
            return $this->redirect()->toRoute('languageRoute/login', array('lang' => $_SESSION['lang']));
        }
    }


    public function addGoogleEvent($offerId, $formData, $offer, $user, $area)
    {

        // Inserts the Calendar event for unique offers. If the offer is copy of existing offer dont insert event.
        $calendar = new Calendar();
        $client = $calendar->getClient();
        $service = new Google_Service_Calendar($client);

        $arr = explode(" ", $formData['photographer_appointment']);
        $date = strtotime($arr[0]);
        $date = date('Y-m-d', $date);

        $time = strtotime($arr[1]);
        $time = date('H:i:s', $time);
        $timeEnd = date('H:i:s', strtotime($time . '+1 hour'));

        $city = $this->cityTable->getNameById($offer->getCityId());
        $neighbourhoodTable = $this->neighbourhoodTable->getNameById($offer->getNeighbourhoodId());
        $userName = $user->getNames();
        $userPhone = $user->getPhone();
        //$event = $service->events->insert ();
        /*$event = new Google_Service_Calendar_Event(array(
            'summary' => $offerId,
            'location' => $formData['photographer_address'],
            'description' => 'Площ: ' . $area . 'кв.м.' . ' '
                . 'Град: ' . $city . ' '
                . 'Квартал: ' . $neighbourhoodTable . ' '
                . 'Адрес на заснемане: ' . $formData['photographer_address'] . ' '
                . 'Агенция/брокер: ' . $userName . ' '
                . 'Телефон: ' . $userPhone,
            'start' => array(
                'dateTime' => $date . 'T' . $time,
                'timeZone' => 'Europe/Sofia',
            ),
            'end' => array(
                'dateTime' => $date . 'T' . $timeEnd,
                'timeZone' => 'Europe/Sofia',
            ),
        ));
        */
        $calendarId = 'primary';
        echo '<pre>';
        var_dump ($event = $service->events->insert($calendarId, new Google_Service_Calendar_Event(array(
            'summary' => $offerId,
            'location' => $formData['photographer_address'],
            'description' => 'Площ: ' . $area . 'кв.м.' . ' '
                . 'Град: ' . $city . ' '
                . 'Квартал: ' . $neighbourhoodTable . ' '
                . 'Адрес на заснемане: ' . $formData['photographer_address'] . ' '
                . 'Агенция/брокер: ' . $userName . ' '
                . 'Телефон: ' . $userPhone,
            'start' => array(
                'dateTime' => $date . 'T' . $time,
                'timeZone' => 'Europe/Sofia',
            ),
            'end' => array(
                'dateTime' => $date . 'T' . $timeEnd,
                'timeZone' => 'Europe/Sofia',
            ))
        )));
        echo '</pre>';
        exit;
    }
}
