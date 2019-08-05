<?php
namespace User\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Select;

/**
 * Class NeighbourhoodTable
 * @package User\Model
 */
class NeighbourhoodTable extends BaseTableModel {
    
    /**
     * Gets all offer types as index -> name array.
     * 
     * @param int $cityId
     * @return array
     */
    public function getTypesArray($cityId = 1) {
        $rowset = $this->tableGateway->select(function (Select $select) use ($cityId) {
            $select->where(['city_id' => $cityId]);
            $select->order('neighbourhood_order DESC');
        });
        if ($rowset) {
            $selectData = array();
            foreach ($rowset as $res) {
                $selectData[$res->neighbourhoodId] = $res->neighbourhoodName;
            }
            return $selectData;
        } else {
            return array();
        }
    }

    /**
     * Gets all offer types as index -> name array.
     *
     * @param int $cityId
     * @return array
     */
    public function getTypesObject($cityId = 1) {
        $rowset = $this->tableGateway->select(function (Select $select) use ($cityId) {
            $select->where(['city_id' => $cityId]);
            $select->order('neighbourhood_order DESC');
        });
        if ($rowset) {
            $selectData = array();
            foreach ($rowset as $res) {
                $selectData[] = ['key' => $res->neighbourhoodId, 'value' => $res->neighbourhoodName];
            }
            return $selectData;
        } else {
            return array();
        }
    }
    
    public function getNameById($neighbourhoodId) { 
        
        $rowset = $this->tableGateway->select(function (Select $select) use ($neighbourhoodId) {
            $select->columns(array(
                'neighbourhood_name'
            ));
            $select->where(array(
                'neighbourhood_id' => $neighbourhoodId
            ));
        });
        return $rowset->current()->getNeighbourhoodName();        
    }
}