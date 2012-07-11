<?php

namespace Zayso\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class BaseController extends Controller
{
    protected function getAccountManager()
    {
        return $this->get('zayso_core.account.manager');
    }
    protected function getGameManager()
    {
        return $this->get('zayso_core.game.manager');
    }
    protected function getTeamManager()
    {
        return $this->get('zayso_core.team.manager');
    }
    protected function getProjectId()
    {
        return $this->container->getParameter('zayso_core.project.master');
    }
    protected function getMasterProjectId()
    {
        return $this->container->getParameter('zayso_core.project.master');
    }
    protected function getCurrentProjectId()
    {
        return $this->container->getParameter('zayso_core.project.current');
    }
    protected function isAdmin()
    {
        return $this->get('security.context')->isGranted('ROLE_ADMIN');
    }
    protected function isUser()
    {
        return $this->get('security.context')->isGranted('ROLE_USER');
    }
    protected function isUserAdmin()
    {
        return $this->get('security.context')->isGranted('ROLE_ADMIN');
    }
    protected function isUserSuperAdmin()
    {
        return $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
    }
    protected function isUserScorer()
    {
        return $this->get('security.context')->isGranted('ROLE_SCORER');
    }
    protected function isUserScorerx()
    {
        return $this->get('security.context')->isGranted('ROLE_SCORERX');
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
        $providerKey = $this->container->getParameter('zayso_core.provider.key'); // secured_area
        
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->get('security.context')->setToken($token);
        return $user;
    }
    // @Depreciated
    protected function getProjectPerson()
    {
        $manager = $this->getAccountManager();
        $projectPerson = $manager->addProjectPerson($this->getProjectId(),$this->getUser()->getPersonId());
        return $projectPerson;
        
        $accountManager = $this->getAccountManager();
        $user = $this->getUser();
        $params = array('personId' => $user->getPersonId(),'projectId' => $this->getProjectId());
        $projectPerson = $accountManager->getProjectPerson($params);
        return $projectPerson;
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

        $this->get('mailer')->send($message);

    }
    /* =========================================================================
     * Add some automatic bundle name processing
     */
    protected $myBundleName  = 'ZaysoCoreBundle';
    protected $myTitlePrefix = 'ZaysoCore ';
    
    protected function getMyBundleName()
    {
        if ($this->container->hasParameter('my_bundle_name')) return $this->container->getParameter('my_bundle_name');
        return $this->myBundleName;
    }
    protected function getMyTitlePrefix()
    {
        if ($this->container->hasParameter('my_title_prefix')) return $this->container->getParameter('my_title_prefix');
        return $this->myTitlePrefix;
    }
    protected function renderx($tplName,$tplData = array())
    {
        $myBundleName  = $this->getMyBundleName();
        $myTitlePrefix = $this->getMyTitlePrefix();
   
        $tplData['myBundleName']  = $myBundleName;
        $tplData['myTitlePrefix'] = $myTitlePrefix;
        
        // Prepend bundle name if needed
        if (substr_count($tplName,':') < 2) $tplName = $myBundleName . ':' . $tplName;
        
        return $this->render($tplName,$tplData);
    }
}
?>
