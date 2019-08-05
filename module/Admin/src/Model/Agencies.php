<?php
namespace Admin\Model;

use Application\Model\BaseModel;

/**
 * Class Agencies
 * @package Admin\Model
 */
class Agencies extends BaseModel{
    function __construct($pData = null)
    {
        
        $this->field = array(
            'id' => '',
            'email' => '',
            'names' => '',
            'password' => '',
            'logo' => '',
            'user_type_id' => '',
            'user_status_id' => '',
            'price_id' => '',
            'phone' => '',
            'subscribed' => '',
            'director' => '',
            'vat_number' => '',
            'company_address' => '',
            'user_status_name' => '',
            'names_en' => '',          
            'description' => '',
            'description_en' => '',
            'parent_user_id' => '',            
            'date_created' => '',
            'date_updated' => '',

            // helper fields
            'num_results' => '',
            'user_type' => '',
            'offers_count' => '',
            'active_count' => '',
            'agents_count' => '',
        );

        if (is_array($pData)) {
            $this->exchangeArray($pData);
        }
    }
}