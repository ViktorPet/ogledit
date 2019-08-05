<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\I18n\Translator\Translator;
use Zend\InputFilter\InputFilter;

/**
 * Class LoginForm
 * @package Admin\Form
 */
class LoginForm extends Form {

    public function __construct() {
        parent::__construct('loginForm');

        $inputFilter = new InputFilter();
        $translator = new Translator();

        // Username
        $this->add([
            'name' => 'username',
            'type' => 'text',
            'options' => [
                'label' => 'Потребителско име'
            ],
        ]);

        // Validate Username
        $inputFilter->add([
            'name' => 'username',
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

        // Password
        $this->add([
            'name' => 'password',
            'type' => 'password',
            'options' => [
                'label' => 'Парола'
            ],
        ]);

        // Validate Password
        $inputFilter->add([
            'name' => 'password',
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
                'label' => 'Вход'
            ],
            'attributes' => [
                'value' => 'Вход'
            ]
        ]);

        $this->setInputFilter($inputFilter);
    }
}