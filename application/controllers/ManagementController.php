<?php

class ManagementController extends Zend_Controller_Action
{

    private $forms = null;
    private $authenticator;

    public function init()
    {
        $this->forms['nowa-runda'] = new Application_Form_MgmtNowaRunda();
        $this->forms['usun-rozpy'] = new Application_Form_MgmtUsunRozpy();
        $this->forms['zmien-armie'] = new Application_Form_MgmtZmienArmie();
        $this->authenticator = new My_Authenticator();
    }

    public function indexAction()
    {
        if(!$this->authenticator->checkCookie()) { return $this->_helper->redirector('authenticate'); }
        foreach($this->forms as $action=>$form) {
            $url = $this->view->url(array('action' => $action));
            $form->setAction($url);
        }
        $this->view->forms = $this->forms;
    }

    public function nowaRundaAction()
    {
        if(!$this->authenticator->checkCookie()) { return $this->_helper->redirector('authenticate'); }
        $this->indexAction();
        $this->_helper->viewRenderer('index');
        $form = $this->forms['nowa-runda'];
        if($this->getRequest()->isPost()) {
            if($form->isValid($this->getRequest()->getPost())) {
                My_Functions::addNowaRunda($form->getValue('data'), $form->getValue('punkty'));
                return $this->_helper->redirector('index');
            }
        }
    }

    public function usunRozpyAction()
    {
        if(!$this->authenticator->checkCookie()) { return $this->_helper->redirector('authenticate'); }
        $this->indexAction();
        $this->_helper->viewRenderer('index');
        $form = $this->forms['usun-rozpy'];
        if($this->getRequest()->isPost()) {
            if($form->isValid($this->getRequest()->getPost())) {
                My_Functions::usunRozpy($form->getValue('naStale'));
                return $this->_helper->redirector('index');
            }
        }
    }

    public function zmienArmieAction()
    {
        if(!$this->authenticator->checkCookie()) { return $this->_helper->redirector('authenticate'); }
        $this->indexAction();
        $this->_helper->viewRenderer('index');
        $form = $this->forms['zmien-armie'];
        if($this->getRequest()->isPost()) {
            if($form->isValid($this->getRequest()->getPost())) {
                $gracze = My_Functions::getGracze();
                foreach($gracze as $gracz) {
                    $gracz->armia = $form->getValue($gracz->ksywa);
                    $gracz->save();
                }
                return $this->_helper->redirector('index');
            }
        }
    }

    public function authenticateAction()
    {
        if($this->authenticator->checkCookie()) { return $this->_helper->redirector('index'); }
        $this->view->form = new Application_Form_MgmtAuth();
        if($this->getRequest()->isPost()) {
            $form = $this->view->form;
            if($form->isValid($this->getRequest()->getPost())) {
                if($this->authenticator->authenticate($form->getValue('pass'))) {
                    return $this->_helper->redirector('index');
                }
            }
        }
    }


}









