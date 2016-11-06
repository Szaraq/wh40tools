<?php

class Application_Form_MgmtNowaRunda extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $mustBeAData = new Zend_Validate_Date();
        $mustBeAData->setMessages(array(Zend_Validate_Date::FALSEFORMAT => 'Musi być datą yyyy-mm-dd',
                                        Zend_Validate_Date::INVALID_DATE => 'Musi być datą yyyy-mm-dd'));
        $lastRundaDate = My_Functions::getActualRunda()->data_od;
        $mustBeAfterLastRunda = new My_Validate_DateAfter($lastRundaDate);
        $this->addElement(
                'text',
                'data',
                array(
                    'label' => 'Data',
                    'value' => date('Y-m-d'),
                    'required' => true,
                    'validators' => array(
                        $mustBeAData,
                        new Zend_Validate_NotEmpty(),
                        $mustBeAfterLastRunda
                    )
                )
                );
        $this->getElement('data')
                ->setAttribs(array('data-role' => 'date', 'data-inline' => 'false'))
                ->getValidator('NotEmpty')->setMessages(array(Zend_Validate_NotEmpty::IS_EMPTY => 'Musisz wpisać datę'));
        
        $greaterThanZero = new Zend_Validate_GreaterThan(0);
        $greaterThanZero->setMessages(array(Zend_Validate_GreaterThan::NOT_GREATER => 'Musi być większe niż zero'));
        $this->addElement(
                'text',
                'punkty',
                array(
                    'label' => 'Punkty',
                    'required' => true,
                    'validators' => array(
                        new Zend_Validate_Int(),
                        $greaterThanZero
                    )
                )
                );
        
        $this->addElement(
                'submit',
                'wyslij',
                array(
                    'label' => 'Dodaj rundę'
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

