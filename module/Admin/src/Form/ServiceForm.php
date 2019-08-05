<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\I18n\Translator\Translator;
use Zend\InputFilter\InputFilter;

/**
 * Description of ServiceForm
 *
 */
class ServiceForm extends Form{
   public function __construct($name = 'serviceForm', $categories, $serviceId = 0) {
        parent::__construct($name);
        
        $inputFilter = new InputFilter();
        $translator = new Translator();

        // Title
        $this->add([
            'name' => 'title',
            'type' => 'text',
            'options' => [
                'label' => 'Заглавие'
            ],
        ]);

        // Validate Title
        $inputFilter->add([
            'name' => 'title',
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

        // Description
        $this->add([
            'name' => 'description',
            'type' => 'text',
            'attributes' => array(
                'id' => 'description'
            ),
            'options' => [
                'label' => 'Текст'
            ],
        ]);
        // Validate Description
        $inputFilter->add([
            'name' => 'description',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StringTrim'
                ]
            ],
        ]);
        
        // Url
        $this->add([
            'name' => 'url',
            'type' => 'text',
            'options' => [
                'label' => 'URL'
            ],
        ]);
        // Validate Url
        $inputFilter->add([
            'name' => 'url',
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
                        'max' => 255
                    ]
                ]
            ]
        ]);
        
        // Image
        $this->add([
            'name' => 'image',
            'type' => 'file',
            'options' => [
                'label' => 'Снимка'
            ],
            'attributes' => [
                'id' => 'file',
            ],
        ]);
        // Validate Image
        
        if($serviceId != 0) {
            $target = PUBLIC_PATH . '/img/service-img/' . $serviceId . '/';
        } else {
            $target = PUBLIC_PATH . '/img/service-img/';
        }        
        $inputFilter->add([
            'name' => 'image',
            'required' => $this->getName() == 'EditService' ? false : true,
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
                        'target' => PUBLIC_PATH . '/media/service/',
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
                        'extension' => array('zip', 'Zip'),
                        'case' => true,
                        'message' => 'Изисква се Zip'
                    ),
                    'break_chain_on_failure' => true,
                ],
            ]
        ]);
        
        // Date Published
        $this->add([
            'name' => 'date_published',
            'type' => 'text',
            'attributes' => array(
                'id' => 'date_published'
            ),
            'options' => [
                //TODO
                'label' => 'Дата',
            ],
        ]);
        // Validate Date Published
        $inputFilter->add([
            'name' => 'date_published',
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
                        'min' => 10,
                        'max' => 32
                    ]
                ]
            ]         
        ]);

        
        // Category Id
        $this->add([
            'name' => 'service_category_id',
            'type' => 'select',
            'options' => [
                //TODO
                'value_options' => $categories,                
                'label' => 'Тип'
            ],
            'attributes' => array(
                'id' => 'service_category_id',
            ),
        ]);
        // Validate Category Id
        $inputFilter->add([
            'name' => 'service_category_id',
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
        
        
        // Login button
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'options' => [
                'label' => 'Запази'
            ],
            'attributes' => [
                'value' => 'Вход'
            ]
        ]);

        $this->setInputFilter($inputFilter);
    }
}
