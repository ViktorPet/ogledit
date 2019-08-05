<?php

namespace Application\Model;

/**
 * Class Offer
 * @package User\Model
 */
class Offer {

    const FOR_POSTING = 1;
    const PAID = 2;
    const FOR_SHOOTING = 3;
    const OFFER_STATUS_ACTIVE = 4;
    const EXPIRED = 5;
    const ARCHIVED = 6;
    const TO_STOP = 7;
    const STOPPED = 8;
    const DELETED = 11;

    public $rawData;
    
    public $id;
    public $propertyTypeName;
    public $cityName;
    public $neighbourhoodName;
    public $price;
    public $area;
    public $dateCreated;
    public $photographerAppointment;    
    public $userNames;
    public $userPhone; 
    public $offerStatusName;
    public $title;
    public $description;
    public $topOffer;
    public $vipOffer;
    public $chatOffer;
    public $schemaOffer;    
    public $currencyId;
    public $constructionYear;    
    public $floor;
    public $bathrooms;
    public $totalRooms;
    public $parkingSlots;
    public $information;
    public $photographerAddress;
    public $youtubeCode1;
    public $youtubeCode2;
    public $google360;
    public $panoramaFile;
    public $facebookImg;
    public $garden;
    public $metaTitle;
    public $metaDescription;
    public $metaKeywords;
    public $dateUpdated;
    public $activeUntilDate;
    public $extraUntilDate;
    public $languageId;
    public $offerStatusId;
    public $offerTypeId;
    public $buildingTypeId;
    public $propertyTypeId;
    public $heatingSystemId;
    public $userId;
    public $neighbourhoodId;
    public $cityId;
    public $street;
    public $lat;
    public $lng;
    public $weeks;
    public $extraWeeks;
    public $numCount;
    public $offerTypeName;
    public $isRegulated;
    public $parcelTypeId;
    public $yard;
    public $yardShot;
    public $counter;
    public $addBy;
    public $userOfferStatusName;
    public $buildingTypeName;
    public $image;
    public $currencyShortName;
    public $galleryImage;
    public $userOfferStatusId;
    public $heatingSystemName;
    public $email;
    public $oldOfferId;
    public $externalPanorama;
    public $userPanoramaFile;
    public $hasAddressFields;
    public $parentUser;
    public $alternativeIdFile;
//    public $offersCount;

    public $numResults;
    
    public function exchangeArray($data) {
        $this->rawData = $data;
        
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->propertyTypeName = (!empty($data['property_type_name'])) ? $data['property_type_name'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->description = (!empty($data['description'])) ? $data['description'] : null;
        $this->topOffer = (!empty($data['top_offer'])) ? $data['top_offer'] : null;
        $this->vipOffer = (!empty($data['vip_offer'])) ? $data['vip_offer'] : null;
        $this->chatOffer = (!empty($data['chat_offer'])) ? $data['chat_offer'] : null;
        $this->schemaOffer = (!empty($data['schema_offer'])) ? $data['schema_offer'] : null;
        $this->price = (!empty($data['price'])) ? $data['price'] : null;
        $this->currencyId = (!empty($data['currency_id'])) ? $data['currency_id'] : null;
        $this->constructionYear = (!empty($data['construction_year'])) ? $data['construction_year'] : null;
        $this->area = (!empty($data['area'])) ? $data['area'] : null;
        $this->floor = (!empty($data['floor'])) ? $data['floor'] : null;
        $this->bathrooms = (!empty($data['bathrooms'])) ? $data['bathrooms'] : null;
        $this->totalRooms = (!empty($data['total_rooms'])) ? $data['total_rooms'] : null;
        $this->parkingSlots = (!empty($data['parking_slots'])) ? $data['parking_slots'] : null;
        $this->information = (!empty($data['information'])) ? $data['information'] : null;
        $this->photographerAddress = (!empty($data['photographer_address'])) ? $data['photographer_address'] : null;
        $this->photographerAppointment = (!empty($data['photographer_appointment'])) ? $data['photographer_appointment'] : null;
        $this->youtubeCode1 = (!empty($data['youtube_code_1'])) ? $data['youtube_code_1'] : null;
        $this->youtubeCode2 = (!empty($data['youtube_code_2'])) ? $data['youtube_code_2'] : null;
        $this->google360 = (!empty($data['google_360'])) ? $data['google_360'] : null;
        $this->panoramaFile = (!empty($data['panorama_file'])) ? $data['panorama_file'] : null;
        $this->facebookImg = (!empty($data['facebook_img'])) ? $data['facebook_img'] : null;
        $this->garden = (!empty($data['garden'])) ? $data['garden'] : null;
        $this->metaTitle = (!empty($data['meta_title'])) ? $data['meta_title'] : null;
        $this->metaDescription = (!empty($data['meta_description'])) ? $data['meta_description'] : null;
        $this->metaKeywords = (!empty($data['meta_keywords'])) ? $data['meta_keywords'] : null;
        $this->dateCreated = (!empty($data['date_created'])) ? $data['date_created'] : null;
        $this->dateUpdated = (!empty($data['date_updated'])) ? $data['date_updated'] : null;
        $this->activeUntilDate = (!empty($data['active_until_date'])) ? $data['active_until_date'] : null;
        $this->extraUntilDate = (!empty($data['extra_until_date'])) ? $data['extra_until_date'] : null;
        $this->languageId = (!empty($data['language_id'])) ? $data['language_id'] : null;
        $this->offerStatusId = (!empty($data['offer_status_id'])) ? $data['offer_status_id'] : 1;
        $this->offerTypeId = (!empty($data['offer_type_id'])) ? $data['offer_type_id'] : null;
        $this->buildingTypeId = (!empty($data['building_type_id'])) ? $data['building_type_id'] : null;
        $this->propertyTypeId = (!empty($data['property_type_id'])) ? $data['property_type_id'] : null;
        $this->heatingSystemId = (!empty($data['heating_system_id'])) ? $data['heating_system_id'] : null;
        $this->userId = (!empty($data['user_id'])) ? $data['user_id'] : null;
        $this->neighbourhoodId = (!empty($data['neighbourhood_id'])) ? $data['neighbourhood_id'] : null;
        $this->cityId = (!empty($data['city_id'])) ? $data['city_id'] : null;
        $this->street = (!empty($data['street'])) ? $data['street'] : null;
        $this->lat = (!empty($data['lat'])) ? $data['lat'] : null;
        $this->lng = (!empty($data['lng'])) ? $data['lng'] : null;
        $this->weeks = (!empty($data['weeks'])) ? $data['weeks'] : null;
        $this->extraWeeks = (!empty($data['extra_weeks'])) ? $data['extra_weeks'] : null;
        $this->isRegulated = (!empty($data['is_regulated'])) ? $data['is_regulated'] : null;
        $this->parcelTypeId = (!empty($data['parcel_type_id'])) ? $data['parcel_type_id'] : null;
        $this->yard = (!empty($data['yard'])) ? $data['yard'] : null;
        $this->yardShot = (!empty($data['yard_shot'])) ? $data['yard_shot'] : null;
        $this->counter = (!empty($data['counter'])) ? $data['counter'] : null;
        $this->addBy = (!empty($data['add_by'])) ? $data['add_by'] : null;
        $this->numCount = (!empty($data['num_count'])) ? $data['num_count'] : 0;
        $this->offerTypeName = (!empty($data['offer_type_name'])) ? $data['offer_type_name'] : null;        
        $this->userOfferStatusId = (!empty($data['user_offer_status_id'])) ? $data['user_offer_status_id'] : null;
        $this->userPanoramaFile = (!empty($data['user_panorama_file'])) ? $data['user_panorama_file'] : null;

        $this->userOfferStatusName = (!empty($data['user_offer_status_name'])) ? $data['user_offer_status_name'] : null;
        $this->offerStatusName = (!empty($data['offer_status_name'])) ? $data['offer_status_name'] : null;
        $this->buildingTypeName = (!empty($data['building_type_name'])) ? $data['building_type_name'] : null;
        $this->heatingSystemName = (!empty($data['heating_system_name'])) ? $data['heating_system_name'] : null;
        $this->cityName = (!empty($data['city_name'])) ? $data['city_name'] : null;
        $this->neighbourhoodName = (!empty($data['neighbourhood_name'])) ? $data['neighbourhood_name'] : null;
        $this->image = (!empty($data['image'])) ? $data['image'] : null;
        $this->galleryImage = (!empty($data['gallery_image'])) ? $data['gallery_image'] : null;
        $this->currencyShortName = (!empty($data['currency_short_name'])) ? $data['currency_short_name'] : null;

        $this->userPhone = (!empty($data['user_phone'])) ? $data['user_phone'] : null;
        $this->userNames = (!empty($data['user_names'])) ? $data['user_names'] : null;
        $this->parentUser = (!empty($data['parent_user_id'])) ? $data['parent_user_id'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->oldOfferId = (!empty($data['old_offer_id'])) ? $data['old_offer_id'] : null;
        $this->externalPanorama = (!empty($data['external_panorama'])) ? $data['external_panorama'] : null;
        $this->hasAddressFields = (!empty($data['has_address_fields'])) ? $data['has_address_fields'] : null;
        
        $this->numResults = (!empty($data['num_results'])) ? $data['num_results'] : null;
        $this->alternativeIdFile = (!empty($data['alternative_id_file'])) ? $data['alternative_id_file'] : null;
//        $this->offersCount = (!empty($data['offers_count'])) ? $data['offers_count'] : null;
    }  
    
    /**
     * @return mixed
     */
    public function toArray() {
        $vars = get_object_vars($this);
        $array = array();
        foreach ($vars as $key => $value) {
            $array [ltrim($key, '_')] = $value;
        }
        return $array;
    }

    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getTopOffer() {
        return $this->topOffer;
    }

    /**
     * @param mixed $topOffer
     */
    public function setTopOffer($topOffer) {
        $this->topOffer = $topOffer;
    }

    /**
     * @return mixed
     */
    public function getVipOffer() {
        return $this->vipOffer;
    }

    /**
     * @param mixed $vipOffer
     */
    public function setVipOffer($vipOffer) {
        $this->vipOffer = $vipOffer;
    }

    /**
     * @return mixed
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price) {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getCurrencyId() {
        return $this->currencyId;
    }

    /**
     * @param mixed $currencyId
     */
    public function setCurrencyId($currencyId) {
        $this->currencyId = $currencyId;
    }

    /**
     * @return mixed
     */
    public function getConstructionYear() {
        return $this->constructionYear;
    }

    /**
     * @param mixed $constructionYear
     */
    public function setConstructionYear($constructionYear) {
        $this->constructionYear = $constructionYear;
    }

    /**
     * @return mixed
     */
    public function getArea() {
        return $this->area;
    }

    /**
     * @param mixed $area
     */
    public function setArea($area) {
        $this->area = $area;
    }

    /**
     * @return mixed
     */
    public function getFloor() {
        return $this->floor;
    }

    /**
     * @param mixed $floor
     */
    public function setFloor($floor) {
        $this->floor = $floor;
    }

    /**
     * @return mixed
     */
    public function getBathrooms() {
        return $this->bathrooms;
    }

    /**
     * @param mixed $bathrooms
     */
    public function setBathrooms($bathrooms) {
        $this->bathrooms = $bathrooms;
    }

    /**
     * @return mixed
     */
    public function getTotalRooms() {
        return $this->totalRooms;
    }

    /**
     * @param mixed $totalRooms
     */
    public function setTotalRooms($totalRooms) {
        $this->totalRooms = $totalRooms;
    }

    /**
     * @return mixed
     */
    public function getParkingSlots() {
        return $this->parkingSlots;
    }

    /**
     * @param mixed $parkingSlots
     */
    public function setParkingSlots($parkingSlots) {
        $this->parkingSlots = $parkingSlots;
    }

    /**
     * @return mixed
     */
    public function getInformation() {
        return $this->information;
    }

    /**
     * @param mixed $information
     */
    public function setInformation($information) {
        $this->information = $information;
    }
    
    /**
     * @return mixed
     */
    public function getFacebookImage() {
        return $this->facebookImg;
    }

    /**
     * @param mixed $information
     */
    public function setFacebookImage($facebookImg) {
        $this->facebookImg = $facebookImg;
    }

    /**
     * @return mixed
     */
    public function getPhotographerAddress() {
        return $this->photographerAddress;
    }

    /**
     * @param mixed $photographerAddress
     */
    public function setPhotographerAddress($photographerAddress) {
        $this->photographerAddress = $photographerAddress;
    }

    /**
     * @return mixed
     */
    public function getPhotographerAppointment() {
        return $this->photographerAppointment;
    }

    /**
     * @param mixed $photographerAppointment
     */
    public function setPhotographerAppointment($photographerAppointment) {
        $this->photographerAppointment = $photographerAppointment;
    }

    /**
     * @return mixed
     */
    public function getYoutubeCode1() {
        return $this->youtubeCode1;
    }

    /**
     * @param mixed $youtubeCode1
     */
    public function setYoutubeCode1($youtubeCode1) {
        $this->youtubeCode1 = $youtubeCode1;
    }

    /**
     * @return mixed
     */
    public function getYoutubeCode2() {
        return $this->youtubeCode2;
    }

    /**
     * @param mixed $youtubeCode2
     */
    public function setYoutubeCode2($youtubeCode2) {
        $this->youtubeCode2 = $youtubeCode2;
    }

    /**
     * @return mixed
     */
    public function getGoogle360() {
        return $this->google360;
    }

    /**
     * @param mixed $google360
     */
    public function setGoogle360($google360) {
        $this->google360 = $google360;
    }

    /**
     * @return mixed
     */
    public function getPanoramaFile() {
        return $this->panoramaFile;
    }

    /**
     * @param mixed $panoramaFile
     */
    public function setPanoramaFile($panoramaFile) {
        $this->panoramaFile = $panoramaFile;
    }

    /**
     * @return mixed
     */
    public function getGarden() {
        return $this->garden;
    }

    /**
     * @param mixed $garden
     */
    public function setGarden($garden) {
        $this->garden = $garden;
    }

    /**
     * @return mixed
     */
    public function getMetaTitle() {
        return $this->metaTitle;
    }

    /**
     * @param mixed $metaTitle
     */
    public function setMetaTitle($metaTitle) {
        $this->metaTitle = $metaTitle;
    }

    /**
     * @return mixed
     */
    public function getMetaDescription() {
        return $this->metaDescription;
    }

    /**
     * @param mixed $metaDescription
     */
    public function setMetaDescription($metaDescription) {
        $this->metaDescription = $metaDescription;
    }

    /**
     * @return mixed
     */
    public function getMetaKeywords() {
        return $this->metaKeywords;
    }

    /**
     * @param mixed $metaKeywords
     */
    public function setMetaKeywords($metaKeywords) {
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * @return mixed
     */
    public function getDateCreated() {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return mixed
     */
    public function getDateUpdated() {
        return $this->dateUpdated;
    }

    /**
     * @param mixed $dateUpdated
     */
    public function setDateUpdated($dateUpdated) {
        $this->dateUpdated = $dateUpdated;
    }

    /**
     * @return mixed
     */
    public function getLanguageId() {
        return $this->languageId;
    }

    /**
     * @param mixed $languageId
     */
    public function setLanguageId($languageId) {
        $this->languageId = $languageId;
    }

    /**
     * @return mixed
     */
    public function getOfferStatusId() {
        return $this->offerStatusId;
    }

    /**
     * @param mixed $offerStatusId
     */
    public function setOfferStatusId($offerStatusId) {
        $this->offerStatusId = $offerStatusId;
    }

    /**
     * @return mixed
     */
    public function getOfferTypeId() {
        return $this->offerTypeId;
    }

    /**
     * @param mixed $offerTypeId
     */
    public function setOfferTypeId($offerTypeId) {
        $this->offerTypeId = $offerTypeId;
    }

    /**
     * @return mixed
     */
    public function getBuildingTypeId() {
        return $this->buildingTypeId;
    }

    /**
     * @param mixed $buildingTypeId
     */
    public function setBuildingTypeId($buildingTypeId) {
        $this->buildingTypeId = $buildingTypeId;
    }

    /**
     * @return mixed
     */
    public function getPropertyTypeId() {
        return $this->propertyTypeId;
    }

    /**
     * @param mixed $propertyTypeId
     */
    public function setPropertyTypeId($propertyTypeId) {
        $this->propertyTypeId = $propertyTypeId;
    }

    /**
     * @return mixed
     */
    public function getHeatingSystemId() {
        return $this->heatingSystemId;
    }

    /**
     * @param mixed $heatingSystemId
     */
    public function setHeatingSystemId($heatingSystemId) {
        $this->heatingSystemId = $heatingSystemId;
    }

    /**
     * @return mixed
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getNeighbourhoodId() {
        return $this->neighbourhoodId;
    }

    /**
     * @param mixed $neighbourhoodId
     */
    public function setNeighbourhoodId($neighbourhoodId) {
        $this->neighbourhoodId = $neighbourhoodId;
    }

    /**
     * @return mixed
     */
    public function getCityId() {
        return $this->cityId;
    }

    /**
     * @param mixed $cityId
     */
    public function setCityId($cityId) {
        $this->cityId = $cityId;
    }

    /**
     * @return mixed
     */
    public function getStreet() {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street) {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getLat() {
        return $this->lat;
    }

    /**
     * @param mixed $lat
     */
    public function setLat($lat) {
        $this->lat = $lat;
    }

    /**
     * @return mixed
     */
    public function getLng() {
        return $this->lng;
    }

    /**
     * @param mixed $lng
     */
    public function setLng($lng) {
        $this->lng = $lng;
    }

    /**
     * @return mixed
     */
    public function getWeeks() {
        return $this->weeks;
    }

    /**
     * @param mixed $weeks
     */
    public function setWeeks($weeks) {
        $this->weeks = $weeks;
    }

    /**
     * @return mixed
     */
    public function getExtraWeeks() {
        return $this->extraWeeks;
    }

    /**
     * @param mixed $extraWeeks
     */
    public function setExtraWeeks($extraWeeks) {
        $this->extraWeeks = $extraWeeks;
    }

    
    /**
     * @return mixed
     */
    public function getNumCount() {
        return $this->numCount;
    }

    /**
     * @param mixed $numCount
     */
    public function setNumCount($numCount) {
        $this->numCount = $numCount;
    }

    /**
     * @return mixed
     */
    public function getOfferTypeName() {
        return $this->offerTypeName;
    }

    /**
     * @param mixed $offerTypeName
     */
    public function setOfferTypeName($offerTypeName) {
        $this->offerTypeName = $offerTypeName;
    }

    /**
     * @return mixed
     */
    public function getPropertyTypeName() {
        return $this->propertyTypeName;
    }

    /**
     * @param mixed $propertyTypeName
     */
    public function setPropertyTypeName($propertyTypeName) {
        $this->propertyTypeName = $propertyTypeName;
    }

    /**
     * @return mixed
     */
    public function getChatOffer() {
        return $this->chatOffer;
    }

    /**
     * @param mixed $chatOffer
     */
    public function setChatOffer($chatOffer) {
        $this->chatOffer = $chatOffer;
    }

    /**
     * @return mixed
     */
    public function getSchemaOffer() {
        return $this->schemaOffer;
    }

    /**
     * @param mixed $schemaOffer
     */
    public function setSchemaOffer($schemaOffer) {
        $this->schemaOffer = $schemaOffer;
    }

    /**
     * @return mixed
     */
    public function getActiveUntilDate() {
        return $this->activeUntilDate;
    }

    /**
     * @param mixed $activeUntilDate
     */
    public function setActiveUntilDate($activeUntilDate) {
        $this->activeUntilDate = $activeUntilDate;
    }

    /**
     * @return mixed
     */
    public function getExtraUntilDate() {
        return $this->extraUntilDate;
    }

    /**
     * @param mixed $extraUntilDate
     */
    public function setExtraUntilDate($extraUntilDate) {
        $this->extraUntilDate = $extraUntilDate;
    }

    /**
     * @return mixed
     */
    public function getIsRegulated() {
        return $this->isRegulated;
    }

    /**
     * @param mixed $isRegulated
     */
    public function setIsRegulated($isRegulated) {
        $this->isRegulated = $isRegulated;
    }

    /**
     * @return mixed
     */
    public function getOfferStatusName() {
        return $this->offerStatusName;
    }

    /**
     * @param mixed $offerStatusName
     */
    public function setOfferStatusName($offerStatusName) {
        $this->offerStatusName = $offerStatusName;
    }

    /**
     * @return mixed
     */
    public function getBuildingTypeName() {
        return $this->buildingTypeName;
    }

    /**
     * @param mixed $buildingTypeName
     */
    public function setBuildingTypeName($buildingTypeName) {
        $this->buildingTypeName = $buildingTypeName;
    }
    
    /**
     * @return mixed
     */
    public function getHeatingSystemName() {
        return $this->heatingSystemName;
    }

    /**
     * @param mixed $buildingTypeName
     */
    public function setHeatingSystemName($heatingSystemName) {
        $this->heatingSystemName = $heatingSystemName;
    }

    /**
     * @return mixed
     */
    public function getCityName() {
        return $this->cityName;
    }

    /**
     * @param mixed $cityName
     */
    public function setCityName($cityName) {
        $this->cityName = $cityName;
    }

    /**
     * @return mixed
     */
    public function getNeighbourhoodName() {
        return $this->neighbourhoodName;
    }

    /**
     * @param mixed $neighbourhoodName
     */
    public function setNeighbourhoodName($neighbourhoodName) {
        $this->neighbourhoodName = $neighbourhoodName;
    }

    /**
     * @return mixed
     */
    public function getNumResults() {
        return $this->numResults;
    }

    /**
     * @param mixed $numResults
     */
    public function setNumResults($numResults) {
        $this->numResults = $numResults;
    }

    /**
     * @return mixed
     */
    public function getParcelTypeId() {
        return $this->parcelTypeId;
    }

    /**
     * @param mixed $parcelTypeId
     */
    public function setParcelTypeId($parcelTypeId) {
        $this->parcelTypeId = $parcelTypeId;
    }

    /**
     * @return mixed
     */
    public function getYard() {
        return $this->yard;
    }

    /**
     * @param mixed $yard
     */
    public function setYard($yard) {
        $this->yard = $yard;
    }
    
    
    /**
     * @return mixed
     */
    public function getYardShot() {
        return $this->yardShot;
    }

    /**
     * @param mixed $yard
     */
    public function setYardShot($yardShot) {
        $this->yardShot = $yardShot;
    }

    /**
     * @return mixed
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image) {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getCurrencyShortName() {
        return $this->currencyShortName;
    }

    /**
     * @param mixed $currencyShortName
     */
    public function setCurrencyShortName($currencyShortName) {
        $this->currencyShortName = $currencyShortName;
    }

    /**
     * @return mixed
     */
    public function getRawData() {
        return $this->rawData;
    }

    /**
     * @param mixed $rawData
     */
    public function setRawData($rawData) {
        $this->rawData = $rawData;
    }

    /**
     * @return mixed
     */
    public function getCounter() {
        return $this->counter;
    }

    /**
     * @param mixed $counter
     */
    public function setCounter($counter) {
        $this->counter = $counter;

    }
    /**
     * @return mixed
     */
    public function getAddBy() {
        return $this->addBy;
    }

    /**
     * @param mixed $addBy
     */
    public function setAddBy($addBy) {
        $this->addBy = $addBy;

    }

    /**
     * @return mixed
     */
    public function getUserName() {
        return $this->userNames;
    }

    /**
     * @param mixed $userNames
     */
    public function setUserName($userNames) {
        $this->userNames = $userNames;
    }
    
    /**
     * @return mixed
     */
    public function getUserPhone() {
        return $this->userPhone;
    }

    /**
     * @param mixed $userPhone
     */
    public function setUserPhone($userPhone) {
        $this->userPhone = $userPhone;
    }
    
    /**
     * @return mixed
     */
    public function getUserOfferStatusId() {
        return $this->userOfferStatusId;
    }

    /**
     * @param mixed $userPhone
     */
    public function setUserOfferStatusId($userOfferStatusId) {
        $this->userOfferStatusId = $userOfferStatusId;
    }
    
    /**
     * @return mixed
     */
    public function getUserOfferStatusName() {
        return $this->userOfferStatusName;
    }

    /**
     * @param mixed $userPhone
     */
    public function setUserOfferStatusName($userOfferStatusName) {
        $this->userOfferStatusName = $userOfferStatusName;
    }

    /**
     * @return mixed
     */
    public function getFacebookImg() {
        return $this->facebookImg;
    }

    /**
     * @param mixed $facebookImg
     */
    public function setFacebookImg($facebookImg) {
        $this->facebookImg = $facebookImg;
    }

    /**
     * @return mixed
     */
    public function getUserNames() {
        return $this->userNames;
    }

    /**
     * @param mixed $userNames
     */
    public function setUserNames($userNames) {
        $this->userNames = $userNames;
    }

    /**
     * @return mixed
     */
    public function getGalleryImage() {
        return $this->galleryImage;
    }

    /**
     * @param mixed $galleryImage
     */
    public function setGalleryImage($galleryImage) {
        $this->galleryImage = $galleryImage;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }
        
    /**
     * @return mixed
     */
    public function getOldOfferId() {
        return $this->oldOfferId;
    }

    /**
     * @param mixed $email
     */
    public function setOldOfferId($oldOfferId) {
        $this->oldOfferId = $oldOfferId;
    }

    /**
     * @return mixed
     */
    public function getExternalPanorama() {
        return $this->externalPanorama;
    }

    /**
     * @param mixed $email
     */
    public function setExternalPanorama($externalPanorama) {
        $this->externalPanorama = $externalPanorama;
    }

    /**
     * @return mixed
     */
    public function getHasAddressFields() {
        return $this->hasAddressFields;
    }

    /**
     * @param mixed $email
     */
    public function setHasAddressFields($hasAddressFields) {
        $this->hasAddressFields = $hasAddressFields;
    }

    /**
     * @return mixed
     */
    public function getUserPanoramaFile() {
        return $this->userPanoramaFile;
    }

    /**
     * @param mixed $email
     */
    public function setUserPanoramaFile($userPanoramaFile) {
        $this->userPanoramaFile = $userPanoramaFile;
    }

    /**
     * @return mixed
     */
    public function getParentUser() {
        return $this->parentUser;
    }

    /**
     * @param mixed $email
     */
    public function setParentUser($parentUser) {
        $this->parentUser = $parentUser;
    }

    /**
     * @return mixed
     */
    public function getAlternativeIdFile() {
        return $this->alternativeIdFile;
    }

    /**
     * @param $alternativeIdFile
     */
    public function setAlternativeIdFile($alternativeIdFile) {
        $this->alternativeIdFile = $alternativeIdFile;
    }

}
