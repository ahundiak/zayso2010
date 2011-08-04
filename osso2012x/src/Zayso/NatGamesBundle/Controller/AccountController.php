<?php

namespace Zayso\NatGamesBundle\Controller;

class AccountController extends BaseController
{
    public function signoutAction()
    {
        $session = $this->getSession();
        $session->set('userData',null);
        return $this->redirect($this->generateUrl('_natgames_welcomex'));
    }
    public function signinAction()
    {
        $session = $this->getSession();
        $accountSigninData = $session->get('accountSigninData');

        if ($accountSigninData)
        {
            $errors = $session->getFlash('errors');
            $accountSigninData['errors'] = $errors;
        }
        else
        {
            $accountSigninData = array
            (
                'userName' => '',
                'userPass' => '',
                'errors'   => array(),
            );
        }
        $tplData = $this->getTplData();
        $tplData['accountSigninData'] = $accountSigninData;
        
        return $this->render('NatGamesBundle:Account:signin.html.twig',$tplData);
    }
    public function signinPostAction()
    {
        $em = $this->getEntityManager();

        $request = $this->getRequest();
        $session = $this->getSession();

        $accountSigninData = $request->request->get('accountSigninData');

        // Really don't want to store the passwords
        $accountSigninDatax = $accountSigninData;
        $accountSigninDatax['userPass'] = '';
        $session->set('accountSigninData',$accountSigninDatax);

        $accountRepo = $em->getRepository('ZaysoBundle:Account');
        $account = $accountRepo->findForUserName($accountSigninData['userName'],$accountSigninData['userPass']);
        if (is_array($account))
        {
            $session->setFlash('errors',$account);
            return $this->redirect($this->generateUrl('_natgames_account_signin'));
        }
        // Signin
        $member = $account->getPrimaryMember();
        $userData = array
        (
            'accountId' => $account->getId(),
            'memberId'  => $member->getId(),
            'personId'  => $member->getPerson()->getId(), // Probably don't need
            'projectId' => $this->getProjectId(),
        );
        $session->set('userData',$userData);

        return $this->redirect($this->generateUrl('_natgames_home'));
    }
    public function createAction()
    {
        $tplData = $this->getTplData();
    
        $session = $this->getSession();
        $accountCreateData = $session->get('accountCreateData');
    
        if ($accountCreateData)
        {
            $errors = $session->getFlash('errors');
            $accountCreateData['errors'] = $errors;
        }
        else
        {
            $accountCreateData = array
            (
                'userName'  => '',
                'userPass1' => '',
                'userPass2' => '',
                'aysoid'    => '',
                'firstName' => '',
                'lastName'  => '',
                'nickName'  => '',
                'email'     => '',
                'cellPhone' => '',
                'region'    => 0,
                'refBadge'  => 'None',
                'errors'    => null, // array('E1','E2'),
            );
        }
        $refBadgePickList = array
        (
            'None'         => 'None',
            'Regional'     => 'Regional',
            'Intermediate' => 'Intermediate',
            'Advanced'     => 'Advanced',
            'National'     => "National",
            'National 2'   => 'National 2',
            'Assistant'    => 'Assistant',
            'U8 Official'  => 'U8',
        );
        $tplData['accountCreateData'] = $accountCreateData;
        $tplData['refBadgePickList']  = $refBadgePickList;
    
        return $this->render('NatGamesBundle:Account:create.html.twig',$tplData);
    }
    public function createPostAction()
    {
        $em = $this->getEntityManager();

        $request = $this->getRequest();
    
        $accountCreateData = $request->request->get('accountCreateData');

        // Really don't want to store the passwords
        $accountCreateDatax = $accountCreateData;
        $accountCreateDatax['userPass1'] = '';
        $accountCreateDatax['userPass2'] = '';
        $session = $this->getRequest()->getSession();
        $session->set('accountCreateData',$accountCreateDatax);

        $accountRepo = $em->getRepository('ZaysoBundle:Account');
        $account = $accountRepo->create($accountCreateData);
        if (is_array($account))
        {
            $session->setFlash('errors',$account);
            return $this->redirect($this->generateUrl('_natgames_account_create'));
        }

        // Setup project person
        $projectId = $this->getProjectId();
        $personId  = $account->getPrimaryPersonId();

        $projectRepo = $em->getRepository('ZaysoBundle:Project');

        $projectPerson = $projectRepo->loadProjectPerson($projectId,$personId);
        $projectPerson->set('accountCreateData',$accountCreateDatax);

        $todo = array('todoPlans' => true, 'todoOpenid' => true, 'todoRefLevel' => true);
        $projectPerson->set('todo',$todo);
        $em->flush();

        // Signin
        $member = $account->getPrimaryMember();
        $userData = array
        (
            'accountId' => $account->getId(),
            'memberId'  => $member->getId(),
            'personId'  => $personId,
            'projectId' => $projectId,
        );
        $session->set('userData',$userData);
    
        //print_r($accountCreateData); die();
    
        return $this->redirect($this->generateUrl('_natgames_home'));
  }
}
