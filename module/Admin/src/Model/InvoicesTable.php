<?php

namespace Admin\Model;

use Application\Model\Base\BaseGridSettings;
use Application\Model\Base\BaseGridTable;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

/**
 * Class InvoicesTable
 * @package Admin\Model
 */
class InvoicesTable extends BaseGridTable {

    protected $invoiceId = null;
    protected $ambiguousColumnMapping = array(
        'id' => 'invoices.id',
        'payment_type_name' => 'payment_types.name',
        'user_names' => 'users.names'

    );


    function setInvoiceId($invoiceId) {
        $this->invoiceId = $invoiceId;
    }

    public function getTable() {
        return $this->tableGateway->select();
    }

    function getData(BaseGridSettings $pGridSettings, $userId, $paging) {
        $this->predicateMapping['user_names'] = new Expression('CONCAT_WS("", users.names, CONCAT( "/", parents.names ) )');
        $thisClass = $this;
        return $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass, $paging, $userId) {
            $select->columns(array(
                '*'
            ));
            $select->join('payment_types', 'invoices.payment_type_id = payment_types.id', array('payment_type_name' => 'name'));
            $select->join(
                array('users' => 'users'),
                'users.id = invoices.user_id',
                array(
                    'user_phone' => 'phone',
                    'user_names' => new Expression('CONCAT_WS("", users.names, CONCAT( " / ", parents.names ) )'),
                    'parent_user_id' => 'parent_user_id'
                )
            );
            $select->join(
                array('parents' => 'users'),
                'parents.id = users.parent_user_id',
                null,
                'left'
            );
            $select->where->notEqualTo('invoices.total_amount', 0);

            // Filter
            $thisClass->filterHelper($pGridSettings, $select);

            // Sort
            $thisClass->sortHelper($pGridSettings, $select, 'invoice_date_updated');

            // Pagination
            if ($paging == true) {
                $thisClass->pagingnHelper($pGridSettings, $select);
            }
        });
    }

    function getCount(BaseGridSettings $pGridSettings = null, $userId = null) {
        $this->predicateMapping['user_names'] = new Expression('CONCAT_WS("", users.names, CONCAT( "/", parents.names ) )');
        $thisClass = $this;
        $res = $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass) {
            $select->columns(array(
                new Expression('COUNT(*) AS num_results')
            ));
            $select->join('payment_types', 'invoices.payment_type_id = payment_types.id', array('payment_type_name' => 'name'));//
            $select->join(
                array('users' => 'users'),
                'users.id = invoices.user_id',
                array(
                    'user_phone' => 'phone',
                    'user_names' => new Expression('CONCAT_WS("", users.names, CONCAT( " / ", parents.names ) )'),
                    'parent_user_id' => 'parent_user_id'
                )
            );
            $select->join(
                array('parents' => 'users'),
                'parents.id = users.parent_user_id',
                null,
                'left'
            );


            $select->where->notEqualTo('invoices.total_amount', 0);
            
            if (!is_null($pGridSettings)) {
                // Filter
                $thisClass->filterHelper($pGridSettings, $select);
            }
        });
        return $res->current()->getField('num_results');
    }

    /**
     * Marks invoice as paid.
     *
     * @param $invoiceId
     */
    public function markPaid($invoiceId) {
        return $this->getTableGateway()->update(array(
            'is_paid' => 1
        ),
            array(
                'id' => $invoiceId
            ));
    }
    
    public function completeInvoice($invoiceId) {
        $this->tableGateway->update(array(
            'is_paid' => 1
        ),
            array(
                'id' => $invoiceId
            )
        );
    }

}
