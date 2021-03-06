<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Functions
 *
 * @author m.osik2
 */
class My_Functions {
    
    public static function getGracze() {
        $Gracze = new Application_Model_DbTable_Gracze();
        $gracze = $Gracze->fetchAll($Gracze->select()->where('data_do IS NULL OR data_do > curdate()')->order('ksywa'));
        return $gracze;
    }
    
    public static function get2d($options, $dim) {
        $options_out = array('' => 'wybierz...');
        foreach($options as $opt) {
            $options_out[$opt[$dim]] = $opt[$dim];
        }
        return $options_out;
    }
    
    public static function getRundaForPotyczka($potyczka) {
        $Rundy = new Application_Model_DbTable_Rundy();
        if(get_class($potyczka) == "Zend_Db_Table_Rowset") { $potyczka = $potyczka->toArray(); }
        return $this->getRundaForDate($potyczk['data']);
    }
    
    public static function getRundyForYear($year = -1) {
        if($year == -1) { $year = date("Y"); }
        $Rundy = new Application_Model_DbTable_Rundy();
        if($year == date("Y")) {
            return $Rundy->fetchAll($Rundy->select()->where('Year(data_do) = ?', $year)->orWhere('data_do IS NULL')->order('data_od DESC'));
        }
        return $Rundy->fetchAll($Rundy->select()->where('Year(data_do) = ?', $year)->order('data_do DESC'));
    }
    
    /**
     * 
     * @param Zend_Db_Table_Row/Int $runda - wiersz rundy lub jej id
     * @return Zend_Db_Table_Rowset
     * @throws Zend_Exception
     */
    public static function getPotyczkiForRunda($runda) {
        if (is_int($runda)) {
            $Runda = new Application_Model_DbTable_Rundy();
            $runda = $Runda->fetchRow('id=?', $runda);
        } elseif (!(get_class($runda) == 'Zend_Db_Table_Row')) {
            throw new Zend_Exception('Argument musi być int lub Row');
        }
        
        $Potyczka = new Application_Model_DbTable_Potyczka();
        if(is_null($runda->data_do)) {
            $potyczki = $Potyczka->fetchAll($Potyczka->select()
                    ->where('data >= ?', $runda->data_od)
                    ->order('data DESC')
                    ->order('id DESC')
                    );
        } else {
            $potyczki = $Potyczka->fetchAll($Potyczka->select()
                    ->where('data >= ?', $runda->data_od)
                    ->where('data <= ?', $runda->data_do)
                    ->order('data DESC')
                    ->order('id DESC')
                    );
        }
        return $potyczki;
    }
    
    public static function getRundaForDate($date) {
        $Rundy = new Application_Model_DbTable_Rundy();
        $out = $Rundy->fetchRow($Rundy->select()
                ->where('data_od <= ?', $date)
                ->where('data_do >= ?', $date));
        if(!is_null($out)) { return $out; }
        return $Rundy->fetchRow('data_do IS NULL');
    }
    
    public static function getActualRunda() {
        $Rundy = new Application_Model_DbTable_Rundy();
        return $Rundy->fetchRow($Rundy->select()
                ->where('data_do IS NULL'));
    }
    
    public static function maxNitems($array, $n = 3){
        asort($array);
        return array_slice(array_reverse($array, true),0,$n, true);
    }
    
    public static function existsRozpaPoprzedniaRunda() {
        $gracze = self::getGracze();
        $url = new Zend_View_Helper_BaseUrl();
        foreach($gracze as $g) {
            if(file_exists($url->baseUrl('/upload/' . $g->ksywa . '2.html'))) { return true; }
        }
        return false;
    }
    
    public static function addNowaRunda($date, $punkty) {
        $Rundy = new Application_Model_DbTable_Rundy();
        $Rundy->getAdapter()->beginTransaction();
        
        $actual = self::getActualRunda();
        $actual->data_do = date('Y-m-d', strtotime('-1 day', strtotime($date)));
        $actual->save();
        
        $data = array(
            'data_od' => $date,
            'data_do' => null,
            'punkty' => $punkty
        );
        $Rundy->createRow($data)->save();
        
        try {
            $Rundy->getAdapter()->commit();
        } catch (Exception $ex) {
            $Rundy->getAdapter()->rollBack();
        }
    }
    
    /**
     * 
     * @param type $gracz
     * @param type $wersja 1 - nowa; 2 - stara
     */
    public static function usunPlik($gracz, $wersja, $ext) {
        $numerWersji = '';
        if($wersja == 2) { $numerWersji = '2'; }
        $path = Zend_Controller_Front::getInstance()->getBaseUrl() . '/upload/' .
                $gracz .
                $numerWersji .
                '.' .
                $ext;
        
        if(file_exists($path)) { unlink($path); }
    }
    
    public static function usunRozpy($naStale) {
        $gracze = self::getGracze();
        foreach($gracze as $gracz) {
            $ksywa = $gracz->ksywa;
            self::usunPlik($ksywa, 2, 'html');
            self::usunPlik($ksywa, 1, 'rosz');
            if($naStale) {
                self::usunPlik($ksywa, 1, 'rosz');
            } else {
                $pathFrom = Zend_Controller_Front::getInstance()->getBaseUrl() . '/upload/' . $ksywa . '.html';
                $pathTo = Zend_Controller_Front::getInstance()->getBaseUrl() . '/upload/' . $ksywa . '2.html';
                rename($pathFrom, $pathTo);
            }
        }
    }
}
