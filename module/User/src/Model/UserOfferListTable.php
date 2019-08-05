<?php

namespace User\Model;

use Application\Model\BaseTableModel;
use User\Model\UserOfferList;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;

/**
 * Description of UserOfferListTable
 *
 */
class UserOfferListTable extends BaseTableModel {
         
    
     /**
     * Get user list.
     *
     * @param $userId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getUserList($userId) {
        return $this->tableGateway->select(function (Select $select) use ($userId) {
            $select->join('offers', 'offers.id = user_offer_lists.offer_id', array());
            $select->join('users', 'users.id = user_offer_lists.user_id', array());

            $select->where->equalTo('user_offer_lists.user_id', $userId);
        });
    }
    
    /**
     * Creates new user public offer.
     *
     * @param Offer $offer
     * @return int
     */
    public function createList($data) {
        $this->tableGateway->insert(array(
            'user_id' => $data['user_id'],
            'offer_id' => $data['offer_id'],
            'link' => $data['link'],
            'date_created' => new Expression('NOW()')
        ));
        return $this->tableGateway->getLastInsertValue();
    }
    
    /**
     * Deletes link.
     *
     * @param $offerId, $userId
     * @return int
     */
    public function delete($offerId, $userId) {
        return $this->tableGateway->delete(array(
            'offer_id' => $offerId,
            'user_id' => $userId
        ));
    }
    
    /**
     * Gets property features full information by id
     *
     * @param $offerId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getOfferInListById($offerId, $userId) {

        return $this->tableGateway->select(function (Select $select) use ($offerId, $userId) {
            $select->columns(array(
               '*',
            ));
                
            $select->where(array(
                'offer_id' => $offerId,
                'user_id' => $userId
            ));
        });
    }
}
