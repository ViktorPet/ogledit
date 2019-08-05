<?php

namespace Admin\Model;

use Application\Model\BaseModel;

/**
 * Description of Pages
 *
 */
class Pages extends BaseModel {
     
    function __construct($pData = null)
    {
//        
//        $this->id = (!empty($data['id'])) ? $data['id'] : null;
//        $this->title = (!empty($data['title'])) ? $data['title'] : null;
//        $this->description = (!empty($data['description'])) ? $data['description'] : null;
//        $this->slug = (!empty($data['slug'])) ? $data['slug'] : null;
//        $this->dateCreated = (!empty($data['date_created'])) ? $data['date_created'] : null;
//        $this->dateUpdated = (!empty($data['date_updated'])) ? $data['date_updated'] : null;
//        $this->meta_title = (!empty($data['meta_title'])) ? $data['meta_title'] : null;
//        $this->meta_description = (!empty($data['meta_description'])) ? $data['meta_description'] : null;
//        $this->meta_keywords = (!empty($data['meta_keywords'])) ? $data['meta_keywords'] : null;
//        $this->language_id = (!empty($data['language_id'])) ? $data['language_id'] : null;
        
        //helper fields
        $this->numResults = (!empty($data['num_results'])) ? $data['num_results'] : null;
        
        
        
        $this->field = array(
            'id' => '',
            'title' => '',
            'description' => '',
            'slug' => '',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'language_id' => '',
            'date_created' => '',
            'date_updated' => '',
            'url' => '',

            // helper fields
            'num_results' => '',
            'language' => '',
        );

        if (is_array($pData)) {
            $this->exchangeArray($pData);
        }
    }
    
    
}
