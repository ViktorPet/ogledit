<?php

namespace Admin\Model;

use Application\Model\BaseModel;

/**
 * Description of Price
 *
 */
class Price extends BaseModel{
    
     function __construct($pData = null)
    {
//        
//        $this->id = (!empty($data['id'])) ? $data['id'] : null;
//        $this->min_offers = (!empty($data['min_offers'])) ? $data['min_offers'] : null;
//        $this->max_offers = (!empty($data['max_offers'])) ? $data['max_offers'] : null;
//        $this->photoshoot_per_sq_price = (!empty($data['photoshoot_per_sq_price'])) ? $data['photoshoot_per_sq_price'] : null;
//        $this->photoshoot_min_price = (!empty($data['photoshoot_min_price'])) ? $data['photoshoot_min_price'] : null;
//        $this->weekly_price = (!empty($data['weekly_price'])) ? $data['weekly_price'] : null;
//        $this->vip_price = (!empty($data['vip_price'])) ? $data['vip_price'] : null;
//        $this->top_price = (!empty($data['top_price'])) ? $data['top_price'] : null;
//        $this->price_name = (!empty($data['price_name'])) ? $data['price_name'] : null;
//        $this->chat = (!empty($data['chat'])) ? $data['chat'] : null;
        
        //helper fields
        $this->numResults = (!empty($data['num_results'])) ? $data['num_results'] : null;
        
        
        
        $this->field = array(
            'id' => '',
            'min_offers' => '',
            'max_offers' => '',
            'photoshoot_per_sq_price' => '',
            'photoshoot_min_price' => '',
            'weekly_price' => '',
            'vip_price' => '',
            'top_price' => '',
            'price_name' => '',
            'chat' => '',
            'price_schema' => '',
            'user_type_id' => '',

            // helper fields
            'num_results' => '',
        );

        if (is_array($pData)) {
            $this->exchangeArray($pData);
        }
    }
    
    
}
