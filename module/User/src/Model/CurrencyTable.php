<?php
namespace User\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Select;

/**
 * Class CurrencyTable
 * @package User\Model
 */
class CurrencyTable extends BaseTableModel {

    /**
     * Gets all offer types as index -> name array.
     *
     * @return array
     */
    public function getTypesArray() {
        $rowset = $this->tableGateway->select(function (Select $select) {
            $select->order('currency_order ASC');
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