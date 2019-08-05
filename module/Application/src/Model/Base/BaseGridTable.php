<?php
namespace Application\Model\Base;
use Application\Model\BaseTableModel;

/*use Zend\Db\Sql\{
    Select, Where
};*/

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;

/**
 * Provides sorting, paging and filterfing functionality
 */
abstract class BaseGridTable extends BaseTableModel
{

    // Resolves ambiguous columns conflicts.
    protected $ambiguousColumnMapping = array();
    // Resolves column names to Predicates, usually used for subquery in Where clause
    protected $predicateMapping = array();

    /**
     * Gets selected data, usually with limit, filter and sort options
     *
     * @param BaseGridSettings $pGridSettings
     * @param $userId
     * @param $paging
     */
    abstract function getData(BaseGridSettings $pGridSettings, $userId, $paging);

    /**
     * Gets just the count of a query, without any limit and sort options
     *
     * @param BaseGridSettings $pGridSettings
     * @param $userId
     */
    abstract function getCount(BaseGridSettings $pGridSettings, $userId);

    /**
     * Filter helper function.
     *
     * @param BaseGridSettings $pGridSettings
     * @param Select $pSelect
     */
    public function filterHelper(BaseGridSettings $pGridSettings, Select $pSelect)
    {        
        if ($pGridSettings->getField('filterscount') > 0) {
            $lastDataField = '';
            $lastFilterOperator = '';
            for ($i = 0; $i < $pGridSettings->getField('filterscount'); $i++) {

                // Gets current filter data.
                $filterDataField = $pGridSettings->getField('filterdatafield' . $i);
                $filterCondition = $pGridSettings->getField('filtercondition' . $i);
                $filterValue = $pGridSettings->getField('filtervalue' . $i);
                $filterOperator = $pGridSettings->getField('filteroperator' . $i);

                if (($filterDataField != '') && ($filterCondition != '') && ($filterOperator != '')) {

                    // Individual field filter
                    $predicatorsArr = array();
                    if ($this->getPredicateForColumn($filterDataField)) {
                        $where = $this->composeWhereString($filterCondition, '?', $filterValue);
                        $predicatorsArr[] = $this->getPredicateForColumn($filterDataField);
                    } else {
                        $where = $this->composeWhereString($filterCondition, $filterDataField, $filterValue);
                    }

                    // Checks if it is a chained filter. This means that there are two conditions for same data field.
                    // This is usually used for date or amount ranges.
                    if ((($i + 1) < $pGridSettings->getField('filterscount')) && ($pGridSettings->getField('filterdatafield' . ($i + 1)) == $filterDataField)) {
                        if ($this->getPredicateForColumn($filterDataField)) {
                            $whereNext = $this->composeWhereString(
                                $pGridSettings->getField('filtercondition' . ($i + 1)), '?', $pGridSettings->getField('filtervalue' . ($i + 1)));
                            $predicatorsArr[] = $this->getPredicateForColumn($filterDataField);
                        } else {
                            $whereNext = $this->composeWhereString(
                                $pGridSettings->getField('filtercondition' . ($i + 1)), $pGridSettings->getField('filterdatafield' . ($i + 1)), $pGridSettings->getField('filtervalue' . ($i + 1)));
                        }

                        $where = ' (' . $where;
                        if ($filterOperator == 0) {
                            $where .= ' AND ';
                        } else {
                            $where .= ' OR ';
                        }
                        $where .= $whereNext . ')';

                        $i += 1;
                    }

                    // Applies the produced Where clause.
                    if ($this->getPredicateForColumn($filterDataField)) {
                        $pSelect->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression($where, $predicatorsArr));
                    } else {
                        $pSelect->where($where);
                    }
                }
            }
        }
    }

    /**
     * Composes where clause based on condiction, field name and value
     *
     * @param string $pFilterCondition
     * @param string $pFilterDataField
     * @param string $filterValue
     * @return composed where string
     */
    private function composeWhereString($pFilterCondition, $pFilterDataField, $pFilterValue = null)
    {
        $where = '';
        switch ($pFilterCondition) {
            case "CONTAINS":
                $where = " " . $this->getRealColumnName($pFilterDataField) . " LIKE '%" . addslashes($pFilterValue) . "%'";
                break;
            case "CONTAINS_CASE_SENSITIVE":
                $where = " " . $this->getRealColumnName($pFilterDataField) . " LIKE '%" . addslashes($pFilterValue) . "%'";
                break;
            case "DOES_NOT_CONTAIN":
                $where = " " . $this->getRealColumnName($pFilterDataField) . " NOT LIKE '%" . addslashes($pFilterValue) . "%'";
                break;
            case "DOES_NOT_CONTAIN_CASE_SENSITIVE":
                $where = " " . $this->getRealColumnName($pFilterDataField) . " NOT LIKE '%" . addslashes($pFilterValue) . "%'";
                break;
            case "EQUAL":
                $where = " " . $this->getRealColumnName($pFilterDataField) . " = '" . addslashes($pFilterValue) . "'";
                break;
            case "EQUAL_CASE_SENSITIVE":
                $where = " " . $this->getRealColumnName($pFilterDataField) . " = '" . addslashes($pFilterValue) . "'";
                break;
            case "NOT_EQUAL":
                $where = " " . $this->getRealColumnName($pFilterDataField) . " <> '" . addslashes($pFilterValue) . "'";
                break;
            case "NOT_EQUAL_CASE_SENSITIVE":
                $where = " " . $this->getRealColumnName($pFilterDataField) . " <> '" . addslashes($pFilterValue) . "'";
                break;
            case "GREATER_THAN":
                $where = " " . $this->getRealColumnName($pFilterDataField) . " > '" . addslashes($pFilterValue) . "'";
                break;
            case "LESS_THAN":
                $where = " " . $this->getRealColumnName($pFilterDataField) . " < '" . addslashes($pFilterValue) . "'";
                break;
            case "GREATER_THAN_OR_EQUAL":
                $where = " " . $this->getRealColumnName($pFilterDataField) . " >= '" . addslashes($pFilterValue) . "'";
                break;
            case "LESS_THAN_OR_EQUAL":
                $where = " " . $this->getRealColumnName($pFilterDataField) . " <= '" . addslashes($pFilterValue) . "'";
                break;
            case "STARTS_WITH":
                $where = " " . $this->getRealColumnName($pFilterDataField) . " LIKE '" . addslashes($pFilterValue) . "%'";
                break;
            case "STARTS_WITH_CASE_SENSITIVE":
                $where = " " . $this->getRealColumnName($pFilterDataField) . " LIKE '" . addslashes($pFilterValue) . "%'";
                break;
            case "ENDS_WITH":
                $where = " " . $this->getRealColumnName($pFilterDataField) . " LIKE '%" . addslashes($pFilterValue) . "'";
                break;
            case "ENDS_WITH_CASE_SENSITIVE":
                $where = " " . $this->getRealColumnName($pFilterDataField) . " LIKE '%" . addslashes($pFilterValue) . "'";
                break;
            case "EMPTY":
                $where = " (" . $this->getRealColumnName($pFilterDataField) . " IS NULL OR " . $this->getRealColumnName($pFilterDataField) . " = '')";
                break;
            case "NOT_EMPTY":
                $where = " (" . $this->getRealColumnName($pFilterDataField) . " IS NOT NULL AND " . $this->getRealColumnName($pFilterDataField) . " <> '')";
                break;
        }
        return $where;
    }

    /**
     * Composes the ORDER BY clause
     *
     * @param BaseGridSettings $pGridSettings
     * @param Select $pSelect
     */
    public function sortHelper(BaseGridSettings $pGridSettings, Select $pSelect, $pDefaultSortDataField = 'id', $pDefaultSortOrder = 'DESC')
    {
        $sortDataField = ($pGridSettings->getField('sortdatafield') != '') ? $pGridSettings->getField('sortdatafield') : $pDefaultSortDataField;
        $sortOrder = ($pGridSettings->getField('sortorder') != '') ? $pGridSettings->getField('sortorder') : $pDefaultSortOrder;
        $pSelect->order($this->getRealColumnName($sortDataField) . ' ' . $sortOrder);
    }

    /**
     * Composes the LIMIT and OFFSET clause
     *
     * @param BaseGridSettings $pGridSettings
     * @param Select $pSelect
     */
    public function pagingnHelper(BaseGridSettings $pGridSettings, Select $pSelect)
    {
        $pSelect->offset($pGridSettings->getField('pagenum') * $pGridSettings->getField('pagesize'));
        $pSelect->limit((int)$pGridSettings->getField('pagesize'));
    }

    /**
     * Resolves ambiguous columns conflicts
     *
     * @param $pColumnName
     * @return relative database name
     */
    public function getRealColumnName($pColumnName)
    {
        return (isset($this->ambiguousColumnMapping[$pColumnName])) ? $this->ambiguousColumnMapping[$pColumnName] : $pColumnName;
    }

    /**
     * Gets Predicate for a column, usually such columns are a result of a subquery
     *
     * @param unknown $pColumnName
     * @return Predicate or null
     */
    public function getPredicateForColumn($pColumnName)
    {
        return (isset($this->predicateMapping[$pColumnName])) ? $this->predicateMapping[$pColumnName] : null;
    }

}