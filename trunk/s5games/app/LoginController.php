<?php
class LoginController extends Controller
{
  function executeGet()
  {
    header("location: index.php");
  }
  function executePost()
  {
    $post = $this->context->post;

    $errors = NULL;

    $userAysoid = $post->get('user_aysoid');
    $userIsAuth = FALSE;
    $userPass = $post->get('user_pass');
    $userPassIsCorrect = FALSE;

    switch($userPass)
    {
      case 's5games':
        $userName = 'General';
        $userPassIsCorrect = TRUE;
        break;
		
      case 'soccer894':
        $userName = 'Admin';
        $userPassIsCorrect = TRUE;
        //if (!$userAysoid) $userAysoid = '99437977';
	break;
		
      default:
        $userName = 'Public';
        $errors[] = '*** Incorrect password';
    }
    // Verify have a valid id
    $user = new User($this->context);
    $user->loadEayso($userAysoid);

    if ($user->isInEayso) $userIsAuth = TRUE;
    else
    {
      $errors[] = '*** AYSOID is invalid or not current';
      $userIsAuth = FALSE;
    }

    if (!$userPassIsCorrect) $userIsAuth = FALSE;

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