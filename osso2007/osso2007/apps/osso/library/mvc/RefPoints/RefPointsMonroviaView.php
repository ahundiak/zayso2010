<?php
require_once 'mvc/RefPoints/RefPointsBaseView.php';

class RefPointsMonroviaView extends RefPointsBaseView
{
    function init()
    {
    	parent::init();
        
        $this->tplTitle   = 'Zayso Referee Points Page';
        $this->tplContent = 'RefPointsMonroviaTpl';
    }
    function getMonroviaRefereePoints($personId)
    {
		$events = $this->getEventsForReferee($personId,1);
    	
    	$points = array('game_cnt' => count($events));
    	
    	return $points;
    }
    function process($data)
    {
    	// Start by seeing if have any referees for this user league
    	$this->referees = $referees = $this->getReferees();
        
        // Calc total points for each referee
        foreach($this->referees as &$referee) 
        {
        	$points = $this->getMonroviaRefereePoints($referee->id);
        	
        	$referee->gameCnt = $points['game_cnt'];
        }
        
        // All the various pick lists and what not
        $this->buildPickLists($referees,$data);
        
        /* And render it  */      
        return $this->renderx();
    }
}
?>
