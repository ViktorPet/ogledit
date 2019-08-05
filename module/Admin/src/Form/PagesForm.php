<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\I18n\Translator\Translator;
use Zend\InputFilter\InputFilter;

/**
 * Description of PagesForm
 *
 */
class PagesForm extends Form {

    public function __construct($languages) {
        parent::__construct('pagesCreate');

        $inputFilter = new InputFilter();
        $translator = new Translator();


        // Language Id
        $this->add(array(
            'name' => 'language_id',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'language_id'
            ),
            'options' => array(
                'label' => 'Език',
                'value_options' => $languages,
            )
        ));
        $inputFilter->add(array(
            'name' => 'language_id',
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


        // Title
        $this->add([
            'name' => 'title',
            'type' => 'text',
            'options' => [
                'label' => 'Заглавие',
            ],            
        ]);

        // Validate Title
        $inputFilter->add([
            'name' => 'title',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StringTrim'
                ]
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 255
                    ]
                ]
            ]
        ]);

        // Description
        $this->add([
            'name' => 'description',
            'type' => 'textarea',
            'attributes' => array(
                'id' => 'description'
            ),
            'options' => [
                'label' => 'Текст'
            ],
        ]);

        // Validate Description
        $inputFilter->add([
            'name' => 'description',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StringTrim'
                ],
            ]
        ]);

        // Meta title
        $this->add([
            'name' => 'meta_title',
            'type' => 'text',
            'options' => [
                'label' => 'Meta title'
            ],
        ]);

        // Validate Meta title
        $inputFilter->add([
            'name' => 'meta_title',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 64
                    ]
                ]
            ]
        ]);


        // Meta description
        $this->add([
            'name' => 'meta_description',
            'type' => 'text',
            'options' => [
                'label' => 'Meta description'
            ],
        ]);

        // Validate Meta description
        $inputFilter->add([
            'name' => 'meta_description',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 64
                    ]
                ]
            ]
        ]);

        // Meta keyword
        $this->add([
            'name' => 'meta_keywords',
            'type' => 'text',
            'options' => [
                'label' => 'Meta keyword'
            ],
        ]);

        // Validate Meta keyword
        $inputFilter->add([
            'name' => 'meta_keywords',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 64
                    ]
                ]
            ]
        ]);


        // Submit button
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'options' => [
                'label' => 'Запази'
            ],
            'attributes' => [
                'value' => 'Запази'
            ]
        ]);

        $this->setInputFilter($inputFilter);
    }

}
