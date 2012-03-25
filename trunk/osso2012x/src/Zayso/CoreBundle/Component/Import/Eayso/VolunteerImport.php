<?php
namespace Zayso\CoreBundle\Component\Import\Eayso;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\Import\BaseImport;

use Doctrine\Common\Util\Debug as DebugEntity;

class VolunteerImport extends BaseImport
{
    protected $record = array
    (
      'region'     => array('cols' => 'Region',         'req' => true,  'default' => 0),
      'aysoid'     => array('cols' => 'AYSOID',         'req' => true,  'default' => ''),
      'firstName'  => array('cols' => 'FirstName',      'req' => true,  'default' => ''),
      'lastName'   => array('cols' => 'LastName',       'req' => true,  'default' => ''),
    //'middleName' => array('cols' => 'MI',             'req' => false, 'default' => ''),
    //'suffix'     => array('cols' => 'suffix',         'req' => false, 'default' => ''),
      'nickName'   => array('cols' => 'NickName',       'req' => false, 'default' => ''),
      'memYear'    => array('cols' => 'Membershipyear', 'req' => true,  'default' => 'MY0000'),
      'email'      => array('cols' => 'Email',          'req' => true, 'default' => ''),
      'homePhone'  => array('cols' => 'HomePhone',      'req' => false, 'default' => ''),
      'workPhone'  => array('cols' => 'WorkPhone',      'req' => false, 'default' => ''),
      'cellPhone'  => array('cols' => 'CellPhone',      'req' => false, 'default' => ''),
      'dob'        => array('cols' => 'DOB',            'req' => false, 'default' => ''),
      'gender'     => array('cols' => 'Gender',         'req' => false, 'default' => ''),
    //'registered' => array('cols' => 'Registered Date','req' => false, 'default' => ''),
    //'changed'    => array('cols' => 'Changed Date',   'req' => false, 'default' => ''),
    );
    protected $manager;
    public function __construct($manager)
    {
        parent::__construct($manager->getEntityManager());
        $this->manager = $manager; 
    }
    public function processItem($item)
    {
        if (!$item->aysoid) return;
        if (!$item->region) return;

        $aysoid = 'AYSOV' . $item->aysoid;
        
        $hasVol = $this->loadVol($aysoid); 
        if (!$hasVol) return;
        
        $vol = $this->manager->loadPersonForAysoid($aysoid);
        if (!$vol) return;
        
        $this->total++;
        
        $rp = $vol->getAysoRegisteredPerson();
        
        // Do not update existing records if older membership year
        $memYear = $this->processMemYear($item->memYear);
        if ($rp->getMemYear()  > $memYear) return;
        if ($rp->getMemYear() != $memYear)
        {
            // Debug::dump($item); die();
        }
        $rp->setMemYear($memYear);
        
        // Region changing is weird, only do it manually
        $region = sprintf('AYSOR%04u',(int)$item->region);
        
        // Rest is for the person
        $dob        = $this->processDate($item->dob);

        $lastName   = $this->processName($item->lastName);
        $nickName   = $this->processName($item->nickName);
        $firstName  = $this->processName($item->firstName);

        $email      = $this->processEmail($item->email);

      //$homePhone = $this->processPhone($item->homePhone);
      //$workPhone = $this->processPhone($item->workPhone);
        $cellPhone = $this->processPhone($item->cellPhone);
        
        if (!$vol->getFirstName()) $vol->setFirstName ($firstName);
        if (!$vol->getLastName())  $vol->setLastName  ($lastName);
        if (!$vol->getNickName())  $vol->setNickName  ($nickName);
     
        if (!$vol->getEmail())     $vol->setEmail     ($email);
        if (!$vol->getCellPhone()) $vol->setCellPhone ($cellPhone);
        
        $vol->setDob       ($dob);
        $vol->setGender    ($item->gender);

        //if (($this->total % 100) == 0) 
        {
            $this->getEntityManager()->flush();
            $this->getEntityManager()->clear();
        }
    }
}

?>
