<?php
namespace Admin\Model;

use Application\Model\BaseModel;

/**
 * Class Categories
 * @package Categories\Model
 */
class Categories extends BaseModel{
    function __construct($pData = null) {
        
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;      
        
        //helper fields
        $this->numResults = (!empty($data['num_results'])) ? $data['num_results'] : null;
        
        
        
        $this->field = array(
            'id' => '',
            'name' => '',          

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
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $email
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getNumResults() {
        return $this->numResults;
    }
}