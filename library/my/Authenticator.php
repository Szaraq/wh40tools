<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Authenticator
 *
 * @author User
 */
class My_Authenticator {
    const COOKIE_NAME = 'wh40token';
    private $salt = 't59MVhTjAA';
    private $date_salt = 'pdTixrywC3';
    private $password = '89c7c69340c453bbe1a0c60be81d1dee';
    private $cookieHelper;
    
    function __construct() {
        $this->cookieHelper = My_CookieHelper::getInstance();
    }

    
    public function authenticate($pass) {
        if($this->checkCookie()) { return true; }
        if(md5($pass . $this->salt) == $this->password) {
            $this->setCookie();
            return true;
        } else {
            return false;
        }
    }
    
    public function checkCookie() {
        $cookie = $this->cookieHelper->getCookie(self::COOKIE_NAME);
        if($cookie == $this->getExpectedCookieValue()) {
            return true;
        } else {
            return false;
        }
    }
    
    private function setCookie() {
        $this->cookieHelper->setCookie(self::COOKIE_NAME, $this->getExpectedCookieValue(), 86400);
    }
    
    private function getExpectedCookieValue() {
        return md5(date('Y-m-d') . $this->date_salt);
    }
}
