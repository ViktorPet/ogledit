<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;


class ChangeImageForm extends Form
{

    public function __construct($offerId)
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