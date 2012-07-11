<?php
namespace Zayso\EaysoBundle\Import;

use Zayso\EaysoBundle\Entity\Volunteer;

class VolunteerImport extends BaseImport
{
    protected $record = array
    (
      'region'     => array('cols' => 'Region',         'req' => true,  'default' => 0),
      'aysoid'     => array('cols' => 'AYSOID',         'req' => true,  'default' => ''),
      'firstName'  => array('cols' => 'FirstName',      'req' => true,  'default' => ''),
      'lastName'   => array('cols' => 'LastName',       'req' => true,  'default' => ''),
      'middleName' => array('cols' => 'MI',             'req' => false, 'default' => ''),
      'suffix'     => array('cols' => 'suffix',         'req' => false, 'default' => ''),
      'nickName'   => array('cols' => 'NickName',       'req' => false, 'default' => ''),
      'memYear'    => array('cols' => 'Membershipyear', 'req' => true,  'default' => 'MY0000'),
      'email'      => array('cols' => 'Email',          'req' => false, 'default' => ''),
      'homePhone'  => array('cols' => 'HomePhone',      'req' => false, 'default' => ''),
      'workPhone'  => array('cols' => 'WorkPhone',      'req' => false, 'default' => ''),
      'cellPhone'  => array('cols' => 'CellPhone',      'req' => false, 'default' => ''),
      'dob'        => array('cols' => 'DOB',            'req' => false, 'default' => ''),
      'gender'     => array('cols' => 'Gender',         'req' => false, 'default' => ''),
      'registered' => array('cols' => 'Registered Date','req' => false, 'default' => ''),
      'changed'    => array('cols' => 'Changed Date',   'req' => false, 'default' => ''),
    );

    protected function init() 
    {
        parent::init();
        $em = $this->getEntityManager();
        $this->volRepo = $em->getRepository('EaysoBundle:Volunteer');
    }
    protected $aysoids = array();
    public function processItem($item)
    {
        $em = $this->getEntityManager();

        if (!$item->aysoid) return;
        if (!$item->region) return;

        $this->total++;

        $aysoid = 'AYSOV' . $item->aysoid;
        if (isset($this->aysoids[$aysoid]))
        {
            // echo "Duplicate aysoid $aysoid\n";
        }
        $this->aysoids[$aysoid] = true;

        $region = sprintf('AYSOR%04u',(int)$item->region);

        $memYear = $this->processMemYear($item->memYear);
        
        $vol = $this->volRepo->find($aysoid);
        if (!$vol)
        {
            $vol = new Volunteer();
            $vol->setId($aysoid);
            $em->persist($vol);
        }
        else
        {
            // Do not update existing records if older membership year
            if ($vol->getMemYear() > $memYear) return;
            
            // Check registered/changed here if memYear matches?
        }
        $dob        = $this->processDate($item->dob);
        $registered = $this->processDate($item->registered);
        $changed    = $this->processDate($item->changed);

        $lastName   = $this->processName($item->lastName);
        $nickName   = $this->processName($item->nickName);
        $firstName  = $this->processName($item->firstName);
        $middleName = $this->processName($item->middleName);

        $email      = $this->processEmail($item->email);

        $homePhone = $this->processPhone($item->homePhone);
        $workPhone = $this->processPhone($item->workPhone);
        $cellPhone = $this->processPhone($item->cellPhone);

        $vol->setRegion    ($region);
        $vol->setMemYear   ($memYear);
        $vol->setFirstName ($firstName);
        $vol->setLastName  ($lastName);
        $vol->setMiddleName($middleName);
        $vol->setNickName  ($nickName);
        $vol->setSuffix    ($item->suffix);
        $vol->setEmail     ($email);
        $vol->setHomePhone ($homePhone);
        $vol->setWorkPhone ($workPhone);
        $vol->setCellPhone ($cellPhone);
        $vol->setDob       ($dob);
        $vol->setGender    ($item->gender);
        $vol->setRegistered($registered);
        $vol->setChanged   ($changed);

        if (($this->total % 100) == 0) $em->flush();
    }
}

?>
