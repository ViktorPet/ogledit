<?php

namespace Admin\Model;

use Application\Model\BaseTableModel;
use User\Model\User;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Application\Model\Base\BaseGridSettings;
use Application\Model\Base\BaseGridTable;

/**
 * Class AgenciesTable
 * @package Admin\Model
 */
class AgenciesTable extends BaseGridTable {

    protected $agencyID = null;
    private static $fieldMap = [
        "user_type" => "user_types.name"
    ];

    function setAgencyId($agencyID) {
        $this->agencyID = $agencyID;
    }

    public function getTable() {
        return $this->tableGateway->select();
    }

    function getData(BaseGridSettings $pGridSettings, $userId, $paging) {

        $thisClass = $this;
        return $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass, $paging, $userId) {
                    if (!is_null($thisClass->agencyID)) {
                        $select->columns(array(
                            '*'
                        ));
                        $select->join('user_types', 'users.user_type_id = user_types.id', array('user_type' => 'name'), 'left');
                        $select->where->equalTo('users.parent_user_id', $thisClass->agencyID);
                        $select->where->equalTo('users.user_deleted', User::USER_NOT_DELETED);
                    } else {


                        $allOffersSelectIn = new Select(array('u1' => 'users'));
                        $allOffersSelectIn->columns(array('id'));
                        $allOffersSelectIn->where->equalTo('u1.id', new Expression('users.id'))->OR->equalTo('u1.parent_user_id', new Expression('users.id'));

                        $allOffersSelect = new Select(array('o' => 'offers'));
                        $allOffersSelect->columns(array( new Expression('count(id)')));
                        $allOffersSelect->where->in('o.user_id', $allOffersSelectIn);


                        $activeOffer = new Select(array('o' => 'offers'));
                        $activeOffer->columns(array( new Expression('count(id)')));
                        $activeOffer->where->in('o.user_id', $allOffersSelectIn);
                        $activeOffer->where->equalTo('o.offer_status_id', 4);


                        $select->columns(array(
                            '*',
                            'offers_count' => $allOffersSelect,
                            'active_count' => $activeOffer
                        ));

                        $select->join('user_types', 'users.user_type_id = user_types.id', array('user_type' => 'name'), 'left');
                        //$userTypeStatuses = array(2, 3);
                        //$select->where->in('users.user_type_id', $userTypeStatuses);

                        $select->where->isNull('users.parent_user_id');
                        $select->where->equalTo('users.user_deleted', User::USER_NOT_DELETED);
                        $select->join('offers', 'offers.user_id = users.id', array(), 'left');
                        $select->group('users.id');
                    }

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
        $thisClass = $this;
        $res = $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass) {
            $select->columns(array(
                new Expression('COUNT(*) AS num_results')
            ));
            $select->join('user_types', 'users.user_type_id = user_types.id', array('user_type' => 'name'), 'left');

            if (!is_null($thisClass->agencyID)) {
                $select->where->equalTo('users.parent_user_id', $thisClass->agencyID);
                $select->where->equalTo('users.user_deleted', User::USER_NOT_DELETED);
            } else {
                //$userTypeStatuses = array(2, 3);
                //$select->where->in('users.user_type_id', $userTypeStatuses);
                $select->where->isNull('users.parent_user_id');
                $select->where->equalTo('users.user_deleted', User::USER_NOT_DELETED);
            }

            foreach ($pGridSettings->toArray() as $key => $value) {
                if (substr($key, 0, 15) === "filterdatafield" && array_key_exists($value, self::$fieldMap)) {
                    $pGridSettings->setField($key, self::$fieldMap[$value]);
                }
            }

            if (!is_null($pGridSettings)) {
                // Filter
                $thisClass->filterHelper($pGridSettings, $select);
            }
        });
        return $res->current()->getField('num_results');
    }

    /**
     * Creates new agency
     *
     * @param Agencies $agency
     * @return mixed
     */
    public function create(Agencies $agency) {
        $file = $agency->getField('logo');
        $this->tableGateway->insert(array(
            'price_id' => $agency->getField('price_id') ? $agency->getField('price_id') : null,
            'user_status_id' => $agency->getField('user_status_id') ? $agency->getField('user_status_id') : 1,
            'user_type_id' => $agency->getField('user_type_id'),
            'names' => $agency->getField('names'),
            'names_en' => $agency->getField('names_en'),
            'description' => $agency->getField('description'),
            'description_en' => $agency->getField('description_en'),
            'phone' => $agency->getField('phone'),
            'director' => $agency->getField('director'),
            'vat_number' => $agency->getField('vat_number'),
            'company_address' => $agency->getField('company_address'),
            'email' => $agency->getField('email'),
            'password' => $agency->getField('password'),
            'logo' => $file['tmp_name'],
            'date_created' => new Expression('NOW()'),
            'date_updated' => new Expression('NOW()'),
        ));
        return $this->tableGateway->getLastInsertValue();
    }

     /**
     * Creates new agency
     *
     * @param Agencies $agency
     * @return mixed
     */
//    public function createAgencyLogo(Agencies $agency) {
//        $file = $agency->getField('logo');
//        $this->tableGateway->insert(array(
//            'logo' => $file['tmp_name'],
//        ));
//        return $this->tableGateway->getLastInsertValue();
//    }
    
    
    
    /**
     * Creates new agent
     *
     * @param Agencies $agent
     * @return mixed
     */
    public function createAgent(Agencies $agent) {
        $file = $agent->getField('logo');
        $this->tableGateway->insert(array(
            'user_status_id' => $agent->getField('user_status_id') ? $agent->getField('user_status_id') : 1,
            'user_type_id' => $agent->getField('user_type_id'),
            'parent_user_id' => $agent->getField('parent_user_id'),
            'names' => $agent->getField('names'),
            'names_en' => $agent->getField('names_en'),
            'description' => $agent->getField('description'),
            'description_en' => $agent->getField('description_en'),
            'phone' => $agent->getField('phone'),          
            'email' => $agent->getField('email'),
            'password' => $agent->getField('password'),
            'logo' => $file['tmp_name'],
            'date_created' => new Expression('NOW()'),
            'date_updated' => new Expression('NOW()'),
        ));
        return $this->tableGateway->getLastInsertValue();
    }

    /**
     * Edit Agencies
     *
     * @param Agencies $agency
     * @return mixed
     */
    public function edit(Agencies $agency) {

        $file = $agency->getField('logo');
        if (strlen($agency->getField('password')) == 0) {
            $this->tableGateway->update(array(
                'price_id' => $agency->getField('price_id') ? $agency->getField('price_id') : null,
                'user_type_id' => $agency->getField('user_type_id'),
                'names' => $agency->getField('names'),
                'names_en' => $agency->getField('names_en'),
                'description' => $agency->getField('description'),
                'description_en' => $agency->getField('description_en'),
                'phone' => $agency->getField('phone'),
                'director' => $agency->getField('director'),
                'vat_number' => $agency->getField('vat_number'),
                'company_address' => $agency->getField('company_address'),
                'email' => $agency->getField('email'),
                'logo' => $file['tmp_name'],
                'date_created' => new Expression('NOW()'),
                'date_updated' => new Expression('NOW()'),
                    ), array('id' => $agency->getField('id')));
        } else {
            $this->tableGateway->update(array(
                'price_id' => $agency->getField('price_id') ? $agency->getField('price_id') : null,
                'user_type_id' => $agency->getField('user_type_id'),
                'names' => $agency->getField('names'),
                'names_en' => $agency->getField('names_en'),
                'description' => $agency->getField('description'),
                'description_en' => $agency->getField('description_en'),
                'phone' => $agency->getField('phone'),
                'director' => $agency->getField('director'),
                'vat_number' => $agency->getField('vat_number'),
                'company_address' => $agency->getField('company_address'),
                'email' => $agency->getField('email'),
                'logo' => $file['tmp_name'],
                'password' => $agency->getField('password'),
                'date_created' => new Expression('NOW()'),
                'date_updated' => new Expression('NOW()'),
                    ), array('id' => $agency->getField('id')));
        }
    }

    /**
     * Edit Agent
     *
     * @param Agencies $agent
     * @return mixed
     */
    public function editAgent(Agencies $agent) {

        $file = $agent->getField('logo');
        if (strlen($agent->getField('password')) == 0) {
            $this->tableGateway->update(array(
                'parent_user_id' => $agent->getField('parent_user_id'),
                'names' => $agent->getField('names'),
                'names_en' => $agent->getField('names_en'),
                'description' => $agent->getField('description'),
                'description_en' => $agent->getField('description_en'),
                'phone' => $agent->getField('phone'),
                'email' => $agent->getField('email'),
                'logo' => $file['tmp_name'],
                'date_created' => new Expression('NOW()'),
                'date_updated' => new Expression('NOW()'),
                    ), array('id' => $agent->getField('id')));
        } else {
            $this->tableGateway->update(array(
                'parent_user_id' => $agent->getField('parent_user_id'),
                'names' => $agent->getField('names'),
                'names_en' => $agent->getField('names_en'),
                'description' => $agent->getField('description'),
                'description_en' => $agent->getField('description_en'),
                'phone' => $agent->getField('phone'),
                'email' => $agent->getField('email'),
                'logo' => $file['tmp_name'],
                'password' => $agent->getField('password'),
                'date_created' => new Expression('NOW()'),
                'date_updated' => new Expression('NOW()'),
                    ), array('id' => $agent->getField('id')));
        }
    }

    /**
     * Gets agency by id
     *
     * @param $agencyId
     * @return mixed
     */
    public function getAgencyById($agencyId) {
        $result = $this->tableGateway->select(function (Select $select) use ($agencyId) {
            $select->where(array(
                'id' => $agencyId
            ));
        });
        return $result->current();
    }

    /**
     * Gets agent by id
     *
     * @param $agentId
     * @return mixed
     */
    public function getAgentById($agentId) {
        $result = $this->tableGateway->select(function (Select $select) use ($agentId) {
            $select->where(array(
                'id' => $agentId
            ));
        });
        return $result->current();
    }

    /**
     * Deletes agency
     *
     * @param agency $agencyId
     */
    public function delete($agencyId) {
        $this->tableGateway->delete(
                array(
                    'id' => $agencyId
                )
        );
    }

    /**
     * Set as Deleted
     *
     * @param $agentId
     */
    public function setAsDeleted($agentId)
    {
        $this->tableGateway->update(array(
            'user_deleted' => User::USER_DELETED
        ), array(
            'id' => $agentId
        ));
    }

    /**
     * Gets agency by agent id
     *
     * @param $agentId
     * @return mixed
     */
    public function getAgencyByAgentId($agentId) {
        $result = $this->tableGateway->select(function (Select $select) use ($agentId) {
            $select->where(array(
                'id' => $agentId
            ));
        });
        return $result->current();
    }

    /**
     * Gets agent count by agency id
     *
     * @param $agencyId
     * @return mixed
     */
    public function getAgentCountByAgencyId($agencyId) {
        $result = $this->tableGateway->select(function (Select $select) use ($agencyId) {
            $select->columns(array(
                new Expression('COUNT(users.id) AS agents_count')
            ));

            $select->where(array(
                'parent_user_id' => $agencyId
            ));
        });
        return $result->current();
    }

    /**
     * Gets agent count by agency id
     *
     * @param $agencyId
     * @return mixed
     */
    public function getAgentsByAgencyId($agencyId) {
        $result = $this->tableGateway->select(function (Select $select) use ($agencyId) {
            $select->columns(['*']);

            $select->where(array(
                'parent_user_id' => $agencyId
            ));
        });
        return $result;
    }

}
