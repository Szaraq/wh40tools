<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class My_Validate_DateAfter extends Zend_Validate_Abstract {
    
    const DATE_INVALID = 'dateInvalid';
    protected $date;
    
    function __construct($date) {
        $this->date = $date;
    }

    protected $_messageTemplates = array(
        self::DATE_INVALID => "%value% nie jest późniejsza od początku ostatniej rundy: %date%"
    );
    
    protected $_messageVariables = array(
        'date' => 'date'
    );

    public function isValid($value) {
        $this->_setValue($value);

        // expecting $value to be YYYY-MM-DD
        if ($value <= $this->date) {
            $this->_error(self::DATE_INVALID);
            return false;
        }

        return true;
    }

}