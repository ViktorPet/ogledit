<?php

namespace Admin\Model;

use Application\Model\BaseModel;

/**
 * Description of Service
 *
 * @author Krasimira Evgenieva
 */
class Service extends BaseModel {

    function __construct($pData = null) {

        $this->field = array(
            'id' => '',
            'title' => '',
            'description' => '',
            'image' => '',
            'panorama_file' => '',
            'url' => '',
            'date_published' => '',
            'date_created' => '',
            'date_updated' => '',
            'service_category_id' => '',
            // helper fields
            'category' => '',
            'num_results' => '',
            'categoryName' => '',
            'num_articles' => ''
        );

        if (is_array($pData)) {
            $this->exchangeArray($pData);
        }
    }

}
