<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CookieHelper
 *
 * @author m.osik2
 */
class My_CookieHelper {
    
    private static $instance = false;
    private $cookie;
    private $request;
    const AGE = 31536000;   //1 rok w sekundach: 60*60*24*365
    //const AGE = 1000;
    
    private function __construct() {
        $this->cookie = new Zend_Http_Header_SetCookie();
        $this->request = new Zend_Controller_Request_Http();
    }
    
    public static function getInstance() {
        if(self::$instance == false) {
            self::$instance = new My_CookieHelper();
        }
        return self::$instance;
    }
    
    public function setCookie($name, $value, $age = self::AGE) {
        $this->cookie->setName($name)->setValue($value)->setMaxAge($age);
        Zend_Controller_Front::getInstance()->getResponse()->setRawHeader($this->cookie);
    }
    
    public function hasCookie($name) {
        $result = $this->getCookie($name);
        return !$result == "";
    }
    
    public function getCookie($name) {
        return $this->request->getCookie($name);
    }
    
    public function generateId() {
        $Potyczka = new Application_Model_DbTable_Potyczka();
        $out = $Potyczka->fetchRow($Potyczka->select()->from($Potyczka->info('name'), array('max(cookie)')))->toArray();
        return $out['max(cookie)'] + 1;
    }
}
