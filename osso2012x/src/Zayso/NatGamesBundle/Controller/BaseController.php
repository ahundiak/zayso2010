<?php

namespace Zayso\NatGamesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Zayso\ZaysoBundle\Component\Security\Core\User\User as User;
use Zayso\ZaysoBundle\Component\Format\HTML as FormatHTML;

class BaseController extends Controller
{
    // protected $user = null;

    protected function getEntityManager()
    {
        return $this->getDoctrine()->getEntityManager('osso2012');
    }
    protected function getAccountManager()
    {
        return $this->get('account.manager');
    }
    protected function getProjectManager()
    {
        return $this->get('zayso.core.project.manager');
    }
    protected function getSession(Request $request = null)
    {
        if ($request) return $request->getSession();
        return $this->getRequest()->getSession();
    }
    protected function getProjectId()
    {
        return 52;
    }
    // Be aware that this returns the string anon for non users
    protected function getUser()
    {
        return $this->get('security.context')->getToken()->getUser();
    }
    // Takes either userName or an actual user object
    protected function setUser($userName)
    {
        if (is_object($userName)) $user = $userName;
        else
        {
            $userProvider = $this->get('security.user.provider.zayso');
            $user = $userProvider->loadUserByUsername($userName);
        }
        $providerKey = 'secured_area';
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->get('security.context')->setToken($token);
        return $user;
    }
    protected function getProjectPerson()
    {
        $accountManager = $this->getAccountManager();
        $user = $this->getUser();
        $params = array('personId' => $user->getPersonId(),'projectId' => $this->getProjectId());
        $projectPerson = $accountManager->getProjectPerson($params);
        return $projectPerson;
    }
    protected function getOpenidProfile(Request $request)
    {
        $rpxApiKey = '827e548231829d8f561f30efda43155b2cd4b1e5';

        $token = $request->get('token');
        if (!$token)
        {
            return 'No social network token';
        }

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
            $error = $authInfo['err']['msg'];
            return $error;
        }

        return $authInfo['profile'];
    }
    protected function getUserx()
    {
        if ($this->user) return $this->user;

        $session = $this->getRequest()->getSession();
//print_r($session->all()); die('attributes');
        $userData = $session->get('userData');
//print_r($userData); die();
        $accountId = 0;
        $memberId  = 0;
        $projectId = $this->getProjectId();

        if (isset($userData['accountId'])) $accountId = $userData['accountId'];
        if (isset($userData['memberId' ])) $memberId  = $userData['memberId'];
        if (isset($userData['projectId'])) $projectId = $userData['projectId'];

        $this->user = new User($this->container);
        $this->user->load($accountId,$memberId,$projectId);
        return $this->user;
    }
    protected function getTplData()
    {
        $tplData = array
        (
            // 'user'   => $this->getUser(),
            'format' =>  new FormatHTML(),
        );
        return $tplData;
    }
}
?>
