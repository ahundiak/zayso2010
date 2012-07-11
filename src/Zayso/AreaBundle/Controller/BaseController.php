<?php

namespace Zayso\AreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class BaseController extends Controller
{
    protected function getAccountManager()
    {
        return $this->get('zayso_area.account.manager');
    }
    protected function getGameManager()
    {
        return $this->get('zayso_area.game.manager');
    }
    protected function getTeamManager()
    {
        return $this->get('zayso_area.team.manager');
    }
    protected function getProjectId()
    {
        return $this->container->getParameter('zayso_area.project.master');
    }
    protected function getCurrentProjectId()
    {
        return $this->container->getParameter('zayso_area.project.current');
    }
    protected function isAdmin()
    {
        return $this->get('security.context')->isGranted('ROLE_ADMIN');
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
            $userProvider = $this->get('zayso_core.user.provider');
            // Need try/catch here
            $user = $userProvider->loadUserByUsername($userName);
        }
        $providerKey = 'secured_area';
        $providerKey = $this->container->getParameter('zayso_area.provider.key'); // secured_area
        
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
