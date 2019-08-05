<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\I18n\Translator\Translator;
use Zend\InputFilter\InputFilter;

/**
 * Class BlogForm
 * @package Admin\Form
 */
class BlogForm extends Form {

    public function __construct($name = 'blogForm', $categories, $languages) {
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
        $inputFilter->add([
            'name' => 'image',
            'required' => $this->getName() == 'EditBlog' ? false : true,
            'filters' => [
                [
                    'name' => 'filerenameupload',
                    'options' => [
                        'target' => PUBLIC_PATH . '/img/blog-img/',
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
        
        // Position
        $this->add([
            'name' => 'position',
            'type' => 'Zend\Form\Element\Select',
            'options' => [
                //TODO
                'options' => array( 
                    '1' => 'Архитектура', 
                    '2' => 'Интериор', 
                    '3' => 'Дизайн', 
                    '4' => 'Изкуство', 
                    '5' => 'Иновации', 
                    '6' => 'Пътешествия', 
                    '7' => 'Как да', 
                    '8' => 'Полезно за потребителя', 
                    '9' => 'Брокерски истории'       
                ),
                'label' => 'Категория'
            ],
            'attributes' => array(
                'id' => 'position'
            ),
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
            ],
            'validators' => [
                [
                    'name' => 'between',
                    'options' => [
                        // TODO
                        'min' => 1,
                        'max' => 9,
                    ],
                ]
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
              

        // Meta Title
        $this->add([
            'name' => 'meta_title',
            'type' => 'text',
            'options' => [
                'label' => 'Meta Title'
            ],
        ]);

        // Validate Meta Title
        $inputFilter->add([
            'name' => 'meta_title',
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
                        'max' => 60
                    ]
                ]
            ]
        ]);
        
        // Meta Description
        $this->add([
            'name' => 'meta_description',
            'type' => 'text',
            'options' => [
                'label' => 'Meta Description'
            ],
        ]);

        // Validate Meta Description
        $inputFilter->add([
            'name' => 'meta_description',
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
                        'max' => 160
                    ]
                ]
            ]
        ]);
        
        // Meta Keywords
        $this->add([
            'name' => 'meta_keywords',
            'type' => 'text',
            'options' => [
                'label' => 'Meta Keywords'
            ],
        ]);

        // Validate Meta Keywords
        $inputFilter->add([
            'name' => 'meta_keywords',
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
                        'max' => 160
                    ]
                ]
            ]
        ]);
                
        // Language Id
        $this->add([
            'name' => 'language_id',
            'type' => 'Zend\Form\Element\Select',
            'options' => [
                //TODO
                'value_options' => $languages,  
                'label' => 'Език'
            ],
            'attributes' => array(
                'id' => 'language_id'
            ),
        ]);
        // Validate Language Id
        $inputFilter->add([
            'name' => 'language_id',
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
                    'name' => 'between',
                    'options' => [
                        // TODO
                        'min' => 1,
                        'max' => 2,
                    ],
                ]
            ]
        ]);
        
        // Category Id
        $this->add([
            'name' => 'category_id',
            'type' => 'select',
            'options' => [
                //TODO
                'value_options' => $categories,                
                'label' => 'Тип'
            ],
            'attributes' => array(
                'id' => 'category_id',
                'onchange' => "changePosition()"
            ),
        ]);
        // Validate Category Id
        $inputFilter->add([
            'name' => 'category_id',
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