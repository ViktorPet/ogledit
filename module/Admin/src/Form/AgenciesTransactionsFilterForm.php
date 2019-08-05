<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\I18n\Translator\Translator;

/**
 * Description of ReportsFilterForm
 *
 */
class AgenciesTransactionsFilterForm extends Form {
    /**
     * Constructs form fields and validation
     *
     * @param bool $edit
     */
    public function __construct() {
        parent::__construct('agenciesTransactionsFilterForm');

        $this->setAttributes(array(
            'class' => 'formBox'
        ));

        $inputFilter = new InputFilter();
        $translator = new Translator();       

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

        $this->setInputFilter($inputFilter);
    }
}
