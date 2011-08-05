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

        $aysoid = 'AYSOV-' . $item->aysoid;
        if (isset($this->aysoids[$aysoid]))
        {
            echo "Duplicate aysoid $aysoid\n";
        }
        $this->aysoids[$aysoid] = true;

        $region = sprintf('AYSOR-%04u',(int)$item->region);

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
            if ($vol->getMemYear() > $item->memYear) return;
        }
        $dob = $item->dob;
        if ($dob)
        {
            $dob = substr($dob,6,4) . substr($dob,0,2) . substr($dob,3,2);
        }
        $vol->setRegion    ($region);
        $vol->setMemYear   ($item->memYear);
        $vol->setFirstName ($item->firstName);
        $vol->setLastName  ($item->lastName);
        $vol->setMiddleName($item->middleName);
        $vol->setNickName  ($item->nickName);
        $vol->setSuffix    ($item->suffix);
        $vol->setEmail     ($item->email);
        $vol->setHomePhone ($item->homePhone);
        $vol->setWorkPhone ($item->workPhone);
        $vol->setCellPhone ($item->cellPhone);
        $vol->setDob       ($dob);
        $vol->setGender    ($item->gender);

        $em->flush();
    }
}

?>
