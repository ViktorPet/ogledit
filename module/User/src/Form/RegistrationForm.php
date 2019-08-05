<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\I18n\Translator;

/**
 * Class RegistrationForm
 * @package User\RegistrationForm
 */
class RegistrationForm extends Form {

    public function __construct($userTypes, $userTable, $userId = 0, Translator $translator, $userTypeId = null) {
        parent::__construct('registrationForm');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $inputFilter = new InputFilter();

        $lang = $_SESSION['lang'];
        $translator->addTranslationFile("phparray", './module/Application/language/lang.array.' . $lang . '.php');

        // User Type Id.
        $this->add([
            'name' => 'user_type_id',
            'type' => 'select',
            'options' => [
                'label' => $translator->translate('User Type'),
                'value_options' => $userTypes,
            ],
        ]);

        // Names.
        $this->add([
            'name' => 'names',
            'type' => 'text',
            'options' => [
                'label' => $translator->translate('Names')
            ],
        ]);

        // Validate Names
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
                        'min' => 3,
                        'max' => 24
                    ]
                ]
            ]
        ]);

        // Phone.
        $this->add([
            'name' => 'phone',
            'type' => 'text',
            'options' => [
                'label' => $translator->translate('Phone')
            ],
        ]);

        // Validate Phone
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
                    'break_chain_on_failure' => true,
                ],
                [
                    'name' => 'between',
                    'options' => [
                        'translator' => $translator,
                        'min' => 1,
                        'max' => 999999999999,
                    ],
                    'break_chain_on_failure' => true,
                ],
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
            //            'required' => true,
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

        // Password Confirm
        $this->add([
            'name' => 'password_confirm',
            'type' => 'password',
            'options' => [
                'label' => $translator->translate('Password')
            ],
        ]);

        // Validate Password Comfirm
        $inputFilter->add([
            'name' => 'password_confirm',
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
        // Validate Image                
        $inputFilter->add([
            'name' => 'logo',
            'required' => false,
            'filters' => [
                [
                    'name' => 'filerenameupload',
                    'options' => [
                        'target' => PUBLIC_PATH . '/media/agents/',
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
                        'maxWidth' => 8000,
                        'maxHeight' => 8000
                    ],
                    'break_chain_on_failure' => true,
                ]
            ]
        ]);

        // Director.
        $this->add([
            'name' => 'director',
            'type' => 'text',
            'options' => [
                'label' => $translator->translate('Director')
            ],
        ]);

        // VAT Number.
        $this->add([
            'name' => 'vat_number',
            'type' => 'text',
            'options' => [
                'label' => $translator->translate('VAT Number')
            ],
        ]);

        // Company Address.
        $this->add([
            'name' => 'company_address',
            'type' => 'text',
            'options' => [
                'label' => $translator->translate('Company Address')
            ],
        ]);

        // Removes fields validation that are not necessary for normal users.
        if ($userTypeId != 1) {

            // Validate VAT Number
            $inputFilter->add([
                'name' => 'vat_number',
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
                    ]
                ]
            ]);
        }

        $this->setInputFilter($inputFilter);
    }

}