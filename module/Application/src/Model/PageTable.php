<?php
namespace Application\Model;

use User\Model\Price;
use Zend\Db\Sql\Select;

/**
 * Class PageTable
 * @package User\Model
 */
class PageTable extends BaseTableModel {

    /**
     * Gets all pages as index -> name array.
     *
     * @return array
     */
    public function getPagesArrayByLang($languageId) {
        $rowset = $this->tableGateway->select(function (Select $select) use ($languageId) {
            $select->where->equalTo('language_id', $languageId);
            $select->order('id ASC');
        });
        if ($rowset) {
            $selectData = array();
            foreach ($rowset as $res) {
                $selectData[] = $res->getRawData();
            }
            return $selectData;
        } else {
            return array();
        }
    }

    /**
     * Gets page by its url.
     * 
     * @param $url
     * @return null
     */
    public function getPageByUrl($url, $languageId) {
        $rowset = $this->tableGateway->select(function (Select $select) use ($url, $languageId) {
            $select->where->equalTo('url', $url);
            $select->where->equalTo('language_id', $languageId);
        });
        if ($rowset->count() > 0) {
            return $rowset->current();
        } else {
            return null;
        }
    }
}