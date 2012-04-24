<?php
namespace Zayso\AreaBundle\Component\Twig;


class AccountPersonExtension extends \Twig_Extension
{
    protected $env;
    protected $accountPerson;
    protected $person;
    protected $registeredPerson;
    
    public function initRuntime(\Twig_Environment $env)
    {
        parent::initRuntime($env);
        $this->env = $env;
    }
    protected function escape($string)
    {
        return twig_escape_filter($this->env,$string);
    }
    public function getFunctions()
    {
        return array(
            'accountPersonSetAccountPerson' => new \Twig_Function_Method($this, 'setAccountPerson'),
            'accountPersonGetPersonName'    => new \Twig_Function_Method($this, 'getPersonName'),
            'accountPersonGetAysoInfo'      => new \Twig_Function_Method($this, 'getAysoInfo'),
        );
    }
    public function setAccountPerson($item)
    {
        $this->accountPerson = $item;
        $this->person        = $item->getPerson();
        if ($this->person) $this->registeredPerson = $this->person->getAysoRegisteredPerson();
        return null;
    }
    public function getPersonName()
    {
        if (!$this->person) return null;
        return $this->person->getPersonName();
    }
    public function getAysoInfo($accountPerson = null)
    {
        $person = $accountPerson->getPerson();
        if (!$person) return '<span style="background: red;">' . 'No AYSO Information' . '</span>';
        
        $registeredPerson = $person->getAysoRegisteredPerson();
        if (!$registeredPerson) return '<span style="background: red;">' . 'No AYSO Information' . '</span>';
        
        $aysoid = substr($registeredPerson->getRegKey(),5);
        
        $aysoid = $this->escape('ZZZ & ' . $aysoid);
        
        $refBadge  = $registeredPerson->getRefBadge();
        $refDate   = $registeredPerson->getRefDate();
        $safeHaven = $registeredPerson->getSafeHaven();
        $memYear   = 'MY' . $registeredPerson->getMemYear();
        
        $yob = $person->getGender() . substr($person->getDob(),0,4);
        
        $org = $person->getOrg();
        if ($org) $orgDesc = $org->getDesc2();
        else      $orgDesc = null;

        $html  =  $memYear . ' ' . $aysoid . ' ' .  '<br />';
        $html .= 'Ref Badge: '   . $refBadge  . '<br />';
        $html .= 'Safe Haven: '  . $safeHaven . '<br />';
        $html .= $orgDesc;

        return $html;

        return 'REF ' . $accountPerson->getRefBadge();
    }

    public function getName()
    {
        return 'account_person_extension';
    }
}
?>
