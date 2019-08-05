<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

use Zend\Mvc\I18n\Translator;

/**
 * Class LoginForm
 * @package User\Form
 */
class LoginForm extends Form {

    public function __construct(Translator $translator) {
        parent::__construct('loginForm');

        $this->setAttribute('method', 'post');

        $inputFilter = new InputFilter();        

        // Email
        $this->add([
            'name' => 'email',
            'type' => 'text',
            'options' => [
                'label' => $translator->translate('Email')
            ],
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

        // Password
        $this->add([
            'name' => 'password',
            'type' => 'password',
            'options' => [
                'label' => $translator->translate('Password')
            ],
        ]);

        // Validate Password
        $inputFilter->add([
            'name' => 'password',
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
                        'min' => 6
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