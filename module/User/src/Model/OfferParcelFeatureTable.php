<?php

namespace User\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Select;

/**
 * Class OfferParcelFeatureTable
 * @package User\Model
 */
class OfferParcelFeatureTable extends BaseTableModel {

    /**
     * Inserts parcel feature for offer.
     * 
     * @param $offerId
     * @param $parcelFeatureId
     * @return int
     */
    public function insertFeature($offerId, $parcelFeatureId) {
        $this->tableGateway->insert(array(
            'offer_id' => $offerId,
            'parcel_feature_id' => $parcelFeatureId
        ));
        return $this->tableGateway->getLastInsertValue();
    }

         /**
     * Deletes parcel feature
     *
     */
    public function deleteFeature($parcelFeatureId) {
        $this->tableGateway->delete(
                array(
                    'parcel_feature_id' => $parcelFeatureId
                )
        );
    }

    /**
     * Gets property features by id
     *
     * @param $offerId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getParcelFeaturesById($offerId) {

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
                $selectData[] = $res->getParcelFeatureId();
            }
            return $selectData;
        } else {
            return array();
        }
    }

    /**
     * Gets property features full by id
     *
     * @param $offerId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getParcelFeaturesInfoById($offerId) {

        return $this->tableGateway->select(function (Select $select) use ($offerId) {
            $select->columns(array(
               '*',
            ));

            $select->join('parcel_features', 'parcel_features.id = parcel_feature_id', array('name' => 'name'));

            $select->where(array(
                'offer_id' => $offerId
            ));
        });
    }

}
