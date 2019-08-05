<?php
namespace User\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\I18n\Translator;

/**
 * Class OfferCreateForm
 * @package User\Form
 */
class OfferCreateForm extends Form {

    public function __construct($offerTypes, $cities, $neighbourhoods, $propertyTypes, $buildingTypes, $heatingSystems, $constructionYears, $currencies, $weeks, $parcelTypes, Translator $translator, $hasOldOffer, $hasExternalPanorama) {
        parent::__construct('createOfferForm');

        $this->setAttribute('method', 'post');

        $inputFilter = new InputFilter();

        $lang = $_SESSION['lang'];
        $translator->addTranslationFile("phparray", './module/Application/language/lang.array.' . $lang . '.php');

        // Offer Type Id.
        $this->add([
            'name' => 'offer_type_id',
            'type' => 'select',
            'options' => [
                'value_options' => $offerTypes
            ],
        ]);

        // City Id.
        $this->add([
            'name' => 'city_id',
            'type' => 'select',
            'options' => [
                'value_options' => $cities
            ],
        ]);

        // Neightbourhood Id.
        $this->add([
            'name' => 'neighbourhood_id',
            'type' => 'select',
            'options' => [
                'value_options' => $neighbourhoods
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
                'label' => 'Street'
            ],
        ]);

        // Property Type Id.
        $this->add([
            'name' => 'property_type_id',
            'type' => 'select',
            'options' => [
                'value_options' => $propertyTypes
            ],
        ]);

        // Building Type Id.
        $this->add([
            'name' => 'building_type_id',
            'type' => 'select',
            'options' => [
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
                'value_options' => $currencies
            ],
        ]);

        // Price.
        $this->add(array(
            'name' => 'price',
            'type' => 'number',
            'options' => array(
                'label' => 'Price'
            )
        ));
        $inputFilter->add(array(
            'name' => 'price',
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                ),
                array(
                    'name' => 'Int'
                ),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'translator' => $translator,
                    ),
                    'break_chain_on_failure' => true,
                ),
                array(
                    'name' => 'between',
                    'options' => array(
                        'translator' => $translator,
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
                'label' => 'Area'
            )
        ));
        $inputFilter->add(array(
            'name' => 'area',
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                ),
                array(
                    'name' => 'Int'
                ),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'translator' => $translator,
                    ),
                    'break_chain_on_failure' => true,
                ),
                array(
                    'name' => 'between',
                    'options' => array(
                        'translator' => $translator,
                        'min' => 1,
                        'max' => 100000,
                    )
                )
            )
        ));

        // Floor.
        $this->add(array(
            'name' => 'floor',
            'type' => 'select',
            'attributes' => array(
                'value'  => 1,
            ),
            'options' => array(
                'label' => 'Floor',
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
                        'translator' => $translator,
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
                'label' => 'Total Rooms',
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
                        'translator' => $translator,
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
                'label' => 'Bathrooms',
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
                        'translator' => $translator,
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
                'label' => 'Parking Slots',
                'value_options' =>  array_merge(array(0 => 'Without'),array_combine(range(1,10), range(1,10)))
            )
        ));
        $inputFilter->add(array(
            'name' => 'parking_slots',
            'required' => false,            
            'validators' => array(
                array(
                    'name' => 'between',
                    'options' => array(
                        'translator' => $translator,
                        'min' => 0,
                        'max' => 99,
                    )
                )
            )
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
                'label' => 'Information',
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
                        'translator' => $translator,
                        'encoding' => 'UTF-8',
                        'min' => 0,
                        'max' => 4096
                    )
                )
            )
        ));

        // Photographer Address
        $this->add(array(
            'name' => 'photographer_address',
            'type' => 'text',
            'attributes' => array(
                'id' => 'photographer_address'
            ),
            'options' => array(
                'label' => 'Photographer Address',
            )
        ));
        if ($hasOldOffer == false && $hasExternalPanorama == false) {
            $inputFilter->add(array(
                'name' => 'photographer_address',
//            'required' => false,
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
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'translator' => $translator,
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 255
                        )
                    )
                )
            ));
        }
        // Photographer Appointment
        $this->add(array(
            'name' => 'photographer_appointment',
            'type' => 'text',
            'attributes' => array(
                'id' => 'photographer_appointment'
            ),
            'options' => array(
                'label' => 'Photographer Appointment',
            )
        ));
        if ($hasOldOffer == false && $hasExternalPanorama == false) {
            $inputFilter->add(array(
                'name' => 'photographer_appointment',
//            'required' => true,
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
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'translator' => $translator,
                            'encoding' => 'UTF-8',
                            'min' => 10,
                            'max' => 32
                        )
                    )
                )
            ));
        }
        // Weeks.
        $this->add([
            'name' => 'weeks',
            'type' => 'select',
            'options' => [
                'value_options' => $weeks
            ],
        ]);

        // Extra Weeks.
        $this->add([
            'name' => 'extra_weeks',
            'type' => 'select',
            'options' => [
                'value_options' => $weeks
            ],
        ]);

        // Yard.
        $this->add(array(
            'name' => 'yard',
//            'type' => 'number',
            'attributes' => array(
                'id' => 'yard'
            ),
            'options' => array(
                'label' => 'Yard',
            )
        ));
        $inputFilter->add(array(
            'name' => 'yard',
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
                    'name' => 'between',
                    'options' => array(
                        'translator' => $translator,
                        'min' => 1,
                        'max' => 9999999,
                    )
                )
            )
        ));

        // Yard Shot.
        $this->add(array(
            'name' => 'yard_shot',
            'type' => 'checkbox',
            'attributes' => array(
                'id' => 'yard_shot'
            ),
            'options' => array(
                'label' => 'Yard Shot',
            )
        ));
        $inputFilter->add(array(
            'name' => 'yard_shot',
            'required' => false
        ));

        // External panorama
        $this->add(array(
            'name' => 'external_panorama',
            'type' => 'checkbox',
            'attributes' => array(
                'id' => 'external_panorama'
            ),
            'options' => array(
                'label' => 'External panorama',
            )
        ));
        $inputFilter->add(array(
            'name' => 'external_panorama',
            'required' => false
        ));

        $this->setInputFilter($inputFilter);
    }

}
