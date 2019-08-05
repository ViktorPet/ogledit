<?php
namespace Admin\Model;

use Application\Model\BaseModel;

/**
 * Class Admin
 * @package Admin\Model
 */
class Admin extends BaseModel{
    function __construct($pData = null)
    {
        
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->username = (!empty($data['username'])) ? $data['username'] : null;
        $this->firstName = (!empty($data['first_name'])) ? $data['first_name'] : null;
        $this->lastName = (!empty($data['last_name'])) ? $data['last_name'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->gender = (!empty($data['gender'])) ? $data['gender'] : null;
        $this->position = (!empty($data['position'])) ? $data['position'] : null;
        $this->userStatusId = (!empty($data['user_status_id'])) ? $data['user_status_id'] : null;
        $this->invalidLoginCount = (!empty($data['invalid_login_count'])) ? $data['invalid_login_count'] : null;
        $this->dateCreated = (!empty($data['date_created'])) ? $data['date_created'] : null;
        $this->dateUpdated = (!empty($data['date_updated'])) ? $data['date_updated'] : null;
        
        //helper fields
        $this->numResults = (!empty($data['num_results'])) ? $data['num_results'] : null;
        
        
        
        $this->field = array(
            'id' => '',
            'email' => '',
            'username' => '',
            'first_name' => '',
            'last_name' => '',
            'password' => '',
            'gender' => '',
            'position' => '',
            'user_status_id' => '',
            'invalid_login_count' => '',
            'date_created' => '',
            'date_updated' => '',

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
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender) {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position) {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getUserStatusId() {
        return $this->userStatusId;
    }

    /**
     * @param mixed $userStatusId
     */
    public function setUserStatusId($userStatusId) {
        $this->userStatusId = $userStatusId;
    }

    /**
     * @return mixed
     */
    public function getInvalidLoginCount() {
        return $this->invalidLoginCount;
    }

    /**
     * @param mixed $invalidLoginCount
     */
    public function setInvalidLoginCount($invalidLoginCount) {
        $this->invalidLoginCount = $invalidLoginCount;
    }

    /**
     * @return mixed
     */
    public function getDateCreated() {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return mixed
     */
    public function getDateUpdated() {
        return $this->dateUpdated;
    }

    /**
     * @param mixed $dateUpdated
     */
    public function setDateUpdated($dateUpdated) {
        $this->dateUpdated = $dateUpdated;
    }
    
    /**
     * @return mixed
     */
    public function getNumResults() {
        return $this->numResults;
    }
}