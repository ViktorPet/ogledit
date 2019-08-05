<?php
namespace User\Model;

/**
 * Class OfferParcelFeature
 * @package User\Model
 */
class OfferParcelFeature {
    public $offerId;
    public $parcelFeatureId;

    public $name;

    public function exchangeArray($data) {
        $this->offerId = (!empty($data['offer_id'])) ? $data['offer_id'] : null;
        $this->parcelFeatureId = (!empty($data['parcel_feature_id'])) ? $data['parcel_feature_id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
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
    public function getParcelFeatureId() {
        return $this->parcelFeatureId;
    }

    /**
     * @param mixed $parcelFeatureId
     */
    public function setParcelFeatureId($parcelFeatureId) {
        $this->parcelFeatureId = $parcelFeatureId;
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
}