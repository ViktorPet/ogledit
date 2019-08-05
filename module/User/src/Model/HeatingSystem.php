<?php
namespace User\Model;

/**
 * Class BuildingType
 * @package User\Model
 */
class HeatingSystem {
    public $id;
    public $name;
    public $heatingSystemOrder;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->heatingSystemOrder = (!empty($data['heating_system_order'])) ? $data['heating_system_order'] : null;
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
    public function getHeatingSystemOrder() {
        return $this->heatingSystemOrder;
    }

    /**
     * @param mixed $heatingSystemOrder
     */
    public function setHeatingSystemOrder($heatingSystemOrder) {
        $this->heatingSystemOrder = $heatingSystemOrder;
    }
    
}