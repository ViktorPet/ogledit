<?php
namespace User\Model;

/**
 * Class Invoice
 * @package User\Model
 */
class Invoice {
    public $id;
    public $userId;
    public $totalAmount;
    public $paymentTypeId;
    public $isPaid;
    public $invoicedateCreated;
    public $invoiceDateUpdated;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->userId  = (!empty($data['user_id'])) ? $data['user_id'] : null;
        $this->totalAmount  = (!empty($data['total_amount'])) ? $data['total_amount'] : null;
        $this->paymentTypeId  = (!empty($data['payment_type_id'])) ? $data['payment_type_id'] : null;
        $this->isPaid  = (!empty($data['is_paid'])) ? $data['is_paid'] : null;
        $this->invoicedateCreated = (!empty($data['invoice_date_created'])) ? $data['invoice_date_created'] : null;
        $this->invoiceDateUpdated  = (!empty($data['invoice_date_updated'])) ? $data['invoice_date_updated'] : null;
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
    public function getTotalAmount() {
        return $this->totalAmount;
    }

    /**
     * @param mixed $totalAmount
     */
    public function setTotalAmount($totalAmount) {
        $this->totalAmount = $totalAmount;
    }

    /**
     * @return mixed
     */
    public function getPaymentTypeId() {
        return $this->paymentTypeId;
    }

    /**
     * @param mixed $paymentTypeId
     */
    public function setPaymentTypeId($paymentTypeId) {
        $this->paymentTypeId = $paymentTypeId;
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
    public function getInvoicedateCreated() {
        return $this->invoicedateCreated;
    }

    /**
     * @param mixed $invoicedateCreated
     */
    public function setInvoicedateCreated($invoicedateCreated) {
        $this->invoicedateCreated = $invoicedateCreated;
    }

    /**
     * @return mixed
     */
    public function getInvoiceDateUpdated() {
        return $this->invoiceDateUpdated;
    }

    /**
     * @param mixed $invoiceDateUpdated
     */
    public function setInvoiceDateUpdated($invoiceDateUpdated) {
        $this->invoiceDateUpdated = $invoiceDateUpdated;
    }

}