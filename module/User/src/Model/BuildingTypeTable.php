<?php
namespace User\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Select;

/**
 * Class BuildingTypeTable
 * @package User\Model
 */
class BuildingTypeTable extends BaseTableModel {

    /**
     * Gets all offer types as index -> name array.
     *
     * @return array
     */
    public function getTypesArray() {
        $rowset = $this->tableGateway->select(function (Select $select) {
            $select->order('building_type_order ASC');
        });
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