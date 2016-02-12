<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NotEquals
 *
 * @author m.osik2
 */
class My_Validate_NotEquals extends Zend_Validate_Abstract {
    
    const EQUALS = 'different';
    
    protected $_messageTemplates = array(
        self::EQUALS=> 'Musi się od siebie różnić'
    );
    
    protected $fieldname;
    
    public function __construct($field) {
        $this->fieldname = $field;
    }
    
    public function isValid($value, $context = null) {
        if(!($value == $context[$this->fieldname])) {
            return true;
        }
        $this->_error(self::EQUALS);
        return false;
    }

}
