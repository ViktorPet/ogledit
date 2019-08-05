<?php

namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class MediaForm extends Form
{


    public function __construct($offerId, $offerObj, $translator)
    {
        parent::__construct('mediaForm');

        $this->setAttribute('method', 'post');

        $inputFilter = new InputFilter();
        $lang = $_SESSION['lang'];
        $translator->addTranslationFile("phparray", './module/Application/language/lang.array.' . $lang . '.php');


        // Panorama
        $this->add(array(
            'name' => 'panorama_file',
            'type' => 'text',
            'attributes' => array(
                'id' => 'panorama_file'
            ),
            'options' => array(
                'label' => 'Линк на панорама',
            )
        ));

        $inputFilter->add(array(
            'name' => 'panorama_file',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'translator' => $translator,
                    ),
                    'break_chain_on_failure' => true,
                )
            )
        ));


        // Video
        $this->add(array(
            'name' => 'youtube_code_1',
            'type' => 'text',
            'attributes' => array(
                'id' => 'youtube_code_1'
            ),
            'options' => array(
                'label' => 'Видео',
            )
        ));

        $inputFilter->add(array(
            'name' => 'youtube_code_1',
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


        //  Image
        $this->add([
            'name' => 'image',
            'type' => 'file',
            'options' => [
                'label' => 'Снимки (изберете най-малко 1 снимка)'
            ],
            'attributes' => [
                'id' => 'image',
                'multiple' => true,
            ],
        ]);
        // Validate Image
        $inputFilter->add([
            'name' => 'image',
            'required' => true,
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
                        'translator' => $translator,
                        'extension' => array('jpg', 'jpeg', 'JPG', 'JPEG'),
                        'case' => true
                    ),
                    'break_chain_on_failure' => true,
                ],
                [
                    'name' => 'filesize',
                    'options' => [
                        'translator' => $translator,
                        'max' => '10MB',
                    ],
                    'break_chain_on_failure' => true,
                ],

                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'translator' => $translator,
                    ],
                    'break_chain_on_failure' => true
                ],
                [
                    'name' => 'Zend\Validator\File\ImageSize',
                    'options' => [
                        'minWidth' => 1600,
                        'translator' => $translator,
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

        // If user update media
        $this->add(array(
            'name' => 'update_user_media',
            'type' => 'checkbox',
            'attributes' => array(
                'id' => 'update_user_media'
            ),
//            'options' => array(
//                'label' => 'Watermark',
//            )
        ));
        $inputFilter->add(array(
            'name' => 'update_user_media',
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