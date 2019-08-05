<?php
namespace User\Model;

/**
 * Class OfferPropertyFeature
 * @package User\Model
 */
class OfferPropertyFeature {
    public $offerId;
    public $propertyFeatureId;

    public $name;
    public $numResults;

    public function exchangeArray($data) {
        $this->offerId = (!empty($data['offer_id'])) ? $data['offer_id'] : null;
        $this->propertyFeatureId = (!empty($data['property_feature_id'])) ? $data['property_feature_id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->numResults = (!empty($data['num_results'])) ? $data['num_results'] : null;
    }

    /**
     * @return mixed
     */
    public function getOfferId() {
        return $this->offerId;
    }

    /**
     * @param mixed $offerId
     */
    public function setOfferId($offerId) {
        $this->offerId = $offerId;
    }

    /**
     * @return mixed
     */
    public function getPropertyFeatureId() {
        return $this->propertyFeatureId;
    }

    /**
     * @param mixed $propertyFeatureId
     */
    public function setPropertyFeatureId($propertyFeatureId) {
        $this->propertyFeatureId = $propertyFeatureId;
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
    public function getNumResults() {
        return $this->numResults;
    }

    /**
     * @param mixed $numResults
     */
    public function setNumResults($numResults) {
        $this->numResults = $numResults;
    }
    
    
}