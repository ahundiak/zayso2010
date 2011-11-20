<?php

namespace Zayso\NatGamesBundle\Controller\Admin\Account;

use Zayso\NatGamesBundle\Controller\BaseController;

use Zayso\ZaysoBundle\Component\Debug;

class AdminAccountListViewHelper
{
    protected function escape($value)
    {
        return htmlspecialchars($value);
    }
    public function setMember($member)
    {
        $this->member  = $member;
        $this->account = $member->getAccount();
        $this->person  = $member->getPerson();
        $this->plans   = $this->person->getNatGamesProjectPerson()->getPlans();

        $this->registeredPerson = $this->person->getAysoRegisteredPerson();
        return;
    }
    public function getUserName  () { return $this->account->getUserName(); }
    public function getFirstName () { return $this->person->getFirstName(); }
    public function getLastName  () { return $this->person->getLastName(); }
    public function getNickName  () { return $this->person->getNickName(); }
    public function getAysoid    () { return substr($this->registeredPerson->getRegKey(),5); }
    public function getEmail     () { return $this->person->getEmail(); }
    public function getDob       () { return $this->person->getDob(); }
    public function getGender    () { return $this->person->getGender(); }
    
    public function getRefBadge  () { return $this->registeredPerson->getRefBadge(); }
    public function getRefDate   () { return $this->registeredPerson->getRefDate(); }
    public function getSafeHaven () { return $this->registeredPerson->getSafeHaven(); }
    public function getMemYear   () { return 'MY' . $this->registeredPerson->getMemYear(); }

    public function getGenderYob () { return $this->person->getGender() . substr($this->person->getDob(),0,4); }
    
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
    public function getPlans()
    {
        $attend = 'Attend: ' . $this->getPlan('attend');
        $referee= 'Referee: ' . $this->getPlan('will_referee');
        return $attend . '<br />' . $referee;

    }
    public function getContactInfo()
    {
        $cellPhone = $this->getCellPhone();
        $email = $this->getEmail();
        $html = $email . '<br />' . $cellPhone;
        return $html;
    }
    public function getAccountInfo()
    {
        $userName  = $this->escape($this->account->getUserName());
        $firstName = $this->escape($this->person->getFirstName());
        $lastName  = $this->escape($this->person->getLastName());
        $nickName  = $this->escape($this->person->getNickName());

        if ($nickName) $name = $firstName . " '" . $nickName . "' " . $lastName;
        else           $name = $firstName . ' '  . $lastName;

        $html = $name . '<br />' . $userName;
        return $html;
    }
    public function getAysoInfo()
    {
        $rp = $this->registeredPerson;
        $aysoid = substr($rp->getRegKey(),5);

        $refBadge  = $rp->getRefBadge();
        if ($refBadge == 'None')
        {
            $refBadge = '<span style="background: yellow;">' . $refBadge . '</span>';
        }
        $refDate   = $rp->getRefDate();
        $safeHaven = $rp->getSafeHaven();
        if ($safeHaven == 'None')
        {
            $safeHaven = '<span style="background: yellow;">' . $safeHaven . '</span>';
        }

        $memYear = 'MY' . $rp->getMemYear();
        if ($memYear < 'MY2011')
        {
            $memYear = '<span style="background: yellow;">' . $memYear . '</span>';
        }
        $yob = $this->getGenderYob();
        
        $html  =  $memYear . ' ' . $aysoid . ' ' . $yob . '<br />';
        $html .= 'Ref Badge: '  . $refDate . ' ' . $refBadge . '<br />';
        $html .= 'Safe Haven: ' . $safeHaven;


        return $html;
    }
}
class ListController extends BaseController
{
    public function listAction($_format)
    {
        $accountManager = $this->get('account.manager');
        $members = $accountManager->getAccountPersons(array('projectId' => $this->getProjectId()));
        
        $tplData = $this->getTplData();
        $tplData['members'] = $members;
        $tplData['memberx'] = new AdminAccountListViewHelper();
        
        if ($_format == 'html') return $this->render('NatGamesBundle:Admin:Account/list.html.twig',$tplData);
        
        $response = $this->render('NatGamesBundle:Admin:Account/list.csv.php',$tplData);
        
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Pragma', 'no-cache');

        $response->headers->set('Expires','Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past


        $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT');
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate');  // HTTP/1.1
        $response->headers->set('Cache-Control', 'pre-check=0, post-check=0, max-age=0'); // HTTP/1.1

        $response->headers->set('Content-Transfer-Encoding', 'none');

        $response->headers->set('Content-Type', 'text/csv;');
    
        $fileName = 'accounts.csv';
        $response->headers->set('Content-Disposition', 'attachment; filename="'. $fileName .'"');
        
        //$response->headers->set('Content-Type', 'text/csv');
        //$response->headers->set('Content-Disposition', 'attachment; filename="accounts.csv"');
        
        return $response;
    }
}
