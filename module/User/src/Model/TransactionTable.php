<?php

namespace User\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

/**
 * Class TransactionTable
 * @package User\Model
 */
class TransactionTable extends BaseTableModel {

    /**
     * Creates new user public offer.
     *
     * @param Transaction $transaction
     * @return int
     */
    public function createTransaction(Transaction $transaction) {
        $this->tableGateway->insert(array(
            'offer_id' => $transaction->getOfferId(),
            'user_id' => $transaction->getUserId(),
            'transaction_code' => $this->generateTransactionCode(),
            'transaction_date_created' => new Expression('NOW()'),
            'transaction_date_updated' => new Expression('NOW()'),
            'is_paid' => $transaction->getIsPaid(),
            'total_price' => $transaction->getTotalPrice(),
            'photoshoot_per_sq_price' => $transaction->getPhotoshootPerSqPrice(),
            'weekly_price' => $transaction->getWeeklyPrice(),
            'vip_price' => $transaction->getVipPrice(),
            'top_price' => $transaction->getTopPrice(),
            'chat_price' => $transaction->getChatPrice(),
            'schema_price' => $transaction->getSchemaPrice(),
            'is_vip' => $transaction->getIsVip(),
            'is_top' => $transaction->getIsTop(),
            'is_chat' => $transaction->getIsChat(),
            'is_schema' => $transaction->getIsSchema(),
            'weeks' => $transaction->getWeeks(),
            'extra_weeks' => $transaction->getExtraWeeks()
        ));
        return $this->tableGateway->getLastInsertValue();
    }

    /**
     * Creates new transaction from a copy of already existing.
     *
     * @param Transaction $transaction
     * @return int
     */
    public function copyTransaction(Transaction $transaction) {
        $this->tableGateway->insert(array(
            'invoice_id' => $transaction->getInvoiceId(),
            'offer_id' => $transaction->getOfferId(),
            'user_id' => $transaction->getUserId(),
            'transaction_code' => $this->generateTransactionCode(),
            'transaction_date_created' => new Expression('NOW()'),
            'transaction_date_updated' => new Expression('NOW()'),
            'is_paid' => $transaction->getIsPaid(),
            'total_price' => $transaction->getTotalPrice(),
            'photoshoot_per_sq_price' => $transaction->getPhotoshootPerSqPrice(),
            'weekly_price' => $transaction->getWeeklyPrice(),
            'vip_price' => $transaction->getVipPrice(),
            'top_price' => $transaction->getTopPrice(),
            'chat_price' => $transaction->getChatPrice(),
            'schema_price' => $transaction->getSchemaPrice(),
            'is_vip' => $transaction->getIsVip(),
            'is_top' => $transaction->getIsTop(),
            'is_chat' => $transaction->getIsChat(),
            'is_schema' => $transaction->getIsSchema(),
            'weeks' => $transaction->getWeeks(),
            'extra_weeks' => $transaction->getExtraWeeks()
        ));
        return $this->tableGateway->getLastInsertValue();
    }

    /**
     * Creates new user public offer.
     *
     * @param Transaction $transaction
     * @return int
     */
    public function updateTransaction(Transaction $transaction) {
        $this->tableGateway->update(array(
            'offer_id' => $transaction->getOfferId(),
            'user_id' => $transaction->getUserId(),
            'transaction_code' => $this->generateTransactionCode(),
            'transaction_date_created' => new Expression('NOW()'),
            'transaction_date_updated' => new Expression('NOW()'),
            'is_paid' => $transaction->getIsPaid(),
            'total_price' => $transaction->getTotalPrice(),
            'photoshoot_per_sq_price' => $transaction->getPhotoshootPerSqPrice(),
            'weekly_price' => $transaction->getWeeklyPrice(),
            'vip_price' => $transaction->getVipPrice(),
            'top_price' => $transaction->getTopPrice(),
            'chat_price' => $transaction->getChatPrice(),
            'schema_price' => $transaction->getSchemaPrice(),
            'is_vip' => $transaction->getIsVip(),
            'is_top' => $transaction->getIsTop(),
            'is_chat' => $transaction->getIsChat(),
            'is_schema' => $transaction->getIsSchema(),
            'weeks' => $transaction->getWeeks(),
            'extra_weeks' => $transaction->getExtraWeeks()
        ), array(
                'id' => $transaction->getId()
            )
        );
        return $transaction->getId();
    }



    /**
     * Generates special transaction code.
     *
     * @return string
     */
    private function generateTransactionCode() {
        $str = '';
        $characters = array_merge(range('A', 'Z'), range('0', '9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < 10; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    /**
     * Gets all user unpaid transactions.
     *
     * @param $userId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getUserCart($userId) {
        return $this->tableGateway->select(function (Select $select) use ($userId) {
                    $select->join('offers', 'offers.id = transactions.offer_id', array('property_type_id', 'offer_type_id', 'area', 'price'));
                    $select->join('property_types', 'property_types.id = offers.property_type_id', array('property_type_name' => 'name'));
                    $select->join('offer_types', 'offer_types.id = offers.offer_type_id', array('offer_type_name' => 'name'));

                    $select->where->equalTo('is_paid', '0');
                    $select->where->isNull('payment_type_id');
                    $select->where->equalTo('transactions.user_id', $userId);
                });
    }

    /**
     * Gets all user transactions for one Invoice.
     *
     * @param $userId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getAllByInvoiceId($invoiceId) {
        return $this->tableGateway->select(function (Select $select) use ($invoiceId) {
                    $select->join('offers', 'offers.id = transactions.offer_id', array('property_type_id', 'offer_type_id', 'area', 'price'));
                    $select->join('property_types', 'property_types.id = offers.property_type_id', array('property_type_name' => 'name'));
                    $select->join('offer_types', 'offer_types.id = offers.offer_type_id', array('offer_type_name' => 'name'));

//            $select->where->equalTo('is_paid', '0');
                    $select->where->equalTo('transactions.invoice_id', $invoiceId);
                });
    }

    /**
     * Deletes transaction by ID.
     *
     * @param $transId
     * @return int
     */
    public function deleteCartItem($transId, $userId) {
        return $this->tableGateway->delete(array(
                    'id' => $transId,
                    'user_id' => $userId
        ));
    }

    /**
     * Updates the invoice ID for particular transaction ID.
     *
     * @param $transactionId
     * @param $invoiceId
     * @return int
     */
    public function updateInvoiceId($transactionId, $invoiceId) {
        $this->tableGateway->update(array(
            'invoice_id' => $invoiceId
                ), array(
            'id' => $transactionId
                )
        );
    }

    /**
     * Completes the transaction.
     *
     * @param $invoiceId
     */
    public function completeTransactionsByInvoice($invoiceId) {
        $this->tableGateway->update(array(
            'is_paid' => 1,
            'transaction_date_updated' => new Expression('NOW()')
                ), array(
            'invoice_id' => $invoiceId
                )
        );
    }

    /**
     * Completes the transaction.
     *
     * @param $invoiceId
     */

    /**
     * Updates all transaction payment methods by their Invoice ID.
     *
     * @param $invoiceId
     * @param $paymentMethodId
     */
    public function updatePaymentMethodByInvoice($invoiceId, $paymentMethodId) {
        $this->tableGateway->update(array(
            'payment_type_id' => $paymentMethodId,
            'transaction_date_updated' => new Expression('NOW()')
                ), array(
            'invoice_id' => $invoiceId
                )
        );
    }

    /**
     * Gets all transactions by Invoice Id.
     * 
     * @param $invoiceId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getByInvoiceId($invoiceId) {
        return $this->tableGateway->select(function (Select $select) use ($invoiceId) {
                    $select->columns(array('*'));
                    $select->where->equalTo('invoice_id', $invoiceId);
                });
    }

    /**
     * Gets the latest paid transactions for selected offer ids. 
     * 
     * @param $offerIds
     * @param $userId
     * @return array
     */
    public function getOffersLastSuccessfulTransactions($offerIds, $userId) {
        $result = array();
        $agentsSelect = new Select('users');
        $agentsSelect->columns(array('id'));
        $agentsSelect->where->equalTo('parent_user_id', $userId)->OR->equalTo('user_id', $userId);

        foreach ($offerIds as $offerId) {
            $transaction = $this->tableGateway->select(function (Select $select) use ($offerId, $agentsSelect) {
                $select->where->equalTo('offer_id', $offerId);
                $select->where->equalTo('is_paid', 1);
                $select->where->in('user_id', $agentsSelect);
                $select->order('transactions.id DESC');
                $select->limit(1);
            });
            if ($transaction->count() == 1) {
                $result[$offerId] = $transaction->current();
            }
        }

        return $result;
    }

    /**
     * Gets last transaction 
     *
     * @param $offerId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getLastTransaction($offerId) {
        $result = $this->tableGateway->select(function (Select $select) use ($offerId) {

            $select->where->equalTo('offer_id', $offerId);
            $select->order('id DESC');
            $select->limit(1);
        });

        return $result;
    }

    /**
     * Gets last transaction
     *
     * @param $offerId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getLastTransactionForUpdate($offerId) {
        $result = $this->tableGateway->select(function (Select $select) use ($offerId) {

            $select->where->equalTo('offer_id', $offerId);
            $select->order('id DESC');
            $select->limit(1);
        });

        return $result->current();
    }

    /**
     * Gets transactions by Id.
     * 
     * @param $transactionId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getTransactionById($transactionId) {
        return $this->tableGateway->select(function (Select $select) use ($transactionId) {
                    $select->columns(array('*'));
                    $select->where->equalTo('id', $transactionId);
                });
    }
    
    /**
     * Gets the number of transactions for given offer.
     *
     * @param $userId
     * @return mixed
     */
    public function getCountTransactionByOfferId($offerId) {
        $rowset = $this->tableGateway->select(function (Select $select) use ($offerId) {
            $select->columns(array(
                'num_count' => new Expression('COUNT(*)')
            ));
            $select->where(array('offer_id' => $offerId));
            $select->where(array('is_paid' => 1));
        });
        return $rowset->current()->getNumCount();
    }

}
