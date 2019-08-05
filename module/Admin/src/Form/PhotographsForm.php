<?php

namespace Admin\Form;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Hydrator\Aggregate\AggregateHydrator;
use Zend\Hydrator\ClassMethods;

/**
 * Description of GalleryForm
 *
 */
class PhotographsForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('photograpsForm');

        // $this->setAttribute('method', 'post');

        //   $this->setAttribute('class', 'form-inline');

        // $inputFilter = new InputFilter();
        /*
                $this->add([
                    'name' => 'photographs',
                    'type' => 'select',
                    'class' => 'form-control',
                    'options' => [
                        'value_options' => [1,2,3,4,5,6,7,8,9,10]
                    ],
                ]);

                $this->setInputFilter($inputFilter);
            }*/
        $this->setHydrator(new ClassMethods());
        $id = new Element\Hidden('id');

        $photographs = array();


        $photographs1 = new Element\Select('photographs1');
        $photographs1->setAttribute('class', 'form-control ');
        $photographs1->setDisableInArrayValidator(true);

        $photographs2 = new Element\Select('photographs2');
        $photographs2->setAttribute('class', 'form-control ');
        $photographs2->setDisableInArrayValidator(true);

        $photographs3 = new Element\Select('photographs3');
        $photographs3->setAttribute('class', 'form-control ');
        $photographs3->setDisableInArrayValidator(true);

        $photographs4 = new Element\Select('photographs4');
        $photographs4->setAttribute('class', 'form-control ');
        $photographs4->setDisableInArrayValidator(true);

        $photographs5 = new Element\Select('photographs5');
        $photographs5->setAttribute('class', 'form-control ');
        $photographs5->setDisableInArrayValidator(true);


        $photographs6 = new Element\Select('photographs6');
        $photographs6->setAttribute('class', 'form-control ');
        $photographs6->setDisableInArrayValidator(true);

        $photographs7 = new Element\Select('photographs7');
        $photographs7->setAttribute('class', 'form-control ');
        $photographs7->setDisableInArrayValidator(true);

        $photographs8 = new Element\Select('photographs8');
        $photographs8->setAttribute('class', 'form-control ');
        $photographs8->setDisableInArrayValidator(true);

        $photographs9 = new Element\Select('photographs9');
        $photographs9->setAttribute('class', 'form-control ');
        $photographs9->setDisableInArrayValidator(true);

        $photographs10 = new Element\Select('photographs10');
        $photographs10->setAttribute('class', 'form-control ');
        $photographs10->setDisableInArrayValidator(true);


        $photographs11 = new Element\Select('photographs11');
        $photographs11->setAttribute('class', 'form-control ');
        $photographs11->setDisableInArrayValidator(true);

        $photographs12 = new Element\Select('photographs12');
        $photographs12->setAttribute('class', 'form-control ');
        $photographs12->setDisableInArrayValidator(true);

        $photographs13 = new Element\Select('photographs13');
        $photographs13->setAttribute('class', 'form-control ');
        $photographs13->setDisableInArrayValidator(true);

        $photographs14 = new Element\Select('photographs14');
        $photographs14->setAttribute('class', 'form-control ');
        $photographs14->setDisableInArrayValidator(true);

        $photographs15 = new Element\Select('photographs15');
        $photographs15->setAttribute('class', 'form-control ');
        $photographs15->setDisableInArrayValidator(true);

        $photographs16 = new Element\Select('photographs16');
        $photographs16->setAttribute('class', 'form-control ');
        $photographs16->setDisableInArrayValidator(true);

        $photographs17 = new Element\Select('photographs17');
        $photographs17->setAttribute('class', 'form-control ');
        $photographs17->setDisableInArrayValidator(true);

        $photographs18 = new Element\Select('photographs18');
        $photographs18->setAttribute('class', 'form-control ');
        $photographs18->setDisableInArrayValidator(true);

        $photographs19 = new Element\Select('photographs19');
        $photographs19->setAttribute('class', 'form-control ');
        $photographs19->setDisableInArrayValidator(true);

        $photographs20 = new Element\Select('photographs20');
        $photographs20->setAttribute('class', 'form-control ');
        $photographs20->setDisableInArrayValidator(true);

        $photographs21 = new Element\Select('photographs21');
        $photographs21->setAttribute('class', 'form-control ');
        $photographs21->setDisableInArrayValidator(true);

        $photographs22 = new Element\Select('photographs22');
        $photographs22->setAttribute('class', 'form-control ');
        $photographs22->setDisableInArrayValidator(true);

        $photographs23 = new Element\Select('photographs23');
        $photographs23->setAttribute('class', 'form-control ');
        $photographs23->setDisableInArrayValidator(true);

        $photographs24 = new Element\Select('photographs24');
        $photographs24->setAttribute('class', 'form-control ');
        $photographs24->setDisableInArrayValidator(true);

        $photographs25 = new Element\Select('photographs25');
        $photographs25->setAttribute('class', 'form-control ');
        $photographs25->setDisableInArrayValidator(true);

        $photographs26 = new Element\Select('photographs26');
        $photographs26->setAttribute('class', 'form-control ');
        $photographs26->setDisableInArrayValidator(true);

        $photographs27 = new Element\Select('photographs27');
        $photographs27->setAttribute('class', 'form-control ');
        $photographs27->setDisableInArrayValidator(true);

        $photographs28 = new Element\Select('photographs28');
        $photographs28->setAttribute('class', 'form-control ');
        $photographs28->setDisableInArrayValidator(true);

        $photographs29 = new Element\Select('photographs29');
        $photographs29->setAttribute('class', 'form-control ');
        $photographs29->setDisableInArrayValidator(true);


        $photographs30 = new Element\Select('photographs30');
        $photographs30->setAttribute('class', 'form-control ');
        $photographs30->setDisableInArrayValidator(true);

        $photographs31 = new Element\Select('photographs31');
        $photographs31->setAttribute('class', 'form-control ');
        $photographs31->setDisableInArrayValidator(true);

        $photographs32 = new Element\Select('photographs32');
        $photographs32->setAttribute('class', 'form-control ');
        $photographs32->setDisableInArrayValidator(true);

        $photographs33 = new Element\Select('photographs33');
        $photographs33->setAttribute('class', 'form-control ');
        $photographs33->setDisableInArrayValidator(true);

        $photographs34 = new Element\Select('photographs34');
        $photographs34->setAttribute('class', 'form-control ');
        $photographs34->setDisableInArrayValidator(true);

        $photographs35 = new Element\Select('photographs35');
        $photographs35->setAttribute('class', 'form-control ');
        $photographs35->setDisableInArrayValidator(true);

        $photographs36 = new Element\Select('photographs36');
        $photographs36->setAttribute('class', 'form-control ');
        $photographs36->setDisableInArrayValidator(true);

        $photographs37 = new Element\Select('photographs37');
        $photographs37->setAttribute('class', 'form-control ');
        $photographs37->setDisableInArrayValidator(true);

        $photographs38 = new Element\Select('photographs38');
        $photographs38->setAttribute('class', 'form-control ');
        $photographs38->setDisableInArrayValidator(true);

        $photographs39 = new Element\Select('photographs39');
        $photographs39->setAttribute('class', 'form-control ');
        $photographs39->setDisableInArrayValidator(true);

        $photographs40 = new Element\Select('photographs40');
        $photographs40->setAttribute('class', 'form-control ');
        $photographs40->setDisableInArrayValidator(true);

        $photographs41 = new Element\Select('photographs41');
        $photographs41->setAttribute('class', 'form-control ');
        $photographs41->setDisableInArrayValidator(true);

        $photographs42 = new Element\Select('photographs42');
        $photographs42->setAttribute('class', 'form-control ');
        $photographs42->setDisableInArrayValidator(true);

        $photographs43 = new Element\Select('photographs43');
        $photographs43->setAttribute('class', 'form-control ');
        $photographs43->setDisableInArrayValidator(true);

        $photographs44 = new Element\Select('photographs44');
        $photographs44->setAttribute('class', 'form-control ');
        $photographs44->setDisableInArrayValidator(true);

        $photographs45 = new Element\Select('photographs45');
        $photographs45->setAttribute('class', 'form-control ');
        $photographs45->setDisableInArrayValidator(true);

        $photographs46 = new Element\Select('photographs46');
        $photographs46->setAttribute('class', 'form-control ');
        $photographs46->setDisableInArrayValidator(true);

        $photographs47 = new Element\Select('photographs47');
        $photographs47->setAttribute('class', 'form-control ');
        $photographs47->setDisableInArrayValidator(true);

        $photographs48= new Element\Select('photographs48');
        $photographs48->setAttribute('class', 'form-control ');
        $photographs48->setDisableInArrayValidator(true);

        $photographs49 = new Element\Select('photographs49');
        $photographs49->setAttribute('class', 'form-control ');
        $photographs49->setDisableInArrayValidator(true);


        $photographs50 = new Element\Select('photographs50');
        $photographs50->setAttribute('class', 'form-control ');
        $photographs50->setDisableInArrayValidator(true);

        $photographs51 = new Element\Select('photographs51');
        $photographs51->setAttribute('class', 'form-control ');
        $photographs51->setDisableInArrayValidator(true);

        $photographs52 = new Element\Select('photographs52');
        $photographs52->setAttribute('class', 'form-control ');
        $photographs52->setDisableInArrayValidator(true);

        $photographs53 = new Element\Select('photographs53');
        $photographs53->setAttribute('class', 'form-control ');
        $photographs53->setDisableInArrayValidator(true);

        $photographs54 = new Element\Select('photographs54');
        $photographs54->setAttribute('class', 'form-control ');
        $photographs54->setDisableInArrayValidator(true);





        /* $button = new Element\Button('button');
         $button->setLabel('Edit');
         $button->setAttribute('class', 'btn btn-danger');
         $button->setValue('Edit');*/


        $submit = new Element\Submit('submit');
        $submit->setValue('Save');
        $submit->setAttribute('class', 'btn btn-primary');

        $this->add($id);

        $this->add($photographs1);
        $this->add($photographs2);
        $this->add($photographs3);
        $this->add($photographs4);
        $this->add($photographs5);
        $this->add($photographs6);
        $this->add($photographs7);
        $this->add($photographs8);
        $this->add($photographs9);
        $this->add($photographs10);
        $this->add($photographs11);
        $this->add($photographs12);
        $this->add($photographs13);
        $this->add($photographs14);
        $this->add($photographs15);
        $this->add($photographs16);
        $this->add($photographs17);
        $this->add($photographs18);
        $this->add($photographs19);
        $this->add($photographs20);
        $this->add($photographs21);
        $this->add($photographs22);
        $this->add($photographs23);
        $this->add($photographs24);
        $this->add($photographs25);
        $this->add($photographs26);
        $this->add($photographs27);
        $this->add($photographs28);
        $this->add($photographs29);
        $this->add($photographs30);
        $this->add($photographs31);
        $this->add($photographs32);
        $this->add($photographs33);
        $this->add($photographs34);
        $this->add($photographs35);
        $this->add($photographs36);
        $this->add($photographs37);
        $this->add($photographs38);
        $this->add($photographs39);
        $this->add($photographs40);
        $this->add($photographs41);
        $this->add($photographs42);
        $this->add($photographs43);
        $this->add($photographs44);
        $this->add($photographs45);
        $this->add($photographs46);
        $this->add($photographs47);
        $this->add($photographs48);
        $this->add($photographs49);
        $this->add($photographs50);
        $this->add($photographs51);
        $this->add($photographs52);
        $this->add($photographs53);
        $this->add($photographs54);


        // $this->add($button);
        $this->add($submit);

    }


}