<?php
namespace User\Model;

/**
 * Class PropertyFeature
 * @package User\Model
 */
class PropertyFeature {
    public $id;
    public $name;
    public $propertyFeatureOrder;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->propertyFeatureOrder = (!empty($data['property_feature_order'])) ? $data['property_feature_order'] : null;
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
    public function getPropertyFeatureOrder() {
        return $this->propertyFeatureOrder;
    }

    /**
     * @param mixed $propertyFeatureOrder
     */
    public function setPropertyFeatureOrder($propertyFeatureOrder) {
        $this->propertyFeatureOrder = $propertyFeatureOrder;
    }
    
}