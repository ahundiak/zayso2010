<?php
namespace Zayso\ZaysoBundle\Import;

use Zayso\ZaysoBundle\Entity\Project;
use Zayso\ZaysoBundle\Entity\PhyTeam;
use Zayso\ZaysoBundle\Entity\PhyTeamPerson;
use Zayso\ZaysoBundle\Entity\SchTeam;

class PhyTeamImport extends BaseImport
{
    protected $record = array
    (
      'region'             => array('cols' => 'RegionNumber',    'req' => true,  'default' => 0),
      'eaysoDesig'         => array('cols' => 'TeamDesignation', 'req' => true,  'default' => ''),
      'eaysoId'            => array('cols' => 'TeamID',          'req' => true,  'default' => 0),
      'key'                => array('cols' => 'TeamKey',         'req' => false, 'default' => ''),
      'name'               => array('cols' => 'TeamName',        'req' => false, 'default' => ''),
      'colors'             => array('cols' => 'TeamColors',      'req' => false, 'default' => ''),
      'age'                => array('cols' => 'DivisionName',    'req' => false, 'default' => ''),
      'gender'             => array('cols' => 'Gender',          'req' => false, 'default' => ''),

      'headCoachFirstName' => array('cols' => 'TeamCoachFName',  'req' => false, 'default' => ''),
      'headCoachLastName'  => array('cols' => 'TeamCoachLName',  'req' => false, 'default' => ''),
      'headCoachEmail'     => array('cols' => 'TeamCoachEmail',  'req' => false, 'default' => ''),
      'headCoachPhone'     => array('cols' => 'TeamCoachPhone',  'req' => false, 'default' => ''),
      'headCoachAysoid'    => array('cols' => 'TeamCoachAysoid', 'req' => false, 'default' => ''),

      'asstCoachFirstName' => array('cols' => 'TeamAsstCoachFName',  'req' => false, 'default' => ''),
      'asstCoachLastName'  => array('cols' => 'TeamAsstCoachLName',  'req' => false, 'default' => ''),
      'asstCoachEmail'     => array('cols' => 'TeamAsstCoachEmail',  'req' => false, 'default' => ''),
      'asstCoachPhone'     => array('cols' => 'TeamAsstCoachPhone',  'req' => false, 'default' => ''),
      'asstCoachAysoid'    => array('cols' => 'TeamAsstCoachAysoid', 'req' => false, 'default' => ''),

      'managerFirstName'   => array('cols' => 'TeamParentFName',  'req' => false, 'default' => ''),
      'managerLastName'    => array('cols' => 'TeamParentLName',  'req' => false, 'default' => ''),
      'managerEmail'       => array('cols' => 'TeamParentEmail',  'req' => false, 'default' => ''),
      'managerPhone'       => array('cols' => 'TeamParentPhone',  'req' => false, 'default' => ''),
      'managerAysoid'      => array('cols' => 'TeamParentAysoid', 'req' => false, 'default' => ''),
    );
// TeamDesignation	TeamKey	TeamName	DivisionID	DivisionName	TeamColors	TeamID	RatingAVG	PlyCount	TeamCoachFName	TeamCoachLName	TeamCoachPhone	TeamCoachEmail	TeamCoachCertification	TeamAsstCoachFName	TeamAsstCoachLName	TeamAsstCoachPhone	TeamAsstCoachEmail	TeamAsstCoachCertification	TeamParentFName	TeamParentLName	TeamParentPhone	TeamParentEmail	TeamParentCertification	RegionNumber	RegionName	UserName

    protected function init()
    {
        parent::init();
        $em = $this->getEntityManager();
        $this->projectRepo = $em->getRepository('ZaysoBundle:Project');
        $this->phyTeamRepo = $em->getRepository('ZaysoBundle:PhyTeam');
        $this->schTeamRepo = $em->getRepository('ZaysoBundle:SchTeam');
    }
    public function processItem($item)
    {
        $em = $this->getEntityManager();

        if (!$item->eaysoId)    return;
        if (!$item->eaysoDesig) return;

        $this->total++;

        $eaysoId    = (int)$item->eaysoId;
        $eaysoDesig = $item->eaysoDesig;

        $headCoachLastName = ucfirst(strtolower($item->headCoachLastName));
        
        $teamKey = substr($item->key,0,6);
        if ($teamKey)
        {
            $age    = substr($teamKey,0,3);
            $gender = substr($teamKey,3,1);
            if ($headCoachLastName) $teamKey .= ' ' . $headCoachLastName;
        }
        else
        {
            $age = null;
            $gender = null;
        }
        $region = (int)$item->region;
        if ($region)
        {
            $teamKey = sprintf('R%04u%s',$region,$teamKey);
            $orgKey = sprintf('AYSOR-%04u',$region);
        }
        else
        {
            $orgKey = null;
        }
        // Find existing team
        $phyTeam = null;
        if (!$phyTeam && $eaysoId)
        {
            $params = array('project' => $this->projectId, 'eaysoId' => $eaysoId);
            $phyTeam = $this->phyTeamRepo->findOneBy($params);
        }
        if (!$phyTeam && $eaysoDesig)
        {
            $params = array('project' => $this->projectId, 'eaysoDesig' => $eaysoDesig);
            $phyTeam = $this->phyTeamRepo->findOneBy($params);
        }
        if (!$phyTeam)
        {
            $phyTeam = new PhyTeam();
            $phyTeam->setProject($this->project);
            $em->persist($phyTeam);
        }
        $phyTeam->setEaysoId   ($eaysoId);
        $phyTeam->setEaysoDesig($eaysoDesig);

        if ($teamKey) $phyTeam->setTeamKey($teamKey);

        if ($age)    $phyTeam->setAge(   $age);
        if ($gender) $phyTeam->setGender($gender);
        if ($orgKey) $phyTeam->setOrgKey($orgKey);

        $phyTeam->setName($item->name);
        $phyTeam->setColors($item->colors);
        $phyTeam->setLevel ('Regular');
        $phyTeam->setStatus('Active');

        /* ================================================================
         * Schedule Team Stuff
         */
        $params = array('project' => $this->projectId, 'phyTeam' => $phyTeam->getId());
        $schTeam = $this->schTeamRepo->findOneBy($params);
        if (!$schTeam)
        {
            $schTeam = new SchTeam();
            $schTeam->setProject($this->project);
            $schTeam->setPhyTeam($phyTeam);
            $em->persist($schTeam);
        }
        if ($teamKey) $schTeam->setTeamKey($teamKey);
        if ($age)     $schTeam->setAge(   $age);
        if ($gender)  $schTeam->setGender($gender);
        if ($orgKey)  $schTeam->setOrgKey($orgKey);

        $schTeam->setLevel($phyTeam->getLevel());
        $schTeam->setType('RS');

        // People
        $this->processPhyTeamPerson($phyTeam,$orgKey,'Head Coach',$item->headCoachFirstName,$item->headCoachLastName,$item->headCoachEmail,$item->headCoachPhone);
        $this->processPhyTeamPerson($phyTeam,$orgKey,'Asst Coach',$item->asstCoachFirstName,$item->asstCoachLastName,$item->asstCoachEmail,$item->asstCoachPhone);
        $this->processPhyTeamPerson($phyTeam,$orgKey,'Manager',   $item->managerFirstName,  $item->managerLastName,  $item->managerEmail,  $item->managerPhone);

        // Done
        $em->flush();
        
        return;        
   }
   public function processPhyTeamPerson($phyTeam,$orgKey,$type,$firstName,$lastName,$email,$phone)
   {
       if (!$lastName) return;

       $person = $phyTeam->getPersonForType($type);
       if (!$person)
       {
           $person = new PhyTeamPerson();
           $person->setPhyTeam($phyTeam);
           $person->setType($type);
           $this->getEntityManager()->persist($person);
       }
       $firstName = ucfirst(strtolower($firstName));
       $lastName  = ucfirst(strtolower($lastName));

       $email = strtolower($email);
       $phone = preg_replace('/\D/','',$phone);

       if ($firstName) $person->setFirstName($firstName);
       if ($lastName)  $person->setLastName ($lastName);
       if ($email)     $person->setEmail    ($email);
       if ($phone)     $person->setPhone    ($phone);
       if ($orgKey)    $person->setOrgKey   ($orgKey);

   }
}
?>