<?php

namespace User\Model;

use Application\Model\Base\BaseGridSettings;
use Application\Model\Base\BaseGridTable;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

/**
 * Description of MailHistoryTable
 *
 */
class NewsletterTable extends BaseGridTable {
    
     protected $ambiguousColumnMapping = array(
        'dateCreated' => 'newsletter.date_created'
    );
    

    public function getTable() {
        return $this->tableGateway->select();
    }

    /**
     * Inserts new mail of the system
     * 
     * @param $email
     * @return int
     */
    public function insertMailToDb($email) {
        $this->tableGateway->insert(array(
            'email' => $email,
            'date_created' => new Expression('NOW()'),
        ));
        return $this->tableGateway->getLastInsertValue();
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
        $rowset = $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass) {
            $select->columns(array(
                new Expression('COUNT(*) AS num_results')
            ));

            if (!is_null($pGridSettings)) {
                // Filter
                $thisClass->filterHelper($pGridSettings, $select);
            }
        });

        return $rowset->current()->getNumResults();
    }

}
