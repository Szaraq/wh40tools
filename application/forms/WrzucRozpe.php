<?php

class Application_Form_WrzucRozpe extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $gracze = My_Functions::getGracze()->toArray();
        $options = array('' => 'wybierz...');
        foreach($gracze as $g) {
            $options[$g['ksywa']] = $g['ksywa'];
        }
        
        $this->addElement(
                'select',
                'gracz',
                array(
                    'label' => 'Wybierz gracza:',
                    'multioptions' => $options,
                    'value' => '',
                    'required' => true
                )
                );
        
        $this->addElement(
                'file',
                'rozpa',
                array(
                    'label' => 'Wybierz plik:',
                    'destination' => 'upload',
                    'required' => true,
                    'validators' => array(
                        array('Count', false, 2),
                        array('Size', false, 1024000),
                        array('Extension', false, 'html,rosz')
                    )
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

