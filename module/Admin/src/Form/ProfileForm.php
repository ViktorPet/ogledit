<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\I18n\Translator\Translator;
use Zend\InputFilter\InputFilter;

/**
 * Description of ProfileForm
 *
 */
class ProfileForm extends Form{
   
     public function __construct($name = 'EditProfile') {
        parent::__construct($name);
        
        $inputFilter = new InputFilter();
        $translator = new Translator();

          
        
        // Username
        $this->add([
            'name' => 'username',
            'type' => 'text',
            'options' => [
                'label' => $translator->translate('User')
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
            ]
        ]);
        
        // First Name
        $this->add([
            'name' => 'first_name',
            'type' => 'text',
            'options' => [
                'label' => $translator->translate('Име')
            ],
        ]);

        // Validate First Name
        $inputFilter->add([
            'name' => 'first_name',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ]
        ]);
        
        // Last Name
        $this->add([
            'name' => 'last_name',
            'type' => 'text',
            'options' => [
                'label' => $translator->translate('Фамилия')
            ],
        ]);

        // Validate First Name
        $inputFilter->add([
            'name' => 'last_name',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ]
        ]);
        
       // Password
        $this->add([
            'name' => 'password',
            'type' => 'password',
            'options' => [
                'label' => ($this->getName() == 'EditProfile') ? $translator->translate('Password (Моля, не въвеждайте парола, ако желате тя да остане непроменена)') : $translator->translate('Password')                
            ],
            'attributes' => array(
                'id' => 'password'
            ),
        ]);

        // Validate Password
        $inputFilter->add([
            'name' => 'password',
            'required' => $this->getName() == 'EditProfile' ? false: true,
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
        
        
        // Password Confirm
        $this->add([
            'name' => 'password_confirm',
            'type' => 'password',
            'options' => [
                'label' => 'Repeat password'
            ],
        ]);

        // Validate Password Comfirm
        $inputFilter->add([
            'name' => 'password_confirm',
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
                    'name' => 'Identical',
                    'options' => [
                        'token' => 'password'
                    ]
                ]
            ]
            
        ]);

        
        
        // Gender
        $this->add([
            'name' => 'gender',
            'type' => 'Zend\Form\Element\Select',
            'options' => [
                //TODO
                'options' => array( 
                    'm' => 'Мъж', 
                    'f' => 'Жена',                     
                ),
                'label' => 'Пол'
            ],
            'attributes' => array(
                'id' => 'gender'
            ),
        ]);
        // Validate Gender
        $inputFilter->add([
            'name' => 'gender',
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
        
        // Position
        $this->add([
            'name' => 'position',
            'type' => 'text',
            'options' => [
                'label' => $translator->translate('Позиция')
            ],
        ]);

        // Validate Position
        $inputFilter->add([
            'name' => 'position',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ]
        ]);                
        
        // Login button
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'options' => [
                'label' => 'Запази'
            ],
            'attributes' => [
                'value' => 'Create'
            ]
        ]);

        $this->setInputFilter($inputFilter);
    }
    
}
