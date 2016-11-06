<?php

class Application_Form_MgmtAuth extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->addElement(
                'password',
                'pass',
                array(
                    'label' => 'Podaj hasło'
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

