<?php

class RozpyController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->gracze = My_Functions::getGracze();
        $this->view->form = new Application_Form_WrzucRozpe();
        
        $url = $this->view->url(array('action' => 'upload'));
        $this->view->form->setAction($url);
    }

    public function uploadAction()
    {
        $form = new Application_Form_WrzucRozpe();
        $this->view->form = $form;
        
        if($this->getRequest()->isPost()) {
            if($form->isValid($this->getRequest()->getPost())) {
                $upload = new Zend_File_Transfer_Adapter_Http();
                $extension = pathinfo($upload->getFileInfo()['rozpa']['name'], PATHINFO_EXTENSION);
                $upload->addFilter('Rename', 'upload\\' . $form->gracz->getValue() . '.' . $extension, true);
                $upload->receive();
                return $this->_helper->redirector('index');
            }
        }
        
        $this->view->form = $form;
    }


}



