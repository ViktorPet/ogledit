<?php

namespace Admin\Model;

use Application\Model\BaseModel;

/**
 * Description of ServiceCategories
 *
 */
class ServiceCategories extends BaseModel {

    function __construct($pData = null) {

        $this->field = array(
            'id' => '',
            'name' => '',
            'image_link' => '',
            // helper fields
            'num_results' => '',
        );

        if (is_array($pData)) {
            $this->exchangeArray($pData);
        }
    }

}
