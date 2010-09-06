<?php
class Osso2007_Account_Openid_OpenidLinkRpxAction extends Osso2007_Action
{
  public function processGet($args)
  {
    // Should not happen
    return $this->redirect('account_openid_link');

    die('Osso2007_Account_Openid_OpenidLinkRpxAction Get');
    $view = new Osso2007_Account_Openid_OpenidLinkView($this->context);
    $data = array();
    $view->process($data);
    return;
  }
  public function processPost($args)
  {
    // Cerad_Debug::dump($_POST);
    // die('Osso2007_Account_Openid_OpenidLinkRpxAction Post');

    $rpxApiKey = '827e548231829d8f561f30efda43155b2cd4b1e5';

    // Pull the token
    $request = $this->context->request;
    $token   = $request->getPost('token');
    if (!$token) return $this->redirect('account_openid_link');

    /* STEP 2: Use the token to make the auth_info API call */
    $post_data = array
    (
      'token'  => $token,
      'apiKey' => $rpxApiKey,
      'format' => 'json'
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, 'https://rpxnow.com/api/v2/auth_info');
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $raw_json = curl_exec($curl);
    curl_close($curl);

    /* STEP 3: Parse the JSON auth_info response */
    $auth_info = json_decode($raw_json, true);
  //Cerad_Debug::dump($auth_info); die();
    
    if ($auth_info['stat'] != 'ok')
    {
      $error = $auth_info['err']['msg'];
      return $this->redirect('account_openid_link');
    }

    /* STEP 3 Continued: Extract the 'identifier' from the response */
    $profile      = $auth_info['profile'];
    $identifier   = $profile['identifier'];
    $providerName = $profile['providerName'];

    // Twitter
    if (isset($profile['email'])) $email = $profile['email'];
    else                          $email = '';

    $user = $this->context->user;
    $accountId = $user->account->id;
    $memberId  = $user->member->id;
    $personId  = $user->person->id;

    if (!$memberId) return $this->redirect('account_openid_link');

    $data = array
    (
      'account_id' => $accountId,
      'member_id'  => $memberId,
      'person_id'  => $personId,
      'status'     => 1,

      'display_name' => $profile['displayName'],
      'user_name'    => $profile['preferredUsername'],

      'email'        => $email,
      'provider'     => $providerName,
      'identifier'   => $identifier,
    );
    $direct = new Osso2007_Account_Openid_OpenidDirect($this->context);
    $direct->insert($data);
    
    return $this->redirect('account_openid_link');

    Cerad_Debug::dump($data);die();
  }
}
?>
