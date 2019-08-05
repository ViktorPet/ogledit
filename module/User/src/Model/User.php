<?php
namespace User\Model;

/**
 * Class User
 * @package User\Model
 */
class User {
    CONST USER_DELETED = 1;
    CONST USER_NOT_DELETED = 0;

    public $rawData;
    
    public $id;
    public $facebookId;
    public $facebookRegComplete;
    public $email;
    public $username;
    public $names;
    public $password;
    public $logo;
    public $userTypeId;
    public $userStatusId;
    public $phone;
    public $subscribed;
    public $director;
    public $vatNumber;
    public $companyAddress;
    public $dateCreated;
    public $dateUpdated;    
    public $verificationCode;    
    public $userDeleted;

    // Additional fields
    public $userStatusName;
    public $agentOffersCount;

    public function exchangeArray($data) {
        $this->rawData = $data;
        
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->facebookId = (!empty($data['facebook_id'])) ? $data['facebook_id'] : null;
        $this->facebookRegComplete = (!empty($data['is_fb_reg_complete'])) ? $data['is_fb_reg_complete'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->names = (!empty($data['names'])) ? $data['names'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->logo = (!empty($data['logo'])) ? $data['logo'] : null;
        $this->userTypeId = (!empty($data['user_type_id'])) ? $data['user_type_id'] : null;
        $this->userStatusId = (!empty($data['user_status_id'])) ? $data['user_status_id'] : null;
        $this->phone = (!empty($data['phone'])) ? $data['phone'] : null;
        $this->subscribed = (!empty($data['subscribed'])) ? $data['subscribed'] : null;
        $this->director = (!empty($data['director'])) ? $data['director'] : null;
        $this->vatNumber = (!empty($data['vat_number'])) ? $data['vat_number'] : null;
        $this->companyAddress = (!empty($data['company_address'])) ? $data['company_address'] : null;
        $this->dateCreated = (!empty($data['date_created'])) ? $data['date_created'] : null;
        $this->dateUpdated = (!empty($data['date_updated'])) ? $data['date_updated'] : null;
        $this->verificationCode = (!empty($data['verification_code'])) ? $data['verification_code'] : null;
        $this->userStatusName = (!empty($data['user_status_name'])) ? $data['user_status_name'] : null;           
        $this->namesEn = (!empty($data['names_en'])) ? $data['names_en'] : null;
        $this->description = (!empty($data['description'])) ? $data['description'] : null;
        $this->descriptionEn = (!empty($data['description_en'])) ? $data['description_en'] : null;
        $this->parentUserId = (!empty($data['parent_user_id'])) ? $data['parent_user_id'] : null;
        $this->priceId = (!empty($data['price_id'])) ? $data['price_id'] : null;
        $this->agentOffersCount = (!empty($data['agent_offers_count'])) ? $data['agent_offers_count'] : null;
        $this->userDeleted = (!empty($data['user_deleted'])) ? $data['user_deleted'] : 0;
    }
    
    /**
     * Converts this object to array.
     *
     * @return array
     */
    public function toArray() {
        return $this->rawData;
    }

    /**
     * @return mixed
     */
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
    public function getFacebookId() {
        return $this->facebookId;
    }

    /**
     * @param mixed $id
     */
    public function setFacebookId($facebookId) {
        $this->facebookId = $facebookId;
    }
    
    /**
     * @return mixed
     */
    public function getFacebookRegComplete() {
        return $this->facebookRegComplete;
    }

    /**
     * @param mixed $facebookRegComplete
     */
    public function setFacebookRegComplete($facebookRegComplete) {
        $this->facebookRegComplete = $facebookRegComplete;
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
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getNames() {
        return $this->names;
    }

    /**
     * @param mixed $names
     */
    public function setNames($names) {
        $this->names = $names;
    }
    
    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getLogo() {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo) {
        $this->logo = $logo;
    }

    /**
     * @return mixed
     */
    public function getUserTypeId() {
        return $this->userTypeId;
    }

    /**
     * @param mixed $userTypeId
     */
    public function setUserTypeId($userTypeId) {
        $this->userTypeId = $userTypeId;
    }

    /**
     * @return mixed
     */
    public function getUserStatusId() {
        return $this->userStatusId;
    }

    /**
     * @param mixed $userStatusId
     */
    public function setUserStatusId($userStatusId) {
        $this->userStatusId = $userStatusId;
    }

    /**
     * @return mixed
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone) {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getSubscribed() {
        return $this->subscribed;
    }

    /**
     * @param mixed $subscribed
     */
    public function setSubscribed($subscribed) {
        $this->subscribed = $subscribed;
    }

    /**
     * @return mixed
     */
    public function getDateCreated() {
        return $this->date_created;
    }

    /**
     * @param mixed $date_created
     */
    public function setDateCreated($date_created) {
        $this->date_created = $date_created;
    }

    /**
     * @return mixed
     */
    public function getDateUpdated() {
        return $this->date_updated;
    }

    /**
     * @param mixed $date_updated
     */
    public function setDateUpdated($date_updated) {
        $this->date_updated = $date_updated;
    }

    /**
     * @return mixed
     */
    public function getUserStatusName() {
        return $this->userStatusName;
    }

    /**
     * @param mixed $userStatusName
     */
    public function setUserStatusName($userStatusName) {
        $this->userStatusName = $userStatusName;
    }

    /**
     * @return mixed
     */
    public function getDirector() {
        return $this->director;
    }

    /**
     * @param mixed $director
     */
    public function setDirector($director) {
        $this->director = $director;
    }

    /**
     * @return mixed
     */
    public function getVatNumber() {
        return $this->vatNumber;
    }

    /**
     * @param mixed $vatNumber
     */
    public function setVatNumber($vatNumber) {
        $this->vatNumber = $vatNumber;
    }

    /**
     * @return mixed
     */
    public function getCompanyAddress() {
        return $this->companyAddress;
    }

    /**
     * @param mixed $companyAddress
     */
    public function setCompanyAddress($companyAddress) {
        $this->companyAddress = $companyAddress;
    }

    /**
     * @return mixed
     */
    public function getNamesEn() {
        return $this->namesEn;
    }

    /**
     * @param mixed $namesEn
     */
    public function setNamesEn($namesEn) {
        $this->namesEn = $namesEn;
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
    public function getDescriptionEn() {
        return $this->descriptionEn;
    }

    /**
     * @param mixed $descriptionEn
     */
    public function setDescriptionEn($descriptionEn) {
        $this->descriptionEn = $descriptionEn;
    }
    
    /**
     * @return mixed
     */
    public function getParentUserId() {
        return $this->parentUserId;
    }

    /**
     * @param mixed $parentUserId
     */
    public function setParentUserId($parentUserId) {
        $this->parentUserId = $parentUserId;
    }
    
    /**
     * @return mixed
     */
    public function getPriceId() {
        return $this->priceId;
    }

    /**
     * @param mixed $priceId
     */
    public function setPriceId($priceId) {
        $this->priceId = $priceId;
    }
    
    /**
     * @return mixed
     */
    public function getAgentOffersCount() {
        return $this->agentOffersCount;
    }

    /**
     * @param mixed $priceId
     */
    public function setAgentOffersCount($agentOffersCount) {
        $this->agentOffersCount = $agentOffersCount;
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
    public function getVerificationCode() {
        return $this->verificationCode;
    }

    /**
     * @param mixed $verificationCode
     */
    public function setVerificationCode($verificationCode) {
        $this->verificationCode = $verificationCode;
    }

    /**
     * @return mixed
     */
    public function getUserDeleted()
    {
        return $this->userDeleted;
    }

    /**
     * @param mixed $userDeleted
     */
    public function setUserDeleted($userDeleted)
    {
        $this->userDeleted = $userDeleted;
    }

}