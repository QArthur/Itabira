<?php

class Application_Model_QcoQH extends Application_Model_Qco
{

	private $file;
	private $numberCommunication;
	private $qcoId;

	/**
	*	Before use a file qh the system must check a number of lines and line's number of communication.
	* This function check a properties of the file and return true if it is correct or not.
	*
	*	@param array $files -> file's data
	*	@param integer $qcoId -> QCO's id
	*	@access public
	*	@return boolean
	*/
	public function checkFile($files,$qcoId)
	{
		$this->file = fopen($files['qco_file']['tmp_name'], 'r');
		if($this->checkNumberLines($files))
		{
			rewind($this->file);
			$startLine = fgets($this->file);
			$tes = explode(' ', $startLine);
			$startLine = $tes[0];
			if($startLine[0].$startLine[1] == '01')
			{
				$numberCommunication = $startLine[2].$startLine[3].$startLine[4].$startLine[5];
				$checked = $this->checkNumberCommunication($numberCommunication, $qcoId);
				if($checked)
				{
					return true;
				}
			}
		}
		return false;
	}

	/**
	*	Check number of lines in file. The register '04' contains a number of lines in file. 
	*	This function check the number inside the file and the real amount of line of the file and returns true or false
	* depending of result.
	*
	*	@param array $files ->files's data
	*	@access private
	*	@return boolean
	*/
	private function checkNumberLines($files)
	{
		$lines = count(file($files['qco_file']['tmp_name']));
		while (!feof($this->file)) 
		{
      $buffer = fgets($this->file, 4096);
			if($buffer[0].$buffer[1] == '04')
      {
      	$countLines = explode('04', $buffer);
      	$countLines = (int)$countLines[1];
      	if($lines == $countLines)
      	{
      		return true;
      	}
      }
		}
		return false;
	}

	/**
	*	Check the number of communication with qco_id.
	*
	*	@param string $numberCommunication -> line's number of communication 
	*	@param integer $qcoId -> qco's id
	*	@access private
	*	@return Zend_Db_Table_Rowset
	*/
	private function checkNumberCommunication($numberCommunication, $qcoId)
	{
		$qco = new Application_Model_DbTable_Qco();
		return $qco->fetchRow($qco->select()
														->where('number_communication = ?',$numberCommunication)
														->where('id = ?',$qcoId)
														);
	}

	/**
	*	Load qh's data and return it on array.
	*
	*	@param array $files -> qh's data file
	*	@access public
	*	@return array
	*/
	public function loadQH($files)
	{
		$qh = array( 'qh' => array(), 'fleet' => array());
		$this->file = fopen($files['qco_file']['tmp_name'], 'r');
		while (!feof($this->file)) 
		{
      $buffer = fgets($this->file, 4096);
      if($buffer[0].$buffer[1] == '02')
      {
      	$typeDay = $this->returnTypeDay($buffer[2].$buffer[3]);
      	$pc = $buffer[4];
      	if(isset($hour))
      	{
      		$qhHour['qh'] = $hour;
      		unset($hour);
    			array_push($qh['qh'], $qhHour);
    			unset($qhHour);
      	}
      	$qhHour = array( 'id' => $typeDay->id, 'name' => $typeDay->name, 'pc' => $pc);
    		$hour = array();
      }
      if($buffer[0].$buffer[1] == '03')
      {
      	if($typeDay->id != '' && $pc != '')
      	{
      		$auxHour = array('hour' => $buffer[2].$buffer[3].$buffer[4].$buffer[5], 'type' => $buffer[6].$buffer[7]);
      		array_push($hour, $auxHour);
      	}
      }
      if($buffer[0].$buffer[1] == '09')
      {
      	$qhFleet = explode('|', $buffer);
      	$fleetHour = array('type_day_id' => $qhFleet[1], 
      											'00' => $qhFleet[2],
      											'01' => $qhFleet[3],
      											'02' => $qhFleet[4],
      											'03' => $qhFleet[5],
      											'04' => $qhFleet[6],
      											'05' => $qhFleet[7],
      											'06' => $qhFleet[8],
      											'07' => $qhFleet[9],
      											'08' => $qhFleet[10],
      											'09' => $qhFleet[11],
      											'10' => $qhFleet[12],
      											'11' => $qhFleet[13],
      											'12' => $qhFleet[14],
      											'13' => $qhFleet[15],
      											'14' => $qhFleet[16],
      											'15' => $qhFleet[17],
      											'16' => $qhFleet[18],
      											'17' => $qhFleet[19],
      											'18' => $qhFleet[20],
      											'19' => $qhFleet[21],
      											'20' => $qhFleet[22],
      											'21' => $qhFleet[23],
      											'22' => $qhFleet[24],
      											'23' => $qhFleet[25]);
      	array_push($qh['fleet'], $fleetHour);
      }
		}
		return $qh;
	}

	public function saveNewQH($qcoId, $oldQcoId, $data)
	{
		$this->saveSchedule($qcoId, $oldQcoId, $data);
	}

	private function saveSchedule($qcoId, $oldQcoId, $data)
	{
		$qhTypeDay = array();
		$fleetTypeDay = array();
		foreach ($data['serializedQh'] as $tmpQh) 
		{
			foreach($tmpQh as $qh)
			{
				$schedule = new Application_Model_DbTable_QcoSchedule();
				$scheduleNew = $schedule->createRow();
				$scheduleNew->qco_id = $qcoId;
				$scheduleNew->type_day_id = $qh['id'];
				$scheduleNew->pc = $qh['pc'];
				$scheduleNewId = $scheduleNew->save();
				foreach($qh['qh'] as $hour)
				{
					$scheduleHour = new Application_Model_DbTable_QcoScheduleHour();
					$scheduleHourNew = $scheduleHour->createRow();
					$scheduleHourNew->qco_schedule_id = $scheduleNewId;
					$scheduleHourNew->hour = substr($hour['hour'], 0, 2).':'.substr($hour['hour'], 2).':00';
					$typeJourney = $this->returnTypeJourneyByAbrev($hour['type']);
					$scheduleHourNew->type_journey_id = $typeJourney->id;
					$scheduleHourNew->save();
				}
				array_push($qhTypeDay,$qh['id']);
			}
			break;
		}
		foreach($data['serializedQh']['fleet'] as $fleet)
		{
  		$qcoFleet = new Application_Model_DbTable_QcoFleet();
  		$qcoFleetNew = $qcoFleet->createRow();
  		$qcoFleetNew->qco_id = $qcoId;
  		$qcoFleetNew->type_day_id = $fleet['type_day_id'];
  		$qcoFleetNew->hour_00 = intval($fleet['00']);
  		$qcoFleetNew->hour_01 = intval($fleet['01']);
  		$qcoFleetNew->hour_02 = intval($fleet['02']);
  		$qcoFleetNew->hour_03 = intval($fleet['03']);
  		$qcoFleetNew->hour_04 = intval($fleet['04']);
  		$qcoFleetNew->hour_05 = intval($fleet['05']);
  		$qcoFleetNew->hour_06 = intval($fleet['06']);
  		$qcoFleetNew->hour_07 = intval($fleet['07']);
  		$qcoFleetNew->hour_08 = intval($fleet['08']);
  		$qcoFleetNew->hour_09 = intval($fleet['09']);
  		$qcoFleetNew->hour_10 = intval($fleet['10']);
  		$qcoFleetNew->hour_11 = intval($fleet['11']);
  		$qcoFleetNew->hour_12 = intval($fleet['12']);
  		$qcoFleetNew->hour_13 = intval($fleet['13']);
  		$qcoFleetNew->hour_14 = intval($fleet['14']);
  		$qcoFleetNew->hour_15 = intval($fleet['15']);
  		$qcoFleetNew->hour_16 = intval($fleet['16']);
  		$qcoFleetNew->hour_17 = intval($fleet['17']);
  		$qcoFleetNew->hour_18 = intval($fleet['18']);
  		$qcoFleetNew->hour_19 = intval($fleet['19']);
  		$qcoFleetNew->hour_20 = intval($fleet['20']);
  		$qcoFleetNew->hour_21 = intval($fleet['21']);
  		$qcoFleetNew->hour_22 = intval($fleet['22']);
  		$qcoFleetNew->hour_23 = intval($fleet['23']);
  		$qcoFleetNew->save();
  		array_push($fleetTypeDay, $fleet['type_day_id']);
		}
		$this->registerOldSchedule($qhTypeDay,$qcoId,$oldQcoId);
		$this->registerOldFleet($fleetTypeDay,$qcoId,$oldQcoId);
	}

	private function registerOldSchedule($qhTypeDay,$qcoId,$oldQcoId)
	{
		$qcoSchedule = new Application_Model_DbTable_QcoSchedule();
		$qcoScheduleRow = $qcoSchedule->fetchAll($qcoSchedule->select()
												->where('qco_id = ?',$oldQcoId)
												->where('type_day_id NOT IN (?)',$qhTypeDay));
		foreach($qcoScheduleRow as $qcoSchedule)
		{
	    $schedule = new Application_Model_DbTable_QcoSchedule();
	    $qcoScheduleRow = $qcoSchedule->toArray();
	    $qcoOldScheduleId = $qcoScheduleRow['id'];
	    unset($qcoScheduleRow['id']);
	    $qcoScheduleRow['qco_id'] = $qcoId;
			$scheduleNew = $schedule->createRow($qcoScheduleRow);
			$scheduleNewId = $scheduleNew->save();  

			$scheduleHour = new Application_Model_DbTable_QcoScheduleHour();
			$scheduleHourRow = $scheduleHour->fetchAll($scheduleHour->select()->where('qco_schedule_id = ?',$qcoOldScheduleId));
			foreach ($scheduleHourRow as $scheduleRow) 
			{
				$scheduleArray = $scheduleRow->toArray();
				unset($scheduleArray['id']);
				$scheduleArray['qco_schedule_id'] = $scheduleNewId;
				$scheduleHourNew = $scheduleHour->createRow($scheduleArray);
				$scheduleHourNew->save();
			}
		}      
	}

	private function registerOldFleet($fleetTypeDay,$qcoId,$oldQcoId)
	{
		$qcoFleet = new Application_Model_DbTable_QcoFleet();
		$qcoFleetRow = $qcoFleet->fetchAll($qcoFleet->select()
												->where('qco_id = ?',$oldQcoId)
												->where('type_day_id NOT IN (?)',$fleetTypeDay));
		foreach($qcoFleetRow as $fleet)
		{
	    $qcoFleetArray = $fleet->toArray();
	    unset($qcoFleetArray['id']);
	    $qcoFleetArray['qco_id'] = $qcoId;
			$scheduleFleetNew = $qcoFleet->createRow($qcoFleetArray);
			$scheduleFleetNew->save();  
		}
	}

	public function returnTypeDay($typeDayId)
	{
		$qco = new Application_Model_DbTable_QcoTypeDay();
		return $qco->fetchRow($qco->select()->where('id = ?',$typeDayId) );
	}

	public function returnTypeJourneyByAbrev($abrev)
	{
		$qco = new Application_Model_DbTable_QcoTypeJourney();
		return $qco->fetchRow($qco->select()->where('abrev = ?',$abrev) );
	}

}

