<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * Description of GalleryForm
 *
 */
class GalleryForm extends Form
{

    public function __construct($offerId, $offerObj, $panoDir)
    {
        parent::__construct('galleryForm');

        $this->setAttribute('method', 'post');

        $inputFilter = new InputFilter();


        //  Images
        $this->add([
            'name' => 'image',
            'type' => 'file',
            'options' => [
                'label' => 'Снимки'
            ],
            'attributes' => [
                'id' => 'image',
                'multiple' => true,
            ],
        ]);
        // Validate Image
        $inputFilter->add([
            'name' => 'image',
            'required' => false,
            'filters' => [
                [
                    'name' => 'filerenameupload',
                    'options' => [
                        'target' => PUBLIC_PATH . '/media/offers/' . $offerId . '/',
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
            ]
        ]);


        // Facebook Image
        $this->add([
            'name' => 'facebook_img',
            'type' => 'file',
            'options' => [
                'label' => 'Facebook (1020x630) - Автоматично оразмеряване'
            ],
            'attributes' => [
                'id' => 'facebook_img',
            ],
        ]);

        // Validate Image
        $inputFilter->add([
            'name' => 'facebook_img',
            'required' => false,
            'filters' => [
                [
                    'name' => 'filerenameupload',
                    'options' => [
                        'target' => PUBLIC_PATH . '/media/offers/' . $offerId . '/',
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
                ]
            ]
        ]);

        // Panorama file
        $this->add([
            'name' => 'panorama_file',
            'type' => 'file',
            'options' => [
                'label' => 'Панорама'
            ],
            'attributes' => [
                'id' => 'panorama_file',
            ],
        ]);

        // Validate Image
        $inputFilter->add([
            'name' => 'panorama_file',
            'required' => false,
            'filters' => [
                [
                    'name' => 'filerenameupload',
                    'options' => [
                        'target' => PUBLIC_PATH . '/media/pano/' . $panoDir . '/',
                        'use_upload_extension' => true,
                        'use_upload_name' => true,
                        'randomize' => true,
                    ]
                ]
            ],
            'validators' => [
                [
                    'name' => 'fileextension',
                    'options' => array(
                        'extension' => array('zip', 'Zip', 'ZIP'),
                        'case' => true,
                    ),
                    'break_chain_on_failure' => true,
                ],
            ]
        ]);


        // Youtube code 1
        $this->add([
            'name' => 'youtube_code_1',
            'type' => 'text',
            'options' => [
                'label' => 'Youtube code 1'
            ],
        ]);

        // Validate Youtube code 1
        $inputFilter->add([
            'name' => 'youtube_code_1',
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
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 60
                    ]
                ]
            ]
        ]);



        // Youtube code 2 file
        $this->add([
            'name' => 'youtube_code_2',
            'type' => 'file',
            'options' => [
                'label' => 'Youtube code 2'
            ],
            'attributes' => [
                'id' => 'youtube_code_2',
            ],
        ]);
        // Validate Image
        $inputFilter->add([
            'name' => 'youtube_code_2',
            'required' => false,
            'filters' => [
                [
                    'name' => 'filerenameupload',
                    'options' => [
                        'target' => PUBLIC_PATH . '/media/video/' . $offerId . '/',
                        'use_upload_extension' => true,
                        'use_upload_name' => true,
                        'randomize' => true,
                    ]
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
