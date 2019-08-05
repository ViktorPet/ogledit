<?php

namespace Admin\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Application\Model\Base\BaseGridSettings;
use Application\Model\Base\BaseGridTable;
use Application\Model\Offer;

/**
 * Description of OffersTable
 *
 */
class OffersTable extends BaseGridTable {

    protected $ambiguousColumnMapping = array(
        'id' => 'offers.id',
        'propertyTypeName' => 'property_types.name',
        'cityName' => 'cities.name',
        'neighbourhoodName' => 'neighbourhoods.neighbourhood_name',
        'buildingTypeName' => 'building_types.name',
        'dateCreated' => 'offers.date_created',
        'offerStatusName' => 'offer_statuses.name',
        'photographerAppointment' => 'offers.photographer_appointment',
        'photographerAddress' => 'offers.photographer_address',
        'userNames' => 'users.names',
        'userPhone' => 'users.phone',
        'alternativeIdFile' => 'offers.alternative_id_file',
    );
    protected $offerStatus = null;
    protected $typeId = null;
    protected $datafield = null;

    function setOfferStatus($offerStatus) {
        $this->offerStatus = $offerStatus;
    }

    function setOfferTypeId($typeId) {
        $this->typeId = $typeId;
    }

    function setOfferDatafield($datafield) {
        $this->datafield = $datafield;
    }

    public function getTable() {
        return $this->tableGateway->select();
    }

    function getData(BaseGridSettings $pGridSettings, $userId, $paging) {
        $this->predicateMapping['userNames'] = new Expression('CONCAT_WS("", users.names, CONCAT( "/", parents.names ) )');

        $thisClass = $this;
        return $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass, $paging, $userId) {
            $select->columns(array(
                        '*'
                    ));

                    // Get data for statistics pages - For panoramas, No panoramas, No Video, For Stopping
                    if ($this->offerStatus == 3) {
                        $select->where->greaterThan('offer_status_id', 1);
                        $select->where->notEqualTo('offer_status_id', 11);
                        $select->where->isNull('gallery.is_front');
                    } elseif ($this->offerStatus == 7) {
                        $select->where->equalTo('offer_status_id', '7');
                    } elseif ($this->offerStatus == 'No panorama') {
                        $select->where->notEqualTo('panorama_file', 'y');
                        $select->where->notEqualTo('offer_status_id', 11);
                    } elseif ($this->offerStatus == 'No video') {
                        $select->where->in('offer_status_id', array(2,4));
                        $select->where->nest()
                                        ->isNull('youtube_code_1')
                                        ->OR
                                        ->equalTo('youtube_code_1', '');
                        $select->where->isNull('youtube_code_2');
                        $select->where->greaterThan('offers.id', 707);
                    }

                    // Get data when dashboard table is clicked
                    if ($this->typeId == 1) {
                        $select->where->equalTo('offer_type_id', '1');
                        if ($this->datafield == 'numCount') {
                            $select->where->equalTo('offer_type_id', '1');
                        } elseif ($this->datafield == 'numActive') {
                            $select->where->equalTo('offer_status_id', '4');
                        } elseif ($this->datafield == 'numVipOffer') {
                            $select->where->isNotNull('vip_offer');
                        } elseif ($this->datafield == 'numTopOffer') {
                            $select->where->isNotNull('top_offer');
                        } elseif ($this->datafield == 'numChatOffer') {
                            $select->where->isNotNull('chat_offer');
                        } elseif ($this->datafield == 'numSchemaOffer') {
                            $select->where->isNotNull('schema_offer');
                        }
                    } elseif ($this->typeId == 2) {
                        $select->where->equalTo('offer_type_id', '2');
                        if ($this->datafield == 'numCount') {
                            $select->where->equalTo('offer_type_id', '2');
                        } elseif ($this->datafield == 'numActive') {
                            $select->where->equalTo('offer_status_id', '4');
                        } elseif ($this->datafield == 'numVipOffer') {
                            $select->where->isNotNull('vip_offer');
                        } elseif ($this->datafield == 'numTopOffer') {
                            $select->where->isNotNull('top_offer');
                        } elseif ($this->datafield == 'numChatOffer') {
                            $select->where->isNotNull('chat_offer');
                        } elseif ($this->datafield == 'numSchemaOffer') {
                            $select->where->isNotNull('schema_offer');
                        }
                    }

                    $select->join('offer_statuses', 'offer_statuses.id = offers.offer_status_id', array('offer_status_name' => 'name'));
                    $select->join('building_types', 'building_types.id = offers.building_type_id', array('building_type_name' => 'name'), Select::JOIN_LEFT);
                    $select->join('property_types', 'property_types.id = offers.property_type_id', array('property_type_name' => 'name'));
                    $select->join('neighbourhoods', 'neighbourhoods.neighbourhood_id = offers.neighbourhood_id', array('neighbourhood_name' => 'neighbourhood_name'));
                    $select->join('cities', 'cities.id = offers.city_id', array('city_name' => 'name'));
                    $select->join(
                        array('users' => 'users'),
                        'users.id = offers.user_id',
                        array(
                            'user_phone' => 'phone',
                            'user_names' => new Expression('CONCAT_WS("", users.names, CONCAT( " / ", parents.names ) )'),
                            'parent_user_id' => 'parent_user_id'
                        )
                    );
                    $select->join(
                        array('parents' => 'users'),
                        'parents.id = users.parent_user_id',
                        null,
                        'left'
                    );
                    $select->join('offer_types', 'offers.offer_type_id = offer_types.id', array('offer_type_name' => 'name'));
                    $select->join('gallery', new Expression('gallery.offer_id = offers.id and gallery.is_front = 1'), array('gallery_image' => 'image'), Select::JOIN_LEFT);


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

    function getCount(BaseGridSettings $pGridSettings = null, $userId = null) {
        $this->predicateMapping['userNames'] = new Expression('CONCAT_WS("", users.names, CONCAT( "/", parents.names ) )');
        $thisClass = $this;
        $rowset = $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass) {
            $select->columns(array(
                new Expression('COUNT(*) AS num_count')
            ));

            // Get data for statistics pages - For panoramas, No panoramas, No Video, For Stopping
            if ($this->offerStatus == 3) {
                $select->where->greaterThan('offer_status_id', 1);
                $select->where->notEqualTo('offer_status_id', 11);
                $select->where->isNull('gallery.is_front');
            } elseif ($this->offerStatus == 7) {
                $select->where->equalTo('offer_status_id', '7');
            } elseif ($this->offerStatus == 'No panorama') {
                $select->where->notEqualTo('panorama_file', 'y');
                $select->where->notEqualTo('offer_status_id', 11);
            } elseif ($this->offerStatus == 'No video') {
                $select->where->in('offer_status_id', array(2,4));
                $select->where->nest()
                    ->isNull('youtube_code_1')
                    ->OR
                    ->equalTo('youtube_code_1', '');
                $select->where->isNull('youtube_code_2');
                $select->where->greaterThan('offers.id', 707);

            }

            // Get data when dashboard table is clicked
            if ($this->typeId == 1) {
                $select->where->equalTo('offer_type_id', '1');
                if ($this->datafield == 'numCount') {
                    $select->where->equalTo('offer_type_id', '1');
                } elseif ($this->datafield == 'numActive') {
                    $select->where->equalTo('offer_status_id', '4');
                } elseif ($this->datafield == 'numVipOffer') {
                    $select->where->isNotNull('vip_offer');
                } elseif ($this->datafield == 'numTopOffer') {
                    $select->where->isNotNull('top_offer');
                } elseif ($this->datafield == 'numChatOffer') {
                    $select->where->isNotNull('chat_offer');
                } elseif ($this->datafield == 'numSchemaOffer') {
                    $select->where->isNotNull('schema_offer');
                }
            } elseif ($this->typeId == 2) {
                $select->where->equalTo('offer_type_id', '2');
                if ($this->datafield == 'numCount') {
                    $select->where->equalTo('offer_type_id', '2');
                } elseif ($this->datafield == 'numActive') {
                    $select->where->equalTo('offer_status_id', '4');
                } elseif ($this->datafield == 'numVipOffer') {
                    $select->where->isNotNull('vip_offer');
                } elseif ($this->datafield == 'numTopOffer') {
                    $select->where->isNotNull('top_offer');
                } elseif ($this->datafield == 'numChatOffer') {
                    $select->where->isNotNull('chat_offer');
                } elseif ($this->datafield == 'numSchemaOffer') {
                    $select->where->isNotNull('schema_offer');
                }
            }

            $select->join('offer_statuses', 'offer_statuses.id = offers.offer_status_id', array('offer_status_name' => 'name'));
            $select->join('building_types', 'building_types.id = offers.building_type_id', array('building_type_name' => 'name'), Select::JOIN_LEFT);
            $select->join('property_types', 'property_types.id = offers.property_type_id', array('property_type_name' => 'name'));
            $select->join('neighbourhoods', 'neighbourhoods.neighbourhood_id = offers.neighbourhood_id', array('neighbourhood_name'));
            $select->join('cities', 'cities.id = offers.city_id', array('city_name' => 'name'));
            $select->join(
                array('users' => 'users'),
                'users.id = offers.user_id',
                array(
                    'user_phone' => 'phone',
                    'user_names' => new Expression('CONCAT_WS("", users.names, CONCAT( " / ", parents.names ) )'),
                    'parent_user_id' => 'parent_user_id'
                )
            );
            $select->join(
                array('parents' => 'users'),
                'parents.id = users.parent_user_id',
                null,
                'left'
            );
            $select->join('offer_types', 'offers.offer_type_id = offer_types.id', array('offer_type_name' => 'name'));
            $select->join('gallery', new Expression('gallery.offer_id = offers.id and gallery.is_front = 1'), array('gallery_image' => 'image'), Select::JOIN_LEFT);

            if (!is_null($pGridSettings)) {
                // Filter
                $thisClass->filterHelper($pGridSettings, $select);
            }
        });

        return $rowset->current()->getNumCount();
    }

    /**
     * Gets the number of for panoramas offers.
     *
     * @return mixed
     */
    public function getCountForPanoramasOffers() {
        $rowset = $this->tableGateway->select(function (Select $select) {
            $select->columns(array(
                'num_count' => new Expression('COUNT(*)')
            ));
            $select->join('gallery', 'gallery.offer_id = offers.id', array('is_front'), Select::JOIN_LEFT);
            $select->where->greaterThan('offer_status_id', 1);
            $select->where->notEqualTo('offer_status_id', 11);
            $select->where->isNull('gallery.is_front');
        });
        return $rowset->current()->getNumCount();
    }

    /**
     * Gets the number of for stopping offers.
     *
     * @return mixed
     */
    public function getCountForStoppingOffers() {
        $rowset = $this->tableGateway->select(function (Select $select) {
            $select->columns(array(
                'num_count' => new Expression('COUNT(*)')
            ));
            $select->where(array(
                'offer_status_id' => 7,
            ));
        });
        return $rowset->current()->getNumCount();
    }

    /**
     * Gets the number of no panorams offers.
     *
     * @return mixed
     */
    public function getCountNoPanoramasOffers() {
        $rowset = $this->tableGateway->select(function (Select $select) {
            $select->columns(array(
                'num_count' => new Expression('COUNT(*)')
            ));
//            $select->where->isNull('panorama_file');
            $select->where->notEqualTo('panorama_file', 'y');
            $select->where->notEqualTo('offer_status_id', 11);
        });
        return $rowset->current()->getNumCount();
    }

    /**
     * Gets the number of no video offers.
     *
     * @return mixed
     */
    public function getCountNoVideoOffers() {
        $rowset = $this->tableGateway->select(function (Select $select) {
            $select->columns(array(
                'num_count' => new Expression('COUNT(*)')
            ));
            $select->where->in('offer_status_id', array(2,4));
            $select->where->nest()
                    ->isNull('youtube_code_1')
                    ->OR
                    ->equalTo('youtube_code_1', '');
            $select->where->isNull('youtube_code_2');
            $select->where->greaterThan('offers.id', 707);
        });
        return $rowset->current()->getNumCount();
    }

    /**
     * Updates offer status to paid by ID.
     *
     * @param $offerId
     * @return int
     */
    public function setPaidById($offerId) {
        return $this->tableGateway->update(array(
                    'offer_status_id' => 2
                        ), array(
                    'id' => $offerId
        ));
    }
    
    /**
     * Updates offer status to active by ID.
     *
     * @param $offerId
     * @return int
     */
    public function setActiveById($offerId) {
        return $this->tableGateway->update(array(
            'offer_status_id' => 4
        ),
            array(
                'id' => $offerId
            ));
    }

    /**
     * Edit Offer
     *
     * @param Offer $offer
     * @return mixed
     */
    public function editUserOffer(Offer $offer, Offer $offerObj, $agency, $activeUntilDate, $extraUntilDate ) {
        $updateData = array(
            'photographer_appointment' => in_array(\Admin\Model\Permission::PHOTOGRAPH_INFO_PERMISSION, $_SESSION["admin_permissions"]['permissions']) ? $offer->getPhotographerAppointment() : $offerObj->getPhotographerAppointment(),
            'photographer_address' => in_array(\Admin\Model\Permission::PHOTOGRAPH_INFO_PERMISSION, $_SESSION["admin_permissions"]['permissions']) ? $offer->getPhotographerAddress() : $offerObj->getPhotographerAddress(),
            'top_offer' => $offer->getTopOffer(),
            'vip_offer' => $offer->getVipOffer(),
            'chat_offer' => $offer->getChatOffer(),
            'schema_offer' => $offer->getSchemaOffer(),
            'price' => $offer->getPrice(),
            'area' => $offer->getArea(),
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
            'user_id' => $offer->getUserId() ?? $agency->getId(),
            'neighbourhood_id' => $offer->getNeighbourhoodId(),
            'city_id' => $offer->getCityId(),
            'street' => $offer->getStreet(),
            'lat' => $offer->getLat(),
            'lng' => $offer->getLng(),
            'is_regulated' => $offer->getIsRegulated(),
            'parcel_type_id' => $offer->getParcelTypeId(),
            'yard' => $offer->getYard(),
            'google_360' => $offer->getGoogle360(),
            'youtube_code_1' => $offer->getYoutubeCode1(),
            'meta_title' => $offer->getMetaTitle(),
            'meta_description' => $offer->getMetaDescription(),
            'meta_keywords' => $offer->getMetaKeywords(),
            'user_offer_status_id' => $offer->getUserOfferStatusId(),
        );

        $file = $offer->getFacebookImage();
        if (strlen($file['tmp_name']) != 0) {
            $updateData['facebook_img'] = $file['tmp_name'];
        }

        $file = $offer->getPanoramaFile();
        if (strlen($file['tmp_name']) != 0 && $offer->getOfferStatusId() == 2) {
            $updateData['panorama_file'] = 'y';
            $updateData['offer_status_id'] = 4;
        } else if (strlen($file['tmp_name']) != 0 && $offer->getOfferStatusId() != 2) {
            $updateData['panorama_file'] = 'y';
            $updateData['offer_status_id'] = $offer->getOfferStatusId();
        } else {
            $updateData['offer_status_id'] = $offer->getOfferStatusId();
        }
        
        $file = $offer->getYoutubeCode2();
        if (strlen($file['tmp_name']) != 0) {
            $updateData['youtube_code_2'] = 'y';
        }

        $this->tableGateway->update($updateData, array('id' => $offerObj->getId()));
    }

    /**
     * Updates panorama file by ID.
     *
     * @param $offerId
     * @return int
     */
    public function setPanoramaStatus($offerId, $status) {
        return $this->tableGateway->update(array(
                    'panorama_file' => $status
                        ), array(
                    'id' => $offerId
        ));
    }

    /**
     * Updates youtube_code_2 file by ID.
     *
     * @param $offerId
     * @return int
     */
    public function setYoutubeCode2Status($offerId, $status) {
        return $this->tableGateway->update(array(
            'youtube_code_2' => $status
        ), array(
            'id' => $offerId
        ));
    }
    
    /**
     * Gets offer info by data Id and User.
     *
     * @param $offerId
     * @param $userId
     * @return array|\ArrayObject|null
     */
    public function getOfferByIdAndUser($offerId, $userId) {
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
     * Gets all offers for insert of meta tags
     *
     * @param $condition
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getOffersWithoutMetaTags($condition) {
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
           
        });
    }
    
    /**
     * Update meta tags
     *
     * @param $offerId
     * @return int
     */   
    public function updateMetaTags($offerId, $metaTitle, $metaDescription, $metaKeywords) {
        $this->tableGateway->update(array(
           'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'meta_keywords' => $metaKeywords
        ), array(
            'id' => $offerId
        ));
    }
    
    /**
     * Update meta title
     *
     * @param $offerId
     * @param $metaTitle
     * @return int
     */   
    public function updateMetaTitle($offerId, $metaTitle) {
        $this->tableGateway->update(array(
           'meta_title' => $metaTitle,
        ), array(
            'id' => $offerId
        ));
    }
    
    /**
     * Update meta description
     *
     * @param $offerId
     * @param $metaDescription
     * @return int
     */   
    public function updateMetaDescription($offerId, $metaDescription) {
        $this->tableGateway->update(array(
           'meta_description' => $metaDescription,
        ), array(
            'id' => $offerId
        ));
    }
    
    
    /**
     * Update meta keywords
     *
     * @param $offerId
     * @param $metaKeywords
     * @return int
     */   
    public function updateMetaKeywords($offerId, $metaKeywords) {
        $this->tableGateway->update(array(
           'meta_keywords' => $metaKeywords,
        ), array(
            'id' => $offerId
        ));
    }
    
    /**
     * Gets all user offers by condition and user Id.
     *
     * @param $userId
     * @param $condition
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getOffer($offerId) {
        return $this->tableGateway->select(function (Select $select) use ($offerId) {
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
            
            $select->where->equalTo('offers.id', $offerId);
           
        });
    }

    public function addFacebookImageForOffer($image, $offerId) {
        $this->tableGateway->update(array(
            'facebook_img' => $image,
        ), array(
            'id' => $offerId
        ));
    }

    public function updateYoutubeCode1($code, $offerId) {
        $this->tableGateway->update(array(
            'youtube_code_1' => $code,
        ), array(
            'id' => $offerId
        ));
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
