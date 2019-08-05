<?php
namespace User\Model;

/**
 * Class PropertyType
 * @package User\Model
 */
class PropertyType {
    public $id;
    public $name;
    public $propertyTypeOrder;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->propertyTypeOrder = (!empty($data['property_type_order'])) ? $data['property_type_order'] : null;
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
    public function getPropertyTypeOrder() {
        return $this->propertyTypeOrder;
    }

    /**
     * @param mixed $propertyTypeOrder
     */
    public function setPropertyTypeOrder($propertyTypeOrder) {
        $this->propertyTypeOrder = $propertyTypeOrder;
    }
    
}