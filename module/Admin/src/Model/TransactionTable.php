<?php

namespace Admin\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Application\Model\Base\BaseGridSettings;
use Application\Model\Base\BaseGridTable;
use Zend\Db\Sql\Where;
/**
 * Class TransactionTable
 * @package User\Model
 */
class TransactionTable extends BaseGridTable {

    protected $filters;
    function setFilters ($filters) {
        $this->filters = $filters;
    }
    
    protected $ambiguousColumnMapping = array(
        'offerId' => 'offers.id',
        'offerTypeName' => 'offers.offer_type_id',
        'propertyTypeName' => 'property_types.name',
        'vipPrice' => 'transactions.vip_price',
        'topPrice' => 'transactions.top_price',
        'chatPrice' => 'transactions.chat_price',
        'schemaPrice' => 'transactions.schema_price',
        'photoshootPerSqPrice' => 'transactions.photoshoot_per_sq_price',
        'weeklyPrice' => 'transactions.weekly_price',
        'totalPrice' => 'transactions.total_price'
    );
    
    protected $invoiceId = null;

    function setInvoiceId($invoiceId) {
        $this->invoiceId = $invoiceId;
    }

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
            'schema_price' => $transaction->getSchemaPrice()
        ));
        return $this->tableGateway->getLastInsertValue();
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

//            $select->where->equalTo('is_paid', '0');
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

    function getData(BaseGridSettings $pGridSettings, $userId, $paging) {

        $thisClass = $this;
        return $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass, $paging, $userId) {
                    $select->columns(array(
                        '*'
                    ));

                    $select->join('offers', 'offers.id = transactions.offer_id', array('property_type_id', 'offer_type_id', 'area', 'price'));
                    $select->join('property_types', 'property_types.id = offers.property_type_id', array('property_type_name' => 'name'));
                    $select->join('offer_types', 'offer_types.id = offers.offer_type_id', array('offer_type_name' => 'name'));

                    $select->where->equalTo('transactions.invoice_id', $thisClass->invoiceId);


                    // Filter
                    $thisClass->filterHelper($pGridSettings, $select);

                    // Sort
                    $thisClass->sortHelper($pGridSettings, $select);

                    // Pagination
                    if ($paging == true) {
                        $thisClass->pagingnHelper($pGridSettings, $select);
                    }
                });
    }

    function getCount(BaseGridSettings $pGridSettings = null, $userId = null) {
        $thisClass = $this;
        $rowset = $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass) {
            $select->columns(array(
                new Expression('COUNT(*) AS num_count')
            ));
            $select->join('offers', 'offers.id = transactions.offer_id', array('property_type_id', 'offer_type_id', 'area', 'price'));
            $select->join('property_types', 'property_types.id = offers.property_type_id', array('property_type_name' => 'name'));
            $select->join('offer_types', 'offer_types.id = offers.offer_type_id', array('offer_type_name' => 'name'));

            if (!is_null($thisClass->invoiceId)) {
                $select->where->equalTo('transactions.invoice_id', $thisClass->invoiceId);
            }

            if (!is_null($pGridSettings)) {
                // Filter
                $thisClass->filterHelper($pGridSettings, $select);
            }
        });
         return $rowset->current()->getNumCount();
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
            'is_paid' => 1
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
     * Gets all transactions by array of user ids
     * 
     * @param $userIds
     * @return \Zend\Db\ResultSet\ResultSet
     */
    function getUsersTransactionsData($userIds) {

        $thisClass = $this;
        return $this->tableGateway->select(function (Select $select) use ($thisClass, $userIds) {
            $select->columns(array(
                '*'
            ));

            $select->join('offers', 'offers.id = transactions.offer_id', array('property_type_id', 'offer_type_id', 'area', 'price'));
            $select->join('property_types', 'property_types.id = offers.property_type_id', array('property_type_name' => 'name'));
            $select->join('offer_types', 'offer_types.id = offers.offer_type_id', array('offer_type_name' => 'name'));
            
            $where = new Where();
            $where->in('transactions.user_id', $userIds);
            if(isset($this->filters)) {             
                if($this->filters['date_from'] == '') {
                    $this->filters['date_from'] = '2000-01-01';
                }
                if($this->filters['date_to'] == '') {
                    $this->filters['date_to'] = '2050-01-01';
                }
                $where->greaterThanOrEqualTo('transactions.transaction_date_created', $this->filters['date_from']);
                $where->lessThanOrEqualTo('transactions.transaction_date_created', $this->filters['date_to']);
            }
            $select->where($where);
            
            $select->order('transaction_date_created DESC');

        });
    }
}
