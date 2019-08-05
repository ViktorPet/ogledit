<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * Description of OfferEditForm
 *
 */
class BannerEditForm extends Form {

    public function __construct($bannerId, $parallax = null) {
        parent::__construct('editBannerForm');

        $this->setAttribute('method', 'post');

        $inputFilter = new InputFilter();

        $this->add([
            'name' => 'parallax',
            'type' => 'file',
            'options' => [
                'label' => $parallax[$bannerId]['text']
            ],
            'attributes' => [
                'id' => 'parallax',
            ],
        ]);

        // Validate Image
        $inputFilter->add([
            'name' => 'parallax',
            'required' => true,
            'filters' => [
                [
                    'name' => 'filerenameupload',
                    'options' => [
                        'target' => PUBLIC_PATH . '/img/parallax/' . $parallax[$bannerId]['name'],
                        'overwrite' => true,
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
                        'maxWidth' => $parallax[$bannerId]['minWidth'] ? $parallax[$bannerId]['minWidth'] : '3600',
                        'maxHeight' => $parallax[$bannerId]['minHeight'] ? $parallax[$bannerId]['minHeight'] : '3600'
                    ],
                    'break_chain_on_failure' => true,
                ]
            ]
        ]);
        
        $this->setInputFilter($inputFilter);
    }

}
