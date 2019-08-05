<?php
namespace User\Model;

use Application\Model\BaseTableModel;
use Application\Model\Offer;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;

/**
 * Class OfferTable
 * @package User\Model
 */
class OfferTable extends BaseTableModel
{

    /**
     * Gets all offer types as index -> name array.
     *
     * @return array
     */
    public function getTypesArray()
    {
        $rowset = $this->tableGateway->select();
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
     * Creates new user public offer.
     *
     * @param Offer $offer
     * @return int
     */
    public function createUserOffer(Offer $offer)
    {
        $this->tableGateway->insert(array(
            'title' => '',
            'description' => '',
            'panorama_file' => 'n',
            'top_offer' => $offer->getTopOffer(),
            'vip_offer' => $offer->getVipOffer(),
//            'chat_offer' => $offer->getChatOffer(),
            'schema_offer' => $offer->getSchemaOffer(),
            'price' => $offer->getPrice(),
            'currency_id' => $offer->getCurrencyId(),
            'construction_year' => $offer->getConstructionYear(),
            'area' => $offer->getArea(),
            'floor' => $offer->getFloor(),
            'bathrooms' => $offer->getBathrooms(),
            'total_rooms' => $offer->getTotalRooms(),
            'parking_slots' => $offer->getParkingSlots(),
            'information' => $offer->getInformation(),
            'photographer_address' => $offer->getOldOfferId() ? '' : $offer->getPhotographerAddress(),
            'photographer_appointment' => $offer->getOldOfferId() ? new Expression('NULL') : $offer->getPhotographerAppointment(),
            'garden' => $offer->getGarden(),
            'date_created' => new Expression('NOW()'),
            'date_updated' => new Expression('NOW()'),
            'active_until_date' => new Expression('DATE_ADD(NOW(),INTERVAL ' . (2 + $offer->getWeeks()) . ' WEEK)'),
            'extra_until_date' => new Expression('DATE_ADD(NOW(),INTERVAL ' . (2 + $offer->getExtraWeeks()) . ' WEEK)'),
            'offer_status_id' => $offer->getOfferStatusId(),
            'offer_type_id' => $offer->getOfferTypeId(),
            'building_type_id' => $offer->getBuildingTypeId(),
            'property_type_id' => $offer->getPropertyTypeId(),
            'heating_system_id' => $offer->getHeatingSystemId(),
            'user_id' => $offer->getUserId(),
            'neighbourhood_id' => $offer->getNeighbourhoodId(),
            'city_id' => $offer->getCityId(),
            'street' => $offer->getStreet(),
            'lat' => $offer->getLat(),
            'lng' => $offer->getLng(),
            'is_regulated' => $offer->getIsRegulated(),
            'parcel_type_id' => $offer->getParcelTypeId(),
            'yard' => $offer->getYard(),
            'yard_shot' => $offer->getYardShot(),
            'youtube_code_1' => $offer->getYoutubeCode1(),
        ));
        return $this->tableGateway->getLastInsertValue();
    }


    /**
     * Update meta tags
     *
     * @param $offerId
     * @return int
     */
    public function updateMetaTags($offerId, $metaTitle, $metaDescription, $metaKeywords)
    {
        $this->tableGateway->update(array(
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'meta_keywords' => $metaKeywords
        ), array(
            'id' => $offerId
        ));
    }


    /**
     * Edit offer
     *
     * @param Offer $offer
     * @return mixed
     */
    public function editUserOffer(Offer $offer, $offerObj, $agencyCreator, $activeUntilDate, $extraUntilDate)
    {
        $agentsSelect = new Select('users');
        $agentsSelect->columns(array('id'));
        $agentsSelect->where->equalTo('parent_user_id', $offerObj->getUserId())->OR->equalTo('user_id', $offerObj->getUserId());

        $where = new Where();
        $where->equalTo('id', $offerObj->getId());
        $where->in('user_id', $agentsSelect);

        $this->tableGateway->update(array(
            'user_id' => $offer->getUserId() ?? $agencyCreator->getId(),
            'offer_status_id' => $offer->getOfferStatusId(),
            'user_offer_status_id' => $offer->getUserOfferStatusId(),
            'title' => '',
            'description' => '',
            'top_offer' => $offer->getTopOffer() ?? $offerObj->getTopOffer(),
            'vip_offer' => $offer->getVipOffer() ?? $offerObj->getVipOffer(),
//            'chat_offer' => $offer->getChatOffer() ?? $offerObj->getChatOffer(),
            'schema_offer' => $offer->getSchemaOffer() ?? $offerObj->getSchemaOffer(),
            'price' => $offer->getPrice(),
            'currency_id' => $offer->getCurrencyId(),
            'construction_year' => $offer->getConstructionYear(),
            'floor' => $offer->getFloor(),
            'bathrooms' => $offer->getBathrooms(),
            'total_rooms' => $offer->getTotalRooms(),
            'parking_slots' => $offer->getParkingSlots(),
            'information' => $offer->getInformation(),
            'garden' => $offer->getGarden(),
            'date_updated' => new Expression('NOW()'),
            'active_until_date' => $offer->getWeeks() ? new Expression('DATE_ADD("' . $activeUntilDate . '",INTERVAL ' . ($offer->getWeeks()) . ' WEEK)') : $offerObj->getActiveUntilDate(),
            'extra_until_date' => $offer->getExtraWeeks() ? new Expression('DATE_ADD("' . $extraUntilDate . '",INTERVAL ' . ($offer->getExtraWeeks()) . ' WEEK)') : $offerObj->getExtraUntilDate(),
            'offer_type_id' => $offer->getOfferTypeId(),
            'building_type_id' => $offer->getBuildingTypeId(),
            'property_type_id' => $offer->getPropertyTypeId(),
            'heating_system_id' => $offer->getHeatingSystemId(),
            'neighbourhood_id' => $offer->getNeighbourhoodId(),
            'city_id' => $offer->getCityId(),
            'street' => $offer->getStreet(),
            'lat' => $offer->getLat(),
            'lng' => $offer->getLng(),
            'is_regulated' => $offer->getIsRegulated(),
            'parcel_type_id' => $offer->getParcelTypeId(),
            'yard' => $offer->getYard()
        ), $where);
    }


    /**
     * Edit offer if user is not attached multimedia
     *
     * @param Offer $offer
     * @return mixed
     */
    public function updateOfferData(Offer $offer, $offerObj, $agencyCreator)
    {
        $agentsSelect = new Select('users');
        $agentsSelect->columns(array('id'));
        $agentsSelect->where->equalTo('parent_user_id', $offerObj->getUserId())->OR->equalTo('user_id', $offerObj->getUserId());

        $where = new Where();
        $where->equalTo('id', $offerObj->getId());
        $where->in('user_id', $agentsSelect);

        $this->tableGateway->update(array(
            //            'panorama_file' => 'n',
            'user_id' => $offer->getUserId() ?? $agencyCreator->getId(),
            'offer_status_id' => $offer->getOfferStatusId(),
            'user_offer_status_id' => $offer->getUserOfferStatusId(),
            'title' => '',
            'description' => '',
            'top_offer' => $offer->getTopOffer(),
            'vip_offer' => $offer->getVipOffer(),
//            'chat_offer' => $offer->getChatOffer(),
            'schema_offer' => $offer->getSchemaOffer(),
            'price' => $offer->getPrice(),
            'currency_id' => $offer->getCurrencyId(),
            'construction_year' => $offer->getConstructionYear(),
            'floor' => $offer->getFloor(),
            'bathrooms' => $offer->getBathrooms(),
            'total_rooms' => $offer->getTotalRooms(),
            'parking_slots' => $offer->getParkingSlots(),
            'information' => $offer->getInformation(),
            'garden' => $offer->getGarden(),
            'date_created' => new Expression('NOW()'),
            'date_updated' => new Expression('NOW()'),
            'photographer_address' => $offer->getPhotographerAddress(),
            'photographer_appointment' => $offer->getPhotographerAppointment(),
            'active_until_date' => new Expression('DATE_ADD(NOW(),INTERVAL ' . (2 + $offer->getWeeks()) . ' WEEK)'),
            'extra_until_date' => new Expression('DATE_ADD(NOW(),INTERVAL ' . (2 + $offer->getExtraWeeks()) . ' WEEK)'),
            'offer_type_id' => $offer->getOfferTypeId(),
            'building_type_id' => $offer->getBuildingTypeId(),
            'property_type_id' => $offer->getPropertyTypeId(),
            'heating_system_id' => $offer->getHeatingSystemId(),
            'neighbourhood_id' => $offer->getNeighbourhoodId(),
            'city_id' => $offer->getCityId(),
            'street' => $offer->getStreet(),
            'lat' => $offer->getLat(),
            'lng' => $offer->getLng(),
            'is_regulated' => $offer->getIsRegulated(),
            'parcel_type_id' => $offer->getParcelTypeId(),
            'yard' => $offer->getYard(),
            'area' => $offer->getArea(),
        ), $where);
    }


    /**
     * Gets the number of active user offers.
     *
     * @param $userId
     * @return mixed
     */
    public function getCountActiveOffers($userId)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use ($userId) {
            $select->columns(array(
                'num_count' => new Expression('COUNT(*)')
            ));
            $select->where(array(
                'user_id' => $userId,
                'offer_status_id' => 4,
            ));
        });
        return $rowset->current()->getNumCount();
    }

    /**
     * Gets offers ids
     *
     * @param $userId
     * @return mixed
     */
    public function getOffersWithPanorama($userId)
    {
        return $this->tableGateway->select(function (Select $select) use ($userId) {
            $select->columns(array(
                '*',
            ));
            $select->where(array(
                'user_id' => $userId,
                'panorama_file' => 'y',
            ));
        });
    }


    /**
     * Gets the number of user offers.
     *
     * @param $userId
     * @return mixed
     */
    public function getCountOffersByUserId($userId)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use ($userId) {
            $select->columns(array(
                'num_count' => new Expression('COUNT(*)')
            ));
            $select->where(array(
                'user_id' => $userId
            ));
        });
        return $rowset->current()->getNumCount();
    }


    /**
     * Updates offer status to paid by ID.
     *
     * @param $offerId
     * @return int
     */
    public function setPaidById($offerId)
    {
        return $this->tableGateway->update(array(
            'offer_status_id' => 2
        ),
            array(
                'id' => $offerId
            ));
    }

    /**
     * Updates offer status to pending payment by ID.
     *
     * @param $offerId
     * @return int
     */
    public function setPendingPaymentById($offerId)
    {
        return $this->tableGateway->update(array(
            'offer_status_id' => 1
        ),
            array(
                'id' => $offerId
            ));
    }

    /**
     * Updates offer status to active by ID.
     *
     * @param $offerId
     * @return int
     */
    public function setActiveById($offerId)
    {
        return $this->tableGateway->update(array(
            'offer_status_id' => 4
        ),
            array(
                'id' => $offerId
            ));
    }

    /**
     * Updates offer status to expired by ID.
     *
     * @param $offerId
     * @return int
     */
    public function setExpiredById($offerId)
    {
        return $this->tableGateway->update(array(
            'offer_status_id' => 5
        ),
            array(
                'id' => $offerId
            ));
    }

    /**
     * Updates offer counter
     *
     * @param $offerId
     * @param $counter
     * @return int
     */
    public function setCounterById($offerId, $counter)
    {
        return $this->tableGateway->update(array(
            'counter' => $counter
        ),
            array(
                'id' => $offerId
            ));
    }

    /**
     * Updates offer status to paid by ID.
     *
     * @param $offerId
     * @return int
     */
    public function setPendingDeletionById($offerId, $userId)
    {
        $agentsSelect = new Select('users');
        $agentsSelect->columns(array('id'));
        $agentsSelect->where->equalTo('parent_user_id', $userId)->OR->equalTo('user_id', $userId);

        $where = new Where();
        $where->equalTo('id', $offerId);
        $where->in('user_id', $agentsSelect);

        return $this->tableGateway->update(array(
            'offer_status_id' => 7
        ), $where);
    }

    /**
     * Updates offer status to delete by admin.
     *
     * @param $offerId
     * @return int
     */
    public function setDeletionById($offerId, $userId)
    {
        $agentsSelect = new Select('users');
        $agentsSelect->columns(array('id'));
        $agentsSelect->where->equalTo('parent_user_id', $userId)->OR->equalTo('user_id', $userId);

        $where = new Where();
        $where->equalTo('id', $offerId);
        $where->in('user_id', $agentsSelect);

        return $this->tableGateway->update(array(
            'offer_status_id' => 11,
            'panorama_file' => 'n',
            'youtube_code_2' => 'n'
        ), $where);
    }

    /**
     * Gets all user sell offers.
     *
     * @param $userId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getUserOffersSell($userId)
    {

        $where = new Where();
        $where->equalTo('offer_type_id', '1')->AND->nest()->isNull('user_offer_status_id')->or->in('user_offer_status_id', array(0))->unnest();

        return $this->getUserOffers($userId, $where);
    }

    /**
     * Gets all user rent offers.
     *
     * @param $userId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getUserOffersRent($userId)
    {
        $where = new Where();
        $where->equalTo('offer_type_id', '2')->AND->nest()->isNull('user_offer_status_id')->or->in('user_offer_status_id', array(0))->unnest();

        return $this->getUserOffers($userId, $where);
    }

    /**
     * Gets all user expired offers.
     *
     * @param $userId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getUserOffersExpired($userId)
    {
        return $this->getUserOffers($userId, array('offer_status_id' => '5'));
    }

    /**
     * Gets all user expired offers.
     *
     * @param $userId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getOffer($userId, $offerId)
    {
        return $this->getUserOffers($userId, array('offers.id' => $offerId));
    }

    /**
     * Gets all user expired offers.
     *
     * @param $userId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getUserOffersArchive($userId)
    {
        return $this->getUserOffersForArchive($userId, array('user_offer_status_id' => '1'));
    }

    /**
     * Gets all user nights offers.
     *
     * @param $userId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getUserOffersNights($userId)
    {
        $where = new Where();
        $where->equalTo('offer_type_id', '3')->AND->nest()->isNull('user_offer_status_id')->or->in('user_offer_status_id', array(0))->unnest();

        return $this->getUserOffers($userId, $where);
    }

    /**
     * Gets all user expired offers.
     *
     * @param $userId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getUserOffersForList($userId, $offerId)
    {
        return $this->getOffersForList(array('offers.id' => $offerId));
    }

    /**
     * Gets all user offers by condition and user Id.
     *
     * @param $userId
     * @param $condition
     * @return \Zend\Db\ResultSet\ResultSet
     */
    private function getUserOffers($userId, $condition)
    {
        return $this->tableGateway->select(function (Select $select) use ($userId, $condition) {
            $select->join('property_types', 'property_types.id = offers.property_type_id', array('property_type_name' => 'name'));
            $select->join('offer_types', 'offer_types.id = offers.offer_type_id', array('offer_type_name' => 'name'));
            $select->join('cities', 'cities.id = offers.city_id', array('city_name' => 'name'));
            $select->join('neighbourhoods', 'neighbourhoods.neighbourhood_id = offers.neighbourhood_id', array('neighbourhood_name' => 'neighbourhood_name'));
            $select->join('currencies', 'currencies.id = offers.currency_id', array('currency_short_name' => 'short_name'));
            $select->join('building_types', 'building_types.id = offers.building_type_id', array('building_type_name' => 'name'), Select::JOIN_LEFT);
            $select->join('heating_systems', 'heating_systems.id = offers.heating_system_id', array('heating_system_name' => 'name'), Select::JOIN_LEFT);
            $select->join('offer_statuses', 'offer_statuses.id = offers.offer_status_id', array('offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('user_offer_statuses', 'user_offer_statuses.id = offers.user_offer_status_id', array('user_offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('gallery', new Expression('gallery.offer_id = offers.id and gallery.is_front = 1'), array('image' => 'image'), Select::JOIN_LEFT);

            $agentsSelect = new Select('users');
            $agentsSelect->columns(array('id'));
            $agentsSelect->where->equalTo('parent_user_id', $userId)->OR->equalTo('user_id', $userId);

            $select->where($condition);
            $select->where->in('user_id', $agentsSelect);
            $select->where->greaterThanOrEqualTo('offer_status_id', 1);
            $select->where->lessThanOrEqualTo('offer_status_id', 5);

            $select->order('property_types.property_type_order ASC, offers.id ASC');

        });
    }

    /**
     * Gets all user offers for arhive by condition and user Id.
     *
     * @param $userId
     * @param $condition
     * @return \Zend\Db\ResultSet\ResultSet
     */
    private function getUserOffersForArchive($userId, $condition)
    {
        return $this->tableGateway->select(function (Select $select) use ($userId, $condition) {
            $select->join('property_types', 'property_types.id = offers.property_type_id', array('property_type_name' => 'name'));
            $select->join('offer_types', 'offer_types.id = offers.offer_type_id', array('offer_type_name' => 'name'));
            $select->join('cities', 'cities.id = offers.city_id', array('city_name' => 'name'));
            $select->join('neighbourhoods', 'neighbourhoods.neighbourhood_id = offers.neighbourhood_id', array('neighbourhood_name' => 'neighbourhood_name'));
            $select->join('currencies', 'currencies.id = offers.currency_id', array('currency_short_name' => 'short_name'));
            $select->join('building_types', 'building_types.id = offers.building_type_id', array('building_type_name' => 'name'), Select::JOIN_LEFT);
            $select->join('heating_systems', 'heating_systems.id = offers.heating_system_id', array('heating_system_name' => 'name'), Select::JOIN_LEFT);
            $select->join('offer_statuses', 'offer_statuses.id = offers.offer_status_id', array('offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('user_offer_statuses', 'user_offer_statuses.id = offers.user_offer_status_id', array('user_offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('gallery', new Expression('gallery.offer_id = offers.id and gallery.is_front = 1'), array('image' => 'image'), Select::JOIN_LEFT);

            $agentsSelect = new Select('users');
            $agentsSelect->columns(array('id'));
            $agentsSelect->where->equalTo('parent_user_id', $userId)->OR->equalTo('user_id', $userId);

            $select->where($condition);
            $select->where->in('user_id', $agentsSelect);
            $select->where->lessThanOrEqualTo('offer_status_id', 6);

            $select->order('property_types.property_type_order ASC, offers.id ASC');

        });
    }


    /**
     * Gets all user offers by condition and user Id.
     *
     * @param $userId
     * @param $condition
     * @return \Zend\Db\ResultSet\ResultSet
     */
    private function getOffersForList($condition)
    {
        return $this->tableGateway->select(function (Select $select) use ($condition) {
            $select->join('property_types', 'property_types.id = offers.property_type_id', array('property_type_name' => 'name'));
            $select->join('offer_types', 'offer_types.id = offers.offer_type_id', array('offer_type_name' => 'name'));
            $select->join('cities', 'cities.id = offers.city_id', array('city_name' => 'name'));
            $select->join('neighbourhoods', 'neighbourhoods.neighbourhood_id = offers.neighbourhood_id', array('neighbourhood_name' => 'neighbourhood_name'));
            $select->join('currencies', 'currencies.id = offers.currency_id', array('currency_short_name' => 'short_name'));
            $select->join('building_types', 'building_types.id = offers.building_type_id', array('building_type_name' => 'name'), Select::JOIN_LEFT);
            $select->join('heating_systems', 'heating_systems.id = offers.heating_system_id', array('heating_system_name' => 'name'), Select::JOIN_LEFT);
            $select->join('offer_statuses', 'offer_statuses.id = offers.offer_status_id', array('offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('user_offer_statuses', 'user_offer_statuses.id = offers.user_offer_status_id', array('user_offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('gallery', new Expression('gallery.offer_id = offers.id and gallery.is_front = 1'), array('image' => 'image'), Select::JOIN_LEFT);

            $select->where($condition);
            $select->where->greaterThanOrEqualTo('offer_status_id', 1);
            $select->where->lessThanOrEqualTo('offer_status_id', 6);

            $select->order('property_types.property_type_order ASC, offers.id ASC');

        });
    }

    /**
     * Gets offer info by data Id and User.
     *
     * @param $offerId
     * @param $userId
     * @return array|\ArrayObject|null
     */
    public function getOfferByIdAndUser($offerId, $userId)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use ($offerId, $userId) {
            $agentsSelect = new Select('users');
            $agentsSelect->columns(array('id'));
            $agentsSelect->where->equalTo('parent_user_id', $userId)->OR->equalTo('user_id', $userId);

            $select->where->in('user_id', $agentsSelect);
            $select->where->equalTo('id', $offerId);
            $select->where->greaterThanOrEqualTo('offer_status_id', 1);
            $select->where->lessThanOrEqualTo('offer_status_id', 6);

        });
        if ($rowset) {
            return $rowset->current();
        } else {
            return null;
        }
    }

    /**
     * Gets offer by id
     *
     * @param $offerId
     * @return mixed
     */
    public function getOfferById($offerId)
    {
        $result = $this->tableGateway->select(function (Select $select) use ($offerId) {
            $select->join('gallery', new Expression('gallery.offer_id = offers.id and gallery.is_front = 1'), array('image' => 'image'), Select::JOIN_LEFT);
            $select->where(array(
                'offers.id' => $offerId
            ));
        });
        return $result->current();
    }

    /**
     * Gets Top offers.
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getTopOffers()
    {
        return $this->tableGateway->select(function (Select $select) {
            $select->join('property_types', 'property_types.id = offers.property_type_id', array('property_type_name' => 'name'));
            $select->join('offer_types', 'offer_types.id = offers.offer_type_id', array('offer_type_name' => 'name'));
            $select->join('cities', 'cities.id = offers.city_id', array('city_name' => 'name'));
            $select->join('neighbourhoods', 'neighbourhoods.neighbourhood_id = offers.neighbourhood_id', array('neighbourhood_name' => 'neighbourhood_name'));
            $select->join('currencies', 'currencies.id = offers.currency_id', array('currency_short_name' => 'short_name'));
            $select->join('building_types', 'building_types.id = offers.building_type_id', array('building_type_name' => 'name'), Select::JOIN_LEFT);
            $select->join('offer_statuses', 'offer_statuses.id = offers.offer_status_id', array('offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('user_offer_statuses', 'user_offer_statuses.id = offers.user_offer_status_id', array('user_offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('gallery', 'gallery.offer_id = offers.id', array('image' => 'image'), Select::JOIN_LEFT);

            $select->where->equalTo('offer_status_id', 4);
            $select->where->equalTo('top_offer', 1);
            $select->where->equalTo('gallery.is_front', 1);

            $rand = new \Zend\Db\Sql\Expression('RAND()');
            $select->order($rand);

        });
    }

    /**
     * Gets VIP offers.
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getVipOffers()
    {
        return $this->tableGateway->select(function (Select $select) {
            $select->join('property_types', 'property_types.id = offers.property_type_id', array('property_type_name' => 'name'));
            $select->join('offer_types', 'offer_types.id = offers.offer_type_id', array('offer_type_name' => 'name'));
            $select->join('cities', 'cities.id = offers.city_id', array('city_name' => 'name'));
            $select->join('neighbourhoods', 'neighbourhoods.neighbourhood_id = offers.neighbourhood_id', array('neighbourhood_name' => 'neighbourhood_name'));
            $select->join('currencies', 'currencies.id = offers.currency_id', array('currency_short_name' => 'short_name'));
            $select->join('building_types', 'building_types.id = offers.building_type_id', array('building_type_name' => 'name'), Select::JOIN_LEFT);
            $select->join('offer_statuses', 'offer_statuses.id = offers.offer_status_id', array('offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('user_offer_statuses', 'user_offer_statuses.id = offers.user_offer_status_id', array('user_offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('gallery', 'gallery.offer_id = offers.id', array('image' => 'image'), Select::JOIN_LEFT);

            $select->where->equalTo('offer_status_id', 4);
            $select->where->equalTo('vip_offer', 1);
            $select->where->equalTo('gallery.is_front', 1);

            $rand = new \Zend\Db\Sql\Expression('RAND()');
            $select->order($rand);

        });
    }

    /**
     * Gets public offer by ID.
     *
     * @param $offerId
     * @return array|\ArrayObject|null
     */
    public function getPublicOfferById($offerId)
    {
        $rowdata = $this->tableGateway->select(function (Select $select) use ($offerId) {
            $select->join('property_types', 'property_types.id = offers.property_type_id', array('property_type_name' => 'name'));
            $select->join('offer_types', 'offer_types.id = offers.offer_type_id', array('offer_type_name' => 'name'));
            $select->join('cities', 'cities.id = offers.city_id', array('city_name' => 'name'));
            $select->join('neighbourhoods', 'neighbourhoods.neighbourhood_id = offers.neighbourhood_id', array('neighbourhood_name' => 'neighbourhood_name'));
            $select->join('currencies', 'currencies.id = offers.currency_id', array('currency_short_name' => 'short_name'));
            $select->join('building_types', 'building_types.id = offers.building_type_id', array('building_type_name' => 'name'), Select::JOIN_LEFT);
            $select->join('heating_systems', 'heating_systems.id = offers.heating_system_id', array('heating_system_name' => 'name'), Select::JOIN_LEFT);
            $select->join('offer_statuses', 'offer_statuses.id = offers.offer_status_id', array('offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('user_offer_statuses', 'user_offer_statuses.id = offers.user_offer_status_id', array('user_offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('gallery', 'gallery.offer_id = offers.id', array('image' => 'image'), Select::JOIN_LEFT);

            $select->where->equalTo('offers.offer_status_id', 4);
            $select->where->equalTo('offers.id', $offerId);
        });
        if ($rowdata->current()) {
            return $rowdata->current();
        } else {
            return null;
        }
    }

    /**
     * Gets offer that is not public public (active) by ID.
     *
     * @param $offerId
     * @return array|\ArrayObject|null
     */
    public function getNotPublicOfferById($offerId)
    {
        $rowdata = $this->tableGateway->select(function (Select $select) use ($offerId) {
            $select->join('property_types', 'property_types.id = offers.property_type_id', array('property_type_name' => 'name'));
            $select->join('offer_types', 'offer_types.id = offers.offer_type_id', array('offer_type_name' => 'name'));
            $select->join('cities', 'cities.id = offers.city_id', array('city_name' => 'name'));
            $select->join('neighbourhoods', 'neighbourhoods.neighbourhood_id = offers.neighbourhood_id', array('neighbourhood_name' => 'neighbourhood_name'));
            $select->join('currencies', 'currencies.id = offers.currency_id', array('currency_short_name' => 'short_name'));
            $select->join('building_types', 'building_types.id = offers.building_type_id', array('building_type_name' => 'name'), Select::JOIN_LEFT);
            $select->join('heating_systems', 'heating_systems.id = offers.heating_system_id', array('heating_system_name' => 'name'), Select::JOIN_LEFT);
            $select->join('offer_statuses', 'offer_statuses.id = offers.offer_status_id', array('offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('user_offer_statuses', 'user_offer_statuses.id = offers.user_offer_status_id', array('user_offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('gallery', 'gallery.offer_id = offers.id', array('image' => 'image'), Select::JOIN_LEFT);

            $select->where->notEqualTo('offers.offer_status_id', 4);
            $select->where->equalTo('offers.id', $offerId);
        });
        if ($rowdata->current()) {
            return $rowdata->current();
        } else {
            return null;
        }
    }

    /**
     * Returns the count of offers, found by the given set of parameters.
     *
     * @param $params
     * @return int
     */
    public function searchOffersCount($params)
    {
        $rowdata = $this->tableGateway->select(function (Select $select) use ($params) {
            $select->columns(array(
                'num_results' => new Expression('COUNT(*)')
            ));

            $select->where->nest()->isNull('offers.user_offer_status_id')->or->in('offers.user_offer_status_id', array(0, 2, 3))->unnest();
            $select->where->equalTo('offers.offer_status_id', 4);

            if (is_numeric($params['offer_type_id'])) {
                $select->where->equalTo('offers.offer_type_id', $params['offer_type_id']);
            }
            if (is_numeric($params['property_type_id'])) {
                $select->where->equalTo('offers.property_type_id', $params['property_type_id']);
            }
            if (is_numeric($params['city_id'])) {
                $select->where->equalTo('offers.city_id', $params['city_id']);
            }
            if ((isset($params['neighbourhood_id'])) && (is_array($params['neighbourhood_id'])) && (count($params['neighbourhood_id']) > 0)) {
                $select->where->in('offers.neighbourhood_id', $params['neighbourhood_id']);
            }
            if (($params['keyword'] != '')) {
                $select->where->like('offers.information', '%' . $params['keyword'] . '%');
            }
            if (is_numeric($params['minprice'])) {
                $select->where->greaterThanOrEqualTo('offers.price', $params['minprice']);
            }
            if (is_numeric($params['maxprice'])) {
                $select->where->lessThanOrEqualTo('offers.price', $params['maxprice']);
            }
            if (is_numeric($params['minsqm'])) {
                $select->where->greaterThanOrEqualTo('offers.area', $params['minsqm']);
            }
            if (is_numeric($params['maxsqm'])) {
                $select->where->lessThanOrEqualTo('offers.area', $params['maxsqm']);
            }
            if (is_numeric($params['floor_from'])) {
                $select->where->greaterThanOrEqualTo('offers.floor', $params['floor_from']);
            }
            if (is_numeric($params['floor_to'])) {
                $select->where->lessThanOrEqualTo('offers.floor', $params['floor_to']);
            }
            if (is_numeric($params['construction_year_from'])) {
                $select->where->greaterThanOrEqualTo('offers.construction_year', $params['construction_year_from']);
            }
            if (is_numeric($params['construction_year_to'])) {
                $select->where->lessThanOrEqualTo('offers.construction_year', $params['construction_year_to']);
            }
            if (is_numeric($params['yard_from'])) {
                $select->where->greaterThanOrEqualTo('offers.yard', $params['yard_from']);
            }
            if (is_numeric($params['yard_to'])) {
                $select->where->lessThanOrEqualTo('offers.yard', $params['yard_to']);
            }
            if (is_numeric($params['heating_system_id'])) {
                $select->where->equalTo('offers.heating_system_id', $params['heating_system_id']);
            }
            if ((isset($params['property_features'])) && (is_array($params['property_features'])) && (count($params['property_features']) > 0)) {
                $featuresSelect = new Select('offer_property_features');
                $featuresSelect->columns(array(
                    'num_results' => new Expression('COUNT(*)')
                ));
                $featuresSelect->where->equalTo('offer_property_features.offer_id', new Expression('offers.id'));
                $featuresSelect->where->in('offer_property_features.property_feature_id', $params['property_features']);
                $select->where->equalTo($featuresSelect, count($params['property_features']));
            }
            if (is_numeric($params['agency_id'])) {
                $agentsSelect = new Select('users');
                $agentsSelect->columns(array('id'));
                $agentsSelect->where->equalTo('parent_user_id', $params['agency_id'])->OR->equalTo('user_id', $params['agency_id']);
                $select->where->in('offers.user_id', $agentsSelect);
            }

        });

        if ($rowdata->current()) {
            return is_numeric($rowdata->current()->getNumResults()) ? $rowdata->current()->getNumResults() : 0;
        } else {
            return 0;
        }

    }

    /**
     * Returns all offers, found by the given set of parameters.
     *
     * @param $params
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function searchOffersData($params, $limit = 2000, $offset = 0)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use ($params, $limit, $offset) {
            $select->join('property_types', 'property_types.id = offers.property_type_id', array('property_type_name' => 'name'));
            $select->join('offer_types', 'offer_types.id = offers.offer_type_id', array('offer_type_name' => 'name'));
            $select->join('cities', 'cities.id = offers.city_id', array('city_name' => 'name'));
            $select->join('neighbourhoods', 'neighbourhoods.neighbourhood_id = offers.neighbourhood_id', array('neighbourhood_name' => 'neighbourhood_name'));
            $select->join('currencies', 'currencies.id = offers.currency_id', array('currency_short_name' => 'short_name'));
            $select->join('building_types', 'building_types.id = offers.building_type_id', array('building_type_name' => 'name'), Select::JOIN_LEFT);
            $select->join('offer_statuses', 'offer_statuses.id = offers.offer_status_id', array('offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('user_offer_statuses', 'user_offer_statuses.id = offers.user_offer_status_id', array('user_offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('gallery', 'gallery.offer_id = offers.id', array('image' => 'image'), Select::JOIN_LEFT);

            $select->where->equalTo('gallery.is_front', 1);
            $select->where->nest()->isNull('offers.user_offer_status_id')->or->in('offers.user_offer_status_id', array(0, 2, 3))->unnest();
            $select->where->equalTo('offers.offer_status_id', 4);

            if (is_numeric($params['offer_type_id'])) {
                $select->where->equalTo('offers.offer_type_id', $params['offer_type_id']);
            }
            if (is_numeric($params['property_type_id'])) {
                $select->where->equalTo('offers.property_type_id', $params['property_type_id']);
            }
            if (is_numeric($params['city_id'])) {
                $select->where->equalTo('offers.city_id', $params['city_id']);
            }
            if ((isset($params['neighbourhood_id'])) && (is_array($params['neighbourhood_id'])) && (count($params['neighbourhood_id']) > 0)) {
                $select->where->in('offers.neighbourhood_id', $params['neighbourhood_id']);
            }
            if (($params['keyword'] != '')) {
                $select->where->like('offers.information', '%' . $params['keyword'] . '%');
            }
            if (is_numeric($params['minprice'])) {
                $select->where->greaterThanOrEqualTo('offers.price', $params['minprice']);
            }
            if (is_numeric($params['maxprice'])) {
                $select->where->lessThanOrEqualTo('offers.price', $params['maxprice']);
            }
            if (is_numeric($params['minsqm'])) {
                $select->where->greaterThanOrEqualTo('offers.area', $params['minsqm']);
            }
            if (is_numeric($params['maxsqm'])) {
                $select->where->lessThanOrEqualTo('offers.area', $params['maxsqm']);
            }
            if (is_numeric($params['floor_from'])) {
                $select->where->greaterThanOrEqualTo('offers.floor', $params['floor_from']);
            }
            if (is_numeric($params['floor_to'])) {
                $select->where->lessThanOrEqualTo('offers.floor', $params['floor_to']);
            }
            if (is_numeric($params['construction_year_from'])) {
                $select->where->greaterThanOrEqualTo('offers.construction_year', $params['construction_year_from']);
            }
            if (is_numeric($params['construction_year_to'])) {
                $select->where->lessThanOrEqualTo('offers.construction_year', $params['construction_year_to']);
            }
            if (is_numeric($params['yard_from'])) {
                $select->where->greaterThanOrEqualTo('offers.yard', $params['yard_from']);
            }
            if (is_numeric($params['yard_to'])) {
                $select->where->lessThanOrEqualTo('offers.yard', $params['yard_to']);
            }
            if (is_numeric($params['heating_system_id'])) {
                $select->where->equalTo('offers.heating_system_id', $params['heating_system_id']);
            }
            if ((isset($params['property_features'])) && (is_array($params['property_features'])) && (count($params['property_features']) > 0)) {
                $featuresSelect = new Select('offer_property_features');
                $featuresSelect->columns(array(
                    'num_results' => new Expression('COUNT(*)')
                ));
                $featuresSelect->where->equalTo('offer_property_features.offer_id', new Expression('offers.id'));
                $featuresSelect->where->in('offer_property_features.property_feature_id', $params['property_features']);
                $select->where->equalTo($featuresSelect, count($params['property_features']));
            }
            if (is_numeric($params['agency_id'])) {
                $agentsSelect = new Select('users');
                $agentsSelect->columns(array('id'));
                $agentsSelect->where->equalTo('parent_user_id', $params['agency_id'])->OR->equalTo('user_id', $params['agency_id']);
                $select->where->in('offers.user_id', $agentsSelect);
            }

            $select->offset($offset);
            $select->limit($limit);

            $select->order('offers.top_offer DESC');
            $select->order('offers.vip_offer DESC');

        });
        return $rowset->toArray();
    }

    public function getNumShotsOnDateTime($photoshootDateTime, $cityId)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use ($photoshootDateTime, $cityId) {
            $select->columns(array(
                'num_count' => new Expression('COUNT(*)')
            ));
            $select->where(array(
                'photographer_appointment' => $photoshootDateTime,
                'city_id' => $cityId
            ));
        });
        return $rowset->current()->getNumCount();
    }

    /**
     * Change the broker for the Offer
     *
     * @param $offerId , $brokerId, $agentId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function editOfferBroker($offerId, $brokerId, $agentId)
    {

        $agentsSelect = new Select('users');
        $agentsSelect->columns(array('id'));
        $agentsSelect->where->equalTo('parent_user_id', $agentId)->OR->equalTo('user_id', $agentId);

        $where = new Where();
        $where->equalTo('id', $offerId);
        $where->in('user_id', $agentsSelect);

        $this->tableGateway->update(array(
            'user_id' => $brokerId,
        ), $where);
    }

    /**
     * Returns all offers that will expire tomorrow.
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getAllExpiringTomorrow()
    {
        return $this->tableGateway->select(function (Select $select) {
            $select->join('users', 'users.id = offers.user_id', array('email' => 'email'));
            $select->where->equalTo('offers.offer_status_id', 4);
            $select->where->lessThan('offers.active_until_date', new Expression('DATE_ADD(NOW(), INTERVAL 1 DAY)'));
        });
    }

    /**
     * Returns all offers that will expire tomorrow.
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getAllExpiringToday()
    {
        return $this->tableGateway->select(function (Select $select) {
            $select->join('users', 'users.id = offers.user_id', array('email' => 'email'));
            $select->where->equalTo('offers.offer_status_id', 4);
            $select->where->lessThan('offers.active_until_date', new Expression('NOW()'));
        });
    }

    /**
     * Returns all offers that extra will expire today.
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getAllExpiringTodayWithExtra()
    {
        return $this->tableGateway->select(function (Select $select) {
            $select->where->nest()
                ->equalTo('offers.top_offer', 1)
                ->OR
                ->equalTo('offers.vip_offer', 1)
                ->OR
                ->equalTo('offers.chat_offer', 1);

            $select->where->lessThan('offers.extra_until_date', new Expression('NOW()'));
        });
    }


    /**
     * Update all offers additional options that will expire today.
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function updateAllExpiringTodayWithExtra()
    {
        $where = new Where();
        $where->nest()
            ->equalTo('offers.top_offer', 1)
            ->OR
            ->equalTo('offers.vip_offer', 1)
            ->OR
            ->equalTo('offers.chat_offer', 1);
        $where->lessThan('extra_until_date', new Expression('NOW()'));

        $this->tableGateway->update(array(
            'top_offer' => null,
            'vip_offer' => null,
            'chat_offer' => null,
            'date_updated' => new Expression('NOW()')
        ), $where);
    }

    /**
     * Returns all offers that will expire tomorrow.
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function updateAllExpiringToday()
    {
        $where = new Where();
        $where->equalTo('offer_status_id', 4);
        $where->lessThan('active_until_date', new Expression('NOW()'));

        $this->tableGateway->update(array(
            'offer_status_id' => 5,
            'date_updated' => new Expression('NOW()')
        ), $where);
    }

    /**
     * Activates offer for another number of weeks.
     *
     * @param $offerId
     * @param int $weeks
     * @param int $extraWeeks
     * @param int $activeUntilDate
     * @param int $extraUntilDate
     *
     */
    public function updateActivationDate($offerId, $weeks, $extraWeeks, $activeUntilDate, $extraUntilDate)
    {
        $this->tableGateway->update(array(
            'date_updated' => new Expression('NOW()'),
            'active_until_date' => new Expression('DATE_ADD("' . $activeUntilDate . '",INTERVAL ' . $weeks . ' WEEK)'),
            'extra_until_date' => new Expression('DATE_ADD("' . $extraUntilDate . '",INTERVAL ' . $extraWeeks . ' WEEK)')


        ), array(
            'id' => $offerId
        ));
    }

    /**
     * Copy all images from one offer to another.
     *
     * @param $offerId
     * @param $mainImage
     */
    public function copyImages($offerId, $oldOfferId)
    {
        $this->tableGateway->getAdapter()->driver->getConnection()->execute(<<<SQL
    INSERT INTO gallery (image, date_created, date_updated, offer_id, is_front)
        SELECT image, NOW(), NOW(), '$offerId' as id, is_front
        FROM gallery
        WHERE offer_id = '$oldOfferId'
SQL
        );
    }

    /**
     * Updates panorama file by ID.
     *
     * @param $offerId
     * @return int
     */
    public function setPanoramaStatus($offerId, $status)
    {
        return $this->tableGateway->update(array(
            'panorama_file' => $status
        ), array(
            'id' => $offerId
        ));
    }

    /**
     * Update offer options (vip, top, chat, schema) when create transaction for resuming expired offer
     *
     * @param $transaction
     */
    public function updateOfferTypesForPay($transaction)
    {

        $this->tableGateway->update(array(

            'top_offer' => $transaction->getIsTop() ?? null,
            'vip_offer' => $transaction->getIsVip() ?? null,
//            'chat_offer' => $transaction->getIsChat() ?? null,
            'schema_offer' => $transaction->getIsSchema() ?? null,

        ), array(
            'id' => $transaction->getOfferId()
        ));
    }

    /**
     * Update offer status and user_offer_status_id
     *
     * @param $transaction
     */
    public function activateOffer($offer)
    {

        return $this->tableGateway->update(array(
            'user_offer_status_id' => $offer->getUserOfferStatusId(),
            'offer_status_id' => $offer->getOfferStatusId()
        ),
            array(
                'id' => $offer->getId()
            ));
    }

    /**
     * @param $panorama
     * @param $video
     * @param $offerId
     */
    public function updateExternalMedia($panorama, $video, $offerId)
    {
        $this->tableGateway->update(array(
            'user_panorama_file' => $panorama,
            'youtube_code_1' => $video,
        ), array(
            'id' => $offerId
        ));
    }

    /**
     * Get the Last active Offers that are gallery.is_front.
     * $numLastOffers - Get the last $numLastOffers offers.
     *
     * @param $numLastOffers
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getLastOffers($numLastOffers)
    {
        return $this->tableGateway->select(function (Select $select) use ($numLastOffers) {
            $select->join('property_types', 'property_types.id = offers.property_type_id', array('property_type_name' => 'name'));
            $select->join('offer_types', 'offer_types.id = offers.offer_type_id', array('offer_type_name' => 'name'));
            $select->join('cities', 'cities.id = offers.city_id', array('city_name' => 'name'));
            $select->join('neighbourhoods', 'neighbourhoods.neighbourhood_id = offers.neighbourhood_id', array('neighbourhood_name' => 'neighbourhood_name'));
            $select->join('currencies', 'currencies.id = offers.currency_id', array('currency_short_name' => 'short_name'));
            $select->join('building_types', 'building_types.id = offers.building_type_id', array('building_type_name' => 'name'), Select::JOIN_LEFT);
            $select->join('offer_statuses', 'offer_statuses.id = offers.offer_status_id', array('offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('user_offer_statuses', 'user_offer_statuses.id = offers.user_offer_status_id', array('user_offer_status_name' => 'name'), Select::JOIN_LEFT);
            $select->join('gallery', 'gallery.offer_id = offers.id', array('image' => 'image'), Select::JOIN_LEFT);

            $select->where->equalTo('offer_status_id', 4);
            $select->where->equalTo('gallery.is_front', 1);

            $select->order('id DESC');
            $select->limit($numLastOffers);
        });
    }

    /**
     * Change the `alternative_id_file` for the offer
     *
     * @param $alternativeIdFile
     * @param $offerId
     */
    public function editAlternativIdFile($alternativeIdFile, $offerId) {
        $this->tableGateway->update(array(
            'alternative_id_file' => $alternativeIdFile,
        ), array(
            'id' => $offerId
        ));
    }
}