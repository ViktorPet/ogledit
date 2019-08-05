<?php
namespace User\Model;

/**
 * Class ParcelFeature
 * @package User\Model
 */
class ParcelFeature {
    public $id;
    public $name;
    public $parcelFeatureOrder;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->parcelFeatureOrder = (!empty($data['parcel_feature_order'])) ? $data['parcel_feature_order'] : null;
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
    public function getParcelFeatureOrder() {
        return $this->parcelFeatureOrder;
    }

    /**
     * @param mixed $parcelFeatureOrder
     */
    public function setParcelFeatureOrder($parcelFeatureOrder) {
        $this->parcelFeatureOrder = $parcelFeatureOrder;
    }
    
}