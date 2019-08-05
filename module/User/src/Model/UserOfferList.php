<?php

namespace User\Model;

/**
 * Description of UserOfferList
 *
 */
class UserOfferList {
   
    public $link;
    public $offerId;
    public $userId;
    public $dateCreated;

    public function exchangeArray($data) {

        $this->link = (!empty($data['link'])) ? $data['link'] : null;
        $this->offerId = (!empty($data['offer_id'])) ? $data['offer_id'] : null;
        $this->userId = (!empty($data['user_id'])) ? $data['user_id'] : null;
        $this->dateCreated = (!empty($data['date_created'])) ? $data['date_created'] : null;
        
    }
    
        /**
     * @return mixed
     */
    public function getOfferId() {
        return $this->offerId;
    }

    /**
     * @param mixed $id
     */
    public function setOfferId($offerId) {
        $this->offerId = $offerId;
    }
    
        /**
     * @return mixed
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @param mixed $id
     */
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * @param mixed $name
     */
    public function setLink($link) {
        $this->link = $link;
    }
    
    /**
     * @return mixed
     */
    public function getDateCreated() {
        return $this->dateCreated;
    }

    /**
     * @param mixed $name
     */
    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
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
}
