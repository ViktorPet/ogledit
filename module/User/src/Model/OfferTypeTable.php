<?php

namespace User\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

/**
 * Class OfferTypeTable
 * @package User\Model
 */
class OfferTypeTable extends BaseTableModel {

    protected $filters = null;

    function setFilters($filters) {
        $this->filters = $filters;
    }

    /**
     * Gets all offer types as index -> name array.
     *
     * @return array
     */
    public function getTypesArray() {
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

    public function getDashboardData() {

        return $this->calculateData();
    }

    private function calculateData() {
        $thisMonthStart = date('Y-m-d', strtotime('first day of this month'));
        $thisMonthEnd = date('Y-m-d', strtotime('first day of next month'));

        $lastMonthStart = date('Y-m-d', strtotime('first day of last month'));
        $lastMonthEnd = date('Y-m-d', strtotime('first day of this month'));

        $thisClass = $this;
        return $this->tableGateway->select(function (Select $select) use ($thisClass, $thisMonthStart, $thisMonthEnd, $lastMonthStart, $lastMonthEnd) {
                    $select->columns(array(
                        'id',
                        'name',
                        'num_count' => new Expression('COUNT(offers.id)'),
                        'num_active' => new Expression('sum(case when offer_status_id = 4 then 1 else 0 end)'),
                        'num_vip_offer' => new Expression('sum(case when vip_offer = 1 then 1 else 0 end)'),
                        'num_top_offer' => new Expression('sum(case when top_offer = 1 then 1 else 0 end)'),
                        'num_chat_offer' => new Expression('sum(case when chat_offer = 1 then 1 else 0 end)'),
                        'num_schema_offer' => new Expression('sum(case when schema_offer = 1 then 1 else 0 end)'),
                    ));

                    $select->join('offers', 'offer_types.id = offers.offer_type_id', array(), "left");
//            $select->group('offers.offer_type_id');
                    $select->group('name');

                    if ($this->filters['city_id']) {
                        $select->join('cities', 'cities.id = offers.city_id', array(), 'left');
                        $select->where(array('city_id' => $this->filters['city_id']));
                    }

                    if ($this->filters['date_from'] != '' && $this->filters['date_to'] != '') {
                        $select->where->between('offers.date_created', $this->filters['date_from'], $this->filters['date_to']);
                    } else {
                        if ($this->filters['date_from'] == '' && $this->filters['date_to'] != '') {
                            $select->where->lessThan('offers.date_created', $this->filters['date_to']);
                        } elseif ($this->filters['date_from'] != '' && $this->filters['date_to'] == '') {
                            $select->where->greaterThan('offers.date_created', $this->filters['date_from']);
                        }
                    }
                })->toArray();
    }
}
