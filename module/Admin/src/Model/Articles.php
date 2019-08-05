<?php
namespace Admin\Model;

use Application\Model\BaseModel;

/**
 * Class Agencies
 * @package Admin\Model
 */
class Articles extends BaseModel {
    function __construct($pData = null)
    {
        
//        $this->id = (!empty($data['id'])) ? $data['id'] : null;
//        $this->email = (!empty($data['title'])) ? $data['title'] : null;
//        $this->username = (!empty($data['description'])) ? $data['description'] : null;
//        $this->firstName = (!empty($data['announcement'])) ? $data['announcement'] : null;
//        $this->lastName = (!empty($data['url'])) ? $data['url'] : null;
//        $this->password = (!empty($data['image'])) ? $data['image'] : null;
//        $this->gender = (!empty($data['position'])) ? $data['position'] : null;
//        $this->dateCreated = (!empty($data['date_published'])) ? $data['date_published'] : null;
//        $this->dateUpdated = (!empty($data['date_created'])) ? $data['date_created'] : null;
//        $this->dateUpdated = (!empty($data['date_updated'])) ? $data['date_updated'] : null;
//        $this->dateUpdated = (!empty($data['meta_title'])) ? $data['meta_title'] : null;
//        $this->dateUpdated = (!empty($data['meta_description'])) ? $data['meta_description'] : null;
//        $this->dateUpdated = (!empty($data['language_id'])) ? $data['language_id'] : null;
//        $this->dateUpdated = (!empty($data['category_id'])) ? $data['category_id'] : null;
//        
//        //helper fields
//        $this->numResults = (!empty($data['num_results'])) ? $data['num_results'] : null;
//        
//        
        
        $this->field = array(
            'id' => '',
            'title' => '',
            'description' => '',
            'announcement' => '',
            'url' => '',
            'image' => '',
            'position' => '',
            'date_published' => '',
            'date_created' => '',
            'date_updated' => '',
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'language_id' => '',            
            'category_id' => '',

            // helper fields
            'language' => '',
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