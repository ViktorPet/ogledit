<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * Description of OfferEditForm
 *
 */
class OfferEditForm extends Form {

    public function __construct($offerTypes, $userOfferStatuses, $offerStatuses, $cities, $neighbourhoods, $propertyTypes, $buildingTypes, $heatingSystems, 
            $constructionYears, $currencies, $weeks, $parcelTypes, $brokers, $offerId, $panoDir) {
        parent::__construct('editOfferForm');

        $this->setAttribute('method', 'post');

        $inputFilter = new InputFilter();

        //photographer_appointment
        $this->add([
            'name' => 'photographer_appointment',
            'type' => 'text',
            'attributes' => array(
                'id' => 'photographer_appointment'
            ),
            'options' => [
                'label' => 'Дата и час на заснемане',
            ],
        ]);

        //photographer_appointment
        $this->add([
            'name' => 'photographer_address',
            'type' => 'text',
            'attributes' => array(
                'id' => 'photographer_address'
            ),
            'options' => [
                'label' => 'Адрес на заснемане',
            ],
        ]);

        // Validate Date Published
        $inputFilter->add([
            'name' => 'photographer_appointment',
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
                        'min' => 10,
                        'max' => 32
                    ]
                ]
            ]
        ]);

        //User Offer status
        $this->add([
            'name' => 'user_offer_status_id',
            'type' => 'select',
            'options' => [
                'label' => 'Статус на потребителя',
                'value_options' => $userOfferStatuses,
                'empty_option' => 'Актуална'
            ],
        ]);
        $inputFilter->add(array(
            'name' => 'user_offer_status_id',
            'required' => false,
        ));

        // Offer Type Id.
        $this->add([
            'name' => 'offer_type_id',
            'type' => 'select',
            'options' => [
                'label' => 'Вид:',
                'value_options' => $offerTypes
            ],
        ]);

         // Offer Type Id.
        $this->add([
            'name' => 'offer_status_id',
            'type' => 'select',
            'options' => [
                'label' => 'Статус:',
                'value_options' => $offerStatuses
            ],
        ]);
        
        // City Id.
        $this->add([
            'name' => 'city_id',
            'type' => 'select',
            'options' => [
                'label' => 'Град:',
                'value_options' => $cities
            ],
        ]);


        // Add by.
        $this->add([
            'name' => 'user_id',
            'type' => 'select',
            'options' => [
                'label' => 'Брокер:',
                'value_options' => $brokers,
                'empty_option' => 'Избери брокер',
                'disable_inarray_validator' => true
            ],
        ]);
        $inputFilter->add(array(
            'name' => 'user_id',
            'required' => false,
        ));

        // Neightbourhood Id.
        $this->add([
            'name' => 'neighbourhood_id',
            'type' => 'select',
            'options' => [
                'label' => 'Регион / Квартал:',
                'value_options' => $neighbourhoods,
                'disable_inarray_validator' => true
            ],
        ]);
        $inputFilter->add(array(
            'name' => 'neighbourhood_id',
            'required' => false,
        ));

        // Street.
        $this->add([
            'name' => 'street',
            'type' => 'text',
            'options' => [
                'label' => 'Адрес:'
            ],
        ]);

        // Property Type Id.
        $this->add([
            'name' => 'property_type_id',
            'type' => 'select',
            'options' => [
                'label' => 'Тип:',
                'value_options' => $propertyTypes
            ],
        ]);

        // Building Type Id.
        $this->add([
            'name' => 'building_type_id',
            'type' => 'select',
            'options' => [
                'label' => 'Вид конструкция:',
                'value_options' => $buildingTypes,
                'disable_inarray_validator' => true
            ],
        ]);
        $inputFilter->add(array(
            'name' => 'building_type_id',
            'required' => false,
        ));

        // Is Regulated.
        $this->add([
            'name' => 'is_regulated',
            'type' => 'select',
            'options' => [
                'label' => 'Регулация',
                'value_options' => array(
                    '0' => 'Не',
                    '1' => 'Да'
                )
            ],
        ]);
        $inputFilter->add(array(
            'name' => 'is_regulated',
            'required' => false,
        ));
        

        // Heating System Id.
        $this->add([
            'name' => 'heating_system_id',
            'type' => 'select',
            'options' => [
                'label' => 'Отопление:',
                'value_options' => $heatingSystems,
                'disable_inarray_validator' => true
            ],
        ]);
        $inputFilter->add(array(
            'name' => 'heating_system_id',
            'required' => false,
        ));

        // Parcel Type Id.
        $this->add([
            'name' => 'parcel_type_id',
            'type' => 'select',
            'options' => [
                'label' => 'Вид:',
                'value_options' => $parcelTypes,
                'disable_inarray_validator' => true
            ],
        ]);
        $inputFilter->add(array(
            'name' => 'parcel_type_id',
            'required' => false,
        ));

        // Construction Year.
        $this->add([
            'name' => 'construction_year',
            'type' => 'select',
            'options' => [
                 'label' => 'Година на строителство',
                'value_options' => $constructionYears,
                'disable_inarray_validator' => true
            ],
        ]);
        $inputFilter->add(array(
            'name' => 'construction_year',
            'required' => false,
        ));

        // Currency Id.
        $this->add([
            'name' => 'currency_id',
            'type' => 'select',
            'options' => [
                'label' => 'Валута',
                'value_options' => $currencies
            ],
        ]);

        // Price.
        $this->add(array(
            'name' => 'price',
            'type' => 'number',
            'options' => array(
                'label' => 'Цена'
            )
        ));
        $inputFilter->add(array(
            'name' => 'price',
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
                    'name' => 'between',
                    'options' => array(
                        'min' => 1,
                        'max' => 9999999,
                    )
                )
            )
        ));

        // Area.
        $this->add(array(
            'name' => 'area',
            'type' => 'number',
            'options' => array(
                'label' => 'Квадратура (кв.м.)'
            )
        ));
        $inputFilter->add(array(
            'name' => 'area',
            'required' => false
        ));

        // Floor.
        $this->add(array(
            'name' => 'floor',
            'type' => 'select',
            'options' => array(
                'label' => 'Етаж',
                'value_options' => array(
                        '-5' => '-5',
                        '-4' => '-4',
                        '-3' => '-3',
                        '-2' => '-2',
                        '-1' => '-1',
                        '0' => 'Партер'
                    ) + array_combine(range(1,30), range(1,30))

            )
        ));
        $inputFilter->add(array(
            'name' => 'floor',
            'required' => false,
            'validators' => array(
                array(
                    'name' => 'between',
                    'options' => array(
                        'min' => -5,
                        'max' => 99,
                    )
                )
            )
        ));

        // Total Rooms.
        $this->add(array(
            'name' => 'total_rooms',
            'type' => 'select',
            'options' => array(
                'label' => 'Брой помещения',
                'value_options' => array_combine(range(1,10), range(1,10))
            )
        ));
        $inputFilter->add(array(
            'name' => 'total_rooms',
            'required' => false,   
            'validators' => array(
                array(
                    'name' => 'between',
                    'options' => array(
                        'min' => 0,
                        'max' => 99,
                    )
                )
            )
        ));

        // Bathrooms.
        $this->add(array(
            'name' => 'bathrooms',
            'type' => 'select',
            'options' => array(
                'label' => 'Бани',
                'value_options' => array_combine(range(1,5), range(1,5))
            )
        ));
        $inputFilter->add(array(
            'name' => 'bathrooms',
            'required' => false,
            'validators' => array(
                array(
                    'name' => 'between',
                    'options' => array(
                        'min' => 0,
                        'max' => 99,
                    )
                )
            )
        ));

        // Parking Slots
        $this->add(array(
            'name' => 'parking_slots',
            'type' => 'select',
            'options' => array(
                'label' => 'Парко места',
                'value_options' =>  array_merge(array(0 => 'Без'),array_combine(range(1,10), range(1,10)))
            )
        ));
        $inputFilter->add(array(
            'name' => 'parking_slots',
            'required' => false, 
            'validators' => array(
                array(
                    'name' => 'between',
                    'options' => array(
                        'min' => 0,
                        'max' => 99,
                    )
                )
            )
        ));

        // Yard.
        $this->add(array(
            'name' => 'yard',
            'type' => 'number',
            'attributes' => array(
                'id' => 'yard'
            ),
            'options' => array(
                'label' => 'Двор (кв.м.)',
            )
        ));
        $inputFilter->add(array(
            'name' => 'yard',
            'required' => false
        ));

        // Yard Shot.
        $this->add(array(
            'name' => 'yard_shot',
            'type' => 'checkbox',
            'attributes' => array(
                'id' => 'yard_shot'
            ),
            'options' => array(
                'label' => 'Заснемане на двор?',
            )
        ));
        $inputFilter->add(array(
            'name' => 'yard_shot',
            'required' => false
        ));

        // Information
        $this->add(array(
            'name' => 'information',
            'type' => 'textarea',
            'attributes' => array(
                'id' => 'information',
                'rows' => 10
            ),
            'options' => array(
                'label' => 'Информация',
            )
        ));
        $inputFilter->add(array(
            'name' => 'information',
            'required' => false,
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
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 0,
                        'max' => 4096
                    )
                )
            )
        ));
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

        // Main Image
        $this->add([
            'name' => 'main_image',
            'type' => 'file',
            'options' => [
                'label' => 'Главна снимка (1920x1280)'
            ],
            'attributes' => [
                'id' => 'main_image'
            ],
        ]);
        // Validate Image
        $inputFilter->add([
            'name' => 'main_image',
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
                [
                    'name' => 'Zend\Validator\File\ImageSize',
                    'options' => [
                        'minWidth' => 1920,
                        'minHeight' => 1280
                    ],                  
                    'break_chain_on_failure' => true,
                ],
                [
                    'name' => 'Zend\Validator\File\ImageSize',
                    'options' => [
                        'maxWidth' => 1920,
                        'maxHeight' => 1280
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

        // Google 360
        $this->add([
            'name' => 'google_360',
            'type' => 'text',
            'options' => [
                'label' => 'Google 360'
            ],
        ]);

        // Validate Meta Title
        $inputFilter->add([
            'name' => 'google_360',
            'required' => false,
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
                        'max' => 160
                    ]
                ]
            ]
        ]);

        
        
        // Is Vip.
        $this->add([
            'name' => 'vip_offer',
            'type' => 'select',
            'options' => [                
                'value_options' => array(
                    '0' => 'Не',
                    '1' => 'Да'
                )
            ],
        ]);
        $inputFilter->add(array(
            'name' => 'vip_offer',
            'required' => false,
        ));
        
        // Is Top.
        $this->add([
            'name' => 'top_offer',
            'type' => 'select',
            'options' => [                
                'value_options' => array(
                    '0' => 'Не',
                    '1' => 'Да'
                )
            ],
        ]);
        $inputFilter->add(array(
            'name' => 'top_offer',
            'required' => false,
        ));
        
        
        // Is Chat.
        $this->add([
            'name' => 'chat_offer',
            'type' => 'select',
            'options' => [                
                'value_options' => array(
                    '0' => 'Не',
                    '1' => 'Да'
                )
            ],
        ]);
        $inputFilter->add(array(
            'name' => 'chat_offer',
            'required' => false,
        ));
        
        // Is Schema.
        $this->add([
            'name' => 'schema_offer',
            'type' => 'select',
            'options' => [                
                'value_options' => array(
                    '0' => 'Не',
                    '1' => 'Да'
                )
            ],
        ]);
        $inputFilter->add(array(
            'name' => 'schema_offer',
            'required' => false,
        ));      
        
        // Weeks.
        $this->add([
            'name' => 'weeks',
            'type' => 'select',
            'options' => [
                'value_options' => $weeks,
                'label' => 'Направи офертата активна за период от:'
            ],
        ]);

        // Extra Weeks.
        $this->add([
            'name' => 'extra_weeks',
            'type' => 'select',
            'options' => [
                'value_options' => $weeks,
                'label' => 'Направи офертата със специален статус за период от:'
            ],
        ]);
        
        $this->setInputFilter($inputFilter);
    }

}
