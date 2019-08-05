<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
/**
 * Base Table Model
 *
 * Class BaseTableModel
 * @package Application\Model
 */
class BaseTableModel {
    // Table gateware object that will be referrenced
    protected $tableGateway;

    /**
     * Provides constructor for the project table gateway object
     *
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Gets the table gateway
     *
     * @return TableGateway
     */
    public function getTableGateway()
    {
        return $this->tableGateway;
    }

    /**
     * Selects all values from the table
     *
     * @return list with maps
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     * Gets all records as KEY => VALUE mapping
     *
     * @param $pKeyColumn
     * @param $pValueColumn
     * @param null $pSortOption
     * @param array|null $pWhereOption
     * @return array
     */
    public function getAllAsKeyValueMap($pKeyColumn, $pValueColumn, $pSortOption = null, array $pWhereOption = null)
    {
        $map = array();
        $res = $this->tableGateway->select(function (Select $select) use ($pSortOption, $pWhereOption) {
            if ($pSortOption) {
                $select->order($pSortOption);
            }

            if ($pWhereOption) {
                $select->where($pWhereOption);
            }
        });
        foreach ($res as $item) {
            if (is_array($pValueColumn)) {
                foreach ($pValueColumn as $column) {
                    $map[$item->getField($pKeyColumn)] .= $item->getField($column) . ' ';
                }
            } else {
                $map[$item->getField($pKeyColumn)] = $item->getField($pValueColumn);
            }
        }
        return $map;
    }

}