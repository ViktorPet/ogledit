<?php
namespace User\Model;

/**
 * Class ParcelType
 * @package User\Model
 */
class ParcelType {
    public $id;
    public $name;
    public $parcelTypeOrder;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->parcelTypeOrder = (!empty($data['parcel_type_order'])) ? $data['parcel_type_order'] : null;
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
    public function getParcelTypeOrder() {
        return $this->parcelTypeOrder;
    }

    /**
     * @param mixed $parcelTypeOrder
     */
    public function setParcelTypeOrder($parcelTypeOrder) {
        $this->parcelTypeOrder = $parcelTypeOrder;
    }
    
}