<?php

namespace Admin\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Application\Model\Base\BaseGridSettings;
use Application\Model\Base\BaseGridTable;
use Admin\Model\Service;

/**
 * Description of ServicesTable
 *
 */
class ServicesTable extends BaseGridTable {

    public function getTable() {
        return $this->tableGateway->select();
    }

    function getData(BaseGridSettings $pGridSettings, $userId, $paging) {
        $thisClass = $this;
        return $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass, $paging, $userId) {
                    $select->columns(array(
                        '*'
                    ));

                    $select->join('service_categories', 'services.service_category_id = service_categories.id', array('category' => 'name'), 'left');

                    foreach ($pGridSettings->toArray() as $key => $value) {
                        if (substr($key, 0, 15) === "filterdatafield") {
                            if ($value == 'category') {
                                $pGridSettings->setField($key, 'service_categories.name');
                            }
                        }
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

            $select->join('service_categories', 'services.service_category_id = service_categories.id', array('category' => 'name'), 'left');

            if (!is_null($pGridSettings)) {
                foreach ($pGridSettings->toArray() as $key => $value) {
                    if (substr($key, 0, 15) === "filterdatafield") {
                        if ($value == 'category') {
                            $pGridSettings->setField($key, 'service_categories.name');
                        }
                    }
                }
                // Filter
                $thisClass->filterHelper($pGridSettings, $select);
            }
        });
        return $res->current()->getField('num_results');
    }

    /**
     * Creates new Service
     *
     * @param Service $service
     * @return mixed
     */
    public function create(Service $service) {
        $file = $service->getField('image');
        $panoramaFile = $service->getField('panorama_file');
        $this->tableGateway->insert(array(
            'title' => $service->getField('title'),
            'description' => $service->getField('description'),
            'url' => $service->getField('url'),
            'panorama_file' => $panoramaFile['name'] != '' ? 'y' : 'n',
            'image' => $file['tmp_name'],
            'date_published' => $service->getField('date_published'),
            'date_created' => new Expression('NOW()'),
            'date_updated' => new Expression('NOW()'),
            'service_category_id' => $service->getField('service_category_id')
        ));
        return $this->tableGateway->getLastInsertValue();
    }

    /**
     * Edit Service
     *
     * @param Service $service
     * @return mixed
     */
    public function edit(Service $service) {
        $file = $service->getField('image');
        if (strlen($file['tmp_name']) == 0) {
            $this->tableGateway->update(array(
                'title' => $service->getField('title'),
                'description' => $service->getField('description'),
                'url' => $service->getField('url'),
                'panorama_file' => $service->getField('panorama_file'),
                'date_published' => $service->getField('date_published'),
                'date_updated' => new Expression('NOW()'),
                'service_category_id' => $service->getField('service_category_id')
                    ), array('id' => $service->getField('id')));
        } else {
            $this->tableGateway->update(array(
                'title' => $service->getField('title'),
                'description' => $service->getField('description'),
                'url' => $service->getField('url'),
                'panorama_file' => $service->getField('panorama_file'),
                'image' => $file['tmp_name'] ? $file['tmp_name'] : NULL,
                'date_published' => $service->getField('date_published'),
                'date_updated' => new Expression('NOW()'),
                'service_category_id' => $service->getField('service_category_id')
                    ), array('id' => $service->getField('id')));
        }
    }

    /**
     * Get Service By Id
     *
     * @param $serviceId
     * @return mixed
     */
    public function getById($serviceId) {
        $res = $this->tableGateway->select(function (Select $select) use ($serviceId) {
            $select->where(array(
                'id' => $serviceId,
            ));
        });
        return $res->current();
    }

    /**
     * Deletes Service
     *
     * @param $serviceId
     */
    public function delete($serviceId) {
        $this->tableGateway->delete(
                array(
                    'id' => $serviceId,
                )
        );
    }

    /**
     * Updates panorama file by ID.
     *
     * @param $serviceId
     * @return int
     */
    public function setPanoramaStatus($serviceId, $status) {
        return $this->tableGateway->update(array(
                    'panorama_file' => $status
                        ), array(
                    'id' => $serviceId
        ));
    }

    /**
     * Get Services
     *
     * @return mixed
     */
    public function getServices($i) {
        return $this->tableGateway->select(function (Select $select) use ($i) {

                    $select->join('service_categories', 'services.service_category_id = service_categories.id', array('categoryName' => 'name'), 'left');

                    $where = new Where();
                    $where->lessThanOrEqualTo('date_published', new Expression('NOW()'));
                    $where->equalTo('service_category_id', $i);

                    $select->where($where);
                    $select->order(array('date_published DESC'))
                            ->limit(1);
                });
    }

    /**
     * Get Services for given category
     *
     * @param $id
     * @return mixed
     */
    public function getServicesByCategoryId($id, $limit = 2000, $offset = 0) {
        $res = $this->tableGateway->select(function (Select $select) use ($id, $limit, $offset) {
            $where = new Where();
            $where->lessThanOrEqualTo('date_published', new Expression('NOW()'));
            $where->equalTo('service_category_id', $id);
//            $where->equalTo('panorama_file', 'y');

            $select->order('date_published DESC');
            $select->offset($offset);
            $select->limit($limit);

            $select->where($where);
        });

        return $res;
    }

    /**
     * Get Service
     *
     * @param $serviceId
     * @return mixed
     */
    public function getServiceById($url) {
        $res = $this->tableGateway->select(function (Select $select) use ($url) {

            $select->join('service_categories', 'services.service_category_id = service_categories.id', array('categoryName' => 'name'), 'left');

            $where = new Where();
            $where->equalTo('url', $url);

            $select->where($where);
        });

        return $res->current();
    }

    /**
     * Get last Services
     *
     * @param $count
     * @return mixed
     */
    public function getLastServices($count, $serviceCategory) {
        return $this->tableGateway->select(function (Select $select) use ($count, $serviceCategory) {
                    $where = new Where();
                    $where->lessThanOrEqualTo('date_published', new Expression('NOW()'));
                    $where->equalTo('service_category_id', $serviceCategory);
                    $where->equalTo('panorama_file', 'y');

                    $select->where($where);

                    $select->order('date_published DESC')
                            ->limit($count);
                });
    }

    /**
     * Gets the count of services by category.
     * 
     * @param type $categoryId
     * @return type
     */
    public function getServicesCount($categoryId) {
        $res = $this->tableGateway->select(function (Select $select) use ($categoryId) {
            $select->columns(array(
                'num_results' => new Expression('COUNT(id)')
            ));
            $select->where->lessThanOrEqualTo('date_published', new Expression('NOW()'));
            $select->where->equalTo('service_category_id', $categoryId);
        });

        return $res->current()->getField('num_results');
    }  

}
