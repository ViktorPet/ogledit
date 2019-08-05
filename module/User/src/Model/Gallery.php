<?php
namespace User\Model;

/**
 * Class Gallery
 * @package User\Model
 */
class Gallery {
    public $id;
    public $image;
    public $dateCreated;
    public $dateUpdated;
    public $offerId;
    public $isFront;
    public $imageOrder;
    public $numResults;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->image = (!empty($data['image'])) ? $data['image'] : null;
        $this->dateCreated = (!empty($data['date_created'])) ? $data['date_created'] : null;
        $this->dateUpdated = (!empty($data['date_updated'])) ? $data['date_updated'] : null;
        $this->offerId = (!empty($data['offer_id'])) ? $data['offer_id'] : null;
        $this->isFront = (!empty($data['is_front'])) ? $data['is_front'] : null;
        $this->imageOrder = (!empty($data['image_order'])) ? $data['image_order'] : null;
        $this->numResults = (!empty($data['num_results'])) ? $data['num_results'] : null;

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
    public function getImage() {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image) {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getDateCreated() {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return mixed
     */
    public function getDateUpdated() {
        return $this->dateUpdated;
    }

    /**
     * @param mixed $dateUpdated
     */
    public function setDateUpdated($dateUpdated) {
        $this->dateUpdated = $dateUpdated;
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
    public function getIsFront() {
        return $this->isFront;
    }

    /**
     * @param mixed $isFront
     */
    public function setIsFront($isFront) {
        $this->isFront = $isFront;
    }


    /**
     * @return mixed
     */
    public function getImageOrder() {
        return $this->imageOrder;
    }

    /**
     * @param mixed $imageOrder
     */
    public function setImageOrder($imageOrder) {
        $this->imageOrder = $imageOrder;
    }

    /**
     * @return mixed
     */
    public function getNumResults() {
        return $this->numResults;
    }

    /**
     * @param mixed $imageOrder
     */
    public function setNumResults($numResults) {
        $this->numResults = $numResults;
    }

}