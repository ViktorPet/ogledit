<?php

namespace User\Model;

use Application\Mapping\UserStatuses;
use Application\Model\BaseTableModel;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Having;

/**
 * Class UserTable
 * @package User\Model
 */
class UserTable extends BaseTableModel
{

    /**
     * Get user by email
     * @param $email
     * @return array|\ArrayObject|null
     */
    public function findByEmail($email)
    {
        $rowset = $this->tableGateway->select(array('email' => $email));
        $row = $rowset->current();
        if (!$row) {
            return null;
        }
        return $row;
    }

    /**
     * Inserts new user of the system
     *
     * @param User $pUser
     * @return int
     */
    public function insert(User $pUser)
    {
        $file = $pUser->getLogo();
        $this->tableGateway->insert(array(
            'email' => $pUser->getEmail(),
            'names' => $pUser->getNames(),
            'password' => $this->generateHash($pUser->getPassword()),
            'logo' => $file['tmp_name'],
            'user_status_id' => $pUser->getUserStatusId(),
            'phone' => $pUser->getPhone(),
            'director' => $pUser->getDirector(),
            'vat_number' => $pUser->getVatNumber(),
            'company_address' => $pUser->getCompanyAddress(),
            'date_created' => new Expression('NOW()'),
            'date_updated' => new Expression('NOW()'),
            'user_type_id' => $pUser->getUserTypeId(),
            'verification_code' => $pUser->getVerificationCode(),
            'user_deleted' => User::USER_NOT_DELETED,
        ));
        return $this->tableGateway->getLastInsertValue();
    }

    /**
     * Inserts user from Facebook.
     *
     * @param User $pUser
     * @return int
     */
    public function insertFromFacebook(User $pUser)
    {
        $this->tableGateway->insert(array(
            'facebook_id' => $pUser->getFacebookId(),
            'is_fb_reg_complete' => $pUser->getFacebookRegComplete(),
            'email' => $pUser->getEmail(),
            'names' => $pUser->getNames(),
            'user_status_id' => $pUser->getUserStatusId(),
            'date_created' => new Expression('NOW()'),
            'date_updated' => new Expression('NOW()'),
            'user_type_id' => 1,
            'user_deleted' => User::USER_NOT_DELETED,
        ));
        return $this->tableGateway->getLastInsertValue();
    }

    /**
     * Fills user profile from Facebook.
     *
     * @param User $pUser
     */
    public function fillFacebookProfile(User $pUser)
    {
        $this->tableGateway->update(array(
            'phone' => $pUser->getPhone(),
            'is_fb_reg_complete' => $pUser->getFacebookRegComplete(),
        ), array('id' => $pUser->getId()));
    }

    /**
     * Fills user profile from Facebook.
     *
     * @param User $pUser
     */
    public function fillNotFBUserFacebookParams(User $pUser)
    {
        $this->tableGateway->update(array(
            'facebook_id' => $pUser->getFacebookId(),
            'is_fb_reg_complete' => $pUser->getFacebookRegComplete(),
        ), array('id' => $pUser->getId()));
    }

    /**
     * Creates new agent
     *
     * @param User $agent
     * @return mixed
     */
    public function createAgent(User $agent, $userType)
    {

        $file = $agent->getLogo();
        $this->tableGateway->insert(array(
            'user_status_id' => 1,
            'user_type_id' => $userType,
            'parent_user_id' => $agent->getParentUserId(),
            'names' => $agent->getNames(),
            'names_en' => $agent->getNamesEn(),
            'description' => $agent->getDescription(),
            'description_en' => $agent->getDescriptionEn(),
            'phone' => $agent->getPhone(),
            'email' => $agent->getEmail(),
            'password' => $agent->getPassword(),
            'logo' => $file['tmp_name'],
            'date_created' => new Expression('NOW()'),
            'date_updated' => new Expression('NOW()'),
            'user_deleted' => User::USER_NOT_DELETED,
        ));
        return $this->tableGateway->getLastInsertValue();
    }


    /**
     * Edit Agent
     *
     * @param User $agent
     * @return mixed
     */
    public function editAgent(User $agent)
    {

        $file = $agent->getLogo();
        if (strlen($agent->getPassword()) == 0) {
            $this->tableGateway->update(array(
                'parent_user_id' => $agent->getParentUserId(),
                'names' => $agent->getNames(),
                'names_en' => $agent->getNamesEn(),
                'description' => $agent->getDescription(),
                'description_en' => $agent->getDescriptionEn(),
                'phone' => $agent->getPhone(),
                'email' => $agent->getEmail(),
                'logo' => $file['tmp_name'],
                'date_created' => new Expression('NOW()'),
                'date_updated' => new Expression('NOW()'),
            ), array('id' => $agent->getId()));
        } else {
            $this->tableGateway->update(array(
                'parent_user_id' => $agent->getParentUserId(),
                'names' => $agent->getNames(),
                'names_en' => $agent->getNamesEn(),
                'description' => $agent->getDescription(),
                'description_en' => $agent->getDescriptionEn(),
                'phone' => $agent->getPhone(),
                'email' => $agent->getEmail(),
                'logo' => $file['tmp_name'],
                'password' => $agent->getPassword(),
                'date_created' => new Expression('NOW()'),
                'date_updated' => new Expression('NOW()'),
            ), array('id' => $agent->getId()));
        }
    }

    /**
     * Edit User Facebook Id
     *
     * @param $userId
     * @param $facebookId
     */
    public function updateFbId($userId, $facebookId)
    {
        $this->tableGateway->update(array(
            'facebook_id' => $facebookId,
        ), array('id' => $userId));
    }

    /**
     * Deletes agent
     *
     * @param agency $agentId
     */
    public function delete($agentId)
    {
        $this->tableGateway->delete(
            array(
                'id' => $agentId
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
     * Updates user data
     *
     * @param User $pUser
     * @return int
     */
    public function update(User $pUser)
    {
        $file = $pUser->getLogo();
        $logo = $file['tmp_name'];
        if ($logo != '') {
            $this->tableGateway->update(array(
                'names' => $pUser->getNames(),
                'logo' => $file['tmp_name'],
                'phone' => $pUser->getPhone(),
                'director' => $pUser->getDirector(),
                'vat_number' => $pUser->getVatNumber(),
                'company_address' => $pUser->getCompanyAddress(),
                'date_updated' => new Expression('NOW()'),
                'user_type_id' => $pUser->getUserTypeId()
            ), array(
                'email' => $pUser->getEmail()
            ));
        } else {
            $this->tableGateway->update(array(
                'names' => $pUser->getNames(),
                'phone' => $pUser->getPhone(),
                'director' => $pUser->getDirector(),
                'vat_number' => $pUser->getVatNumber(),
                'company_address' => $pUser->getCompanyAddress(),
                'date_updated' => new Expression('NOW()'),
                'user_type_id' => $pUser->getUserTypeId()
            ), array(
                'email' => $pUser->getEmail()
            ));
        }

        return $this->tableGateway->getLastInsertValue();
    }

    /**
     * Updates user password
     *
     * @param User $pUser
     * @return int
     */
    public function changePassword(User $pUser)
    {
        $this->tableGateway->update(array(
            'password' => $this->generateHash($pUser->getPassword()),
            'date_updated' => new Expression('NOW()')
        ), array(
            'email' => $pUser->getEmail()
        ));
        return $this->tableGateway->getLastInsertValue();
    }

    /**
     * Generates hash for password with strong salt.
     *
     * @param string $password
     * @param number $cost
     * @return hashed string
     */
    public static function generateHash($pString, $pCost = 11)
    {
        $salt = substr(base64_encode(openssl_random_pseudo_bytes(17)), 0, 22);
        $salt = str_replace("+", ".", $salt);
        $param = '$' . implode('$', array(
                "2y", // Blowfish version
                str_pad($pCost, 2, "0", STR_PAD_LEFT),
                $salt
            ));
        return crypt($pString, $param);
    }

    /**
     * Gets all agents.
     *
     * @param $userId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getAgents($userId)
    {
        return $this->getUserAgents($userId);
    }

    /**
     * Gets all agents by agency Id.
     *
     * @param $userId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    private function getUserAgents($userId)
    {

        return $this->tableGateway->select(function (Select $select) use ($userId) {
            $select->columns(array(
                '*',
            ));

            $select->where(array(
                'parent_user_id' => $userId,
                'user_deleted' => User::USER_NOT_DELETED,
            ));
        });

    }

    /**
     * Gets the number of offers by user id.
     *
     * @return mixed
     */
    public function getCountOffersByUserId($userId)
    {
        return $this->tableGateway->select(function (Select $select) use ($userId) {

            $select->columns(array(
                new Expression('COUNT(offers.id)')
            ));
            $select->join('offers', 'users.id = offers.user_id', array());

            $select->where(array(
                'user_id' => $userId,
            ));
        });

    }

    /**
     * Gets agent by id
     *
     * @param $agentId
     * @return mixed
     */
    public function getAgentById($agentId)
    {
        $result = $this->tableGateway->select(function (Select $select) use ($agentId) {
            $select->where(array(
                'id' => $agentId
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
    public function getUserById($userId)
    {
        $result = $this->tableGateway->select(function (Select $select) use ($userId) {
            $select->where(array(
                'id' => $userId
            ));
        });
        return $result->current();
    }


    /**
     * Gets all price by agency Id.
     *
     * @param $userPriceId
     * @return array|\ArrayObject|null
     */
    public function getPriceIdByUserId($userPriceId)
    {
        $rowset = $this->tableGateway->select(array('id' => $userPriceId));
        $row = $rowset->current();
        if (!$row) {
            return null;
        }
        return $row;
    }

    /**
     * Gets agency by parent user Id.
     *
     * @param $parentUserId
     * @return array|\ArrayObject|null
     */
    public function getAgencyIdByParentUserId($parentUserId)
    {
        $rowset = $this->tableGateway->select(array('id' => $parentUserId));
        $row = $rowset->current();
        if (!$row) {
            return null;
        } else {
            return $row;
        }
    }


    /**
     * Gets all primary agencies.
     *
     * @return array
     */
    public function getAllAgencies()
    {
        $rowset = $this->tableGateway->select(function (Select $select) {
            $select->where->isNull('parent_user_id');
            $select->where->equalTo('user_status_id', 1);
            $select->where->notEqualTo('user_type_id', 1);
            $select->order('names ASC');
        });
        if ($rowset) {
            $selectData = array();
            foreach ($rowset as $res) {
                $selectData[$res->id] = $res->getNames();
            }
            return $selectData;
        } else {
            return array();
        }
    }

    /**
     * Gets all primary agencies all data.
     *
     * @return array
     */
    public function getAllAgenciesAllData()
    {
        $rowset = $this->tableGateway->select(function (Select $select) {
            $select->where->isNull('parent_user_id');
            $select->where->equalTo('user_status_id', 1);
            $select->where->notEqualTo('user_type_id', 1);
            $select->order('names ASC');
        });
        if ($rowset) {
            return $rowset;
        } else {
            return array();
        }
    }

    /**
     * Gets all primary agencies.
     *
     * @return array
     */
    public function getAllAgenciesWithActiveOffers()
    {
        $rowset = $this->tableGateway->select(function (Select $select) {
            $select->columns(array('*', new Expression('COUNT(offers.id) as offers_count')));
            $select->where->isNull('parent_user_id');
            $select->where->equalTo('user_status_id', 1);
            $select->where->notEqualTo('user_type_id', 1);
            $select->order('names ASC');

            $select->join('offers', 'offers.user_id = users.id', array(), Select::JOIN_LEFT);
            $select->group('users.id');

            $having = new Having();
            $having->greaterThan('offers_count', 0);

            $select->where->equalTo('offers.offer_status_id', 4);
            $select->having($having);

        });
        if ($rowset) {
            $selectData = array();
            foreach ($rowset as $res) {
                $selectData[$res->id] = $res->getNames();
            }
            return $selectData;
        } else {
            return array();
        }
    }

    /**
     * Gets $number of rand sorted primary agencies with logo images.
     * @param $number
     * @return array
     */
    public function getAgenciesForLogo($number)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use ($number) {
            $select->columns(array('*', new Expression('COUNT(offers.id) as offers_count')));
            $select->where->isNull('parent_user_id');
            $select->where->equalTo('user_status_id', 1);
            $select->where->notEqualTo('user_type_id', 1);
            $select->where->notEqualTo('logo', '');

            $select->join('offers', 'offers.user_id = users.id', array(), Select::JOIN_LEFT);
            $select->group('users.id');

            $having = new Having();
            $having->greaterThan('offers_count', 0);

            $select->where->isNotNull('offers.id');
            $select->where->equalTo('offers.offer_status_id', 4);

            $select->having($having);

            $rand = new Expression('RAND()');
            $select->order($rand);
            $select->limit($number);
        });
        if ($rowset) {
            $selectData = array();
            foreach ($rowset as $res) {
                $selectData[$res->id] = $res->getLogo();
            }
            return $selectData;
        } else {
            return array();
        }
    }

    public function getBrokersArray($agencyId)
    {
        $rowset = $this->tableGateway->select(array('parent_user_id' => $agencyId, 'user_deleted' => User::USER_NOT_DELETED));
        if ($rowset) {
            $selectData = array();
            foreach ($rowset as $res) {
                $selectData[$res->id] = $res->names;
            }
            return $selectData;
        } else {
            return array();
        }
    }

    public function verifyAccountByCode($code)
    {
        return $this->tableGateway->update(
            array(
                'user_status_id' => UserStatuses::APPROVED,
                'verification_code' => '',
            ),
            array(
                'verification_code' => $code,
            )
        );
    }
}
