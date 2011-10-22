<?php

namespace Zayso\NatGamesBundle\Controller;
use Zayso\ZaysoBundle\Component\Debug;

class MemberViewHelper
{
    public function setMember($member)
    {
        $this->member  = $member;
        $this->account = $member->getAccount();
        $this->person  = $member->getPerson();
        $this->plans   = $this->person->getNatGamesProjectPerson()->getPlans();
        return;
    }
    public function getFirstName () { return $this->person->getFirstName(); }
    public function getLastName  () { return $this->person->getLastName(); }
    public function getNickName  () { return $this->person->getNickName(); }
    public function getAysoid    () { return $this->person->getAysoid(); }
    public function getEmail     () { return $this->person->getEmail(); }
    
    public function getCellPhone () 
    { 
        $phone = $this->person->getCellPhone(); 
        $phone = trim(preg_replace('/\D/','',$phone));
        if (!$phone) return $phone;
        
        $phone = substr($phone,0,3) . '.' . substr($phone,3,3) . '.' . substr($phone,6,4);  
        return $phone;
    }
    
    public function getRegion()  
    { 
        
        $region = $this->person->getOrgKey();
        $region = substr($region,4);
        $region = str_replace('-','',$region);
        return $region;
    }
    public function getPlan($name)
    {
        if (isset($this->plans[$name])) return $this->plans[$name];
        return 'NS';
    }
}
class AdminController extends BaseController
{
    protected function isAuth()
    {
        $user = $this->getUser();
        if (!$user->isSignedIn()) return false;
        if (!$user->isAdmin   ()) return false;
        return true;
    }
    public function indexAction()
    {
        // Check auth
        if (!$this->isAuth()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        $tplData = $this->getTplData();
        return $this->render('NatGamesBundle:Admin:index.html.twig',$tplData);
    }
    public function accountsAction($_format)
    {
        
        // Check auth
        if (!$this->isAuth()) return $this->redirect($this->generateUrl('_natgames_welcomex'));

        $accountManager = $this->get('account.manager');
        $accounts = $accountManager->getAccounts();
        
        $account = $accounts[0];
        $person = $account->getPrimaryMember()->getPerson();
        $projectPerson = $person->getNatGamesProjectPerson();
        $plans = $projectPerson->getPlans();
        //Debug::dump($plans);
        //die();
      //die('Count ' . count($accounts));
        
        //$accounts = array($accounts[0],$accounts[1]);
        
        $tplData = $this->getTplData();
        $tplData['accounts'] = $accounts;
        $tplData['memberx']  = new MemberViewHelper();
        
        if ($_format == 'html') return $this->render('NatGamesBundle:Admin:accounts.html.twig',$tplData);
        
        $response = $this->render('NatGamesBundle:Admin:accounts.csv.php',$tplData);
        $response->headers->set('Content-Type', 'application/csv');
        return $response;
    }
}
