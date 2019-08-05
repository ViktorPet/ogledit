<?php
namespace User\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Select;

/**
 * Class PropertyTypeTable
 * @package User\Model
 */
class PropertyTypeTable extends BaseTableModel {

    /**
     * Gets all offer types as index -> name array.
     *
     * @return array
     */
    public function getTypesArray() {
        $rowset = $this->tableGateway->select(function (Select $select) {
            $select->order('property_type_order ASC');
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