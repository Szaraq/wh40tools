<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        //return $this->_helper->redirector(array('controller' => 'Wyniki', 'action' => 'index'));
        
        /* COOKIE - TODO */
        $cookie = new Zend_Http_Header_SetCookie();
        $cookie->setName('test')->setValue('testVal')->setMaxAge(10);
        
        $this->getResponse()->setRawHeader($cookie);
        
        /* /COOKIE */
        
    }


}

