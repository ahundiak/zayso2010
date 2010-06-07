<?php
class SignupController extends Controller
{
  function executeGet()
  {
    // Make sure are logged in
    $relocate = "location: index.php?page=schedule";
    if (!$this->isReferee()) return header($relocate);
		
    $tpl = new Cerad_Data();
		
    // Need to have game and position
    $gameId = (int)$this->getGet('game');
    $posId  = (int)$this->getGet('pos');
    if (!$gameId || !$posId) return header($relocate);
		
    $tpl->gameId = $gameId;
    $tpl->posId  = $posId;
		
    // See if record exists and if so pull data from it
    $sql = "SELECT * FROM game_person WHERE game_num = :game_id AND pos_id = :pos_id;";
    $params = array
    (
      'game_id' => $gameId,
      'pos_id'  => $posId,
    );
    $db  = $this->getDb();
    $row = $db->fetchRow($sql,$params);
    if ($row)
    {
      $tpl->refRegion    = $row['region'];
      $tpl->refFirstName = $row['fname'];
      $tpl->refLastName  = $row['lname'];
      $tpl->refAysoid    = $row['aysoid'];
      $tpl->refStatus    = $row['status'];
    }
    // Session data if we have it
    $tpl->errors = $this->getSess('ref_errors');
		
    if (!$row || $tpl->errors)
    {
      $tpl->refRegion    = $this->getSess('ref_region');
      $tpl->refFirstName = $this->getSess('ref_first_name');
      $tpl->refLastName  = $this->getSess('ref_last_name');
      $tpl->refAysoid    = $this->getSess('ref_aysoid');
      $tpl->refStatus    = $this->getSess('ref_status',1);
    }
    if ($tpl->errors) $_SESSION['ref_errors'] = NULL;
		
    // Referee positions
    $tpl->posPickList = array
    (
      1 => 'Center Referee',
      2 => 'Assistant Referee 1',
      3 => 'Asssitant Referee 2',
      4 => 'Assessor',
      5 => 'Assessor 2',
    );
    // Requested Assessments
    $tpl->assPickList = array
    (
      1 => 'National Upgrade',
      2 => 'Advanced Upgrade',
      3 => 'Intermediate Observation',
      4 => 'Informal Observation',
    );
    // Regions
    $regionRepo = new RegionRepo();
    $tpl->regionPickList = $regionRepo->getRegionPickList();
		
    // Query
    $query = new Query($this->getDb());
    $events = $query->queryGamesForIds($gameId);
		
    if (count($events) != 1) return header($relocate);
			
    $tpl->game = array_shift($events);
		
    // Cerad_Debug::dump($data->game);
		
    // Preprocess the officials
    $persons = array
    (
      1 => array('pos' => 'Center Referee', 'name' => '&nbsp;'),
      2 => array('pos' => 'Assistant 1',    'name' => '&nbsp;'),
      3 => array('pos' => 'Asssitant 2',    'name' => '&nbsp;'),
      4 => array('pos' => 'Assessor',       'name' => '&nbsp;'),
      5 => array('pos' => 'Assessor 2',     'name' => '&nbsp;'),
    );
    $personsx = $tpl->game->getPersons();
    foreach($personsx as $personx)
    {
      $name   = $personx->fname . ' ' . $personx->lname;
      $region = $personx->region;
      // if ($region < 1000) $region = 'R0' . $region;
      // else                $region = 'R'  . $region;
			
      $name = $region . ' ' . $name;
      $posId = $personx->posId;
      $persons[$posId]['name'] = $name;
    }
    $tpl->persons = $persons;
		
    // Status options
    $tpl->statusPickList = array
    (
      1 => 'Request this game',
      2 => 'Ref only if needed',
    );
    if ($this->isAdmin())
    {
      $tpl->statusPickList[3] = 'Assigned by admin';
      $tpl->statusPickList[4] = 'Approved';
      $tpl->statusPickList[5] = 'Remove';
    }

    // And process it
    $this->processTemplate('signup.phtml',$tpl);
  }
  function executePost()
  {
    $refRegion    = (int)$this->getPost('referee_region');
    $refFirstName =      $this->getPost('referee_first_name');
    $refLastName  =      $this->getPost('referee_last_name');
    $refAysoid    = (int)$this->getPost('referee_aysoid');
    $refStatus    = (int)$this->getPost('referee_status');
		
    if (!$refAysoid) $refAysoid = NULL;
		
    $errors = NULL;
    if (!$refRegion)    $errors[] = '*** Please enter your region number';
    if (!$refFirstName) $errors[] = '*** Please enter your first name';
    if (!$refLastName)  $errors[] = '*** Please enter your last name';
		
    $gameId = (int)$this->getPost('game_id');
    $posId  = (int)$this->getPost('pos_id');
		
    // Check data
    $relocate = "location: index.php?page=schedule";
    if (!$gameId || !$posId) return header($relocate);
    if (!$this->isReferee()) return header($relocate);
		
    // Ok so far
    $relocateOk = "location: index.php?page=signup&game={$gameId}&pos={$posId}";
    $db = $this->getDb();
		
    // See if a removal
    if ($refStatus == 5)
    {
      if (!$this->isAdmin()) return header($relocate);
    //if (!$this->getPost('referee_remove_confirm')) return header($relocateOk);
			
      $db = $this->getDb();
      $sql = "DELETE FROM game_person WHERE game_num = :game_id AND pos_id = :pos_id;";
      $params = array
      (
        'game_id' => $gameId,
	'pos_id'  => $posId,
      );
      $db->execute($sql,$params);
      $_SESSION['ref_status'] = 1;
      return header($relocateOk);
    }
    $_SESSION['ref_region']     = $refRegion;
    $_SESSION['ref_first_name'] = $refFirstName;
    $_SESSION['ref_last_name']  = $refLastName;
    $_SESSION['ref_aysoid']     = $refAysoid;
    $_SESSION['ref_status']     = $refStatus;
    $_SESSION['ref_errors']     = $errors;
		
    if ($errors) return header($relocateOk);
		
    // See if have an existing record
    $sql = "SELECT * FROM game_person WHERE game_num = :game_id AND pos_id = :pos_id;";
    $params = array
    (
      'game_id' => $gameId,
      'pos_id'  => $posId,
    );
    $row = $db->fetchRow($sql,$params);
    if (!$row)
    {
      $row['game_person_id'] = NULL;
    }
    // Xfer data
    $row['aysoid']   = $refAysoid;
    $row['fname']    = $refFirstName;
    $row['lname']    = $refLastName;
    $row['region']   = $refRegion;
    $row['status']   = $refStatus;
    $row['game_num'] = $gameId;
    $row['pos_id']   = $posId;
    $row['notes']    = NULL;

    if ($row['game_person_id']) $db->update('game_person','game_person_id',$row);
    else                        $db->insert('game_person','game_person_id',$row);
		
    return header($relocateOk);
  }
}
?>