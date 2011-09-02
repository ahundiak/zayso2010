<?php
require_once 'mvc/RefPoints/RefPointsBaseView.php';

class RefPointsMadisonView extends RefPointsBaseView
{
    function init()
    {
    	parent::init();
        
        $this->tplTitle   = 'Zayso Referee Points Page';
        $this->tplContent = 'RefPointsMadisonTpl';
    }
    function getMadisonRefereePoints($personId)
    {
    	$events = $this->getEventsForReferee($personId,4);
    			
		// And now once for the points
		$pending      = 0;
		$processed    = 0;
		$pending_rt   = 0;
		$processed_rt = 0;
		
		$divCR = array(
			 4 => 1,  5 => 1,  6 => 1, // U08
			 7 => 2,  8 => 2,  9 => 2, // U10
			10 => 2, 11 => 2, 12 => 2, // U12
			13 => 3, 14 => 3, 15 => 3, // U14
			16 => 4, 17 => 4, 18 => 4, // U16
			19 => 4, 20 => 4, 21 => 4, // U19
		);
		$divAR = array(
			 4 => 0,  5 => 0,  6 => 0, // U08
		     7 => 1,  8 => 1,  9 => 1, // U10
			10 => 1, 11 => 1, 12 => 1, // U12
			13 => 1, 14 => 1, 15 => 1, // U14
			16 => 2, 17 => 2, 18 => 2, // U16
			19 => 2, 20 => 2, 21 => 2, // U19
		);
		$div4th = array(
			16 => 1, 17 => 1, 18 => 1, // U16
			19 => 1, 20 => 1, 21 => 1, // U19
		);
		
		foreach($events as $event) 
		{
                    $points = 0;

                    $eventPersonType = $event['type'];
			
                    switch($eventPersonType)
                    {
			case EventPersonTypeModel::TYPE_CR:
				$div = $divCR;
				break;
				
			case EventPersonTypeModel::TYPE_AR1:
			case EventPersonTypeModel::TYPE_AR2:
				$div = $divAR;
				break;
				
			case EventPersonTypeModel::TYPE_4TH:
				$div = $div4th;
				break;
				
			default:
				$div = array();
			}
			
			$divId = $event['division'];
			
			if (isset($div[$divId])) {
				$points = $div[$divId];
				if ($points && $event['youth'] && ($divId >= 7) && ($eventPersonType != EventPersonTypeModel::TYPE_4TH)) $points++;
			}
			
			// Filter
			if ($event['use'] == 0) $points = 0;
                        switch($event['sch_type']) {
			case 1:
				if ($event['point2'] == 1) $pending   += $points;
				if ($event['point2'] == 3) $processed += $points;
				break;
			case 2:
				if ($event['point2'] == 1) $pending_rt   += $points;
				if ($event['point2'] == 3) $processed_rt += $points;
				break;
			}
		}
		return array(
			'pending'      => $pending,
			'processed'    => $processed,
			'pending_rt'   => $pending_rt,
			'processed_rt' => $processed_rt,
		);
    }
    function process($data)
    {
    	// Start by seeing if have any Madison referees
    	$this->referees = $referees = $this->getReferees();
        
        // Calc total points for each referee
        foreach($this->referees as $referee) 
        {
        	$points = $this->getMadisonRefereePoints($referee->id);
        	
        	$referee->pending   = $points['pending'];
        	$referee->processed = $points['processed'];
        	
        	$referee->pending_rt   = $points['pending_rt'];;
        	$referee->processed_rt = $points['processed_rt'];
        }
        $this->buildPickLists($referees,$data);
        
        /* And render it  */      
        return $this->renderx();
    }
}
?>
