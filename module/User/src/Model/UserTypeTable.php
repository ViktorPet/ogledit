<?php

namespace User\Model;

use Application\Model\BaseTableModel;

/**
 * Class UserTypeTable
 * @package User\Model
 */
class UserTypeTable extends BaseTableModel {

    /**
     * Gets all user types as index -> name array.
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

    /**
     * Gets agencies user types as index -> name array.
     * 
     * @return array
     */
    public function getTypesForAgenciesArray() {
        $rowset = $this->tableGateway->select();
        if ($rowset) {
            $selectData = array();
            foreach ($rowset as $res) {
               
                if ($res->id == 1) {
                    unset($res);
                    continue;
                }

                $selectData[$res->id] = $res->name;
            }
            return $selectData;
        } else {
            return array();
        }
    }

}
