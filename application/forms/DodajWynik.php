<?php

class Application_Form_DodajWynik extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $gracze = My_Functions::getGracze()->toArray();
        $options_gracze = My_Functions::get2d($gracze, 'ksywa');
        
        $this->addElement(
                'select',
                'gracz1',
                array(
                    'label' => 'Gracz 1',
                    'multioptions' => $options_gracze,
                    'value' => '',
                    'required' => true
                )
                );
        $this->addElement(
                'select',
                'gracz2',
                array(
                    'label' => 'Gracz 2',
                    'multioptions' => $options_gracze,
                    'value' => '',
                    'required' => true,
                    'validators' => array(
                        new My_Validate_NotEquals('gracz2'),
                        new My_Validate_PotyczkaNotExistInDatabase()
                    )
                )
                );
        $this->gracz2->getValidator("My_Validate_NotEquals")->setMessages(array(My_Validate_NotEquals::EQUALS => 'Gracz1 musi się różnić od Gracza2'));
        
        $Armie = new Application_Model_DbTable_Armie();
        $armie = $Armie->fetchAll()->toArray();
        $options_armie = My_Functions::get2d($armie, 'nazwa');
        $this->addElement(
                'select',
                'armia1',
                array(
                    'multioptions' => $options_armie,
                    'value' => '',
                    'required' => true
                )
                );
        $this->addElement(
                'select',
                'armia2',
                array(
                    'multioptions' => $options_armie,
                    'value' => '',
                    'required' => true
                )
                );
        
        $greaterThanZero = new Zend_Validate_GreaterThan(0);
        $greaterThanZero->setMessages(array(Zend_Validate_GreaterThan::NOT_GREATER => 'Musi być większe od zera'));
        $mustBeAnInteger = new Zend_Validate_Int();
        $mustBeAnInteger->setMessages(array(Zend_Validate_Int::NOT_INT => 'Musi być liczbą całkowitą'));
        $this->addElement(
                'text',
                'wynik1',
                array(
                    'label' => 'Punkty',
                    'value' => '0',
                    'required' => true,
                    'validators' => array(
                        $greaterThanZero,
                        $mustBeAnInteger
                    )
                )
                );
        $this->addElement(
                'text',
                'wynik2',
                array(
                    'label' => 'Punkty',
                    'value' => '0',
                    'required' => true,
                    'validators' => array(
                        $greaterThanZero,
                        $mustBeAnInteger
                    )
                )
                );
        
        $Misje = new Application_Model_DbTable_Misje();
        $misje = $Misje->fetchAll('rodzaj = 1');
        $options_misje = My_Functions::get2d($misje, 'nazwa');
        $this->addElement(
                'select',
                'misja',
                array(
                    'label' => 'Misja',
                    'multioptions' => $options_misje,
                    'value' => '',
                    'required' => false
                )
                );
        
        $mustBeAData = new Zend_Validate_Date();
        $mustBeAData->setMessages(array(Zend_Validate_Date::FALSEFORMAT => 'Musi być datą yyyy-mm-dd',
                                        Zend_Validate_Date::INVALID_DATE => 'Musi być datą yyyy-mm-dd'));
        $this->addElement(
                'text',
                'data',
                array(
                    'label' => 'Data',
                    'value' => date('Y-m-d'),
                    'required' => true,
                    'validators' => array(
                        $mustBeAData
                    )
                )
                );
        
        $this->addElement(
                'textarea',
                'uwagi',
                array(
                    'label' => 'Punkty',
                    'value' => '',
                    'required' => false,
                    'placeholder' => 'Tu możesz wpisać swoje różne głupie uwagi'
                )
                );
        
        $this->addElement(
                'submit',
                'wyslij',
                array(
                    'label' => 'Wyślij'
                )
                );
    }


}

