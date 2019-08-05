<?php
namespace Admin\Model;


use Application\Model\BaseTableModel;
use Zend\Db\Sql\Expression;

use Zend\Db\Sql\Select; 
use Application\Model\Base\BaseGridSettings;
use Application\Model\Base\BaseGridTable;

use Admin\Model\Permission;

/**
 * Class UserPermissionTable
 * @package Admin\Model
 */
//class AdminPermissionsTable extends BaseTableModel {
class AdminPermissionsTable extends BaseGridTable {

    /**
     * Get admin permission by id
     *
     * @param $admin_id
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function findPermissionsByAdminId($admin_id) {
        $resultSet = $this->tableGateway->select(['admin_id' => $admin_id]);
        return $resultSet;
    }
    
    public function getTable()
    {
        return $this->tableGateway->select();
    }
    function getData(BaseGridSettings $pGridSettings, $userId, $paging)
    {        
        $thisClass = $this;        
        $asd = $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass, $paging, $userId) {
            $select->columns(array(
                '*'
            ));
            
            $select->where(array('admin_id' => $userId));
            
            // Filter
//            $thisClass->filterHelper($pGridSettings, $select);

            // Sort
//            $thisClass->sortHelper($pGridSettings, $select);

            // Pagination
            if ($paging == true) {
                $thisClass->pagingnHelper($pGridSettings, $select);
            }

        });  
        return $asd;
    }
    
    function getCount(BaseGridSettings $pGridSettings = null, $userId = null)
    {        
        $thisClass = $this;        
        $res = $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass) {
            $select->columns(array(
                new Expression('COUNT(*) AS num_results')
            ));

            if(!is_null($pGridSettings)){
                // Filter
                $thisClass->filterHelper($pGridSettings, $select);
            }
        });                
        return $res->current()->getField('num_results');                
    } 
    
    public function checkIfExist($adminId, $permissionId) {            
        $res = $this->tableGateway->select(function (Select $select) use ($adminId, $permissionId) {
            $select->where(array(
                'admin_id' => $adminId,
                'permission_id' => $permissionId,
            ));
        });
        return $res->current();              
    }
    
    public function createByAdminIdAndPermissionId($adminId, $permissionId) {             
        $this->tableGateway->insert(
            array(
                'admin_id' => $adminId,
                'permission_id' => $permissionId
            )
        );        
    }
    
    public function deleteByAdminIdAndPermissionId($adminId, $permissionId) {      
        $this->tableGateway->delete(
            array(
                'admin_id' => $adminId,
                'permission_id' => $permissionId
            )
        );       
    } 
}