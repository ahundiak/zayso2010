<?php
namespace Zayso\S5GamesBundle\Controller\Account;

use Zayso\CoreBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;

class CreateController extends BaseController
{
    public function createAction(Request $request)
    {
        // Must have a profile to get here
        $profile = $request->getSession()->get('openidProfile');
        if (!$this->isProfileValid($profile)) $profile = null;
        if (!$profile)
        {
            return $this->redirect($this->generateUrl('zayso_s5games_welcome'));
        }
        // New form stuff
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->newAccountPersonAyso();

        // Init with profile info
        $accountPerson->setOpenidDisplayName($profile['displayName']);
        $accountPerson->setOpenidProvider   ($profile['providerName']);

        if (isset($profile['preferredUsername'])) $accountPerson->setUserName($profile['preferredUsername']);
        if (isset($profile['email']))             $accountPerson->setEmail($profile['email']);
        if (isset($profile['verifiedEmail']))     $accountPerson->setEmail($profile['verifiedEmail']);
            
        if (isset($profile['name']['givenName' ])) $accountPerson->setFirstName($profile['name']['givenName']);
        if (isset($profile['name']['familyName'])) $accountPerson->setLastName ($profile['name']['familyName']);
        
        $accountFormType = $this->get('zayso_s5games.account.create.formtype');

        $form = $this->createForm($accountFormType, $accountPerson);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $accountPerson->setProjectPersonData($this->getProjectId());
                if ($profile)
                {
                    $openid = $accountPerson->getFirstOpenid();
                    $openid->setProfile($profile);
                }
                $account = $accountManager->createAccountFromAccountPersonAyso($accountPerson);
                
                if ($account) 
                {
                    // Send email
                    
                    // And sign in
                    $user = $this->setUser($account->getUserName());
                    
                    $subject = sprintf('[Area] - Created %s %s %s',$user->getName(),$user->getRegion(),$user->getAysoid());
                    $this->sendEmail($subject,$subject);
                    
                    return $this->redirect($this->generateUrl('zayso_area_home'));
                }
            }
        }
        $tplData = array();
        $tplData['form'] = $form->createView();

        return $this->render('ZaysoS5GamesBundle:Account:create.html.twig',$tplData);
    }
    public function createAccount($accountPerson)
    {
        $accountManager = $this->getAccountManager();

        // See if already have a person
        $person = $accountManager->getPerson(array('projectId' => $this->getProjectId(),'aysoid' => $accountPerson->getAysoid()));
        if ($person) {
            $accountPerson->setPerson($person);
        }
        else
        {
            // Check for same person, different project
            // If found copy existing newProjectPerson and set it
        }

        $em = $accountManager->getEntityManager();
        $em->persist($accountPerson); // Everything cascades
        $em->flush();
        
        $mailerEnabled = $this->container->getParameter('mailer_enabled');
        if (!$mailerEnabled) return $accountPerson->getAccount();
        
        $message = \Swift_Message::newInstance();
        $message->setSubject('[NatGames2012] New Account ' . $accountPerson->getUserName());
        $message->setFrom('ahundiak@zayso.org');
        $message->setTo  ('ahundiak@gmail.com');
        
        //$message->setBody('The Body');
        
        $message->setBody($this->renderView('NatGamesBundle:Account:email.txt.twig', array('ap' => $accountPerson)));

        $this->get('mailer')->send($message);


        return $accountPerson->getAccount();
  }
    /* ===============================================
     * Use this check because sometime the session variable
     * seems to go away
     */
    protected function isProfileValid($profile)
    {
        if (!is_array($profile)) return false;

        // Must have identifier
        if (!isset($profile['identifier']) || (strlen($profile['identifier']) < 8)) return false;

        // Must have providername
        if (!isset($profile['providerName']) || (strlen($profile['providerName']) < 4)) return false;

        // Good enough
        return true;
    }
}
