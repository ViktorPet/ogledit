<?php

namespace Admin\Model;

use Application\Model\BaseModel;

/**
 * Description of GalleryTable
 *
 */
class Gallery  extends BaseModel {
    function __construct($pData = null)
    {   
        $this->field = array(
            'id' => '',
            'image' => '',
            'date_created' => '',
            'date_updated' => '',
            'offer_id' => '',
            'is_front' => '',
            'image_order' => '',
            
             // helper fields
            'num_results' => '',
        );

        if (is_array($pData)) {
            $this->exchangeArray($pData);
        }
    }
    
}