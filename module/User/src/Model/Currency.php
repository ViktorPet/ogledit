<?php
namespace User\Model;

/**
 * Class Currency
 * @package User\Model
 */
class Currency {
    public $id;
    public $name;
    public $currencyOrder;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->currencyOrder = (!empty($data['currency_order'])) ? $data['currency_order'] : null;
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
    public function getCurrencyOrder() {
        return $this->currencyOrder;
    }

    /**
     * @param mixed $currencyOrder
     */
    public function setCurrencyOrder($currencyOrder) {
        $this->currencyOrder = $currencyOrder;
    }
    
}