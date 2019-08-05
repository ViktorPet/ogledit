<?php
namespace User\Model;

use Zend\Db\Sql\Sql;
use Application\Model\BaseTableModel;
use Zend\Db\Sql\Select;

/**
 * Class CityTable
 * @package User\Model
 */
class CityTable extends BaseTableModel {

    /**
     * Gets all offer types as index -> name array.
     *
     * @return array
     */
    public function getTypesArray2() {
        $rowset = $this->tableGateway->select(function (Select $select) {
            $select->order('city_order DESC, id ASC');
            /*  $select->columns(array(
                  'name',
                  'numbers'
              ));*/
        });
        if ($rowset) {
            $selectData1 = array();
            $selectData2 = array();
            $selectData3 = array();
            //   $selectData = array($selectData1, $selectData2);
            foreach ($rowset as $res) {
                $selectData1[$res->id] = $res->name;
                $selectData2[$res->id] = $res->number;
                $selectData3[$res->id] = $res->id;
            }
            return $selectData = array($selectData1, $selectData2, $selectData3);
        } else {
            return array();
        }
    }

    public function getTypesArray() {
        $rowset = $this->tableGateway->select(function (Select $select) {
            $select->order('city_order DESC, id ASC');
        });
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

    public function getNameById($cityId) {

        $rowset = $this->tableGateway->select(function (Select $select) use ($cityId) {
            $select->columns(array(
                'name'
            ));
            $select->where(array(
                'id' => $cityId
            ));
        });
        return $rowset->current()->getName();
    }



    public function edit(City $city)
    {
        $update = $this->tableGateway->update (['number' => $city->getNumber ()], ['id'=>$city->getId()]);
        //echo '<pre>';
   
        //echo var_dump($this->tableGateway->getSql ()->update ()->set (['number' => 1])->where ('id > 0')->getSqlString ());
        //echo '</pre>';
        //exit;
        //echo print_r ($update->getRawState (), true);
        //echo print_r (get_class_methods($update), true);  exit;

        //var_dump($update->getRawState ());

    }

    public function getById($cityId) {
        $res = $this->tableGateway->select(function (Select $select) use ($cityId) {
            $select->where(array(
                'id' => $cityId,
            ));
        });
        return $res->current();
    }
}