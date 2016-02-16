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
        $ksywa = '';
        if(My_CookieHelper::getInstance()->hasCookie('ksywa')) { $ksywa = My_CookieHelper::getInstance()->getCookie('ksywa'); }
        
        $this->addElement(
                'select',
                'gracz',
                array(
                    'label' => '1. Wybierz gracza:',
                    'multioptions' => $options,
                    'value' => $ksywa,
                    'required' => true,
                    'validators' => array(array('NotEmpty'))
                )
                );
        $this->gracz->getValidator('NotEmpty')->setMessages(array(Zend_Validate_NotEmpty::IS_EMPTY => 'Musisz wybrać gracza'));
        
        $this->addElement(
                'file',
                'rozpa',
                array(
                    'label' => '2. Wybierz plik:',
                    'destination' => 'upload',
                    'required' => true,
                    'validators' => array(
                        array('Count', false, 2),
                        array('Size', false, 1024000),
                        array('Extension', false, 'html,rosz'),
                        array('Upload')
                    )
                )
                );
        $this->rozpa->getValidator('Count')->setMessages(array(
                Zend_Validate_File_Count::TOO_MANY => 'Możesz wybrać max 2 pliki',
                ));
        $this->rozpa->getValidator('Size')->setMessages(array(Zend_Validate_File_Size::TOO_BIG => 'Plik jest za duży'));
        $this->rozpa->getValidator('Extension')->setMessages(array(Zend_Validate_File_Extension::FALSE_EXTENSION => 'Plik może mieć tylko rozszerzenie "HTML" lub "rosz"'));
        $this->rozpa->getValidator('Upload')->setMessages(array(Zend_Validate_File_Upload::NO_FILE => 'Musisz wybrać rozpę'));
        
        $this->addElement(
                'submit',
                'wyslij',
                array(
                    'label' => 'Wyślij'
                )
                );
        
        
        $this->setDecorators(array(
            'FormElements',
            'Form',
            array(array('div' => 'HtmlTag'), array('tag' => 'div', 'style' => 'width: 60%; margin: auto')),
        ));
        $this->getDecorator('Form')->setOption('data-ajax', 'false');
        $this->addDisplayGroup(array($this->gracz, $this->rozpa), 'wybor');
        $this->addDisplayGroup(array($this->wyslij), 'sub');
        
        /*$this->getDisplayGroup('wybor')->addDecorators(array(
                array(array('div' => 'HtmlTag'), array('tag' => 'div', 'class' => 'ui-grid-b'))
                ));
        $this->getElement('gracz')->addDecorators(array(
                array(array('div' => 'HtmlTag'), array('tag' => 'div', 'class' => 'ui-block-a', 'style' => 'width: 50%'))
                ));
        $this->getElement('rozpa')->addDecorators(array(
                array(array('div' => 'HtmlTag'), array('tag' => 'div', 'class' => 'ui-block-b', 'style' => 'float: right; width: 50%'))
                ));
        
        $this->getDisplayGroup('wybor')->removeDecorator('DtDdWrapper');
        $this->getDisplayGroup('sub')->removeDecorator('DtDdWrapper');*/
        $this->getElement('wyslij')->setDecorators(array('ViewHelper'));
        $this->getElement('wyslij')->addDecorators(array(array(array('div' => 'HtmlTag'), array('tag' => 'div', 'style' => 'width: 50%; margin: auto'))));
        
        //var_dump($this->getDisplayGroup('sub')->getDecorators());
    }


}

