<?php

namespace Zayso\ZaysoAreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class BaseController extends Controller
{
    protected function getAccountManager()
    {
        return $this->get('zayso_area.account.manager');
    }
    protected function getProjectManager()
    {
        return $this->get('zayso.core.project.manager');
    }
    protected function getProjectId()
    {
        return 70;
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
            // Need try/catch here
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
    protected function getTplData()
    {
        $tplData = array
        (
            // 'user'   => $this->getUser(),
            'format' =>  new FormatHTML(),
        );
        return $tplData;
    }
    protected function sendEmail($subject,$body)
    {
        $mailerEnabled = $this->container->getParameter('mailer_enabled');
        if (!$mailerEnabled) return;
        
        $message = \Swift_Message::newInstance();
        $message->setSubject($subject);
        $message->setFrom('admin@zayso.org');
        $message->setTo  ('ahundiak@gmail.com');
        
        $message->setBody($body);
        
        //$message->setBody($this->renderView('NatGamesBundle:Account:email.txt.twig', array('ap' => $accountPerson)));

        $this->get('mailer')->send($message);

    }
}
?>
