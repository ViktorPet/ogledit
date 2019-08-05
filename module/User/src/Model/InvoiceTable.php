<?php
namespace User\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

/**
 * Class InvoiceTable
 * @package User\Model
 */
class InvoiceTable extends BaseTableModel {

    /**
     * Gets invoice ID by user ID.
     * 
     * @param $userId
     * @return int
     */
    public function getCurrentUserInvoiceId($userId) {
        $currInvoice = $this->tableGateway->select(function (Select $select) use ($userId) {
            $select->where->equalTo('is_paid', '0');
            $select->where->isNull('payment_type_id');
            $select->where->equalTo('user_id', $userId);
        });

        if ($currInvoice->current()) {
            return $currInvoice->current()->getId();
        } else {
            return $this->insertBlank($userId);
        }
    }

    /**
     * Inserts new invoice for the user.
     * 
     * @param $userId
     * @return int
     */
    public function insertBlank($userId) {
        $this->tableGateway->insert(array(
            'user_id' => $userId,
            'total_amount' => 0,
            'is_paid' => 0,
            'invoice_date_created' => new Expression('NOW()'),
            'invoice_date_updated' => new Expression('NOW()')
        ));
        return $this->tableGateway->getLastInsertValue();
    }

    /**
     * Updates invoice total amount.
     *
     * @param $invoiceId
     * @param $totalAmount
     */
    public function updateInvoiceTotalAmount($invoiceId, $totalAmount) {
        $this->tableGateway->update(array(
            'total_amount' => $totalAmount
        ),
            array(
                'id' => $invoiceId
            )
        );
    }

    /**
     * Updates invoice payment method amount.
     *
     * @param $invoiceId
     * @param $paymentTypeId
     */
    public function updateInvoicePaymentMethod($invoiceId, $paymentTypeId) {
        $this->tableGateway->update(array(
            'payment_type_id' => $paymentTypeId,
            'invoice_date_updated' => new Expression('NOW()')
        ),
            array(
                'id' => $invoiceId
            )
        );
    }

    /**
     * Completes invoice as paid.
     * 
     * @param $invoiceId
     */
    public function completeInvoice($invoiceId) {
        $this->tableGateway->update(array(
            'is_paid' => 1,
            'invoice_date_updated' => new Expression('NOW()')
        ),
            array(
                'id' => $invoiceId
            )
        );
    }
    
    /**
     * Get user id by $code.
     * 
     * @param $code
     * @return array|\ArrayObject|null
     */
    public function getCurrentUserIdByCode($code) {
        $rowset = $this->tableGateway->select(function (Select $select) use ($code) {
            $select->columns(array(
               'user_id',
            ));    
            
             $select->where(array(
                'id' => $code
            ));
        });

        if ($rowset) {
            return $rowset->current();
        } else {
            return null;
        }
    }
   
}