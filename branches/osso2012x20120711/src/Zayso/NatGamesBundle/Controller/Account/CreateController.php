<?php
namespace Zayso\NatGamesBundle\Controller\Account;

use Zayso\NatGamesBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;

class CreateController extends BaseController
{
    public function createAction(Request $request)
    {
        // Hook for profile stuff, see area controller
        $profile = null;
        
        // New form stuff
        $accountManager = $this->getAccountManager();
      //$accountPerson = $accountManager->newAccountPerson(array('projectId' => $this->getProjectId()));
        $accountPerson = $accountManager->newAccountPersonAyso();
        
        $accountFormType = $this->get('zayso_natgames.account.create.formtype');

        $form = $this->createForm($accountFormType, $accountPerson);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                // No need to worry about project, handled by home controller
                //$accountPerson->setProjectPersonData($this->getProjectId(),$todo);
                
                if ($profile)
                {
                    $openid = $accountPerson->getFirstOpenid();
                    $openid->setProfile($profile);
                }
                $account = $accountManager->createAccountFromAccountPersonAyso($accountPerson);
                
                if ($account) 
                {
                    // Send email
                    $this->sendEmail($account);
                    
                    // Done
                    $this->setUser($account->getUserName());
                    return $this->redirect($this->generateUrl('zayso_natgames_home'));
                }
            }
        }
        $tplData = $this->getTplData();
        $tplData['form'] = $form->createView();

        return $this->render('ZaysoNatGamesBundle:Account:create.html.twig',$tplData);
    }
    protected function sendEmail($account)
    {
        $mailerEnabled = $this->container->getParameter('mailer_enabled');
        if (!$mailerEnabled) return;
        
        $accountPerson = $account->getPrimaryAccountPerson();
        
        $message = \Swift_Message::newInstance();
        $message->setSubject('[NatGames2012] New Account ' . $account->getUserName());
        $message->setFrom('ahundiak@zayso.org');
        $message->setTo  ('ahundiak@gmail.com');
        
        $message->setBody($this->renderView('ZaysoNatGamesBundle:Account:email.txt.twig', array('ap' => $accountPerson)));

        $this->get('mailer')->send($message);
        
    }
}
