<?php
namespace Zayso\NatGamesBundle\Controller\Account;

use Zayso\NatGamesBundle\Controller\BaseController;

use Symfony\Component\HttpFoundation\Request;

class CreateController extends BaseController
{
    public function createAction(Request $request)
    {
        // New form stuff
        $accountManager = $this->getAccountManager();
        $accountPerson = $accountManager->newAccountPerson(array('projectId' => $this->getProjectId()));

        $accountFormType = $this->get('account.create.formtype');

        $form = $this->createForm($accountFormType, $accountPerson);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $account = $this->createAccount($accountPerson);
                
                if ($account) 
                {
                    $this->setUser($account->getUserName());
                    return $this->redirect($this->generateUrl('zayso_natgames_home'));
                }
            }
        }
        $tplData = $this->getTplData();
        $tplData['form'] = $form->createView();

        return $this->render('ZaysoNatGamesBundle:Account:create.html.twig',$tplData);
    }
    public function createAccount($accountPerson)
    {
        $accountManager = $this->getAccountManager();
        $em = $accountManager->getEntityManager();

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
        // Need to verify existing region
        $regionKey = $accountPerson->getPerson()->getOrgKey();
        $region = $accountManager->getOrgForKey($regionKey);
        if (!$region)
        {
            $region = $accountManager->newOrg();
            $region->setId($regionKey);
            $region->setDesc1('AYSO Region ' . substr($regionKey,5,4));
            $em->persist($region); $em->flush(); // die('Added Org');
        }
        $em->persist($accountPerson); // Everything cascades
        $em->flush();
        
        $mailerEnabled = $this->container->getParameter('mailer_enabled');
        if (!$mailerEnabled) return $accountPerson->getAccount();
        
        $message = \Swift_Message::newInstance();
        $message->setSubject('[NatGames2012] New Account ' . $accountPerson->getUserName());
        $message->setFrom('ahundiak@zayso.org');
        $message->setTo  ('ahundiak@gmail.com');
        
        //$message->setBody('The Body');
        
        $message->setBody($this->renderView('ZaysoNatGamesBundle:Account:email.txt.twig', array('ap' => $accountPerson)));

        $this->get('mailer')->send($message);


        return $accountPerson->getAccount();
  }
  /*
   *             $message = \Swift_Message::newInstance();
            $message->setSubject('Hello Email');
            $message->setFrom('ahundiak@zayso.org');
            $message->setTo  ('ahundiak@gmail.com');
            $message->setBody('The Body');
//      ->setBody($this->renderView('HelloBundle:Hello:email.txt.twig', array('name' => $name)))

            // $this->get('mailer')->send($message);

   */
}
