<?php
namespace User\Model;

use Application\Model\BaseTableModel;

/**
 * Class UserStatusTable
 * @package User\Model
 */
class UserStatusTable extends BaseTableModel {

     /**
     * Gets all offer types as index -> name array.
     *
     * @return array
     */
    public function getTypesArray() {
        $rowset = $this->tableGateway->select();
        if ($rowset) {
            $selectData = array();
            foreach ($rowset as $res) {
                $selectData[$res->id] = $res->name;
            }
            return $selectData;
        } else {
            return array();
        }
    }
    
}