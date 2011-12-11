<?php
namespace Zayso\NatGamesBundle\Component\Import;

use Zayso\ZaysoBundle\Component\Import\BaseImport;
use Zayso\ZaysoBundle\Component\Debug;

class AccountImport extends BaseImport
{
    //AP ID,Account,Ver,First Name,Last  Name,Nick  Name,Email,Cell Phone,Region,AYSOID,DOB,Gender,Ref Badge,Ref Date,Safe Haven,MY,Attend,Referee

    protected $record = array
    (
      'id'        => array('cols' => 'AP ID',     'req' => true,  'default' => 0),
      'userName'  => array('cols' => 'Account',   'req' => true,  'default' => ''),
      'firstName' => array('cols' => 'First Name','req' => true,  'default' => ''),
      'lastName'  => array('cols' => 'Last  Name','req' => false, 'default' => ''),
      'nickName'  => array('cols' => 'Nick  Name','req' => false, 'default' => ''),
      'email'     => array('cols' => 'Email',     'req' => false, 'default' => ''),
      'cellPhone' => array('cols' => 'Cell Phone','req' => true,  'default' => ''),
      'region'    => array('cols' => 'Region',    'req' => false, 'default' => ''),
      'aysoid'    => array('cols' => 'AYSOID',    'req' => false, 'default' => ''),
      'dob'       => array('cols' => 'DOB',       'req' => false, 'default' => ''),
      'gender'    => array('cols' => 'Gender',    'req' => false, 'default' => ''),
      'refBadge'  => array('cols' => 'Ref Badge', 'req' => false, 'default' => ''),
      'refDate'   => array('cols' => 'Ref Date',  'req' => false, 'default' => ''),
      'safeHaven' => array('cols' => 'Safe Haven','req' => false, 'default' => ''),
      'memYear'   => array('cols' => 'MY',        'req' => false, 'default' => ''),
    );
    public function __construct($accountManager)
    {
        parent::__construct($accountManager->getEntityManager());
        $this->accountManager = $accountManager;
    }
    public function process($params = array())
    {
        $this->projectId = $params['projectId']; // Not actually using projectId
        return parent::process($params);
    }
    public function processItem($item)
    {
        $em = $this->getEntityManager();
        $accountManager = $this->accountManager;

        if (!$item->id) return;

        $this->total++;

        // Grab the account person
        // Just grabbing the ap results in an aupdate message, track down later
        $accountPersons = $accountManager->getAccountPersons(array('accountPersonId' => $item->id));
        if (count($accountPersons) != 1) die('No account person for ' . $item->id);
        $accountPerson = $accountPersons[0];

        $person = $accountPerson->getPerson();
        if (!$person) die('No person for ' . $item->id);

        $registeredPerson = $person->getAysoRegisteredPerson();
        if (!$registeredPerson) die('No registered person for ' . $item->id);

        $dob = $this->processDate($item->dob);
        if ($dob) $person->setDob($dob);

        $gender = $item->gender;
        if ($gender) $person->setGender($gender);

        $email = $this->processEmail($item->email);
        if ($email) $person->setEmail($email);

        $cellPhone = $this->processPhone($item->cellPhone);
        if ($cellPhone) $person->setCellPhone($cellPhone);

        $region = $item->region;
        if ($region) $person->setOrgKey('AYSO' . $region);

        if ($item->firstName) $person->setFirstName($item->firstName);
        if ($item->lastName)  $person->setLastName ($item->lastName);
        if ($item->nickName)  $person->setNickName ($item->nickName);

        $aysoid = $item->aysoid;
        if ($aysoid) $registeredPerson->setRegKey('AYSOV' . $aysoid);

        $refBadge = $item->refBadge;
        if ($refBadge && $refBadge != 'Nonex') $registeredPerson->setRefBadge($refBadge);

        $refDate = $this->processDate($item->refDate);
        if ($refDate) $registeredPerson->setRefDate($refDate);

        $safeHaven = $item->safeHaven;
        if ($safeHaven) $registeredPerson->setSafeHaven($safeHaven);

        $memYear = $this->processMemYear($item->memYear);
        if ($memYear) $registeredPerson->setMemYear($memYear);
        
        return;
    }
}

?>
