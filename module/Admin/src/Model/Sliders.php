<?php

namespace Admin\Model;

use Application\Model\BaseModel;

/**
 * Description of GalleryTable
 *
 */
class Sliders  extends BaseModel {

    const MOBILE = 1;
    const DESKTOP = 2;

    function __construct($pData = null)
    {   
        $this->field = array(
            'id' => '',
            'name' => '',

            'link' => '',
            'desktop_img' => '',
            'mobile_img' => '',

            'link_en' => '',
            'desktop_img_en' => '',
            'mobile_img_en' => '',

             // helper fields
            'num_results' => '',
        );

        if (is_array($pData)) {
            $this->exchangeArray($pData);
        }
    }
    
}