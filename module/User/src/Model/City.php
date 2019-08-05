<?php
namespace User\Model;

/**
 * Class City
 * @package User\Model
 */
class City {
    public $id;
    public $name;
    public $parentId;
    public $cityOrder;
    public $number;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->parentId = (!empty($data['parent_id'])) ? $data['parent_id'] : null;
        $this->cityOrder = (!empty($data['city_order'])) ? $data['city_order'] : null;
        $this->number = (!empty($data['number'])) ? $data['number'] : null;
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
    public function getParentId() {
        return $this->parentId;
    }

    /**
     * @param mixed $parentId
     */
    public function setParentId($parentId) {
        $this->parentId = $parentId;
    }

    /**
     * @return mixed
     */
    public function getCityOrder() {
        return $this->cityOrder;
    }

    /**
     * @param mixed $cityOrder
     */
    public function setCityOrder($cityOrder) {
        $this->cityOrder = $cityOrder;
    }

    public function getNumber() {
        return $this->number;
    }


    public function setNumber($number) {
        $this->number = $number;
    }
}