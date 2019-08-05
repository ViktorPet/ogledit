<?php

namespace User\Model;

/**
 * Description of Newsletter
 *
 */
class Newsletter {
    
    public $id;
    public $email;
    public $dateCreated;
    public $numResults;

    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->dateCreated = (!empty($data['date_created'])) ? $data['date_created'] : null;
        
        $this->numResults = (!empty($data['num_results'])) ? $data['num_results'] : null;

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
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param mixed $name
     */
    public function setEmail($email) {
        $this->email = $email;
    }    
    
    /**
     * @return mixed
     */
    public function getDateCreated() {
        return $this->dateCreated;
    }

    /**
     * @param mixed $name
     */
    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
    }
    
    /**
     * @return mixed
     */
    public function getNumResults() {
        return $this->numResults;
    }

    /**
     * @param mixed $name
     */
    public function setNumResults($numResults) {
        $this->numResults = $numResults;
    }
    
    /**
     * @return mixed
     */
    public function toArray() {
        $vars = get_object_vars($this);
        $array = array();
        foreach ($vars as $key => $value) {
            $array [ltrim($key, '_')] = $value;
        }
        return $array;
    }
    
}
