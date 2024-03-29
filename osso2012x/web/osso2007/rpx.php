<?php
// Below is a very simple PHP 5 script that implements the RPX token URL processing.
// The code below assumes you have the CURL HTTP fetching library.

$rpxApiKey = '827e548231829d8f561f30efda43155b2cd4b1e5';

if(isset($_POST['token'])) {

  /* STEP 1: Extract token POST parameter */
  $token = $_POST['token'];

  /* STEP 2: Use the token to make the auth_info API call */
  $post_data = array('token' => $_POST['token'],
                     'apiKey' => $rpxApiKey,
                     'format' => 'json');
// print_r($post_data); echo "\n";
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
//print_r($auth_info);

  if ($auth_info['stat'] == 'ok') {

    /* STEP 3 Continued: Extract the 'identifier' from the response */
    $profile = $auth_info['profile'];
    $identifier = $profile['identifier'];
    $identifierx = htmlspecialchars($identifier);
    $identifierx = str_replace('/','_',$identifier);
    header("Location: http://local.osso2010.org/osso2007/home/openid/$identifierx");
    return;

    if (isset($profile['photo'])) {
      $photo_url = $profile['photo'];
    }

    if (isset($profile['displayName'])) {
      $name = $profile['displayName'];
    }

    if (isset($profile['email'])) {
      $email = $profile['email'];
    }

    /* STEP 4: Use the identifier as the unique key to sign the user into your system.
This will depend on your website implementation, and you should add your own
code here.
*/

/* an error occurred */
} else {
  // gracefully handle the error. Hook this into your native error handling system.
  echo 'An error occured: ' . $auth_info['err']['msg'];
}
}
?>

