<?php
namespace Admin\Model;

use Application\Model\BaseModel;

/**
 * Class Invoice
 * @package Admin\Model
 */
class Invoice extends BaseModel{
    function __construct($pData = null)
    {
        
        $this->field = array(
            'id' => '',
            'user_id' => '',
            'total_amount' => '',
            'payment_type_id' => '',
            'is_paid' => '',
            'invoice_date_created' => '',
            'invoice_date_updated' => '',

            // helper fields
            'payment_type_name' => '',
            'num_results' => '',
            'user_phone' => '',
            'user_names' => '',
            'agency_name' => '',
            'parent_user_id' => '',

        );

        if (is_array($pData)) {
            $this->exchangeArray($pData);
        }
    }
}