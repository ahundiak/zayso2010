<?php

namespace Zayso\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;

class OpenidProfile
{
    protected $template = array
    (
        'name' => array('givenName' => '', 'familyName' => '', 'formatted' => '' ),
        'verifiedEmail'     => '',
        'displayName'       => '',
        'preferredUsername' => '',
        'providerName'      => '',
        'identifier'        => '',
        'email'             => '',
    );

    function __construct($data = array())
    {
        $this->data = array_merge($this->template,$data);
    }

    public function __get($name)
    {
        if (isset($this->data[$name])) return $this->data[$name];
        
        return null;
    }
    public function __isset($name)
    {
        if (isset($this->data[$name])) return true;
        return false;
    }
}
class SecurityController extends Controller
{
    public function rpxAction(Request $request)
    {
        $rpxApiKey = '827e548231829d8f561f30efda43155b2cd4b1e5';

        $token = $request->get('token');
        if (!$token) die('no token');  // If yu cancel out
        
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
        $authInfo = json_decode($raw_json, true);

        if ($authInfo['stat'] != 'ok')
        {
            $error = $authInfo['err']['msg'];die($error);
            return $this->redirect('/login');
        }

        /* STEP 4 Continued: Extract the 'identifier' from the response */
        $profile  = new OpenidProfile($authInfo['profile']);

        //$identifier   = $profile['identifier'];
        //$providerName = $profile['providerName'];


        //print_r($authInfo);
        //die($identifier);

        return $this->render('UserBundle:Security:openid.html.twig', array(
            'profile' => $profile,
        ));
    }
        /* ===============
         * Openid
         * Array ( [profile] => Array (
         * [displayName] => ahundiak
         * [preferredUsername] => ahundiak
         * [url] => http://ahundiak.myopenid.com/
         * [providerName] => MyOpenID
         * [identifier] => http://ahundiak.myopenid.com/ )
         * [stat] => ok ) http://ahundiak.myopenid.com/
         */
        /* ===============
         * Array ( [profile] => Array (
         *  [googleUserId] => 113055156735633728525
         *  [name] => Array ( [givenName] => Art [familyName] => Hundiak [formatted] => Art Hundiak )
         *  [verifiedEmail] => ahundiak@gmail.com
         *  [displayName] => ahundiak
         *  [preferredUsername] => ahundiak
         *  [providerName] => Google
         *  [identifier] => https://www.google.com/accounts/o8/id?id=AItOawlw690VK5sxrrejWazT_iCy_cMC6Xs2fv4
         *  [email] => ahundiak@gmail.com )
         *
         *  [stat] => ok ) https://www.google.com/accounts/o8/id?id=AItOawlw690VK5sxrrejWazT_iCy_cMC6Xs2fv4    }
         */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('UserBundle:Security:login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }
}
