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

        $accountFormType = $this->get('account.create.formtype'); // new AccountCreateType($this->getEntityManager());

        $form = $this->createForm($accountFormType, $accountPerson);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $account = $this->createAccount($accountPerson);
                
                if ($account) return $this->redirect($this->generateUrl('_natgames_home'));
                
                // Try again!
                // return $this->redirect($this->generateUrl('_natgames_account_create'));
            }
        }
        $tplData = $this->getTplData();
        $tplData['form'] = $form->createView();

        return $this->render('NatGamesBundle:Account:create.html.twig',$tplData);
    }
    public function createAccount($accountPerson)
    {
        $accountManager = $this->getAccountManager();
        $em = $accountManager->getEntityManager();

        $person = $accountManager->getPerson(array('projectId' => $this->getProjectId(),'aysoid' => $accountPerson->getAysoid()));
        //die('Count ' . count($person->getMembers()));
        if ($person) {
            $accountPerson->setPerson($person);
            // $projectPerson = $person->getNatGamesProjectPerson();
            //die($projectPerson->getId() . ' ' . $projectPerson->getPerson()->getId() . ' ' . $projectPerson->getProject()->getId());
        }
        else
        {
            // Check for same person, different project
            // If found copy existing newProjectPerson and set it
        }
        // $members = $accountPerson->getPerson()->getMembers();
        //die('Count ' . count($accountPerson->getPerson()->getNatGamesProjectPerson()->getProject()->getPersons()));

        $em->persist($accountPerson);
      //$em->persist($accountPerson->getAccount());
      //$em->persist($accountPerson->getPerson());
      //$em->persist($accountPerson->getPerson()->getNatGamesProjectPerson());
      //$em->persist($accountPerson->getPerson()->getAysoRegisteredPerson());

        // Should be try/catch
        $em->flush();

        // Signin
        $account = $accountPerson->getAccount();
        $member  = $accountPerson; // $account->getPrimaryMember();
        $userData = array
        (
            'accountId' => $account->getId(),
            'memberId'  => $member->getId(),
          //'personId'  => $member->getPerson()->getId(),
            'projectId' => $this->getProjectId(),
        );
        $this->getSession()->set('userData',$userData);

        // Also save signin information
        $accountSigninData = array
        (
            'userName' => $account->getUserName(),
            'userPass' => '',
        );
        $this->getSession()->set('accountSigninData',$accountSigninData);
    
        return $account;
        
        return $this->redirect($this->generateUrl('_natgames_home'));
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
