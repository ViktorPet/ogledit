<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class ChangeImageForm extends Form {

    public function __construct($offerId)
    {
        parent::__construct();

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
                        'max' => '10MB',
                    ],
                    'break_chain_on_failure' => true,
                ],
                [
                    'name' => 'Zend\Validator\File\ImageSize',
                    'options' => [
                        'minWidth' => 1600,
                    ],
                    'break_chain_on_failure' => true,
                ],
            ]
        ]);

        // Watermark
        $this->add(array(
            'name' => 'has_watermark',
            'type' => 'checkbox',
            'attributes' => array(
                'id' => 'has_watermark'
            ),
            'options' => array(
                'label' => 'Watermark',
            )
        ));
        $inputFilter->add(array(
            'name' => 'has_watermark',
            'required' => false
        ));


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