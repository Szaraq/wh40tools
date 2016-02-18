<?php

class Application_Form_DodajWynik extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $gracze = My_Functions::getGracze()->toArray();
        $options_gracze = My_Functions::get2d($gracze, 'ksywa');
        $this->setName('dodawanie');
        
        $this->addElement(
                'select',
                'gracz1',
                array(
                    'label' => 'Gracz 1',
                    'multioptions' => $options_gracze,
                    'value' => '',
                    'required' => true,
                    'validators' => array(new Zend_Validate_NotEmpty())
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
                        new My_Validate_NotEquals('gracz1'),
                        new My_Validate_PotyczkaNotExistInDatabase(),
                        new Zend_Validate_NotEmpty()
                    )
                )
                );
        $this->gracz2->getValidator("My_Validate_NotEquals")->setMessages(array(My_Validate_NotEquals::EQUALS => 'Gracz1 musi się różnić od Gracza2'));
        $this->gracz2->getValidator("My_Validate_PotyczkaNotExistInDatabase")->setMessages(array(My_Validate_PotyczkaNotExistInDatabase::EXISTS => 'Taka potyczka już istnieje'));
        $this->gracz1->getValidator("NotEmpty")->setMessages(array(Zend_Validate_NotEmpty::IS_EMPTY => 'Musisz wybrać gracza'));
        $this->gracz2->getValidator("NotEmpty")->setMessages(array(Zend_Validate_NotEmpty::IS_EMPTY => 'Musisz wybrać gracza'));
        
        $Armie = new Application_Model_DbTable_Armie();
        $armie = $Armie->fetchAll()->toArray();
        $options_armie = My_Functions::get2d($armie, 'nazwa');
        $this->addElement(
                'select',
                'armia1',
                array(
                    'multioptions' => $options_armie,
                    'value' => '',
                    'required' => false
                )
                );
        $this->addElement(
                'select',
                'armia2',
                array(
                    'multioptions' => $options_armie,
                    'value' => '',
                    'required' => false
                )
                );
        
        $greaterThanZero = new Zend_Validate_GreaterThan(-1);
        $greaterThanZero->setMessages(array(Zend_Validate_GreaterThan::NOT_GREATER => 'Musi być większe lub równe zero'));
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
        $this->getElement('wynik1')->setAttrib('onclick', 'this.select();');
        $this->getElement('wynik2')->setAttrib('onclick', 'this.select();');
        
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
                        $mustBeAData,
                        new Zend_Validate_NotEmpty()
                    )
                )
                );
        $this->getElement('data')
                ->setAttribs(array('data-role' => 'date', 'data-inline' => 'false'))
                ->getValidator('NotEmpty')->setMessages(array(Zend_Validate_NotEmpty::IS_EMPTY => 'Musisz wpisać datę'));
        
        $this->addElement(
                'textarea',
                'uwagi',
                array(
                    'label' => 'Punkty',
                    'value' => '',
                    'required' => false,
                    'placeholder' => 'Tu możesz wpisać swoje różne głupie uwagi',
                    'filters' => array(new Zend_Filter_StripTags())
                )
                );
        
        $this->addElement(
                'submit',
                'wyslij',
                array(
                    'label' => 'Wyślij'
                )
                );
        $this->getElement('wyslij')->setAttrib('data-theme', 'c');
        
        $this->addDisplayGroup(array($this->gracz1, $this->gracz2), 'gracze')
                ->addDecorators(array(array(array('div' => 'HtmlTag'), array('tag' => 'div', 'class' => 'ui-grid-b'))))
                ->removeDecorator('DtDdWrapper');
        $this->addDisplayGroup(array($this->armia1, $this->armia2), 'armie')
                ->addDecorators(array(array(array('div' => 'HtmlTag'), array('tag' => 'div', 'class' => 'ui-grid-b'))))
                ->removeDecorator('DtDdWrapper');
        $this->addDisplayGroup(array($this->wynik1, $this->wynik2), 'wyniki')
                ->addDecorators(array(array(array('div' => 'HtmlTag'), array('tag' => 'div', 'class' => 'ui-grid-b'))))
                ->removeDecorator('DtDdWrapper');
        $this->addDisplayGroup(array($this->misja), 'misja_group')->removeDecorator('DtDdWrapper');
        $this->addDisplayGroup(array($this->data), 'data_group')->removeDecorator('DtDdWrapper');
        $this->addDisplayGroup(array($this->uwagi), 'uwagi_group')->removeDecorator('DtDdWrapper');
        $this->addDisplayGroup(array($this->wyslij), 'submit')->removeDecorator('DtDdWrapper');
        
        foreach($this->getDisplayGroups() as $group) {
            $group->removeDecorator('DtDdWrapper');
            $group->removeDecorator('HtmlTag');
        }
        $this->getElement('armia1')->removeDecorator('Label');
        $this->getElement('armia2')->removeDecorator('Label');
        //$this->getElement('wyslij')->removeDecorator('DtDdWrapper');
        
        $this->setDecorators(array(
            'FormElements',
            'Form',
            array(array('div' => 'HtmlTag'), array('tag' => 'div', 'style' => 'width: 60%; margin: auto')),
        ));
        $this->getDecorator('Form')->setOption('data-ajax', 'false');
        
        $this->rozmiescKoloSiebie($this->gracz1, $this->gracz2);
        $this->rozmiescKoloSiebie($this->armia1, $this->armia2);
        $this->rozmiescKoloSiebie($this->wynik1, $this->wynik2);
    }
    
    private function rozmiescKoloSiebie($element1, $element2) {
        $element1->addDecorators(array(
                array(array('div' => 'HtmlTag'), array('tag' => 'div', 'class' => 'ui-block-a', 'style' => 'width: 50%'))
                ));
        $element2->addDecorators(array(
                array(array('div' => 'HtmlTag'), array('tag' => 'div', 'class' => 'ui-block-b', 'style' => 'float: right; width: 50%'))
                ));
    }


}

