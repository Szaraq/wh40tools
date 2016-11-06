<?php

class Application_Form_MgmtUsunRozpy extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->addElement(
                'checkbox',
                'naStale',
                array(
                    'label' => 'Usunąć na stałe?'
                )
                );
        $this->getElement("naStale")->addDecorator(array('div' => 'HtmlTag'), array('tag' => 'div', 'style' => 'padding-right: 60%'));
        
        $this->addElement(
                'submit',
                'wyslij',
                array(
                    'label' => 'Usuń rozpy'
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

