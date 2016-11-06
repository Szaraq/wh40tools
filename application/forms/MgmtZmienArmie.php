<?php

class Application_Form_MgmtZmienArmie extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $gracze = My_Functions::getGracze();
        $Armie = new Application_Model_DbTable_Armie();
        $armie = $Armie->fetchAll()->toArray();
        $options_armie = My_Functions::get2d($armie, 'nazwa');
        
        foreach($gracze as $gracz) {
            $this->addElement(
                'select',
                $gracz->ksywa,
                array(
                    'label' => $gracz->ksywa,
                    'multioptions' => $options_armie,
                    'value' => $gracz->armia,
                    'required' => false
                )
                );
            $this->getElement($gracz->ksywa)->addDecorator(array('div' => 'HtmlTag'), array('tag' => 'div', 'style' => 'width: 60%; margin: auto'));
        }
        
        $this->addElement(
                'submit',
                'wyslij',
                array(
                    'label' => 'Ustaw armie'
                )
                );
        $this->getElement("wyslij")->addDecorator(array('div' => 'HtmlTag'), array('tag' => 'div', 'style' => 'padding-left: 60%'));
        $this->getElement('wyslij')->setAttrib('data-theme', 'c');
        $this->setDecorators(array(
            'FormElements',
            'Form',
        ));
        $this->getDecorator('Form')->setOption('data-ajax', 'false');
    }


}

