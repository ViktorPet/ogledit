<?php
namespace User\Model;

/**
 * Class Neighbourhood
 * @package User\Model
 */
class Neighbourhood {
    public $neighbourhoodId;
    public $neighbourhoodName;
    public $cityId;

    public function exchangeArray($data) {
        $this->neighbourhoodId = (!empty($data['neighbourhood_id'])) ? $data['neighbourhood_id'] : null;
        $this->neighbourhoodName = (!empty($data['neighbourhood_name'])) ? $data['neighbourhood_name'] : null;
        $this->cityId = (!empty($data['city_id'])) ? $data['city_id'] : null;
    }

    /**
     * @return mixed
     */
    public function getNeighbourhoodId() {
        return $this->neighbourhoodId;
    }

    /**
     * @param mixed $neighbourhoodId
     */
    public function setNeighbourhoodId($neighbourhoodId) {
        $this->neighbourhoodId = $neighbourhoodId;
    }

    /**
     * @return mixed
     */
    public function getNeighbourhoodName() {
        return $this->neighbourhoodName;
    }

    /**
     * @param mixed $neighbourhoodName
     */
    public function setNeighbourhoodName($neighbourhoodName) {
        $this->neighbourhoodName = $neighbourhoodName;
    }

    /**
     * @return mixed
     */
    public function getCityId() {
        return $this->cityId;
    }

    /**
     * @param mixed $cityId
     */
    public function setCityId($cityId) {
        $this->cityId = $cityId;
    }
    
}