<?php
namespace User\Model;

/**
 * Class Price
 * @package User\Model
 */
class Price {
    public $id;
    public $minOffers;
    public $maxOffers;
    public $photoshootPerSqPrice;
    public $photoshootMinPrice;
    public $weeklyPrice;
    public $vipPrice;
    public $topPrice;
    public $priceName;
    public $chat;
    public $priceSchema;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->minOffers = (!empty($data['min_offers'])) ? $data['min_offers'] : null;
        $this->maxOffers = (!empty($data['max_offers'])) ? $data['max_offers'] : null;
        $this->photoshootPerSqPrice = (!empty($data['photoshoot_per_sq_price'])) ? $data['photoshoot_per_sq_price'] : 0;
        $this->photoshootMinPrice = (!empty($data['photoshoot_min_price'])) ? $data['photoshoot_min_price'] : 0;
        $this->weeklyPrice = (!empty($data['weekly_price'])) ? $data['weekly_price'] : 0;
        $this->vipPrice = (!empty($data['vip_price'])) ? $data['vip_price'] : 0;
        $this->topPrice = (!empty($data['top_price'])) ? $data['top_price'] : 0;
        $this->priceName = (!empty($data['price_name'])) ? $data['price_name'] : 0;
        $this->chat = (!empty($data['chat'])) ? $data['chat'] : 0;
        $this->priceSchema = (!empty($data['price_schema'])) ? $data['price_schema'] : 0;
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
    public function getMinOffers() {
        return $this->minOffers;
    }

    /**
     * @param mixed $minOffers
     */
    public function setMinOffers($minOffers) {
        $this->minOffers = $minOffers;
    }

    /**
     * @return mixed
     */
    public function getMaxOffers() {
        return $this->maxOffers;
    }

    /**
     * @param mixed $maxOffers
     */
    public function setMaxOffers($maxOffers) {
        $this->maxOffers = $maxOffers;
    }

    /**
     * @return mixed
     */
    public function getPhotoshootPerSqPrice() {
        return $this->photoshootPerSqPrice;
    }

    /**
     * @param mixed $photoshootPerSqPrice
     */
    public function setPhotoshootPerSqPrice($photoshootPerSqPrice) {
        $this->photoshootPerSqPrice = $photoshootPerSqPrice;
    }

    /**
     * @return mixed
     */
    public function getPhotoshootMinPrice() {
        return $this->photoshootMinPrice;
    }

    /**
     * @param mixed $photoshootMinPrice
     */
    public function setPhotoshootMinPrice($photoshootMinPrice) {
        $this->photoshootMinPrice = $photoshootMinPrice;
    }

    /**
     * @return mixed
     */
    public function getWeeklyPrice() {
        return $this->weeklyPrice;
    }

    /**
     * @param mixed $weeklyPrice
     */
    public function setWeeklyPrice($weeklyPrice) {
        $this->weeklyPrice = $weeklyPrice;
    }

    /**
     * @return mixed
     */
    public function getVipPrice() {
        return $this->vipPrice;
    }

    /**
     * @param mixed $vipPrice
     */
    public function setVipPrice($vipPrice) {
        $this->vipPrice = $vipPrice;
    }

    /**
     * @return mixed
     */
    public function getTopPrice() {
        return $this->topPrice;
    }

    /**
     * @param mixed $topPrice
     */
    public function setTopPrice($topPrice) {
        $this->topPrice = $topPrice;
    }
    
    /**
     * @return mixed
     */
    public function getPriceName() {
        return $this->priceName;
    }

    /**
     * @param mixed $priceName
     */
    public function setPriceName($priceName) {
        $this->priceName = $priceName;
    }
    
    /**
     * @return mixed
     */
    public function getChat() {
        return $this->chat;
    }

    /**
     * @param mixed $chat
     */
    public function setChat($chat) {
        $this->chat = $chat;
    }
    
    /**
     * @return mixed
     */
    public function getPriceSchema() {
        return $this->priceSchema;
    }

    /**
     * @param mixed $priceSchema
     */
    public function setPriceSchema($priceSchema) {
        $this->priceSchema = $priceSchema;
    }


}