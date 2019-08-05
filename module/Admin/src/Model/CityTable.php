<?php
namespace Admin\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Select;

/**
 * Class CityTable
 * @package User\Model
 */
class CityTable extends BaseTableModel {

    /**
     * Gets all offer types as index -> name array.
     *
     * @return array
     */
    public function getTypesArray() {
        $rowset = $this->tableGateway->select(function (Select $select) {
            $select->order('city_order DESC, id ASC');
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
    
    public function getNameById($cityId) { 
        
        $rowset = $this->tableGateway->select(function (Select $select) use ($cityId) {
            $select->columns(array(
                'name'
            ));
            $select->where(array(
                'id' => $cityId
            ));
        });
        return $rowset->current()->getName();        
    }
}