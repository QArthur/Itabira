<?php

class Application_Model_UsedFleet
{
	/**
	*	Array of month 
	*/
	public $month = array('Meses', 
			'Janeiro', 
			'Fevereiro',
			'Março',
			'Abril', 
			'Maio',
			'Junho',
			'Julho',
			'Agosto',
			'Setembro',
			'Outubro', 
			'Novembro',
			'Dezembro'
	);

	/**
	*	Array of type of day 
	*/
	public $day = array( 
			'Dom', 
			'Util',
			'Util',
			'Util', 
			'Util',
			'Util',
			'Sab'
	);
	/**
	*	List the used fleet by line in a certain period of time.
	*
	*	@param integer $field -> Line's number communication
	*	@access public
	*	@return Zend_Db_Table_Rowset
	*/
	public function listByLine($field)
	{
		$usedFleet = new Application_Model_DbTable_McoData();
		if($field != '*')
		return $usedFleet->fetchAll($usedFleet->select()->where('line = ?',$field));
		else
			return $usedFleet->fetchAll($usedFleet->select());
	}
    

	/**
	*	Calculate general used fleet.
	*
	*	@param array $data -> Period to do the survey
	*	@param array $hour -> Pick time to do the survey
	*	@access public
	*	@return Zend_Db_Table_Rowset
	*/
	public function getGenUsedfleet($date, $hour)
	{
			$index=0;
			$dateAux = Application_Model_General::dateToUs($date[0]);
			$endDate = 	date('Y-m-d', strtotime(Application_Model_General::dateToUs($date[1]) .' +1 day'));
			while($dateAux != $endDate){
				$generalUsedFleet = new Application_Model_DbTable_McoData(); 
				$select = $generalUsedFleet->select()->setIntegrityCheck(false); //morning		             
			    $select	->distinct()->from('mco_data', 'vehicle_number')
			            ->where('start_date = ?', $dateAux)
						->where('start_hour >= ?', $hour[0][0])
						->where('start_hour <= ?', $hour[0][1])
						->order('start_date')
						->order('vehicle_number');
				$rows = $generalUsedFleet->fetchAll($select); 
				$dados[$index][0] =  date('d/m/y', strtotime($dateAux));  
				$dados[$index][1] = count($rows); 
				$generalUsedFleet = new Application_Model_DbTable_McoData(); 
				$select = $generalUsedFleet->select()->setIntegrityCheck(false);//afternoon		             
			    $select	->distinct()->from('mco_data', 'vehicle_number')
			            ->where('start_date = ?', $dateAux)
						->where('start_hour >= ?', $hour[1][0])
						->where('start_hour <= ?', $hour[1][1])
						->order('start_date')
						->order('vehicle_number');
				$rows = $generalUsedFleet->fetchAll($select);   
				$dados[$index][2] = count($rows);				
				if($dados[$index][1] > $dados[$index][2]) 
					$dados[$index][3] = $dados[$index][1];
				else
					$dados[$index][3]= $dados[$index][2];
				$dateAux = date('Y-m-d', strtotime($dateAux .' +1 day')); 
				$index = $index +1;
		    }
		return $dados;		
	}	

	/**
	*	Calculate the average of used fleet per type of day.
	*
	*	@param array $matAux -> data of month, type of day, numerber of fleet.
	*	@param array $type -> the type to calculate the average
	*	@access public
	*	@return matrix contain Ref, DU, Sab, Dom. Average for each type.
	*/

	public function getGeneralAverage($dados)
	{
		$muf = new Application_Model_UsedFleet();
		$sum=0;
		$numMed=0;
		for ($i=0; $i < count($dados) ; $i++) { 			
			$curMonth = date('m', strtotime(Application_Model_General::dateToUs($dados[$i][0]))); 
			$matAux=null;
			$index=0;
			while ( date('m', strtotime(Application_Model_General::dateToUs($dados[$i][0]))) == $curMonth
					and $i<count($dados)) {				
				$matAux[$index][0] = $muf->month[intval($curMonth)]."/"
							.date('Y', strtotime(Application_Model_General::dateToUs($dados[$i][0])));;
				$matAux[$index][1] = $muf->day[
							   jddayofweek ( cal_to_jd(CAL_GREGORIAN, 
			 				   date('m', strtotime(Application_Model_General::dateToUs($dados[$i][0])))
			 				  ,date('d', strtotime(Application_Model_General::dateToUs($dados[$i][0])))
			 				  ,date('Y', strtotime(Application_Model_General::dateToUs($dados[$i][0])))) , 0)
  							              ];  				
				$matAux[$index][2] = $dados[$i][3];
			 $index++;
			 $i++;			 
			}
			$average[$numMed][0] = $matAux[$index-1][0];
			$average[$numMed][1] = $muf->getAverage($matAux, "Util");
			$average[$numMed][2] = $muf->getAverage($matAux, "Sab");
			$average[$numMed][3] = $muf->getAverage($matAux, "Dom");
			$numMed++;
		}
		//echo $average[0][0];
		Return $average;
	}

	/**
	*	Auxiliar function to Calculate the average of used fleet per type of day.
	*	Called by getGeneralAverage
	*	@param array $matAux -> data of month, type of day, numerber of fleet.
	*	@param array $type -> the type to calculate the average
	*	@access public
	*	@return matrix contain Ref, DU, Sab, Dom. Average for each type.
	*/
	public function getAverage($matAux, $type)
	{
		//echo $matAux[0][1];
		$sum = 0;
		$count = 0;
		for ($i=0; $i < count($matAux) ; $i++) { 
				if($matAux[$i][1] == $type && $matAux[$i][2]>0){
					$sum = $sum + $matAux[$i][2];
					$count ++;
				}
		}
		return number_format($sum/$count,0,".",".");
	}

	/**
	*	Export General Used Fleet.
	*	
	*	@param array $matAux -> data of month, type of day, numerber of fleet.
	*	@param array $type -> the type to calculate the average
	*	@access public
	*	@return matrix contain Ref, DU, Sab, Dom. Average for each type.
	*/
	public function exportGeneralUF($date, $hour)
	{
	  $generalUsedfleet = new Application_Model_UsedFleet();
      $usedFleet = $generalUsedfleet->getGenUsedfleet($date, $hour);
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename=FrotaUtilizada.csv');
      header('Pragma: no-cache');
      header('Expires: 0');
      echo "Data;Pico Mannhã;Pico Tarde;Pico;\n";
      foreach ($usedFleet as $dados) 
      {
        foreach ($dados as $print) {
        	echo "$print;";
        }
    
        echo "\n";
      }
           exit;
	}

	/**
	*	Calculate used fleet bu RIT.
	*
	*	@param array $data -> Period to do the survey
	*	@param array $hour -> Pick time to do the survey
	*	@access public
	*	@return A matrix of data
	*/
	public function getRitUsedfleet($date, $hour)
	{
			$index=0;
			$dateAux = Application_Model_General::dateToUs($date[0]);
			$endDate = 	date('Y-m-d', strtotime(Application_Model_General::dateToUs($date[1]) .' +1 day'));
			while($dateAux != $endDate){
				for ($i=1; $i <=7 ; $i++) { 
					
					$ritUsedFleet = new Application_Model_DbTable_McoData(); 
					$select = $ritUsedFleet->select()->setIntegrityCheck(false); //morning		             
				    $select	->distinct()->from(array('m' => 'mco_data'), 'vehicle_number')
				            ->joinInner(array('h' => 'vehicle_historic'),'h.external_number=m.vehicle_number')
				            ->where('m.start_date = ?', $dateAux)
							->where('m.start_hour >= ?', $hour[0][0])
							->where('m.start_hour <= ?', $hour[0][1])
							->where('h.consortium = ?', $i)
							->where('h.end_historic_date is NULL')
							->order('m.start_date')
							->order('m.vehicle_number');
					$rows = $ritUsedFleet->fetchAll($select); 
					$dados[$index][0] =  date('d/m/y', strtotime($dateAux)); 
					$dados[$index][1] = $i;
					$dados[$index][2] = count($rows); 
				
					$ritUsedFleet = new Application_Model_DbTable_McoData(); 
					$select = $ritUsedFleet->select()->setIntegrityCheck(false); //afternoom	             
				    $select	->distinct()->from(array('m' => 'mco_data'), 'vehicle_number')
				    		->joinInner(array('h' => 'vehicle_historic'),'h.external_number=m.vehicle_number')
				            ->where('m.start_date = ?', $dateAux)
							->where('m.start_hour >= ?', $hour[1][0])
							->where('m.start_hour <= ?', $hour[1][1])
							->where('h.consortium = ?', $i)
							->where('h.end_historic_date is NULL')
							->order('m.start_date')
							->order('m.vehicle_number');
					$rows = $ritUsedFleet->fetchAll($select);   
					$dados[$index][3] = count($rows);				
					if($dados[$index][2] > $dados[$index][3]) 
						$dados[$index][4] = $dados[$index][2];
					else
						$dados[$index][4]= $dados[$index][3];
					$index = $index +1;
				}
				$dateAux = date('Y-m-d', strtotime($dateAux .' +1 day')); 
				$index = $index +1;
		    }
		return $dados;		
	}

	/**
	*	Calculate the average of used fleet per type of day.
	*
	*	@param array $matAux -> data of month, type of day, numerber of fleet.
	*	@param array $type -> the type to calculate the average
	*	@access public
	*	@return matrix contain Ref, DU, Sab, Dom. Average for each type.
	*/

	public function getRitAverage($dados)
	{
		$muf = new Application_Model_UsedFleet();
		$sum=0;
		$numMed=0;
		for ($i=0; $i < count($dados) ; $i++) { 			
			$curMonth = date('m', strtotime(Application_Model_General::dateToUs($dados[$i][0]))); 
			$matAux=null;
			$index=0;
			while ( date('m', strtotime(Application_Model_General::dateToUs($dados[$i][0]))) == $curMonth
					and $i<count($dados)) {				
				$matAux[$index][0] = $muf->month[intval($curMonth)]."/"
							.date('Y', strtotime(Application_Model_General::dateToUs($dados[$i][0])));;
				$matAux[$index][1] = $muf->day[
							   jddayofweek ( cal_to_jd(CAL_GREGORIAN, 
			 				   date('m', strtotime(Application_Model_General::dateToUs($dados[$i][0])))
			 				  ,date('d', strtotime(Application_Model_General::dateToUs($dados[$i][0])))
			 				  ,date('Y', strtotime(Application_Model_General::dateToUs($dados[$i][0])))) , 0)
  							              ];  				
				$matAux[$index][2] = $dados[$i][3];
			 $index++;
			 $i++;			 
			}
			$average[$numMed][0] = $matAux[$index-1][0];
			$average[$numMed][1] = $muf->getAverage($matAux, "Util");
			$average[$numMed][2] = $muf->getAverage($matAux, "Sab");
			$average[$numMed][3] = $muf->getAverage($matAux, "Dom");
			$numMed++;
		}
		//echo $average[0][0];
		Return $average;
	}

	/**
	*	Export General Used Fleet.
	*	
	*	@param array $matAux -> data of month, type of day, numerber of fleet.
	*	@param array $type -> the type to calculate the average
	*	@access public
	*	@return matrix contain Ref, DU, Sab, Dom. Average for each type.
	*/
	public function exportRitUF($date, $hour)
	{
	  $generalUsedfleet = new Application_Model_UsedFleet();
      $usedFleet = $generalUsedfleet->getGenUsedfleet($date, $hour);
      header('Content-Type: text/csv');
      header('Content-Disposition: attachment; filename=FrotaUtilizada.csv');
      header('Pragma: no-cache');
      header('Expires: 0');
      echo "Data;Pico Mannhã;Pico Tarde;Pico;\n";
      foreach ($usedFleet as $dados) 
      {
        foreach ($dados as $print) {
        	echo "$print;";
        }
    
        echo "\n";
      }
           exit;
	}

}
?>
