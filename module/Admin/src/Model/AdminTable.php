<?php

namespace Admin\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Application\Model\Base\BaseGridSettings;
use Application\Model\Base\BaseGridTable;
use Admin\Model\Admin;

/**
 * Class AdminTable
 * @package Admin\Model
 */
class AdminTable extends BaseGridTable {

    public function getTable() {
        return $this->tableGateway->select();
    }

    function getData(BaseGridSettings $pGridSettings, $userId, $paging) {
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

    function getCount(BaseGridSettings $pGridSettings = null, $userId = null) {
        $thisClass = $this;
        $res = $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass) {
            $select->columns(array(
                new Expression('COUNT(*) AS num_results')
            ));

            if (!is_null($pGridSettings)) {
                // Filter
                $thisClass->filterHelper($pGridSettings, $select);
            }
        });
        return $res->current()->getField('num_results');
    }

    /**
     * Get use by email
     *
     * @param $email
     * @return array|\ArrayObject|null
     */
    public function findByEmail($email) {
        $rowset = $this->tableGateway->select(array('email' => $email));
        $row = $rowset->current();
        if (!$row) {
            return null;
        }
        return $row;
    }

    /**
     * Set invalid login count to zero
     *
     * @param $adminId
     */
    public function resetInvalidLoginCount($adminId) {
        $this->tableGateway->update(['invalid_login_count' => 0], ['id' => $adminId]);
    }

    /**
     * Increase the invalid login count
     *
     * @param $adminId
     */
    public function addInvalidLoginCount($adminId) {
        $this->tableGateway->update(['invalid_login_count' => new Expression('invalid_login_count + 1')], ['id' => $adminId]);
    }

    /**
     * Change the user status
     *
     * @param $adminId
     * @param $statusId
     */
    public function changeAdminStatus($adminId, $statusId) {
        $this->tableGateway->update(['user_status_id' => $statusId], ['id' => $adminId]);
    }

    /**
     * Creates new Admin
     *
     * @param Admin $admin
     * @return mixed
     */
    public function create(Admin $admin) {
        $this->tableGateway->insert(array(
            'email' => $admin->getField('email'),
            'username' => $admin->getField('username'),
            'first_name' => $admin->getField('first_name'),
            'last_name' => $admin->getField('last_name'),
            'password' => $admin->getField('password'),
            'gender' => $admin->getField('gender'),
            'position' => $admin->getField('position'),
            'date_created' => new Expression('NOW()'),
            'date_updated' => new Expression('NOW()'),
            'user_status_id' => $admin->getField('user_status_id'),
        ));
    }

    /**
     * Edit Admin
     *
     * @param Admin $admin
     * @return mixed
     */
    public function edit(Admin $admin) {
        if (strlen($admin->getField('password')) == 0) {
            $this->tableGateway->update(array(
                'email' => $admin->getField('email'),
                'username' => $admin->getField('username'),
                'first_name' => $admin->getField('first_name'),
                'last_name' => $admin->getField('last_name'),
                'gender' => $admin->getField('gender'),
                'position' => $admin->getField('position'),
                'date_created' => new Expression('NOW()'),
                'date_updated' => new Expression('NOW()'),
                'user_status_id' => $admin->getField('user_status_id'),
                    ), array('id' => $admin->getField('id')));
        } else {
            $this->tableGateway->update(array(
                'email' => $admin->getField('email'),
                'username' => $admin->getField('username'),
                'first_name' => $admin->getField('first_name'),
                'last_name' => $admin->getField('last_name'),
                'password' => $admin->getField('password'),
                'gender' => $admin->getField('gender'),
                'position' => $admin->getField('position'),
                'date_created' => new Expression('NOW()'),
                'date_updated' => new Expression('NOW()'),
                'user_status_id' => $admin->getField('user_status_id'),
                    ), array('id' => $admin->getField('id')));
        }
    }

    /**
     * Updates admin profile
     * 
     * @param Admin $admin
     * @return int
     */
    public function updateProfile(Admin $admin) {
        if (strlen($admin->getField('password')) == 0) {

            $this->tableGateway->update(array(
                'username' => $admin->getField('username'),
                'first_name' => $admin->getField('first_name'),
                'last_name' => $admin->getField('last_name'),
                'gender' => $admin->getField('gender'),
                'position' => $admin->getField('position'),
                'date_updated' => new Expression('NOW()'),
                    ), array(
                'id' => $admin->getField('id')
            ));
        } else {
            $this->tableGateway->update(array(
                'username' => $admin->getField('username'),
                'first_name' => $admin->getField('first_name'),
                'last_name' => $admin->getField('last_name'),
                'password' => $admin->getField('password'),
                'gender' => $admin->getField('gender'),
                'position' => $admin->getField('position'),
                'date_updated' => new Expression('NOW()'),
                    ), array(
                'id' => $admin->getField('id')
            ));
        }

        return $this->tableGateway->getLastInsertValue();
    }

    /**
     * Get Admin By Id
     *
     * @param $adminId
     * @return mixed
     */
    public function getById($adminId) {
        $res = $this->tableGateway->select(function (Select $select) use ($adminId) {
            $select->where(array(
                'id' => $adminId,
            ));
        });
        return $res->current();
    }

    /**
     * Get Admin Id By Email
     *
     * @param $adminEmail
     * @return mixed
     */
    public function getIdByEmail($adminEmail) {
        $res = $this->tableGateway->select(function (Select $select) use ($adminEmail) {
            $select->where(array(
                'email' => $adminEmail,
            ));
        });
        return $res->current();
    }

    /**
     * Deletes Admin
     *
     * @param $adminId
     */
    public function delete($adminId) {
        if ($adminId != '1') {
            $this->tableGateway->delete(
                    array(
                        'id' => $adminId,
                    )
            );
        }
    }

}
