<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\I18n\Translator\Translator;
use Zend\InputFilter\InputFilter;

/**
 * Description of DashboardFilterForm
 *
 */
class DashboardFilterForm extends Form {

    public function __construct($cities) {
        parent::__construct('dashboardFilterForm');

        $inputFilter = new InputFilter();
        $translator = new Translator();


        // city
        $this->add(array(
            'name' => 'city_id',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'city_id'
            ),
            'options' => array(
                'label' => 'Град',
                'empty_option' => 'Всички',
                'value_options' => $cities,
            )
        ));
        $inputFilter->add(array(
            'name' => 'city_id',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            )
        ));


        // период 
        $this->add(array(
            'name' => 'period_id',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'period_id'
            ),
            'options' => array(
                //'label' => 'Период',
                'empty_option' => 'Всички',
                'value_options' => array(
                    '1' => 'Този месец',
                    '2' => 'Миналия месец'     
                )
            )
        ));
        $inputFilter->add(array(
            'name' => 'period_id',
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
        
        
        // Date From
        $this->add(array(
            'name' => 'date_from',
            'type' => 'DateTime',
            'attributes' => array(
                'id' => 'date_from',
                'min' => '2012-01-01',
                'max' => '2020-01-01',
            ),
            'options' => array(                
                'format' => 'yy-mm-dd'
            )
        ));
        $inputFilter->add(array(
            'name' => 'date_from',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'GreaterThan',
                    'options' => array(
                        'min' => '2012-01-01',
                    )
                ),
                array(
                    'name' => 'LessThan',
                    'options' => array(
                        'max' => '2020-01-01',
                    )
                ),         
            )
        ));

        // Date To
        $this->add(array(
            'name' => 'date_to',
            'type' => 'DateTime',
            'attributes' => array(
                'id' => 'date_to',
                'min' => '2012-01-01',
                'max' => '2020-01-01',
            ),
            'options' => array(
                'format' => 'yy-mm-dd'
            )
        ));
        $inputFilter->add(array(
            'name' => 'date_to',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'GreaterThan',
                    'options' => array(
                        'min' => '2012-01-01',
                    )
                ),
                array(
                    'name' => 'LessThan',
                    'options' => array(
                        'max' => '2020-01-01',
                    )
                ),
            )
        ));       
        
        

        // Submit button
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'options' => [
                'label' => 'Търси'
            ],
            'attributes' => [
                'value' => 'Търси'
            ]
        ]);

        $this->setInputFilter($inputFilter);
    }

}
