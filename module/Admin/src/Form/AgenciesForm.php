<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\I18n\Translator\Translator;
use Zend\InputFilter\InputFilter;

/**
 * Description of AgenciesForm
 *
 */
class AgenciesForm extends Form {

    public function __construct($name = null, $userTypes, $prices, $agenciesTable, $agencyId = 0, $userTypeId = 0) {
        parent::__construct($name);

        $inputFilter = new InputFilter();
        $translator = new Translator();
        if ($name != 'createAgent' && $name != 'EditAgent') {
            // user_type_id
            $this->add(array(
                'name' => 'user_type_id',
                'type' => 'Select',
                'attributes' => array(
                    'id' => 'user_type_id'
                ),
                'options' => array(
                    'label' => 'Вид',
                    'value_options' => $userTypes,
                )
            ));
            $inputFilter->add(array(
                'name' => 'user_type_id',
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


            $this->add(array(
                'name' => 'price_id',
                'type' => 'Select',
                'attributes' => array(
                    'id' => 'price_id'
                ),
                'options' => array(
                    'label' => 'Ценови пакет',
                    'empty_option' => 'Избери ценови пакет',
                    'value_options' => $prices,
                )
            ));
            $inputFilter->add(array(
                'name' => 'price_id',
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
        }



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


        // Description bg
        $this->add([
            'name' => 'description',
            'type' => 'textarea',
            'options' => [
                'label' => 'Текст(БГ)',
            ],
        ]);

        // Validate Description bg
        $inputFilter->add([
            'name' => 'description',
            'required' => false,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
            ],
        ]);


        // Description en
        $this->add([
            'name' => 'description_en',
            'type' => 'textarea',
            'options' => [
                'label' => 'Текст(EN)',
            ],
        ]);

        // Validate Description en
        $inputFilter->add([
            'name' => 'description_en',
            'required' => false,
            'filters' => [
                [
                    'name' => 'StripTags'
                ],
                [
                    'name' => 'StringTrim'
                ]
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
                        'min' => 7,
                        'max' => 128
                    ]
                ],
                [
                    'name' => 'EmailAddress'
                ],
                [
                    'name' => 'Zend\Validator\Db\NoRecordExists',
                    'options' => [
                        'table' => 'users',
                        'field' => 'email',
                        'exclude' => array(
                            'field' => 'id',
                            'value' => $agencyId,
                        ),
                        'adapter' => $agenciesTable->getTableGateway()->getAdapter(),
                    ],
                ],
            ],
        ]);


        // Password
        $this->add([
            'name' => 'password',
            'type' => 'password',
            'options' => [
                'label' => ($this->getName() == 'EditAgencies' || $this->getName() == 'EditAgent') ? $translator->translate('Password (Моля, не въвеждайте парола, ако желате тя да остане непроменена)') : $translator->translate('Password')
            ],
            'attributes' => array(
                'id' => 'password'
            ),
        ]);

        // Validate Password
        $inputFilter->add([
            'name' => 'password',
            'required' => ($this->getName() == 'EditAgencies' || $this->getName() == 'EditAgent') ? false : true,
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
        if ($agencyId != 0) {
            $target = PUBLIC_PATH . '/media/agents/' . $agencyId . '/';
        } else {
            $target = PUBLIC_PATH . '/media/agents/';
        }

        $inputFilter->add([
            'name' => 'logo',
            'required' => false,
            'filters' => [
                [
                    'name' => 'filerenameupload',
                    'options' => [
//                        'target' => 'public/img/partners/',
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
                        'extension' => array('jpg', 'jpeg', 'JPG', 'JPEG'),
                        'case' => true,
                    ),
                    'break_chain_on_failure' => true,
                ],
                [
                    'name' => 'filesize',
                    'options' => [
                        // gets file max size from php.ini
                        'max' => ini_get('upload_max_filesize') . 'B',
                    ],
                    'break_chain_on_failure' => true,
                ],
                [
                    'name' => 'Zend\Validator\File\ImageSize',
                    'options' => [
                        'minWidth' => 128,
                        'minHeight' => 128,
                        'maxWidth' => 8000,
                        'maxHeight' => 8000
                    ],
                    'break_chain_on_failure' => true,
                ]
            ]
        ]);

        if ($name != 'createAgent' && $name != 'EditAgent') {
            // Director
            $this->add([
                'name' => 'director',
                'type' => 'text',
                'options' => [
                    'label' => 'МОЛ',
                ],
            ]);

            // Validate Director
            $inputFilter->add([
                'name' => 'director',
                'required' => false,
                'filters' => [
                    [
                        'name' => 'StripTags'
                    ],
                    [
                        'name' => 'StringTrim'
                    ]
                ],
            ]);


            //  VAT Number.
            $this->add([
                'name' => 'vat_number',
                'type' => 'text',
                'options' => [
                    'label' => 'ЕИК/БУЛСТАТ',
                ],
            ]);

            // Validate VAT Number
            $inputFilter->add([
                'name' => 'vat_number',
                'required' => ($userTypeId == 1) ? false : true,
                'filters' => [
                    [
                        'name' => 'StripTags'
                    ],
                    [
                        'name' => 'StringTrim'
                    ]
                ],
            ]);

            // Company Address.
            $this->add([
                'name' => 'company_address',
                'type' => 'text',
                'options' => [
                    'label' => 'Адрес',
                ],
            ]);

            // Validate Company Address.
            $inputFilter->add([
                'name' => 'company_address',
                'required' => false,
                'filters' => [
                    [
                        'name' => 'StripTags'
                    ],
                    [
                        'name' => 'StringTrim'
                    ]
                ],
            ]);

        }

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
