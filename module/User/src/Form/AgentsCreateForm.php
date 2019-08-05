<?php

namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\I18n\Translator;

/**
 * Description of AgentsCreateForm
 *
 */
class AgentsCreateForm extends Form{
    
    public function __construct($name = null, $userTable, $userId = 0, Translator $translator) {
        parent::__construct($name);

        $inputFilter = new InputFilter();        
        
        $lang = $_SESSION['lang'];           
        $translator->addTranslationFile("phparray",'./module/Application/language/lang.array.'.$lang.'.php');  

        // Name bg
        $this->add([
            'name' => 'names',
            'type' => 'text',
            'options' => [
                'label' => 'Име(БГ)',
            ],
        ]);

        // Validate Name bg
        $inputFilter->add([
            'name' => 'names',            
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
                        'min' => 1,
                        'max' => 64
                    ]
                ]
            ]
        ]);


        // Name en
        $this->add([
            'name' => 'names_en',
            'type' => 'text',
            'options' => [
                'label' => 'Име(EN)',
            ],
        ]);

        // Validate Name en
        $inputFilter->add([
            'name' => 'names_en',            
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
                        'min' => 1,
                        'max' => 64
                    ]
                ]
            ]
        ]);


        // Description bg
        $this->add([
            'name' => 'description',
            'type' => 'textarea',
            'required' => false,
            'options' => [
                'label' => 'Текст(БГ)',
            ],
        ]);

        // Description en
        $this->add([
            'name' => 'description_en',
            'type' => 'textarea',
            'required' => false,
            'options' => [
                'label' => 'Текст(EN)',
            ],
        ]);

        // Phone
        $this->add([
            'name' => 'phone',
            'type' => 'text',
            'options' => [
                'label' => 'Телефон',
            ],
        ]);

        // Validate Description bg
        $inputFilter->add([
            'name' => 'phone',            
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
                ],                
            ]
        ]);

        // Email
        $this->add([
            'name' => 'email',
            'type' => 'text',
            'options' => [
                'label' => $translator->translate('E-mail')
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
                    ],
                    'break_chain_on_failure' => true,
                ],
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'translator' => $translator,                       
                    ],
                    'break_chain_on_failure' => true,
                ],
                [
                    'name' => 'Zend\Validator\Db\NoRecordExists',
                    'options' => [
                        'translator' => $translator,
                        'table' => 'users',
                        'field' => 'email',
                        'exclude' => array(
                            'field' => 'id',
                            'value' => $userId,
                        ),
                        'adapter' => $userTable->getTableGateway()->getAdapter(),
                    ],
                ],
            ],
        ]);


        // Password
        $this->add([
            'name' => 'password',
            'type' => 'password',
            'options' => [
                'label' => ($this->getName() == 'EditAgent') ? $translator->translate('Password (Моля, не въвеждайте парола, ако желате тя да остане непроменена)') : $translator->translate('Password')
            ],
            'attributes' => array(
                'id' => 'password'
            ),
        ]);

        // Validate Password
        $inputFilter->add([
            'name' => 'password',
            'required' => ($this->getName() == 'EditAgent') ? false : true,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ],
            'validators' => [
//                [
//                    'name' => 'StringLength',
//                    'options' => [
//                        'min' => 6
//                    ]
//                ]
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

        // Image
        $this->add([
            'name' => 'logo',
            'type' => 'file',
            'options' => [
                'label' => 'Лого'
            ],
            'attributes' => [
                'id' => 'file',
            ],
        ]);
        
        if($userId != 0) {
            $target = PUBLIC_PATH . '/media/agents/' . $userId . '/';
        } else {
            $target = PUBLIC_PATH . '/media/agents/';
        }
        
        // Validate Image                
        $inputFilter->add([
            'name' => 'logo',
            'required' => ($this->getName() == 'EditAgencies' || $this->getName() == 'EditAgent') ? false : false,            
            'filters' => [
                [
                    'name' => 'filerenameupload',
                    'options' => [
                        'target' => $target,
                        'use_upload_extension' => true,
                        'use_upload_name' => true,
                        'randomize' => true,
                    ]
                ],
            ],
            'validators' => [
                [
                    'name' => 'fileextension',
                    'options' => array(
                        'extension' => array('jpg', 'jpeg', 'JPG', 'JPEG', 'png'),
                        'translator' => $translator,
                        'case' => true,
                    ),
                    'break_chain_on_failure' => true,
                ],
                [
                    'name' => 'filesize',
                    'options' => [
                        'translator' => $translator,
                        // gets file max size from php.ini
                        'max' => ini_get('upload_max_filesize') . 'B',
                    ],
                    'break_chain_on_failure' => true,
                ],
                [
                    'name' => 'Zend\Validator\File\ImageSize',
                    'options' => [
                        'translator' => $translator,
                        'minWidth' => 128,
                        'minHeight' => 128,
                        'maxWidth' => 800,
                        'maxHeight' => 800
                    ],
                    'break_chain_on_failure' => true,
                ]
            ]
        ]);

        // Submit button
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'options' => [
                'label' => 'Добави'
            ],
            'attributes' => [
                'value' => 'Добави'
            ]
        ]);

        $this->setInputFilter($inputFilter);
    }

    
    
}
