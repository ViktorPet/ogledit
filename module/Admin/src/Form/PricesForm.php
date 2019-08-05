<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\I18n\Translator\Translator;
use Zend\InputFilter\InputFilter;

/**
 * Description of PricesForm
 *
 */
class PricesForm extends Form {

    public function __construct($userTypes) {
        parent::__construct();

        $inputFilter = new InputFilter();
        $translator = new Translator();

        
        // user_type_id
            $this->add(array(
                'name' => 'user_type_id',
                'type' => 'Select',
                'attributes' => array(
                    'id' => 'user_type_id'
                ),
                'options' => array(
                    'label' => 'Вид',
                    'empty_option' => 'Изберете вид акаунт',
                    'value_options' => $userTypes,
                )
            ));
            $inputFilter->add(array(
                'name' => 'user_type_id',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                )
            ));
            
        
          // Price name
        $this->add([
            'name' => 'price_name',
            'type' => 'text',
            'options' => [
                'label' => 'Име на пакет'
            ],
            'attributes' => array(
                'id' => 'price_name'
            ),
        ]);
        // Validate Price name
        $inputFilter->add([
            'name' => 'price_name',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ],
        ]);
        

        // Min offers
        $this->add([
            'name' => 'min_offers',
            'type' => 'text',
            'options' => [
                'label' => 'Минимален брой оферти'
            ],
            'attributes' => array(
                'id' => 'min_offers'
            ),
        ]);
        // Validate Min offers
        $inputFilter->add([
            'name' => 'min_offers',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ],
        ]);



        // Max offers
        $this->add([
            'name' => 'max_offers',
            'type' => 'text',
            'options' => [
                'label' => 'Максимален брой оферти'
            ],
            'attributes' => array(
                'id' => 'max_offers'
            ),
        ]);
        // Validate Min offers
        $inputFilter->add([
            'name' => 'max_offers',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ],
        ]);
        

        // photoshoot_per_sq_price
        $this->add([
            'name' => 'photoshoot_per_sq_price',
            'type' => 'text',
            'options' => [
                'label' => 'Заснемане на имот (кв. м.)'
            ],
        ]);

        // Validate photoshoot_per_sq_price
        $inputFilter->add([
            'name' => 'photoshoot_per_sq_price',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ],
        ]);


        // photoshoot_min_price
        $this->add([
            'name' => 'photoshoot_min_price',
            'type' => 'text',
            'options' => [
                'label' => 'Заснемане на имот'
            ],
        ]);

        // Validate photoshoot_per_sq_price
        $inputFilter->add([
            'name' => 'photoshoot_min_price',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ],
        ]);


        // weekly_price
        $this->add([
            'name' => 'weekly_price',
            'type' => 'text',
            'options' => [
                'label' => 'Цена за седмица на брой оферта'
            ],
        ]);

        // Validate weekly_price
        $inputFilter->add([
            'name' => 'weekly_price',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ],
        ]);


        // vip_price
        $this->add([
            'name' => 'vip_price',
            'type' => 'text',
            'options' => [
                'label' => 'ВИП'
            ],
        ]);

        // Validate vip_price
        $inputFilter->add([
            'name' => 'vip_price',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ],
        ]);


        // top_price
        $this->add([
            'name' => 'top_price',
            'type' => 'text',
            'options' => [
                'label' => 'ТОП'
            ],
        ]);

        // Validate top_price
        $inputFilter->add([
            'name' => 'top_price',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ],
        ]);
        
        
        // chat
        $this->add([
            'name' => 'chat',
            'type' => 'text',
            'options' => [
                'label' => 'ЧАТ'
            ],
        ]);

        // Validate chat
        $inputFilter->add([
            'name' => 'chat',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ],
        ]);
        
        
          // 
        $this->add([
            'name' => 'price_schema',
            'type' => 'text',
            'options' => [
                'label' => 'Схема'
            ],
        ]);

        // Validate chat
        $inputFilter->add([
            'name' => 'price_schema',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ],
        ]);


        // Login button
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'options' => [
                'label' => 'Запази'
            ],
            'attributes' => [
                'value' => 'Вход'
            ]
        ]);

        $this->setInputFilter($inputFilter);
    }

}
