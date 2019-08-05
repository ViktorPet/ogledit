<?php
namespace User\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Select;

/**
 * Class PriceTable
 * @package User\Model
 */
class PriceTable extends BaseTableModel {

    /**
     * Gets prices according to number of active offers.
     *
     * @param $userId
     * @return array
     */
    public function getPriceByActiveOffers($numOffers) {
        $rowset = $this->tableGateway->select(function (Select $select) use ($numOffers) {
            $select->where->lessThanOrEqualTo('min_offers', $numOffers);
            $select->where->greaterThanOrEqualTo('max_offers', $numOffers);
        });

        if ($rowset) {
            return $rowset->current();
        } else {
            return new Price();
        }
    }
    
     /**
     * Gets all price types as index -> name array.
     * 
     * @return array
     */
    public function getTypesArray() {
        $rowset = $this->tableGateway->select();
        if ($rowset) {
            $selectData = array();
            foreach ($rowset as $res) {
                $selectData[$res->id] = $res->priceName;
            }
            return $selectData;                     
        } else {
            return array();
        }
    }   
    
    
    /**
     * Gets prices according to number of active offers.
     *
     * @param $userId
     * @return array
     */
    public function getPriceByUserPriceId($priceId) {
        $rowset = $this->tableGateway->select(function (Select $select) use ($priceId) {
            $select->where->equalTo('id', $priceId);
        });

        if ($rowset) {
            return $rowset->current();
        } else {
            return new Price();
        }
    }
    
    
    /**
     * Gets prices according to number of active offers.
     *
     * @param $userId
     * @return array
     */
    public function getPriceByActiveOffersAndTypeId($numOffers, $userTypeId) {
        $rowset = $this->tableGateway->select(function (Select $select) use ($numOffers, $userTypeId) {
            $select->where->lessThanOrEqualTo('min_offers', $numOffers);
            $select->where->greaterThanOrEqualTo('max_offers', $numOffers);
            $select->where->equalTo('user_type_id', $userTypeId);
        });

        if ($rowset) {
            return $rowset->current();
        } else {
            return new Price();
        }
    }
    
}