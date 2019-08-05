<?php
namespace Application\Model\Base;

use Application\Model\BaseModel;

/**
 * Grid visual table settings
 */
class BaseGridSettings extends BaseModel
{

    /**
     * Defines model fields and set default values
     */
    function __construct($pData = null)
    {
        $this->field = array(
            'pagenum' => '0',
            'pagesize' => '20',
            'filterscount' => '',
            'filtervalue0' => '',
            'filtervalue1' => '',
            'filtervalue2' => '',
            'filtervalue3' => '',
            'filtervalue4' => '',
            'filtervalue5' => '',
            'filtervalue6' => '',
            'filtervalue7' => '',
            'filtervalue8' => '',
            'filtervalue9' => '',
            'filtervalue10' => '',
            'filtercondition0' => '',
            'filtercondition1' => '',
            'filtercondition2' => '',
            'filtercondition3' => '',
            'filtercondition4' => '',
            'filtercondition5' => '',
            'filtercondition6' => '',
            'filtercondition7' => '',
            'filtercondition8' => '',
            'filtercondition9' => '',
            'filtercondition10' => '',
            'filterdatafield0' => '',
            'filterdatafield1' => '',
            'filterdatafield2' => '',
            'filterdatafield3' => '',
            'filterdatafield4' => '',
            'filterdatafield5' => '',
            'filterdatafield6' => '',
            'filterdatafield7' => '',
            'filterdatafield8' => '',
            'filterdatafield9' => '',
            'filterdatafield10' => '',
            'filteroperator0' => '',
            'filteroperator1' => '',
            'filteroperator2' => '',
            'filteroperator3' => '',
            'filteroperator4' => '',
            'filteroperator5' => '',
            'filteroperator6' => '',
            'filteroperator7' => '',
            'filteroperator8' => '',
            'filteroperator9' => '',
            'filteroperator10' => '',
            'sortdatafield' => '',
            'sortorder' => ''
        );

        if (is_array($pData)) {
            $this->exchangeArray($pData);
        }
    }

}