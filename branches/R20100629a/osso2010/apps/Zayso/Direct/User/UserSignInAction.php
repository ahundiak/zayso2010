<?php
class Direct_User_UserSignInAction extends Direct_BaseAction
{
	function load($params = array())
	{
		// Should use session information to pre-populate sign in information
		$results = array
		(
		  'success' => true,
		  'data'    => array
		  (
		    'user_name' => 'ahundiak',
		    'user_pass' => 'qwepoi'
		  )
		);
		return $results;
	}
	function submit($params)
	{
		if (isset($params['user_name'])) $userName = $params['user_name'];
		else                             $userName = '';
		
    if (isset($params['user_pass'])) $userPass = $params['user_pass'];
    else                             $userPass = '';
		
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