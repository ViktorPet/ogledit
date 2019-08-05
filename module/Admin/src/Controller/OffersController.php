<?php

namespace Admin\Controller;

use Admin\Form\ChangeImageForm;
use Admin\Form\GalleryForm;
use Admin\Form\OfferEditForm;
use Admin\Model\AdminPermissionsTable;
use Admin\Model\Gallery;
use Admin\Model\GalleryTable;
use Admin\Model\OffersTable;
use Admin\Model\PermissionTable;
use Application\Helper\ImageManager;
use Application\Helper\FunctionsHelper;
use Application\Model\Offer;
use User\Model\BuildingTypeTable;
use User\Model\CityTable;
use User\Model\CurrencyTable;
use User\Model\HeatingSystemTable;
use User\Model\NeighbourhoodTable;
use User\Model\OfferParcelFeatureTable;
use User\Model\OfferPropertyFeatureTable;
use User\Model\OfferStatusTable;
use User\Model\OfferTable;
use User\Model\OfferTypeTable;
use User\Model\UserOfferStatusTable;
use User\Model\ParcelFeatureTable;
use User\Model\ParcelTypeTable;
use User\Model\PriceTable;
use User\Model\PropertyFeatureTable;
use User\Model\PropertyTypeTable;
use User\Model\TransactionTable;
use User\Model\UserTable;
use Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Admin\Model\AdminTable;
use Application\Model\Base\BaseGridSettings;

/**
 * Description of OffersController
 *
 */
class OffersController extends BaseController
{

    private $userTable;
    private $offerTypeTable;
    private $userOfferStatusTable;
    private $offerStatusTable;
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
    protected $authService;
    private $offersTable;
    private $adminPermissionsTable;
    private $permissionTable;
    private $offerPropertyFeatureTable;
    private $parcelTypeTable;
    private $parcelFeatureTable;
    private $offerParcelFeatureTable;
    private $galleryTable;
    private $adminTable;

    public function __construct(
        UserTable $userTable,
        OfferTypeTable $offerTypeTable,
        UserOfferStatusTable $userOfferStatusTable,
        OfferStatusTable $offerStatusTable,
        CityTable $cityTable,
        NeighbourhoodTable $neighbourhoodTable,
        PropertyTypeTable $propertyTypeTable,
        BuildingTypeTable $buildingTypeTable,
        HeatingSystemTable $heatingSystemTable,
        CurrencyTable $currencyTable,
        PropertyFeatureTable $propertyFeatureTable,
        OfferTable $offerTable,
        TransactionTable $transactionTable,
        PriceTable $priceTable,
        AuthenticationService $authService,
        OffersTable $offersTable,
        AdminPermissionsTable $adminPermissionsTable,
        OfferPropertyFeatureTable $offerPropertyFeatureTable,
        ParcelTypeTable $parcelTypeTable,
        ParcelFeatureTable $parcelFeatureTablee,
        OfferParcelFeatureTable $offerParcelFeatureTable,
        PermissionTable $permissionTable,
        GalleryTable $galleryTable,
        AdminTable $adminTable)
    {

        parent::__construct($authService, $permissionTable);

        $this->userTable = $userTable;
        $this->offerTypeTable = $offerTypeTable;
        $this->userOfferStatusTable = $userOfferStatusTable;
        $this->offerStatusTable = $offerStatusTable;
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
        $this->authService = $authService;
        $this->offersTable = $offersTable;
        $this->offerPropertyFeatureTable = $offerPropertyFeatureTable;
        $this->parcelTypeTable = $parcelTypeTable;
        $this->parcelFeatureTable = $parcelFeatureTablee;
        $this->offerParcelFeatureTable = $offerParcelFeatureTable;
        $this->adminPermissionsTable = $adminPermissionsTable;
        $this->galleryTable = $galleryTable;
        $this->adminTable = $adminTable;
    }

    public function offersAction()
    {
        $typeId = $this->params()->fromRoute('typeId', '');
        $datafield = $this->params()->fromRoute('datafield', '');

        if ($typeId != '' && $datafield != '') {
            return new ViewModel(array('typeId' => $typeId, 'datafield' => $datafield));
        } else {
            return new ViewModel();
        }
    }

    public function offersDataAction()
    {
        $typeId = $this->params()->fromRoute('typeId', '');
        $datafield = $this->params()->fromRoute('datafield', '');

        $this->offersTable->setOfferTypeId($typeId);
        $this->offersTable->setOfferDatafield($datafield);

        return $this->getJSONTableGridData($this->offersTable);
    }

    /**
     * Edit Offer page.
     *
     * @return ViewModel
     */
    public function offersEditAction()
    {
        if ($this->authService->hasIdentity()) {
            $offerId = $this->params()->fromRoute('offerId', '');
            $offerObj = $this->offerTable->getOfferById($offerId);
            $offerObjRawData = $offerObj->getRawData();
            if ($offerObjRawData['property_type_id'] !== 20 && $offerObjRawData['floor'] == null) {
                $offerObjRawData['floor'] = '0';
            }


            $offerCreator = $this->userTable->getAgentById($offerObj->userId);
            if ($offerCreator->parentUserId != '') {
                $agency = $this->userTable->getAgencyIdByParentUserId($offerCreator->parentUserId);
                $broker = $offerCreator;
            } else {
                $agency = $offerCreator;
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

            $editFormData = array(
                'vipOffer' => $offerObj->getVipOffer(),
                'topOffer' => $offerObj->getTopOffer(),
                'chatOffer' => $offerObj->getChatOffer(),
                'schemaOffer' => $offerObj->getSchemaOffer(),
                'property_features' => $propertyOfferFeatures,
                'parcel_features' => $parcelOfferFeatures
            );
            $request = $this->getRequest();

            if($offerObj->getOfferStatusId() == Offer::OFFER_STATUS_ACTIVE) {
                $panoDir = $offerId;
            } else {
                $panoDir = $offerObj->getAlternativeIdFile();
            }

            if(is_null($panoDir)) {
                // $offerObj->getAlternativeIdFile() Has returned `null`. We Create AlternativeIdFile
                do {
                    $panoDir = FunctionsHelper::randomString(20);
                } while (file_exists(PUBLIC_PATH . '/media/pano/' . $panoDir));
                $this->offersTable->editAlternativIdFile($panoDir, $offerId);
            }

            if (!file_exists(PUBLIC_PATH . '/media/pano/' . $panoDir)) {
                mkdir(PUBLIC_PATH . '/media/pano/' . $panoDir, 0777, TRUE);
            }

            if ($request->isPost()) {
                if (!file_exists(PUBLIC_PATH . '/media/offers/' . $offerId)) {
                    mkdir(PUBLIC_PATH . '/media/offers/' . $offerId, 0777, TRUE);
                }
                if (!file_exists(PUBLIC_PATH . '/media/video/' . $offerId)) {
                    mkdir(PUBLIC_PATH . '/media/video/' . $offerId, 0777, TRUE);
                }

                $editFormData = $request->getPost();

                $editForm = new OfferEditForm(
                    $this->offerTypeTable->getTypesArray(), $this->userOfferStatusTable->getTypesArray(),
                    $this->offerStatusTable->getTypesArray(), $this->cityTable->getTypesArray(),
                    $this->neighbourhoodTable->getTypesArray($editFormData['city_id']), $this->propertyTypeTable->getTypesArray(),
                    $this->buildingTypeTable->getTypesArray(), $this->heatingSystemTable->getTypesArray(),
                    $constructionYears, $this->currencyTable->getTypesArray(), $weeks, $this->parcelTypeTable->getTypesArray(),
                    $this->userTable->getBrokersArray($agency->id),
                    $offerId, $panoDir

                );
                $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());

                $editForm->setData($post);
                if ($editForm->isValid()) {

                    $editFormData = $editForm->getData();

                    if ($editFormData['facebook_img']['tmp_name'] != '') {
                        $pathParts = pathinfo($editFormData['facebook_img']['tmp_name']);
                        $newFilePath = $pathParts['dirname'] . '/' . $pathParts['filename'] . '.' . $pathParts['extension'];
                        ImageManager::resizeImageFull($editFormData['facebook_img']['tmp_name'], $newFilePath, 1020, 630);
                        $editFormData['facebook_img']['tmp_name'] = $pathParts['filename'] . '.' . $pathParts['extension'];
                    }

                    if ($editFormData['main_image']['tmp_name'] != '') {
                        $pathParts = pathinfo($editFormData['main_image']['tmp_name']);
                        $newFilePath = $pathParts['dirname'] . '/front-' . $pathParts['filename'] . '.' . $pathParts['extension'];
                        ImageManager::resizeImage($editFormData['main_image']['tmp_name'], $newFilePath);
                        $editFormData['main_image']['tmp_name'] = $pathParts['filename'] . '.' . $pathParts['extension'];
                    }

                    if ($editFormData['panorama_file']['tmp_name'] != '') {
                        $tempName = $editFormData['panorama_file']['tmp_name'];
                        $tempName = explode("/", $tempName);
                        $tempName = end($tempName);
                        $editFormData['panorama_file']['tmp_name'] = $tempName;

                        $filter = new \Zend\Filter\Decompress(array(
                            'adapter' => 'Zip',
                            'options' => array(
                                'target' => PUBLIC_PATH . '/media/pano/' . $panoDir . '/',
                            )
                        ));
                        $decompressed = $filter->filter(PUBLIC_PATH . '/media/pano/' . $panoDir . '/' . $tempName);
                        unlink(PUBLIC_PATH . '/media/pano/' . $panoDir . '/' . $tempName);

                    }

                    if ($editFormData['youtube_code_2']['tmp_name'] != '') {
                        $tempName = $editFormData['youtube_code_2']['tmp_name'];
                        $tempName = end(explode("/", $tempName));
                        $editFormData['youtube_code_2']['tmp_name'] = $tempName;

                        $filter = new \Zend\Filter\Decompress(array(
                            'adapter' => 'Zip',
                            'options' => array(
                                'target' => PUBLIC_PATH . '/media/video/' . $offerId . '/',
                            )
                        ));
                        $decompressed = $filter->filter(PUBLIC_PATH . '/media/video/' . $offerId . '/' . $tempName);
                        unlink(PUBLIC_PATH . '/media/video/' . $offerId . '/' . $tempName);
                    }

                    $offer = new Offer();
                    $offer->exchangeArray($editFormData);

                    $offer->setLat($request->getPost()['lat']);
                    $offer->setLng($request->getPost()['lng']);

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

                    $this->offersTable->editUserOffer($offer, $offerObj, $agency, $activeUntilDate, $extraUntilDate);
                    if ($editFormData['main_image']['tmp_name'] != '') {
                        $this->galleryTable->addMainImageForOffer($editFormData['main_image']['tmp_name'], $offerObj->getId());
                    }

                    // Inserts property or parcel features.
                    if ($offer->getPropertyTypeId() != 20) {
                        // Inserts property features.                        
                        if (isset($post['property_features'])) {
                            foreach ($propertyFeatures as $featureId => $featureVaue) {
                                if (!in_array($featureId, $propertyOfferFeatures) && in_array($featureId, $post['property_features'])) {
                                    $this->offerPropertyFeatureTable->insertFeature($offerId, $featureId);
                                } elseif (in_array($featureId, $propertyOfferFeatures) && !in_array($featureId, $post['property_features'])) {
                                    $this->offerPropertyFeatureTable->deleteFeature($featureId);
                                }
                            }
                        }
                    } else {
                        // Inserts parcel features.
                        if (isset($post['parcel_features'])) {
                            foreach ($parcelFeatures as $featureId => $featureValue) {
                                if (!in_array($featureId, $parcelOfferFeatures) && in_array($featureId, $post['parcel_features'])) {
                                    $this->offerParcelFeatureTable->insertFeature($offerId, $featureId);
                                } elseif (in_array($featureId, $parcelOfferFeatures) && !in_array($featureId, $post['parcel_features'])) {
                                    $this->offerParcelFeatureTable->deleteFeature($featureId);
                                }
                            }
                        }
                    }

                    if ($editFormData['offer_status_id'] == Offer::OFFER_STATUS_ACTIVE &&
                        $panoDir != $offerId
                    ) {
                        // If offer status IS active and $panoDir IS NOT $offerId.
                        rename(PUBLIC_PATH . '/media/pano/' . $panoDir, PUBLIC_PATH . '/media/pano/' . $offerId);
                        $this->offersTable->editAlternativIdFile(null, $offerId);

                    } elseif ($editFormData['offer_status_id'] != Offer::OFFER_STATUS_ACTIVE &&
                        $panoDir == $offerId
                    ) {
                        // If offer status IS NOT active and $panoDir IS $offerId.
                        do {
                            $panoDir = FunctionsHelper::randomString(20);
                        } while (file_exists(PUBLIC_PATH . '/media/pano/' . $panoDir));
                        rename(PUBLIC_PATH . '/media/pano/' . $offerId, PUBLIC_PATH . '/media/pano/' . $panoDir);
                        $this->offersTable->editAlternativIdFile($panoDir, $offerId);
                    }

                    return $this->redirect()->toRoute('languageRoute/adminOffersEdit', array('offerId' => $offerId));
                }
            } else {
                $editForm = new OfferEditForm(
                    $this->offerTypeTable->getTypesArray(), $this->userOfferStatusTable->getTypesArray(),
                    $this->offerStatusTable->getTypesArray(), $this->cityTable->getTypesArray(),
                    $this->neighbourhoodTable->getTypesArray($offerObj->cityId), $this->propertyTypeTable->getTypesArray(),
                    $this->buildingTypeTable->getTypesArray(), $this->heatingSystemTable->getTypesArray(), $constructionYears,
                    $this->currencyTable->getTypesArray(), $weeks, $this->parcelTypeTable->getTypesArray(),
                    $this->userTable->getBrokersArray($agency->id),
                    $offerId, $panoDir
                );
                $editForm->setData($offerObjRawData);
            }

            $editForm->prepare();
            return new ViewModel(
                array(
                    'editForm' => $editForm,
                    'offerObj' => $offerObj,
                    'propertyFeatures' => $propertyFeatures,
                    'parcelFeatures' => $parcelFeatures,
                    'editFormData' => $editFormData,
                    'agency' => $agency,
                    'broker' => $broker
                )
            );
        } else {
            return $this->redirect()->toRoute('languageRoute/login');
        }
    }

    /**
     * Gets Neighbourhoods by city ID.
     *
     * @return \Zend\Http\Response|JsonModel
     */
    public function neighbourhoodsDataAction()
    {
        if ($this->authService->hasIdentity()) {
            $request = $this->getRequest();
            $res = $this->neighbourhoodTable->getTypesObject($request->getQuery('cityId'));
            return new JsonModel($res);
        } else {
            return $this->redirect()->toRoute('languageRoute/login');
        }
    }

    /**
     * Puts an offer to a stage of delete and deletes every picture, panorama
     * and the dir they are in.
     *
     * @return \Zend\Http\Response
     */
    public function offersDeleteAction()
    {
        if ($this->authService->hasIdentity()) {
            $admin = $this->adminTable->findByEmail($this->authService->getIdentity());
            $offerId = $this->params()->fromRoute('offerId');
            $offerObj = $this->offerTable->getOfferById($offerId);
            $userId = $this->params()->fromRoute('userId');

            // set the status to Delete    
            $user = $this->userTable->getAgentById($userId);

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

            $panoDir = $offerId;
            if (!is_null($offerObj->getAlternativeIdFile())) {
                $panoDir = $offerObj->getAlternativeIdFile();
            }

            $dir = PUBLIC_PATH . '/media/pano/' . $panoDir;
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


            $this->offersTable->editAlternativIdFile(null, $offerId);

            $this->offerTable->setDeletionById($offerId, $user->getId());
            return $this->redirect()->toRoute('languageRoute/adminOffers');
        } else {
            return $this->redirect()->toRoute('languageRoute/login');
        }
    }

    /**
     * Delete panorama
     *
     * @return \Zend\Http\Response
     */
    public function offersDeletePanoramaAction()
    {
        if ($this->authService->hasIdentity()) {
            $admin = $this->adminTable->findByEmail($this->authService->getIdentity());
            $offerId = $this->params()->fromRoute('id');
            $offerObj = $this->offerTable->getOfferById($offerId);

            $panoDir = $offerId;
            if (!is_null($offerObj->getAlternativeIdFile())) {
                $panoDir = $offerObj->getAlternativeIdFile();
            }
            $dir = PUBLIC_PATH . '/media/pano/' . $panoDir;
            $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }

            $this->offersTable->setPanoramaStatus($offerId, 'n');

            return $this->redirect()->toRoute('languageRoute/adminOffersEdit', array('offerId' => $offerId));
        } else {
            return $this->redirect()->toRoute('languageRoute/login');
        }
    }

    public function forPanoramasAction()
    {
        return new ViewModel();
    }

    public function forPanoramasDataAction()
    {
        $offerStatus = 3;
        $this->offersTable->setOfferStatus($offerStatus);
        return $this->getJSONTableGridData($this->offersTable);
    }

    public function forStoppingAction()
    {
        return new ViewModel();
    }

    public function forStoppingDataAction()
    {
        $offerStatus = 7;
        $this->offersTable->setOfferStatus($offerStatus);
        return $this->getJSONTableGridData($this->offersTable);
    }

    public function noPanoramasAction()
    {
        return new ViewModel();
    }

    public function noPanoramasDataAction()
    {
        $offerStatus = 'No panorama';
        $this->offersTable->setOfferStatus($offerStatus);
        return $this->getJSONTableGridData($this->offersTable);
    }

    public function noVideoAction()
    {
        return new ViewModel();
    }

    public function noVideoDataAction()
    {
        $offerStatus = 'No video';
        $this->offersTable->setOfferStatus($offerStatus);
        return $this->getJSONTableGridData($this->offersTable);
    }

    /**
     * Get gallery data
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function offersGalleryAction()
    {
        $offerId = $this->params()->fromRoute('offerId', '');
        $offerObj = $this->offerTable->getOfferById($offerId);

        if(empty($offerObj)) {
            return $this->redirect()->toRoute('languageRoute/adminOffers');
        }

        if($offerObj->getOfferStatusId() == Offer::OFFER_STATUS_ACTIVE) {
            $panoDir = $offerId;
        } else {
            $panoDir = $offerObj->getAlternativeIdFile();
        }

        if(is_null($panoDir)) {
            // $offerObj->getAlternativeIdFile() Has returned `null`. We Create AlternativeIdFile
            do {
                $panoDir = FunctionsHelper::randomString(20);
            } while (file_exists(PUBLIC_PATH . '/media/pano/' . $panoDir));
            $this->offersTable->editAlternativIdFile($panoDir, $offerId);
        }

        if (!file_exists(PUBLIC_PATH . '/media/pano/' . $panoDir)) {
            mkdir(PUBLIC_PATH . '/media/pano/' . $panoDir, 0777, TRUE);
        }

        $galleryForm = new GalleryForm($offerId, $offerObj, $panoDir);
        $galleryForm->setData($offerObj->getRawData());

        if ($offerId != '') {
            return new ViewModel(array('offerId' => $offerId, 'galleryForm' => $galleryForm, 'offerObj' => $offerObj));
        } else {
            return $this->redirect()->toRoute('languageRoute/adminOffers');
        }
    }

    public function offersGalleryDataAction()
    {
        $offerId = $this->params()->fromRoute('offerId', '');
        $this->galleryTable->setOfferId($offerId);
        return $this->getJSONTableGridData($this->galleryTable);
    }

    /**
     * Create multimedia(pictures, panorama, video) for offer
     *
     * @return \Zend\Http\Response
     */
    public function offersGalleryCreateAction()
    {

        $offerId = $this->params()->fromRoute('offerId', '');
        $offerObj = $this->offerTable->getOfferById($offerId);

        if(empty($offerObj)) {
            return $this->redirect()->toRoute('languageRoute/adminOffers');
        }

        if($offerObj->getOfferStatusId() == Offer::OFFER_STATUS_ACTIVE) {
            $panoDir = $offerId;
        } else {
            $panoDir = $offerObj->getAlternativeIdFile();
        }

        if(is_null($panoDir)) {
            // $offerObj->getAlternativeIdFile() Has returned `null`. We Create AlternativeIdFile
            do {
                $panoDir = FunctionsHelper::randomString(20);
            } while (file_exists(PUBLIC_PATH . '/media/pano/' . $panoDir));
            $this->offersTable->editAlternativIdFile($panoDir, $offerId);
        }

        $galleryForm = new GalleryForm($offerId, $offerObj, $panoDir);
        $request = $this->getRequest();

        if ($request->isPost()) {

            if (!file_exists(PUBLIC_PATH . '/media/offers/' . $offerId)) {
                mkdir(PUBLIC_PATH . '/media/offers/' . $offerId, 0777, TRUE);
            }
            if (!file_exists(PUBLIC_PATH . '/media/video/' . $offerId)) {
                mkdir(PUBLIC_PATH . '/media/video/' . $offerId, 0777, TRUE);
            }

            $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());

            $galleryForm->setData($post);

            if ($galleryForm->isValid()) {

                $galleryFormData = $galleryForm->getData();

                $data = [];

                $uploadImageCount = $galleryFormData['image'][0]['size'] > 0 ? count($galleryFormData['image']) : 0;
                $hasMainImage = !is_null($this->galleryTable->getMainImage($offerId));
                $imageCount = intval($this->galleryTable->getImageCount($offerId));
                if ($imageCount + $uploadImageCount > 20) {
                    $this->flashMessenger()->addErrorMessage('Максималният брой снимки за тази оферта е 20. Можете да прикачите ' . (20 - $imageCount) . ' снимки.');

                    foreach ($galleryFormData['image'] as $key => $image) {
                        if($image['size'] > 0) {
                            unlink($image['tmp_name']);
                        }
                    }
                    return $this->redirect()->toRoute('languageRoute/adminOffersGallery', array('offerId' => $offerId));
                }
                foreach ($galleryFormData['image'] as $key => $image) {
                    if ($image['size'] > 0) {
                        $pathParts = pathinfo($galleryFormData['image'][$key]['tmp_name']);
                        $newFilePath = $pathParts['dirname'] . '/front-' . $pathParts['filename'] . '.' . $pathParts['extension'];

                        $data['image'] = $image;
                        $data['image']['tmp_name'] = $pathParts['filename'] . '.' . $pathParts['extension'];

                        $gallery = new Gallery($data);
                        if ($data['image']['name'] != '') {
                            if (!is_null($this->galleryTable->getLastImageOrder($offerId))) {
                                $lastImage = $this->galleryTable->getLastImageOrder($offerId)->toArray();
                            } else {
                                $lastImage['image_order'] = 0;
                            }

                            if ($hasMainImage) {
                                $this->galleryTable->create($gallery, $offerId, $lastImage['image_order']);
                            } else {
                                $this->galleryTable->create($gallery, $offerId, $lastImage['image_order'], 1);
                                $hasMainImage = true;
                            }
                            ImageManager::resizeImage($galleryFormData['image'][$key]['tmp_name'], $newFilePath);
                        }
                    }
                }


                if ($galleryFormData['facebook_img']['tmp_name'] != '') {
                    $pathParts = pathinfo($galleryFormData['facebook_img']['tmp_name']);
                    $newFilePath = $pathParts['dirname'] . '/' . $pathParts['filename'] . '.' . $pathParts['extension'];
                    ImageManager::resizeImageFull($galleryFormData['facebook_img']['tmp_name'], $newFilePath, 1020, 630);
                    $galleryFormData['facebook_img']['tmp_name'] = $pathParts['filename'] . '.' . $pathParts['extension'];
                }

                if ($galleryFormData['facebook_img']['tmp_name'] != '') {
                    $this->offersTable->addFacebookImageForOffer($galleryFormData['facebook_img']['tmp_name'], $offerId);
                }

                if ($galleryFormData['panorama_file']['tmp_name'] != '') {
                    $tempName = $galleryFormData['panorama_file']['tmp_name'];
                    $tempName = explode("/", $tempName);
                    $tempName = end($tempName);
                    $galleryFormData['panorama_file']['tmp_name'] = $tempName;

                    $this->offersTable->setPanoramaStatus($offerId, 'y');

                    $filter = new \Zend\Filter\Decompress(array(
                        'adapter' => 'Zip',
                        'options' => array(
                            'target' => PUBLIC_PATH . '/media/pano/' . $panoDir . '/',
                        )
                    ));
                    $decompressed = $filter->filter(PUBLIC_PATH . '/media/pano/' . $panoDir . '/' . $tempName);
                    unlink(PUBLIC_PATH . '/media/pano/' . $panoDir . '/' . $tempName);

                    if ($offerObj->offerStatusId == 2) {
                        $offerObj->offerStatusId = Offer::OFFER_STATUS_ACTIVE;
                        $this->offersTable->setActiveById($offerId);
                        $this->offersTable->editAlternativIdFile(null, $offerId);
                        if($panoDir != $offerId) {
                            rename(PUBLIC_PATH . '/media/pano/' . $panoDir, PUBLIC_PATH . '/media/pano/' . $offerId);
                            $this->offersTable->editAlternativIdFile(null, $offerId);
                        }
                    }
                }

                if ($galleryFormData['youtube_code_2']['tmp_name'] != '') {
                    $tempName = $galleryFormData['youtube_code_2']['tmp_name'];
                    $tempName = end(explode("/", $tempName));
                    $galleryFormData['youtube_code_2']['tmp_name'] = $tempName;

                    $filter = new \Zend\Filter\Decompress(array(
                        'adapter' => 'Zip',
                        'options' => array(
                            'target' => PUBLIC_PATH . '/media/video/' . $offerId . '/',
                        )
                    ));
                    $decompressed = $filter->filter(PUBLIC_PATH . '/media/video/' . $offerId . '/' . $tempName);
                    unlink(PUBLIC_PATH . '/media/video/' . $offerId . '/' . $tempName);
                    $this->offersTable->setYoutubeCode2Status($offerId, 'y');
                }

                $this->offersTable->updateYoutubeCode1($galleryFormData['youtube_code_1'], $offerId);

                return $this->redirect()->toRoute('languageRoute/adminOffersGallery', array('offerId' => $offerId));
            }
        }
        return $this->redirect()->toRoute('languageRoute/adminOffersGallery', array('offerId' => $offerId));
    }

    /**
     * Delete offer image from gallery
     *
     * @return \Zend\Http\Response
     */
    public function offersGalleryDeleteAction()
    {

        $imageId = $this->params()->fromRoute('imageId', '');
        $offer = $this->galleryTable->getOfferIdByImage($imageId)->toArray();
        $image = $this->galleryTable->getImage($imageId)->toArray();
        $offerId = $offer['offer_id'];

        try {
            $this->galleryTable->delete($imageId);
            unlink(PUBLIC_PATH . '/media/offers/' . $offerId . '/' . $image['image']);
            unlink(PUBLIC_PATH . '/media/offers/' . $offerId . '/front-' . $image['image']);
        } catch (InvalidQueryException $e) {
            $this->flashMessenger()->addErrorMessage('This image cannot be deleted');
        }

        return $this->redirect()->toRoute('languageRoute/adminOffersGallery', array('offerId' => $offerId));
    }

    /**
     * Exports offers
     *
     * @return ViewModel
     */
    public function offersExportAction()
    {
        $gridSettings = new BaseGridSettings($this->params()->fromQuery());
        $columnsToRemove = array('rawData', 'title', 'description', 'topOffer', 'vipOffer', 'chatOffer', 'schemaOffer', 'currencyId', 'constructionYear', 'floor', 'bathrooms', 'totalRooms', 'parkingSlots', 'information', 'photographerAddress', 'youtubeCode1', 'youtubeCode2', 'google360', 'panoramaFile', 'facebookImg', 'garden', 'metaTitle', 'metaDescription', 'metaKeywords', 'dateUpdated', 'activeUntilDate', 'languageId', 'offerStatusId', 'offerTypeId', 'buildingTypeId', 'propertyTypeId', 'heatingSystemId', 'userId', 'neighbourhoodId', 'cityId', 'street', 'lat', 'lng', 'weeks', 'numCount', 'offerTypeName', 'isRegulated', 'parcelTypeId', 'yard', 'yardShot', 'counter', 'addBy', 'userOfferStatusName', 'image', 'currencyShortName', 'buildingTypeName', 'galleryImage', 'userOfferStatusId', 'heatingSystemName', 'email', 'oldOfferId', 'numResults');
        $headers = array('ID', 'Вид имот', 'Град', 'Квартал', 'Цена', 'Площ', 'Дата на въвеждане', 'Дата на заснемане', 'Агенция', 'Телефон', 'Статус');

        $count = $this->offersTable->getCount($gridSettings);
        $data = array();
        if ($count > 0) {
            $items = $this->offersTable->getData($gridSettings, null, true);

            foreach ($items as $item) {
                $itemAsArray = $item->toArray();
                foreach ($columnsToRemove as $column) {
                    unset($itemAsArray[$column]);
                }
                $data[] = $itemAsArray;
            }
        }

        set_time_limit(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="offers.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $result = new ViewModel(array('data' => $data, 'headers' => $headers));
        $result->setTerminal(true);
        $result->setTemplate('layout/export');
        return $result;
    }

    /**
     * Insert meta tags for offer
     */
    public function insertOfferMetaTagsAction()
    {

        //Get offers without meta title and insert new one
        $offersWithTitleEmptyString = $this->offersTable->getOffersWithoutMetaTags(array('meta_title' => ''))->toArray();
        $offersWithTitleNull = $this->offersTable->getOffersWithoutMetaTags(array('meta_title' => null))->toArray();
        $offersWithoutMetaTitle = array_merge($offersWithTitleEmptyString, $offersWithTitleNull);
        foreach ($offersWithoutMetaTitle as $key => $value) {
            $metaTitle = $value['offerTypeName'] . ' ' . $value['propertyTypeName'] . ' ' . $value['cityName'];
            $this->offersTable->updateMetaTitle($value['id'], $metaTitle);
        }

        //Get offers without meta description and insert new one
        $offersWithDescriptionEmptyString = $this->offersTable->getOffersWithoutMetaTags(array('meta_description' => ''))->toArray();
        $offersWithDescriptionNull = $this->offersTable->getOffersWithoutMetaTags(array('meta_description' => null))->toArray();
        $offersWithoutMetaDescription = array_merge($offersWithDescriptionEmptyString, $offersWithDescriptionNull);
        foreach ($offersWithoutMetaDescription as $key => $value) {
            $metaDescription = strip_tags(mb_substr($value['information'], 0, 100));
            if ($metaDescription == '') {
                $metaDescription = $value['offerTypeName'] . ' ' . $value['propertyTypeName'] . ' ' . $value['cityName'];
            }
            $this->offersTable->updateMetaDescription($value['id'], $metaDescription);
        }

        //Get offers without meta keywords and insert new ones
        $offersWithKeywordsEmptyString = $this->offersTable->getOffersWithoutMetaTags(array('meta_keywords' => ''))->toArray();
        $offersWithKeywordsNull = $this->offersTable->getOffersWithoutMetaTags(array('meta_keywords' => null))->toArray();
        $offersWithoutMetaKeywords = array_merge($offersWithKeywordsEmptyString, $offersWithKeywordsNull);

        foreach ($offersWithoutMetaKeywords as $key => $offerValue) {
            $features = '';
            $featureNames = array();
            $ids = array();
            $metaTitle = $offerValue['offerTypeName'] . ',' . $offerValue['propertyTypeName'] . ',' . $offerValue['cityName'];

            if (($offerValue['propertyTypeId']) != 20) {
                $ids = $this->offerPropertyFeatureTable->getPropertyFeaturesById($offerValue['id']);
            } else {
                $ids = $this->offerParcelFeatureTable->getParcelFeaturesById($offerValue['id']);
            }

            foreach ($ids as $key => $featureId) {
                if (($offerValue['propertyTypeId']) != 20) {
                    $featureNames[] = $this->propertyFeatureTable->getTypesArrayForOffer($featureId);
                } else {
                    $featureNames[] = $this->parcelFeatureTable->getTypesArrayForOffer($featureId);
                }
            }

            foreach ($featureNames as $key => $name) {
                $features .= ',' . $name[0];
            }

            $metaKeywords = $metaTitle . $features;
            $this->offersTable->updateMetaKeywords($offerValue['id'], mb_substr($metaKeywords, 0, 160));
        }
    }

    /**
     * Update the main image
     *
     * @return \Zend\Http\Response
     */
    public function mainImageAction()
    {
        $imageId = $this->params()->fromRoute('imageId');
        $offerId = $this->galleryTable->getOfferIdByImage($imageId)->toArray();

        $image = $this->galleryTable->getImage($imageId)->toArray();
        $this->galleryTable->updateMainImageForOffer($image['image'], $offerId['offer_id']);

        return $this->redirect()->toRoute('languageRoute/adminOffersGallery', array('offerId' => $offerId['offer_id']));
    }


    /**
     * Update image. Remove the old image and insert the new one.
     *
     * @return \Zend\Http\Response
     */
    public function imageUpdateAction()
    {
        $imageId = $this->params()->fromRoute('id');
        $offerId = $this->galleryTable->getOfferIdByImage($imageId)->toArray();
        $oldImage = $this->galleryTable->getImage($imageId);

        $changeImageForm = new ChangeImageForm($offerId);
        $request = $this->getRequest();
        if ($request->isPost()) {

            $post = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
            $changeImageForm->setData($post);

            if ($changeImageForm->isValid()) {
                $changeImageFormData = $changeImageForm->getData();
                $image = $changeImageFormData['image'];

                $pathParts = pathinfo($image['tmp_name']);
                $newFilePath = $pathParts['dirname'] . '/' . $offerId['offer_id'] . '/front-' . $pathParts['filename'] . '.' . $pathParts['extension'];

                $this->galleryTable->updateImage($imageId, $pathParts['filename'] . '.' . $pathParts['extension']);
                ImageManager::resizeImage($image['tmp_name'], $newFilePath);
                rename(PUBLIC_PATH . '/media/offers/' . $pathParts['filename'] . '.' . $pathParts['extension'], PUBLIC_PATH . '/media/offers/' . $offerId['offer_id'] . '/' . $pathParts['filename'] . '.' . $pathParts['extension']);
                unlink($pathParts['dirname'] . '/' . $offerId['offer_id'] . '/' . $oldImage->getField('image'));
                unlink($pathParts['dirname'] . '/' . $offerId['offer_id'] . '/front-' . $oldImage->getField('image'));

                return $this->redirect()->toRoute('languageRoute/adminOffersGallery', array('offerId' => $offerId['offer_id']));
            }
        }
        return $this->redirect()->toRoute('languageRoute/adminOffersGallery', array('offerId' => $offerId['offer_id']));
    }

    /**
     * Moves image up in the ordering.
     *
     * @return \Zend\Http\Response
     */
    public function imageUpAction()
    {
        $imageId = $this->params()->fromRoute('id');
        $offerId = $this->galleryTable->getOfferIdByImage($imageId)->toArray();
        $oldImageOrder = $this->params()->fromRoute('imageOrder');
        $newImageOrder = $oldImageOrder - 1;

        $this->galleryTable->updateImageOrder($imageId, $newImageOrder, $oldImageOrder, $offerId['offer_id']);
        return $this->redirect()->toRoute('languageRoute/adminOffersGallery', array('offerId' => $offerId['offer_id']));

    }

    /**
     * Moves image down in the ordering.
     *
     * @return \Zend\Http\Response
     */
    public function imageDownAction()
    {

        $imageId = $this->params()->fromRoute('id');
        $offerId = $this->galleryTable->getOfferIdByImage($imageId)->toArray();
        $oldImageOrder = $this->params()->fromRoute('imageOrder');
        $newImageOrder = $oldImageOrder + 1;

        $this->galleryTable->updateImageOrder($imageId, $newImageOrder, $oldImageOrder, $offerId['offer_id']);
        return $this->redirect()->toRoute('languageRoute/adminOffersGallery', array('offerId' => $offerId['offer_id']));

    }

    /**
     * Insert image order.
     * Console route.
     */
    public function insertImageOrderAction()
    {
        $offers = $this->galleryTable->getOfferIds()->toArray();
        $ids = [];
        foreach ($offers as $key => $value) {
            array_push($ids, $value['offer_id']);
        }

        foreach ($ids as $key => $value) {
            $images = [];
            $images = $this->galleryTable->getImages($value)->toArray();

            foreach ($images as $key => $value) {
                $this->galleryTable->insertImageOrder($value['id'], $value['offer_id'], $key + 1);
            }
        }
    }
}
