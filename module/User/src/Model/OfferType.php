<?php
namespace User\Model;

/**
 * Class OfferType
 * @package User\Model
 */
class OfferType {
    public $id;
    public $name;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        
        $this->numCount = (!empty($data['num_count'])) ? $data['num_count'] : 0;
        $this->numActive = (!empty($data['num_active'])) ? $data['num_active'] : 0;
        $this->numVipOffer = (!empty($data['num_vip_offer'])) ? $data['num_vip_offer'] : 0;
        $this->numTopOffer = (!empty($data['num_top_offer'])) ? $data['num_top_offer'] : 0;
        $this->numChatOffer = (!empty($data['num_chat_offer'])) ? $data['num_chat_offer'] : 0;
        $this->numSchemaOffer = (!empty($data['num_schema_offer'])) ? $data['num_schema_offer'] : 0;
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
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
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