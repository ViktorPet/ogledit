<?php
namespace User\Model;
use Application\Model\Offer;

/**
 * Class Transaction
 * @package User\Model
 */
class Transaction extends Offer {
    public $id;
    public $offerId;
    public $userId;
    public $invoiceId;
    public $transactionCode;
    public $transactionDateCreated;
    public $transactionDateUpdated;
    public $isPaid = 0;
    public $totalPrice = 0;
    public $photoshootPerSqPrice = 0;
    public $weeklyPrice = 0;
    public $vipPrice = 0;
    public $topPrice = 0;
    public $chatPrice = 0;
    public $schemaPrice = 0;
    public $numCount;
    public $isVip;
    public $isTop;
    public $isChat;
    public $isSchema;
    public $weeks;
    public $extraWeeks;

    public function exchangeArray($data) {
        parent::exchangeArray($data);

        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->offerId = (!empty($data['offer_id'])) ? $data['offer_id'] : null;
        $this->userId = (!empty($data['user_id'])) ? $data['user_id'] : null;
        $this->invoiceId = (!empty($data['invoice_id'])) ? $data['invoice_id'] : null;
        $this->transactionCode = (!empty($data['transaction_code'])) ? $data['transaction_code'] : null;
        $this->transactionDateCreated = (!empty($data['transaction_date_created'])) ? $data['transaction_date_created'] : null;
        $this->transactionDateUpdated = (!empty($data['transaction_date_updated'])) ? $data['transaction_date_updated'] : null;
        $this->isPaid = (!empty($data['is_paid'])) ? $data['is_paid'] : 0;
        $this->totalPrice = (!empty($data['total_price'])) ? $data['total_price'] : 0;
        $this->photoshootPerSqPrice = (!empty($data['photoshoot_per_sq_price'])) ? $data['photoshoot_per_sq_price'] : 0;
        $this->weeklyPrice = (!empty($data['weekly_price'])) ? $data['weekly_price'] : 0;
        $this->vipPrice = (!empty($data['vip_price'])) ? $data['vip_price'] : 0;
        $this->topPrice = (!empty($data['top_price'])) ? $data['top_price'] : 0;
        $this->chatPrice = (!empty($data['chat_price'])) ? $data['chat_price'] : 0;
        $this->schemaPrice = (!empty($data['schema_price'])) ? $data['schema_price'] : 0;
        $this->numCount = (!empty($data['num_count'])) ? $data['num_count'] : 0;
        $this->isVip = (!empty($data['is_vip'])) ? $data['is_vip'] : 0;
        $this->isTop = (!empty($data['is_top'])) ? $data['is_top'] : 0;
        $this->isChat = (!empty($data['is_chat'])) ? $data['is_chat'] : 0;
        $this->isSchema = (!empty($data['is_schema'])) ? $data['is_schema'] : 0;
        $this->weeks = (!empty($data['weeks'])) ? $data['weeks'] : null;
        $this->extraWeeks = (!empty($data['extra_weeks'])) ? $data['extra_weeks'] : null;
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
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getTransactionCode() {
        return $this->transactionCode;
    }

    /**
     * @param mixed $transactionCode
     */
    public function setTransactionCode($transactionCode) {
        $this->transactionCode = $transactionCode;
    }
    
    /**
     * @return mixed
     */
    public function getTransactionDateCreated() {
        return $this->transactionDateCreated;
    }

    /**
     * @param mixed $transactionDateCreated
     */
    public function setTransactionDateCreated($transactionDateCreated) {
        $this->transactionDateCreated = $transactionDateCreated;
    }

    /**
     * @return mixed
     */
    public function getTransactionDateUpdated() {
        return $this->transactionDateUpdated;
    }

    /**
     * @param mixed $transactionDateUpdated
     */
    public function setTransactionDateUpdated($transactionDateUpdated) {
        $this->transactionDateUpdated = $transactionDateUpdated;
    }

    /**
     * @return mixed
     */
    public function getIsPaid() {
        return $this->isPaid;
    }

    /**
     * @param mixed $isPaid
     */
    public function setIsPaid($isPaid) {
        $this->isPaid = $isPaid;
    }

    /**
     * @return mixed
     */
    public function getTotalPrice() {
        return $this->totalPrice;
    }

    /**
     * @param mixed $totalPrice
     */
    public function setTotalPrice($totalPrice) {
        $this->totalPrice = $totalPrice;
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
    public function getInvoiceId() {
        return $this->invoiceId;
    }

    /**
     * @param mixed $invoiceId
     */
    public function setInvoiceId($invoiceId) {
        $this->invoiceId = $invoiceId;
    }

    /**
     * @return int
     */
    public function getChatPrice() {
        return $this->chatPrice;
    }

    /**
     * @param int $chatPrice
     */
    public function setChatPrice($chatPrice) {
        $this->chatPrice = $chatPrice;
    }

    /**
     * @return int
     */
    public function getSchemaPrice() {
        return $this->schemaPrice;
    }

    /**
     * @param int $schemaPrice
     */
    public function setSchemaPrice($schemaPrice) {
        $this->schemaPrice = $schemaPrice;
    }
    
    /**
     * @return mixed
     */
    public function getNumCount() {
        return $this->numCount;
    }

    public function getIsVip() {
        return $this->isVip;
    }
    public function setIsVip($isVip) {
        $this->isVip = $isVip;
    }
    
    public function getIsTop() {
        return $this->isTop;
    }
    public function setIsTop($isTop) {
        $this->isTop = $isTop;
    }
    
    public function getIsChat() {
        return $this->isChat;
    }
    public function setIsChat($isChat) {
        $this->isChat = $isChat;
    }
    
    public function getIsSchema() {
        return $this->isSchema;
    }
    public function setIsSchema($isSchema) {
        $this->isSchema = $isSchema;
    }
    
      /**
     * @return mixed
     */
    public function getWeeks() {
        return $this->weeks;
    }
    
    /**
     * @param mixed $weeks
     */
    public function setWeeks($weeks) {
        $this->weeks = $weeks;
    }

    /**
     * @return mixed
     */
    public function getExtraWeeks() {
        return $this->extraWeeks;
    }

    /**
     * @param mixed $extraWeeks
     */
    public function setExtraWeeks($extraWeeks) {
        $this->extraWeeks = $extraWeeks;
    }
}