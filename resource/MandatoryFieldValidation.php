<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 6/19/2018
 * Time: 8:13 AM
 */

class MandatoryFieldException extends ServerException
{
        private $missingFields ;
        public  function __construct($missingFields)
        {
            $this->missingFields = $missingFields;
            parent::__construct("Missing required Fields - ".implode(', ', $this->missingFields));
        }
}

class MandatoryFieldValidation
{
    private $fields;
    private $values;
    public function validate() {
        $missingFields = [];
        foreach($this->fields as $fieldName=>$displayLabel) {
            if (!array_key_exists($fieldName,$this->values) || empty($this->values[$fieldName]))
                $missingFields[] = $displayLabel;
        }
        if (!empty($missingFields))
            throw new MandatoryFieldException($missingFields);
    }

    public function __construct($fields,$values) {
        $this->fields = $fields;
        $this->values = $values;
    }
}

