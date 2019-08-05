<?php
namespace Application\Model;

class BaseModel {
    
    // Holds all database table field names
    protected $field = Array();

    /**
     * Set values from array
     *
     * @param map $pData
     */
    public function exchangeArray($pData)
    {
        if (is_array($pData)) {
            foreach ($pData as $key => $value) {
                if (isset($this->field[$key])) {
                    $this->field[$key] = $pData[$key];
                }
            }
        }
    }

    /**
     * Sets field value if exists
     *
     * @param key $pKey
     * @param value $pValue
     */
    public function setField($pKey, $pValue)
    {
        if (isset($this->field[$pKey])) {
            $this->field[$pKey] = $pValue;
        }
    }

    /**
     * Gets field value
     *
     * @param key $pKey
     * @return value or null:
     */
    public function getField($pKey)
    {
        return $this->field[$pKey];
    }

    /**
     * Returns all data as array
     *
     * @return multitype:
     */
    public function toArray()
    {
        return $this->field;
    }
}
