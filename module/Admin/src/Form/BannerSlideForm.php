<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * Description of BannerSlideForm
 *
 */
class BannerSlideForm extends Form {

    public function __construct($slidersTable, $slideId = null, $edit = false)
    {
        parent::__construct('bannerSlideForm');

        $this->setAttribute('method', 'post');

        $inputFilter = new InputFilter();


        // Наименование
        $this->add([
            'name' => 'name',
            'type' => 'text',
            'options' => [
                'label' => 'Наименование'
            ],
        ]);
        // Validate Наименование
        $inputFilter->add([
            'name' => 'name',
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
                    'name' => 'Zend\Validator\Db\NoRecordExists',
                    'options' => [
                        'table' => 'sliders',
                        'field' => 'name',
                        'adapter' => $slidersTable->getTableGateway()->getAdapter(),
                        'exclude' => array(
                            'field' => 'id',
                            'value' => $slideId,
                        ),
                    ],
                ],
            ],
        ]);


        // Адрес
        $this->add([
            'name' => 'link',
            'type' => 'text',
            'options' => [
                'label' => 'Адрес'
            ],
        ]);

        // Desktop Image
        $this->add([
            'name' => 'desktop_img',
            'type' => 'file',
            'options' => [
                'label' => 'Десктоп'
            ],
            'attributes' => [
                'id' => 'desktop_img',
            ],
        ]);

        // Validate Desktop Image
        $inputFilter->add([
            'name' => 'desktop_img',
            'required' => false,
            'filters' => [
                [
                    'name' => 'filerenameupload',
                    'options' => [
                        'target' => PUBLIC_PATH . '/img/banners-slide/desktop/',
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
                        'extension' => array('jpg'),
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
                        'minWidth' => 1920,
                        'minHeight' => 400,
                        'maxWidth' => 1920,
                        'maxHeight' => 400,
                    ],
                    'break_chain_on_failure' => true,
                ]
            ]
        ]);

        if ($edit) {
            // Delete Desktop Image
            $this->add(array(
                'name' => 'desktop_img_delete',
                'type' => 'checkbox',
                'attributes' => array(
                    'id' => 'desktop_img_delete'
                ),
                'options' => array(
                    'label' => 'Изтрии десктоп?',
                )
            ));
            $inputFilter->add(array(
                'name' => 'desktop_img_delete',
                'required' => false
            ));
        }

        // Mobile Image
        $this->add([
            'name' => 'mobile_img',
            'type' => 'file',
            'options' => [
                'label' => 'Телефон'
            ],
            'attributes' => [
                'id' => 'mobile_img',
            ],
        ]);

        // Validate Mobile Image
        $inputFilter->add([
            'name' => 'mobile_img',
            'required' => false,
            'filters' => [
                [
                    'name' => 'filerenameupload',
                    'options' => [
                        'target' => PUBLIC_PATH . '/img/banners-slide/mobile/',
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
                        'extension' => array('jpg'),
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
                        'minWidth' => 750,
                        'minHeight' => 750,
                        'maxWidth' => 750,
                        'maxHeight' => 750,
                    ],
                    'break_chain_on_failure' => true,
                ]
            ]
        ]);

        if ($edit) {
            // Delete Desktop Image
            $this->add(array(
                'name' => 'mobile_img_delete',
                'type' => 'checkbox',
                'attributes' => array(
                    'id' => 'mobile_img_delete'
                ),
                'options' => array(
                    'label' => 'Изтрии телефон?',
                )
            ));
            $inputFilter->add(array(
                'name' => 'mobile_img_delete',
                'required' => false
            ));
        }





























        // Адрес
        $this->add([
            'name' => 'link_en',
            'type' => 'text',
            'options' => [
                'label' => 'Адрес'
            ],
        ]);

        // Desktop Image
        $this->add([
            'name' => 'desktop_img_en',
            'type' => 'file',
            'options' => [
                'label' => 'Десктоп'
            ],
            'attributes' => [
                'id' => 'desktop_img_en',
            ],
        ]);

        // Validate Desktop Image
        $inputFilter->add([
            'name' => 'desktop_img_en',
            'required' => false,
            'filters' => [
                [
                    'name' => 'filerenameupload',
                    'options' => [
                        'target' => PUBLIC_PATH . '/img/banners-slide/desktop/',
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
                        'extension' => array('jpg'),
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
                        'minWidth' => 1920,
                        'minHeight' => 400,
                        'maxWidth' => 1920,
                        'maxHeight' => 400,
                    ],
                    'break_chain_on_failure' => true,
                ]
            ]
        ]);

        if ($edit) {
            // Delete Desktop Image
            $this->add(array(
                'name' => 'desktop_img_en_delete',
                'type' => 'checkbox',
                'attributes' => array(
                    'id' => 'desktop_img_en_delete'
                ),
                'options' => array(
                    'label' => 'Изтрии десктоп?',
                )
            ));
            $inputFilter->add(array(
                'name' => 'desktop_img_en_delete',
                'required' => false
            ));
        }

        // Mobile Image
        $this->add([
            'name' => 'mobile_img_en',
            'type' => 'file',
            'options' => [
                'label' => 'Телефон'
            ],
            'attributes' => [
                'id' => 'mobile_img_en',
            ],
        ]);

        // Validate Mobile Image
        $inputFilter->add([
            'name' => 'mobile_img_en',
            'required' => false,
            'filters' => [
                [
                    'name' => 'filerenameupload',
                    'options' => [
                        'target' => PUBLIC_PATH . '/img/banners-slide/mobile/',
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
                        'extension' => array('jpg'),
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
                        'minWidth' => 750,
                        'minHeight' => 750,
                        'maxWidth' => 750,
                        'maxHeight' => 750,
                    ],
                    'break_chain_on_failure' => true,
                ]
            ]
        ]);

        if ($edit) {
            // Delete Desktop Image
            $this->add(array(
                'name' => 'mobile_img_en_delete',
                'type' => 'checkbox',
                'attributes' => array(
                    'id' => 'mobile_img_en_delete'
                ),
                'options' => array(
                    'label' => 'Изтрии телефон?',
                )
            ));
            $inputFilter->add(array(
                'name' => 'mobile_img_en_delete',
                'required' => false
            ));
        }
        
        $this->setInputFilter($inputFilter);
    }

}
