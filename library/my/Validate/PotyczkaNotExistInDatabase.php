<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NotExistInDatabase
 *
 * @author m.osik2
 */
class My_Validate_PotyczkaNotExistInDatabase extends Zend_Validate_Abstract {
    
    const EXISTS = 'exists';
    
    protected $_messageTemplates = array(
        self::EXISTS => 'Taka potyczka już istnieje w bazie danych'
    );
    
    public function isValid($value, $context = null) {
        $runda = My_Functions::getRundaForDate($context['data']);
        $Potyczka = new Application_Model_DbTable_Potyczka();
        
        if(is_null($runda->data_do)) {
            $potyczka = $Potyczka->fetchRow($Potyczka->select()
                    ->where('gracz1 = ? OR gracz2 = ?', $context['gracz1'])
                    ->where('gracz1 = ? OR gracz2 = ?', $context['gracz2'])
                    ->where('data >= ?', $runda->data_od)
                    );
        } else {
            $potyczka = $Potyczka->fetchRow($Potyczka->select()
                    ->where('gracz1 = ? OR gracz2 = ?', $context['gracz1'])
                    ->where('gracz1 = ? OR gracz2 = ?', $context['gracz2'])
                    ->where('data >= ?', $runda->data_od)
                    ->where('data <= ?', $runda->data_do)
                    );
        }
        
        if(!isset($potyczka)) { return true; }
        $this->_error(self::EXISTS);
        return false;
    }

}
