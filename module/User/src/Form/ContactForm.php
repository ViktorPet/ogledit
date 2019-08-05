<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

use Zend\Mvc\I18n\Translator;

/**
 * Class ContactForm
 * @package User\Form
 */
class ContactForm extends Form {

    public function __construct(Translator $translator) {        
        parent::__construct('contactForm');

        $this->setAttribute('method', 'post');

        $inputFilter = new InputFilter();        

        // name
        $this->add([
            'name' => 'name',
            'type' => 'text',
        ]);

        // Validate Password
        $inputFilter->add([
            'name' => 'name',
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
                    'name' => 'NotEmpty',
                    'options' => [                        
                        'translator' => $translator,
                    ],
                    'break_chain_on_failure' => true,
                ],
                [
                    'name' => 'StringLength',
                    'options' => [
                        'translator' => $translator,
                        'min' => 3
                    ]
                ]
            ]
        ]);
        
        // Email
        $this->add([
            'name' => 'email',
            'type' => 'text',   
        ]);

        // Validate Email
        $inputFilter->add([
            'name' => 'email',
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
                    'name' => 'NotEmpty',
                    'options' => [                        
                        'translator' => $translator,
                    ],
                    'break_chain_on_failure' => true,
                ],
                [
                    'name' => 'StringLength',
                    'options' => [
                        'translator' => $translator,
                        'encoding' => 'UTF-8',
                        'min' => 7,
                        'max' => 128
                    ]
                ],
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'translator' => $translator,                        
                    ]
                ]
            ]
        ]); 
        
        // Phone.
        $this->add([
            'name' => 'phone',
            'type' => 'text',
        ]);

        // Validate Phone
        $inputFilter->add([
            'name' => 'phone',
            'required' => false,
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
                        'translator' => $translator,
                        'min' => 3,
                        'max' => 16
                    ]
                ]
            ]
        ]);

        // frmsmbx
        $this->add([
            'name' => 'frmsmbx',
            'type' => 'textarea',
            'attributes' => [
                'rows' => 5,                  
            ]
        ]);

        // Validate Password
        $inputFilter->add([
            'name' => 'frmsmbx',
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
                    'name' => 'NotEmpty',
                    'options' => [                        
                        'translator' => $translator,
                    ],
                    'break_chain_on_failure' => true,
                ],
                [
                    'name' => 'StringLength',
                    'options' => [
                        'translator' => $translator,
                        'min' => 3
                    ]
                ]
            ]
        ]);
        
        // Login button
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'options' => [
                'label' => $translator->translate('Login')
            ],
            'attributes' => [
                'value' => $translator->translate('Login')
            ]
        ]);

        $this->setInputFilter($inputFilter);
    }

}