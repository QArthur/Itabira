<?php

class AgendamentoController extends Zend_Controller_Action
{

    public function init()
    {
      $this->_helper->layout()->setLayout('dashboard');
    }

    public function indexAction()
    {
      $scheduling = new Application_Model_Scheduling();
			$vehicle = new Application_Model_Vehicle();
      $general = new Application_Model_General();
      $vehicleId = $this->getRequest()->getParam('id');
      $this->view->save = $this->getRequest()->getParam('save');
      $this->view->vehicle = $vehicle->returnById($vehicleId);
      if($this->view->vehicle->external_number != '' && $scheduling->checkScheduled($this->view->vehicle->external_number))
      {
        $this->_redirect('/agendamento/success/id/'.$vehicleId);
      }
    	if ( $this->getRequest()->isPost() ) 
      {
        $data = $this->getRequest()->getPost();
        date_default_timezone_set('America/Sao_Paulo');

        $schedulingHour = new Application_Model_DbTable_SchedulingHourAllowed();
        $select = $schedulingHour->select()->setIntegrityCheck(false);
        $select ->from(array('scheduling_hour_allowed'), array('hour') )
                ->where('id = ?', $data['hour']);
        $hour = $schedulingHour->fetchRow($select);

        if( $data['date']." ".$hour->hour < date('Y-n-j H:i:s', strtotime("+1 day")) ){
          $this->view->save = 'scheduling_error';
        }
        else if($scheduling->newSchedule($data))
        {
					$this->_redirect('/agendamento/success/id/'.$vehicleId);
        }
        else
        {
        	$this->view->save = 'error';
        }
      }
      if($this->view->vehicle->external_number == '')
      {
        $this->view->vehicle = $vehicle->returnInactiveById($vehicleId);
      }
      $this->view->hour = $scheduling->returnHour();
    }

    public function successAction()
    {
      $vehicle = new Application_Model_Vehicle();
      $scheduling = new Application_Model_Scheduling();
      $vehicleId = $this->getRequest()->getParam('id');
      $this->view->vehicle = $vehicle->returnById($vehicleId);
      if($this->view->vehicle->external_number == '')
      {
        $this->view->vehicle = $vehicle->returnInactiveById($vehicleId);
      }
      $this->view->schedule = $scheduling->returnScheduling($this->view->vehicle->external_number);
    }

    public function cancelSchedulingAction()
    {
      $scheduling = new Application_Model_Scheduling();
      $scheduleId = $this->getRequest()->getParam('id');
      if( $scheduling->remove($scheduleId) ){
        return $this->_redirect('/agendamento/cancel-protocol');
      }
      return $this->_redirect('/agendamento/success/save/failed');
    }

    public function cancelProtocolAction()
    {
    }


}

