<?php
namespace User\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Select;

/**
 * Class ParcelTypeTable
 * @package User\Model
 */
class ParcelTypeTable extends BaseTableModel {

    /**
     * Gets all offer types as index -> name array.
     *
     * @return array
     */
    public function getTypesArray() {
        $rowset = $this->tableGateway->select(function (Select $select) {
            $select->order('parcel_type_order ASC');
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