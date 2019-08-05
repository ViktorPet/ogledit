<?php

namespace Admin\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select; 
use Application\Model\Base\BaseGridSettings;
use Application\Model\Base\BaseGridTable;
use Admin\Model\Price;

/**
 * Description of PricesTable
 *
 */
class PricesTable extends BaseGridTable{
   
    public function getTable()
    {
        return $this->tableGateway->select();
    }
    
    function getData(BaseGridSettings $pGridSettings, $userId, $paging)
    {
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
    
    function getCount(BaseGridSettings $pGridSettings = null, $userId = null)
    {        
        $thisClass = $this;        
        $res = $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass) {
            $select->columns(array(
                new Expression('COUNT(*) AS num_results')
            ));           
            
            if(!is_null($pGridSettings))
            {
                // Filter
                $thisClass->filterHelper($pGridSettings, $select);
            }
        });              
        return $res->current()->getField('num_results');        
    }
    
    /**
     * Creates new price
     *
     * @param Price $price
     * @return mixed
     */
    public function create(Price $price)
    {
        $this->tableGateway->insert(array(
            'user_type_id' => $price->getField('user_type_id') ? $price->getField('user_type_id') : null,
            'min_offers' => $price->getField('min_offers'),
            'max_offers' => $price->getField('max_offers'),
            'photoshoot_per_sq_price' => $price->getField('photoshoot_per_sq_price'),
            'photoshoot_min_price' => $price->getField('photoshoot_min_price'),
            'weekly_price' => $price->getField('weekly_price'),
            'vip_price' => $price->getField('vip_price'),
            'top_price' => $price->getField('top_price'),
            'price_name' => $price->getField('price_name'),
            'chat' => $price->getField('chat'),
            'price_schema' => $price->getField('price_schema'),
        ));
        return $this->tableGateway->getLastInsertValue();
    }
    
      /**
     * Edits price
     *
     * @param Price $price
     */
    public function edit(Price $price)
    {
        $data = [
             'user_type_id' => $price->getField('user_type_id') ? $price->getField('user_type_id') : null,
            'min_offers' => $price->getField('min_offers'),
            'max_offers' => $price->getField('max_offers'),
            'photoshoot_per_sq_price' => $price->getField('photoshoot_per_sq_price'),
            'photoshoot_min_price' => $price->getField('photoshoot_min_price'),
            'weekly_price' => $price->getField('weekly_price'),
            'vip_price' => $price->getField('vip_price'),
            'top_price' => $price->getField('top_price'),
            'price_name' => $price->getField('price_name'),
            'chat' => $price->getField('chat'),
            'price_schema' => $price->getField('price_schema'),
        ];

        $this->tableGateway->update($data, ['id' => $price->getField('id')]);
    }
    
    
    /**
     * Deletes price
     *
     * @param type $priceId
     */
    public function delete($priceId)
    {
        $this->tableGateway->delete(
            array(
                'id' => $priceId
            )
        );
    }
    
    /**
     * Gets price by id
     *
     * @param $priceId
     * @return mixed
     */
    public function getPriceById($priceId)
    {
        $result = $this->tableGateway->select(function (Select $select) use ($priceId) {
            $select->where(array(
                'id' => $priceId
            ));
        });
        return $result->current();
    }
    
}
