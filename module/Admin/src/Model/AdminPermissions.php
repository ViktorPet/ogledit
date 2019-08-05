<?php
namespace Admin\Model;

use Application\Model\BaseModel;
/**
 * Class AdminPermission
 * @package Admin\Model
 */
class AdminPermissions extends BaseModel{
    public $adminId;
    public $permissionId;

    function __construct($pData = null)
    {                
        $this->field = array(
            'admin_id' => '',
            'permission_id' => '',          

            // helper fields
            'num_results' => '',
        );

        if (is_array($pData)) {
            $this->exchangeArray($pData);
        }
    }
    
//    public function exchangeArray($data) {
////        $this->adminId = (!empty($data['admin_id'])) ? $data['admin_id'] : null;
////        $this->permissionId = (!empty($data['permission_id'])) ? $data['permission_id'] : null;
////        $this->numResults = (!empty($data['num_results'])) ? $data['num_results'] : null;
////        
//    }

     /**
     * @return mixed
     */
    public function getNumResults() {
        return $this->numResults;
    }

    /**
     * @param mixed $adminId
     */
    public function setNumResults($numResults) {
        $this->numResults = $numResults;
    }
    
    /**
     * @return mixed
     */
    public function getAdminId() {
        return $this->adminId;
    }

    /**
     * @param mixed $adminId
     */
    public function setAdminId($adminId) {
        $this->adminId = $adminId;
    }

    /**
     * @return mixed
     */
    public function getPermissionId() {
        return $this->permissionId;
    }

    /**
     * @param mixed $permissionId
     */
    public function setPermissionId($permissionId) {
        $this->permissionId = $permissionId;
    }
}