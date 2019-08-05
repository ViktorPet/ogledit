<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\I18n\Translator;

/**
 * Class FacebookForm
 * @package User\FacebookForm
 */
class FacebookForm extends Form {

    public function __construct(Translator $translator) {
        parent::__construct('facebookForm');

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $inputFilter = new InputFilter();
        
        $lang = $_SESSION['lang'];           
        $translator->addTranslationFile("phparray",'./module/Application/language/lang.array.'.$lang.'.php');   
        
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
                    'name' => 'StringLength',
                    'options' => [
                        'translator' => $translator,
                        'min' => 3,
                        'max' => 16
                    ]
                ]
            ]
        ]);
        
        $this->setInputFilter($inputFilter);
    }

}