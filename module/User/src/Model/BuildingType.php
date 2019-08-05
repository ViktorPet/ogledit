<?php
namespace User\Model;

/**
 * Class BuildingType
 * @package User\Model
 */
class BuildingType {
    public $id;
    public $name;
    public $buildingTypeOrder;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->buildingTypeOrder = (!empty($data['building_type_order'])) ? $data['building_type_order'] : null;
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
    public function getBuildingTypeOrder() {
        return $this->buildingTypeOrder;
    }

    /**
     * @param mixed $buildingTypeOrder
     */
    public function setBuildingTypeOrder($buildingTypeOrder) {
        $this->buildingTypeOrder = $buildingTypeOrder;
    }
    
}