<?php
namespace Admin\Model;

use Application\Model\BaseModel;

/**
 * Class Languages
 * @package Languages\Model
 */
class Languages extends BaseModel{
    function __construct($pData = null)
    {
        
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->language = (!empty($data['language'])) ? $data['language'] : null;      
        
        //helper fields
        $this->numResults = (!empty($data['num_results'])) ? $data['num_results'] : null;
        
        
        
        $this->field = array(
            'id' => '',
            'language' => '',          

            // helper fields
            'num_results' => '',
        );

        if (is_array($pData)) {
            $this->exchangeArray($pData);
        }
    }
    
    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language) {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getNumResults() {
        return $this->numResults;
    }
}