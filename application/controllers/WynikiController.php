<?php

class WynikiController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $year = $this->getRequest()->getParam('year');
        if(is_null($year)) { $year = date('Y'); }
        $this->view->year = $year;
        $this->view->form = new Application_Form_DodajWynik();
        $rundy = My_Functions::getRundyForYear($year);
        
        $potyczki_out = array();
        $count = $rundy->count();
        foreach($rundy as $r) {
            $potyczki_out[$count--] = My_Functions::getPotyczkiForRunda($r);
        }
        $this->view->potyczki = $potyczki_out;
        
        $url = $this->view->url(array('action' => 'upload'));
        $this->view->form->setAction($url);
        
        $wyniki = $this->obliczWyniki($year);
        $this->view->wyniki = $wyniki;
        $this->view->rozegrane = $this->obliczRozegraneGry();
        
        $podium = My_Functions::maxNitems($wyniki, 3);
        $count2 = 0;
        foreach($podium as $k=>$p) {
            $podium[$count2++] = $podium[$k];
            unset($podium[$k]);
        }
        
        $this->view->podium = $podium;
        
        $this->view->potyczki_moje = $this->getMojePotyczki();
        
        $Runda = new Application_Model_DbTable_Rundy();
        $this->view->lata = $Runda->fetchAll($Runda->select()
                ->distinct()
                ->from($Runda->info('name'), 'Year(data_od)')
                ->order('data_od DESC')
                ->group('Year(data_od)'))
                ->toArray();
    }

    private function getMojePotyczki()
    {
        My_CookieHelper::getInstance()->setCookie('id', '1');
        if(My_CookieHelper::getInstance()->hasCookie('id')) {
            $cookieId = My_CookieHelper::getInstance()->getCookie('id');
            $Potyczka = new Application_Model_DbTable_Potyczka();
            $potyczki_moje = $Potyczka->fetchAll($Potyczka->select()->from($Potyczka->info('name'), array('id'))->where('cookie = ?', $cookieId))->toArray();
            $out = array();
            foreach($potyczki_moje as $pm) { $out[] = $pm['id']; }
            return $out;
        } else {
            return null;
        }
    }

    private function obliczWyniki($year = -1)
    {
        if($year == -1) { $year = date("Y"); }
        $gracze = My_Functions::getGracze();
        $out = array();
        foreach($gracze as $g) {
            $out[$g->ksywa] = $this->obliczWynikDlaGracza($year, $g);
        }
        return $out;
    }

    private function obliczWynikDlaGracza($year, $gracz)
    {
        $rundy = My_Functions::getRundyForYear($year);
        $runda_min = $rundy->getRow($rundy->count()-1)->data_od;
        $runda_max = $rundy->getRow(0)->data_do;
        if(is_null($runda_max)) { $runda_max = "2999-01-01"; }
        
        $out = 0;
        
        $Potyczka = new Application_Model_DbTable_Potyczka();
        $potyczki = $Potyczka->fetchAll($Potyczka->select()
                ->where('gracz1 = ?', $gracz->ksywa)
                ->where('wynik1 > wynik2')
                ->where('data >= ?', $runda_min)
                ->where('data <= ?', $runda_max));
        $out += $potyczki->count() * 3;
        
        $potyczki = $Potyczka->fetchAll($Potyczka->select()
                ->where('gracz2 = ?', $gracz->ksywa)
                ->where('wynik1 < wynik2')
                ->where('data >= ?', $runda_min)
                ->where('data <= ?', $runda_max));
        $out += $potyczki->count() * 3;
        
        $potyczki = $Potyczka->fetchAll($Potyczka->select()
                ->where('gracz1 = ? OR gracz2 = ?', $gracz->ksywa)
                ->where('wynik1 = wynik2')
                ->where('data >= ?', $runda_min)
                ->where('data <= ?', $runda_max));
        $out += $potyczki->count();
        
        return $out;
    }

    private function obliczRozegraneGry()
    {
        return $this->getRozegraneGry();
    }

    private function getRozegraneGry()
    {
        $Runda = new Application_Model_DbTable_Rundy();
        $runda = $Runda->fetchRow('data_do IS NULL');
        $gracze = My_Functions::getGracze();
        $Potyczka = new Application_Model_DbTable_Potyczka();
        $out = array();
        
        foreach($gracze as $g) {
            $potyczki = $Potyczka->fetchAll($Potyczka->select()
                    ->where('gracz1 = ? OR gracz2 = ?', $g->ksywa)
                    ->where('data >= ?', $runda->data_od)
                    );
            $out[$g->ksywa] = $potyczki;
        }
        
        return $out;
    }

    public function uploadAction()
    {
        $this->indexAction();
        $this->_helper->viewRenderer('index');
        $form = new Application_Form_DodajWynik();
        $this->view->form = $form;
        $cookieHelper = My_CookieHelper::getInstance();
        
        if($this->getRequest()->isPost()) {
            if($form->isValid($this->getRequest()->getPost())) {
                $Potyczka = new Application_Model_DbTable_Potyczka();
                $values = $form->getValues();
                $data = array();
                foreach($values as $k => $v) {
                    $data[$k] = $v;
                }
                if($cookieHelper->hasCookie('id')) {
                    $data['cookie'] = $cookieHelper->getCookie('id');
                } else {
                    $id = $cookieHelper->generateId();
                    $cookieHelper->setCookie('id', $id);
                    $data['cookie'] = $id;
                }
                $Potyczka->createRow($data)->save();
                return $this->_helper->redirector('index');
            }
        }
        
        $this->view->form = $form;
    }

    public function ktoNieGralAction()
    {
        $this->_helper->layout()->setLayout('popup_layout');
        $this->view->gry = $this->getKtoNieGral();
    }

    private function getKtoNieGral()
    {
        $gracze_in = My_Functions::getGracze()->toArray();
        
        /* Wyrzucam z wyniku armię i data_do */
        foreach($gracze_in as $g) {
            $gracze[$g['ksywa']] = $g['ksywa'];
        }
        $all = array();
        
        /* Lista wszystkich możliwych gier */
        foreach($gracze as $g) {
            $all[$g] = $gracze;
        }
        
        /* Eliminuję opcje gry "sam ze sobą" */
        foreach ($all as $k=>$a) {
            unset($all[$k][$k]);
        }
        
        /* Eliminuję rozegrane gry */
        $rozegrane = $this->getRozegraneGry();
        foreach($rozegrane as $r) {
            foreach($r as $gry) {
                unset($all[$gry->gracz1][$gry->gracz2]);
                unset($all[$gry->gracz2][$gry->gracz1]);
            }
        }
        
        /* Eliminuję gry powtarzane: X z Y zostaje, wyrzucam Y z X */
        $out = array();
        foreach($all as $k=>$a) {
            foreach($a as $o) {
                if(!isset($out[$o][$k])) { $out[$k][$o] = $o; }
            }
        }
        
        return $out;
    }

    public function detailsAction()
    {
        $this->_helper->layout()->setLayout('popup_layout');
        $id_potyczki = $this->getParam('id');
        $Potyczka = new Application_Model_DbTable_Potyczka();
        $potyczka = $Potyczka->fetchRow($Potyczka->select()->where('id = ?', $id_potyczki));
        $this->view->potyczka = $potyczka;
    }

    public function deleteAction()
    {
        $Potyczka = new Application_Model_DbTable_Potyczka();
        $Potyczka->fetchRow($Potyczka->select()->where('id = ?', $this->getParam('id')))->delete();
        return $this->_helper->redirector('index');
    }


}









