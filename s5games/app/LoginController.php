<?php
class LoginController extends Controller
{
  function executeGet()
  {
    header("location: index.php");
  }
  function tryOSSO($userName,$userPass)
  {
    $userPass = md5($userPass);

    // Usual query
    $sql = <<<EOT
SELECT
  account.account_id AS account_id,
  member.member_id   AS member_id,
  person.aysoid      AS aysoid
FROM
  account
LEFT JOIN member ON member.account_id = account.account_id
LEFT JOIN person ON person.person_id  = member.member_id
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
    $db = $this->context->dbOSSO;

    $row = $db->fetchRow($sql,$paramsx);

    if (!$row) return NULL;

    if (!$row['aysoid']) return NULL;

    return $row['aysoid'];
  }
  function executePost()
  {
    $post = $this->context->post;

    $errors = NULL;

    $userAysoid = $post->get('user_name');
    $userName   = $post->get('user_name');
    $userPass   = $post->get('user_pass');
    
    $userIsAuth = FALSE;
    $userIsOSSO = FALSE;

    // See if in osso
    $aysoid = $this->tryOSSO($userName,$userPass);
    if ($aysoid)
    {
      $userAysoid = $aysoid;
      $userIsOSSO = TRUE;
    }
    // Load the user
    $user = new User($this->context);
    $user->loadEayso($userAysoid);

    // Verify have a valid aysoid
    if ($user->isInEayso) $userIsAuth = TRUE;
    else
    {
      $errors[] = '*** AYSOID is invalid or not current';
      $userIsAuth = FALSE;
    }

    // Check password
    if (!$userIsOSSO)
    {
      if ($userPass != 's5games') $userAuth = FALSE;

      // Admin must sign in from osso
      if ($user->isAdmin)
      {
        // $user = new User($this->context);
        $userIsAuth = FALSE;
        $errors[] = '*** Invalid Admin Login Attempt';
      }
    }
    $session = $this->context->session;
    
    $session->set('user_name',   $userName);
    $session->set('user_pass',   $userPass);
    $session->set('user_aysoid', $userAysoid);
    $session->set('user_is_auth',$userIsAuth);
    $session->set('user_errors', $errors);

    if ($userIsAuth)
    {
      header("location: index.php?page=schedule");
      return;
    }
    header("location: index.php");
  }
}
?>