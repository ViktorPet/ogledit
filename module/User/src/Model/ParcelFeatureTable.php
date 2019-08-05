<?php

namespace User\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Select;

/**
 * Class ParcelFeatureTable
 * @package User\Model
 */
class ParcelFeatureTable extends BaseTableModel {

    /**
     * Gets all offer types as index -> name array.
     *
     * @return array
     */
    public function getTypesArray() {
        $rowset = $this->tableGateway->select(function (Select $select) {
            $select->order('parcel_feature_order ASC');
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

    /**
     * Gets all offer types as index -> name array  for given offer by feature id
     *
     * @return array
     */
    public function getTypesArrayForOffer($id) {
        $rowset = $this->tableGateway->select(function (Select $select) use ($id) {
            $select->order('parcel_feature_order ASC');
            $select->where(array(
                'id' => $id
            ));
        });
        if ($rowset) {
            $selectData = array();
            foreach ($rowset as $res) {
                $selectData[] = $res->name;
            }
            return $selectData;
        } else {
            return array();
        }
    }

}
