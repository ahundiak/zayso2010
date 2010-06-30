<?php
class Direct_User_UserSignIn extends ExtJS_Direct_Base
{
  function signout($params = array())
  {
    $userInfo = $this->context->getUserDefaultInfo();
    $data = array
    (
      'member_id'   => $userInfo['id'],
      'member_name' => $userInfo['name'],
    );
    $this->context->setSessionData($data);

    $results = array
    (
      'success'   => true,
      'member_id' => $userInfo['id'],
    );
    return $results;
  }
  function load($params = array())
  {
    // Should use session information to pre-populate sign in information
    $sessionData = $this->context->getSessionData();

    $userId = $sessionData['member_id'];
    if ($userId == -1) $userId = -2; // Default to referee

    $userInfo = $this->context->getUserInfo($userId);

    $results = array
    (
      'success' => true,
      'data'    => array
      (
        'user_name' => $userInfo['name'],
	'user_pass' => $userInfo['pass'],
      )
    );
    return $results;
  }
  protected function checkPredefined($userName,$userPass)
  {
    $users = $this->context->getUsersPredefined();

    $userName = ucfirst(strtolower($userName));
    
    // Check for predefined
    if (!isset($users[$userName])) return NULL;

    $userInfo = $users[$userName];

    if ($userInfo['pass'] && ($userInfo['pass'] != $userPass))
    {
      return array
      (
        'success' => false,
        'msg'     => 'Sign on failed!',
        'errors'  => array('user_pass' => 'Missing or invalid password'),
      );
    }
    $sessionData = array
    (
      'member_id'   => $userInfo['id'],
      'member_name' => $userName,
    );
    $this->context->setSessionData($sessionData);
    
    return array
    (
      'success'   => true,
      'msg'       => 'You are signed on!',
      'member_id' => $userInfo['id'],
    );
  }
  function submit($params)
  {
    if (isset($params['user_name'])) $userName = $params['user_name'];
    else                             $userName = '';
		
    if (isset($params['user_pass'])) $userPass = $params['user_pass'];
    else                             $userPass = '';

    // Check for predefined standard users
    $results = $this->checkPredefined($userName,$userPass);
    if ($results) return $results;

    // Lookup in zayso
    $userPass = md5($userPass);
		
    // Usual query
    $sql = <<<EOT
SELECT
  account.account_id AS account_id,
  member.member_id   AS member_id
FROM
  account
LEFT JOIN member ON member.account_id = account.account_id
WHERE
  account.account_user = :user_name AND
  account.account_pass = :user_pass AND
  member.level = 1
EOT;
    
		$paramsx = array
		(
		  'user_name' => $userName,
		  'user_pass' => $userPass
		);
		$db   = $this->context->db;
    $rows = $db->fetchRows($sql,$paramsx);
		
    if (count($rows)) // Add check for number of rows just in case member.level is screwed
    {
      $results = array
      (
        'success'   => true,
        'msg'       => 'You are signed on!',
        'member_id' => $rows[0]['member_id']
      );
      return $results;
    }
    // Prepare for failuer
    $results = array
    (
      'success' => false,
      'msg'     => 'Sign on failed!',
      'errors'  => array()
    );
    // See if account exists
    $sql = <<<EOT
SELECT
  account.account_id   AS account_id,
  account.account_pass AS account_pass
FROM
  account
WHERE
  account.account_user = :user_name
EOT;

    $paramsx = array
    (
      'user_name' => $userName
    );
    $db   = $this->context->db;
    $rows = $db->fetchRows($sql,$paramsx);
    if (count($rows) == 0)
    {
      $results['errors'] = array('user_name' => 'Missing or invalid user name');
      return $results;	
    }
    $row = $rows[0];
    if ($row['account_pass'] != $userPass)
    {
      $results['errors'] = array('user_pass' => 'Missing or invalid password');
    	return $results;
    }
    // Something is hosed up with link to member account
    $results['errors'] = array('user_name' => 'Problem with account, please contact the administrator');
    return $results;  
	}
}
?>