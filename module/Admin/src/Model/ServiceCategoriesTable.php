<?php

namespace Admin\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Application\Model\Base\BaseGridSettings;
use Application\Model\Base\BaseGridTable;
use Admin\Model\ServiceCategories;
use Zend\Db\Sql\Where;

/**
 * Description of ServiceCategoriesTable
 *
 */
class ServiceCategoriesTable extends BaseGridTable {

    public function getTable() {
        return $this->tableGateway->select();
    }

    function getData(BaseGridSettings $pGridSettings, $userId, $paging) {
        
    }

    function getCount(BaseGridSettings $pGridSettings = null, $userId = null) {
        
    }

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
                $selectData[$res->getField('id')] = $res->getField('name');
            }
            return $selectData;
        } else {
            return array();
        }
    }

    /**
     * Get category name
     *
     * @param $id
     * @return mixed
     */
    public function getServiceCategoryNameById($id) {
        $res = $this->tableGateway->select(function (Select $select) use ($id) {
            $where = new Where();
            $where->equalTo('id', $id);
            $select->where($where);
        });

        return $res->current();
    }

    /**
     * Get Service categories
     *
     * @return mixed
     */
    public function getServiceCategories() {
        $rowset = $this->tableGateway->select(function (Select $select) {
            $select->columns(array(
                '*'
            ));
        });

        return $rowset;
    }

}
