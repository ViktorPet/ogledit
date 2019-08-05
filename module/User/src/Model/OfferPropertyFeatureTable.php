<?php
namespace User\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

/**
 * Class OfferPropertyFeatureTable
 * @package User\Model
 */
class OfferPropertyFeatureTable extends BaseTableModel {

    /**
     * Inserts property feature for offer.
     * 
     * @param $offerId
     * @param $propFeatureId
     * @return int
     */
    public function insertFeature($offerId, $propFeatureId) {
        $this->tableGateway->insert(array(
            'offer_id' => $offerId,
            'property_feature_id' => $propFeatureId
        ));
        return $this->tableGateway->getLastInsertValue();
    }

     /**
     * Deletes property feature
     *
     */
    public function deleteFeature($propFeatureId) {
        $this->tableGateway->delete(
                array(
                    'property_feature_id' => $propFeatureId
                )
        );
    }      
        
    /**
     * Gets property features by id
     *
     * @param $offerId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getPropertyFeaturesById($offerId) {

        $rowset =  $this->tableGateway->select(function (Select $select) use ($offerId) {
            $select->columns(array(
               '*',
            ));
            
            $select->where(array(
                'offer_id' => $offerId
            ));
        });
        if ($rowset) {
            $selectData = array();
            foreach ($rowset as $res) {
                $selectData[] = $res->getPropertyFeatureId();
            }
            return $selectData;
        } else {
            return array();
        }
    }
    
    /**
     * Gets property features full information by id
     *
     * @param $offerId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getPropertyFeaturesInfoById($offerId) {

        return $this->tableGateway->select(function (Select $select) use ($offerId) {
            $select->columns(array(
               '*',
            ));
            
            $select->join('property_features', 'property_features.id = property_feature_id', array('name' => 'name'));
                
            $select->where(array(
                'offer_id' => $offerId
            ));
        });
    }
}
