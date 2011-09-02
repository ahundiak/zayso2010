<?php
require_once 'mvc/RefPoints/RefPointsBaseView.php';

class RefPointsHuntsvilleView extends RefPointsBaseView
{
    function init()
    {
        parent::init();
        
        $this->tplTitle   = 'Zayso Referee Points Page';
        $this->tplContent = 'RefPointsHuntsvilleTpl';
    }
    function getHuntsvilleRefereePoints($personId)
    {
        $events = $this->getEventsForReferee($personId,7);
    	
        $count     = 0;
        $pending   = 0;
        $processed = 0;
		
        foreach($events as $event) 
        {
            $points = 0;

            $eventPersonType = $event['type'];
			
            switch($eventPersonType)
            {
                case EventPersonTypeModel::TYPE_CR: 
                    $div = Osso2007_Referee_Points_RefPointsHuntsville::$divPointsCR; 
                    break;
				
                case EventPersonTypeModel::TYPE_AR1:
                case EventPersonTypeModel::TYPE_AR2:
                    $div = Osso2007_Referee_Points_RefPointsHuntsville::$divPointsAR;
                    break;
				
                default: $div = array(); 
                    
            }		
            $divId = $event['division'];
			
            if (isset($div[$divId])) 
            {
                $points = $div[$divId];
		$count++;
                
                // Filter
                if ($event['use'] == 0) $points = 0;
                switch($event['sch_type']) 
                {
                    case 1:
                        if ($event['point2'] == 1) $pending   += $points;
                        if ($event['point2'] == 3) $processed += $points;
                    break;
                }
            }
        }
        
    // Done
    return array(
        'pending'   => $pending,
        'processed' => $processed,
        'game_cnt'  => $count,
    );
  }
  function process($data)
  {
    // Start by seeing if have any referees for this user league
    $this->referees = $referees = $this->getReferees();
        
    // Calc total points for each referee
    foreach($this->referees as &$referee) 
    {
      $points = $this->getHuntsvilleRefereePoints($referee->id);
        	
      $referee->gameCnt   = $points['game_cnt'];
      $referee->pending   = $points['pending'];
      $referee->processed = $points['processed'];
    }
        
    // All the various pick lists and what not
    $this->buildPickLists($referees,$data);
        
    /* And render it  */      
    return $this->renderx();
  }
}
?>
