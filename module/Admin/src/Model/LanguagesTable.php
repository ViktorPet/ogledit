<?php
namespace Admin\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Expression;

use Zend\Db\Sql\Select; 
use Application\Model\Base\BaseGridSettings;
use Application\Model\Base\BaseGridTable;

use Admin\Model\Admin;
/**
 * Class LanguagesTable
 * @package Admin\Model
 */
class LanguagesTable extends BaseGridTable {
    
    public function getTable()
    {
        return $this->tableGateway->select();
    }
    function getData(BaseGridSettings $pGridSettings, $userId, $paging)
    {
        $thisClass = $this;        
        return $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass, $paging, $userId) {
            $select->columns(array(
                '*'
            ));
            
            // Filter
            $thisClass->filterHelper($pGridSettings, $select);

            // Sort
            $thisClass->sortHelper($pGridSettings, $select);

            // Pagination
            if ($paging == true) {
                $thisClass->pagingnHelper($pGridSettings, $select);
            }
        });        
    }
    
    function getCount(BaseGridSettings $pGridSettings = null, $userId = null)
    {        
        $thisClass = $this;        
        $res = $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass) {
            $select->columns(array(
                new Expression('COUNT(*) AS num_results')
            ));

            if(!is_null($pGridSettings)){
                // Filter
                $thisClass->filterHelper($pGridSettings, $select);
            }
        });                
        return $res->current()->getField('num_results');        
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
                $selectData[$res->getField('id')] = $res->getField('language');
            }
            return $selectData;
        } else {
            return array();
        }
    }
}