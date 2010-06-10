<?php
class SignupController extends Controller
{
  function executeGet()
  {
    $context = $this->context;
    $db      = $context->db;
    $get     = $context->get;
    $user    = $context->user;
    $session = $context->session;

    // Make sure are logged in
    $relocate = "location: index.php?page=schedule";
    if (!$user->isReferee) return header($relocate);
		
    $tpl = new Cerad_Data();
    $tpl->user = $user;

    // Need to have game and position
    $gameId = (int)$get->get('game');
    $posId  = (int)$get->get('pos');
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
    $row = $db->fetchRow($sql,$params);
    if ($row)
    {
      $tpl->refRegion    = $row['region'];
      $tpl->refFirstName = $row['fname'];
      $tpl->refLastName  = $row['lname'];
      $tpl->refAysoid    = $row['aysoid'];
      $tpl->refStatus    = $row['status'];
      $tpl->refAss       = $row['ass_id'];
    }
    // Session data if we have it
    $tpl->errors = $session->get('ref_errors');
		
    if (!$row || $tpl->errors)
    {
      $tpl->refRegion    = $session->get('ref_region');
      $tpl->refFirstName = $session->get('ref_first_name');
      $tpl->refLastName  = $session->get('ref_last_name');
      $tpl->refAysoid    = $session->get('ref_aysoid');
      $tpl->refStatus    = $session->get('ref_status',1);
      $tpl->refAss       = $session->get('ref_ass',0);
    }
    if ($tpl->errors) $session->set('ref_errors',NULL);

    if (!$tpl->refAysoid)
    {
      $tpl->refAysoid = $user->aysoid;
    }
    // Always need aysoid
    $ref = new User($context);
    $ref->loadEayso($tpl->refAysoid);
    $tpl->refRegion    = $ref->region;
    $tpl->refFirstName = $ref->fnamex;
    $tpl->refLastName  = $ref->lname;
    $tpl->refBadge     = $ref->getRefereeBadgeDesc();

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
    $query = new Query($db);
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
      $posId = $personx->posId;
      $persons[$posId]['name'] = $personx->desc;
    }
    $tpl->persons = $persons;
		
    // Status options
    $tpl->statusPickList = array
    (
      1 => 'Request this game',
      2 => 'Ref only if needed',
    );
    if ($user->isAdmin)
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
    $context = $this->context;
    $user    = $context->user;
    $post    = $context->post;
    $session = $context->session;

    $refRegion    = (int)$post->get('referee_region');
    $refFirstName =      $post->get('referee_first_name');
    $refLastName  =      $post->get('referee_last_name');

    $refAysoid    = (int)$post->get('referee_aysoid');
    $refStatus    = (int)$post->get('referee_status');
    $refAss       = (int)$post->get('referee_ass');
		
    // if (!$refAysoid) $refAysoid = NULL;
		
    $errors = NULL;
    //if (!$refRegion)    $errors[] = '*** Please enter your region number';
    //if (!$refFirstName) $errors[] = '*** Please enter your first name';
    //if (!$refLastName)  $errors[] = '*** Please enter your last name';
    if (!$refAysoid)    $errors[] = '*** Please enter your 8 digit ayso volunteer number';

    $ref = new User($context);
    $ref->loadEayso($refAysoid);
    $refRegion    = $ref->region;
    $refFirstName = $ref->fnamex;
    $refLastName  = $ref->lname;

    if ($refAysoid)
    {
      if (!$ref->isInEayso) $errors[] = '*** The volunteer number is not valid or current';
      if (!$ref->isReferee) $errors[] = '*** Volunteer is not a current certified referee';
    }
    $gameId = (int)$post->get('game_id');
    $posId  = (int)$post->get('pos_id');
		
    // Check data
    $relocate = "location: index.php?page=schedule";
    if (!$gameId || !$posId) return header($relocate);
    if (!$user->isReferee)   return header($relocate);
		
    // Ok so far
    $relocateOk = "location: index.php?page=signup&game={$gameId}&pos={$posId}";
    $db = $context->db;
		
    // See if a removal
    if ($refStatus == 5)
    {
      if (!$user->isAdmin) return header($relocate);
    //if (!$this->getPost('referee_remove_confirm')) return header($relocateOk);
			
      $sql = "DELETE FROM game_person WHERE game_num = :game_id AND pos_id = :pos_id;";
      $params = array
      (
        'game_id' => $gameId,
	'pos_id'  => $posId,
      );
      $db->execute($sql,$params);
      $session->set('ref_status',1);
      $session->set('ref_aysoid',$refAysoid);
      return header($relocateOk);
    }
    $session->set('ref_region',    $refRegion);
    $session->set('ref_first_name',$refFirstName);
    $session->set('ref_last_name', $refLastName);
    $session->set('ref_aysoid',    $refAysoid);
    $session->set('ref_status',    $refStatus);
    $session->set('ref_ass',       $refAss);
    $session->set('ref_errors',    $errors);
		
    if ($errors) return header($relocateOk);

    // Might have been just a lookup
    if (!$post->get('referee_signup')) return header($relocateOk);
    
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
    $row['ass_id']   = $refAss;
    $row['notes']    = NULL;

    if ($user->isAdmin || !MYAPP_CONFIG_DISABLE_SIGNUPS)
    {
      if ($row['game_person_id']) $db->update('game_person','game_person_id',$row);
      else                        $db->insert('game_person','game_person_id',$row);
    }
    return header($relocateOk);
  }
}
?>