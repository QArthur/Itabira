<?php

class UsedFleetController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout()->setLayout('dashboard');
      $authNamespace = new Zend_Session_Namespace('userInformation');
      $this->view->institution = $authNamespace->institution;
      if($this->view->institution != 1)
      {
        return $this->_redirect('/doesntallow');
      }
      $mail = new Application_Model_Mail();
      $this->view->messages = $mail->getUnreadMessages();
    }

    public function indexAction()
    {
        // action body
    }

     public function newAction()
    {
    
    }

     public function viewAction()
    {
      $usedfleet = new Application_Model_UsedFleet();
      $pagination = new Application_Model_Pagination();
      $page = $this->getRequest()->getParam('page');
      $this->view->field = $this->getRequest()->getParam('field');
      if($page == '') $page = 1;
      if($this->view->field != '')
      {
        $usedfleet = $usedfleet->listByLine($this->view->field);
        $this->view->list = $pagination->generatePagination($usedfleet,$page,10);
       
      }
    /*  else
      {
        $usedfleet = $usedfleet->lists();
      }
      */
     // $this->view->list = $pagination->generatePagination($usedfleet,$page,10);
    }

     public function reportAction()
    {
      $this->view->save = $this->getRequest()->getParam('save');
    }

     public function reportGeneralAction()
    {  
      $generalUsedfleet = new Application_Model_UsedFleet(); 
      $date[0] = $_GET["data_ini"];
      $date[1] = $_GET["data_fim"];
      $hour[0][0]= $_GET["hora_ini_m"];
      $hour[0][1]= $_GET["hora_fim_m"];
      $hour[1][0]= $_GET["hora_ini_t"];
      $hour[1][1]= $_GET["hora_fim_t"]; 

      if ($date[0] == '' || $date[1] == '' || $hour[0][0] == '' || $hour[0][1] == '' || $hour[1][0] == '' || $hour[1][1] == '') {
        
        $this->_redirect('/used-fleet/report/save/empty');

       } 
       else {

         switch ($_GET["report"]) {         
           case 0:              
            $this->view->date = $date;
            $this->view->hour = $hour; 
            $dados = $generalUsedfleet->getGenUsedfleet($date, $hour); 
            $this->view->data = $dados;
            $this->view->average = $generalUsedfleet->getGeneralAverage($dados);
             break;

           case 1:
             $generalUsedfleet->exportGeneralUF($date, $hour);
             exit;
             break;
           
           default:
             # code...
             break;
         } 
        }
    }

    public function reportRitAction()
    {
      $ritUsedfleet = new Application_Model_UsedFleet(); 
      $date[0] = $_GET["data_ini"];
      $date[1] = $_GET["data_fim"];
      $hour[0][0]= $_GET["hora_ini_m"];
      $hour[0][1]= $_GET["hora_fim_m"];
      $hour[1][0]= $_GET["hora_ini_t"];
      $hour[1][1]= $_GET["hora_fim_t"]; 

      if ($date[0] == '' || $date[1] == '' || $hour[0][0] == '' || $hour[0][1] == '' || $hour[1][0] == '' || $hour[1][1] == '') {
        
        $this->_redirect('/used-fleet/report/save/empty');

       } 
       else {

         switch ($_GET["report"]) {         
           case 0:              
            $this->view->date = $date;
            $this->view->hour = $hour; 
            $dados = $ritUsedfleet->getRitUsedfleet($date, $hour); 
            $this->view->data = $dados;
            $this->view->average = $ritUsedfleet->getRitAverage($dados);
             break;

           case 1:
             $ritUsedfleet->exportRitUF($date, $hour);
             exit;
             break;
           
           default:
             # code...
             break;
         } 
        }

    }

    
}

