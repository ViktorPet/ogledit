<?php

namespace Admin\Model;

use Application\Model\Base\BaseGridSettings;
use Application\Model\Base\BaseGridTable;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

/**
 * Description of PagesTable
 *
 */
class PagesTable extends BaseGridTable {

    private static $fieldMap = [
        "id" => "languages.id",
        "language" => "languages.language"
    ];

    public function getTable() {
        return $this->tableGateway->select();
    }

    function getData(BaseGridSettings $pGridSettings, $userId, $paging) {
        $thisClass = $this;
        return $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass, $paging, $userId) {
            $select->columns(array(
                '*'
            ));

            $select->join('languages', 'pages.language_id = languages.id', array('language'), 'left');

            // Filter
            $thisClass->filterHelper($pGridSettings, $select);

            // Sort
            $thisClass->sortHelper($pGridSettings, $select, 'pages.id', 'ASC');

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

            $select->join('languages', 'pages.language_id = languages.id', array('language'), 'left');

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
     * Creates new page
     *
     * @param Pages $page
     * @return mixed
     */
    public function create(Pages $page) {
        $this->tableGateway->insert(array(
            'language_id' => $page->getField('language_id'),
            'title' => $page->getField('title'),
            'description' => $page->getField('description'),
            'meta_title' => $page->getField('meta_title'),
            'meta_description' => $page->getField('meta_description'),
            'meta_keywords' => $page->getField('meta_keywords'),
            'date_created' => new Expression('NOW()'),
            'date_updated' => new Expression('NOW()'),
            'url' => $page->getField('url')
        ));
        return $this->tableGateway->getLastInsertValue();
    }

    /**
     * Edits page
     *
     * @param Pages $page
     */
    public function edit(Pages $page) {
        $data = [
            'language_id' => $page->getField('language_id'),
            'title' => $page->getField('title'),
            'description' => $page->getField('description'),
            'meta_title' => $page->getField('meta_title'),
            'meta_description' => $page->getField('meta_description'),
            'meta_keywords' => $page->getField('meta_keywords'),
            'date_updated' => new Expression('NOW()')
        ];

        $this->tableGateway->update($data, ['id' => $page->getField('id')]);
    }


    /**
     * Deletes page
     *
     * @param type $pageId
     */
    public function delete($pageId) {
        $this->tableGateway->delete(
            array(
                'id' => $pageId
            )
        );
    }

    /**
     * Gets page by id
     *
     * @param $pageId
     * @return mixed
     */
    public function getPageById($pageId) {
        $result = $this->tableGateway->select(function (Select $select) use ($pageId) {
            $select->where(array(
                'id' => $pageId
            ));
        });
        return $result->current();
    }

}
