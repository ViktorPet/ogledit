<?php
namespace Admin\Model;

use Application\Model\BaseTableModel;

use Zend\Db\Sql\Select; 
/**
 * Class PermissionTable
 * @package Admin\Model
 */
class PermissionTable extends BaseTableModel {

     /**
     * Gets all offer types as index -> name array.
     *
     * @return array
     */
    public function getPermissionsArray() {        
        $rowset = $this->tableGateway->select();
        if ($rowset) {
            $selectData = array();
            foreach ($rowset as $res) {
                $selectData[$res->module . '\\' . $res->controller . '\\' . $res->action] = $res->id;
            }
            return $selectData;
        } else {
            return array();
        }
    }
    
    public function getPermissionsForGridArray() {        
        $rowset = $this->tableGateway->select(function (Select $select) {
        });
//        $rowset = $this->tableGateway->select('');

        if ($rowset) {
            $selectData = array();
            foreach ($rowset as $res) {                                                   
                $selectData[$res->id] = $res->description;
            }
            return $selectData;
        } else {
            return array();
        }
    }
    
    public function getPermissionByDescription($description) {            
        $res = $this->tableGateway->select(function (Select $select) use ($description) {
            $select->where(array(
                'description' => $description,                
            ));
        });
        return $res->current();              
    }
    
    public function getPefmissionsDescriptions() {        
        $rowset = $this->tableGateway->select();
        if ($rowset) {           
            $selectData = array();
            foreach ($rowset as $res) {                                                   
                $selectData[$res->id] = $res->description;
            }           
            return $selectData;
        } else {
            return array();
        }
    }
}