<?php
namespace Zayso\NatGamesBundle\Component\Import;

use Zayso\CoreBundle\Component\Import\BaseImport;
use Zayso\CoreBundle\Component\Debug;

use Zayso\CoreBundle\Entity\Org;

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
        
      'region'      => array('cols' => 'Region',  'req' => false, 'default' => ''),
      'regionDesc2' => array('cols' => 'Area',    'req' => false, 'default' => ''),
        
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
        $accountPerson = $accountManager->getAccountPerson(array('accountPersonId' => $item->id));
        if (!$accountPerson) return;

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

        $this->processRegion($person,$item->region);
        
        $this->processRegionDesc2($item->regionDesc2);
        
        if ($item->firstName) $person->setFirstName($item->firstName);
        if ($item->lastName)  $person->setLastName ($item->lastName);
        if ($item->nickName)  $person->setNickName ($item->nickName);

        // This I do not like, be careful about changing aysoid
        $aysoid = $item->aysoid;
        if ($aysoid) 
        {
            $aysoid = 'AYSOV' . $aysoid;
            if ($registeredPerson->getRegKey() != $aysoid) die('aysoid changed' . ' ' . $aysoid);
            // $registeredPerson->setRegKey('AYSOV' . $aysoid);
        }
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
    /* ==============================================================
     * See if the region has changed
     */
    protected function processRegion($person,$region)
    {
        if (!$region) return;
        
        $org = $person->getOrg();
        $region = 'AYSO' . $region;
        
        if ($org && ($org->getId() == $region)) return;
        
        // Need some code to deal with new or existinf changed region
        die('Region changed');
        
        
    }
    /* ==============================================================
     * Process a descriptive ayso area string
     * A07O-R0178 Aiea, HI
     * 
     * Uses Org repo, independent of account manager
     * Code should probsbly be moved to an OrgManager at some point
     */
    public function processRegionDesc2($regionDesc2)
    {
        // Make sure have one
        $regionDesc2 = trim($regionDesc2);
        if (!$regionDesc2) return;
        
        // Break out location
        $location = substr($regionDesc2,11);
        $area     = substr($regionDesc2, 0,10);
        
        // Break out area and region
        $items = explode('-',$area);
        if (count($items) != 2) return;
        
        $area   = trim($items[0]);
        $region = trim($items[1]);
        
        if (strlen($area)   != 4) return;
        if (strlen($region) != 5) return;
        
        if ($area[0]   != 'A') return;
        if ($region[0] != 'R') return;
        
        $areaKey   = 'AYSO' . $area;
        $regionKey = 'AYSO' . $region;
        
        // Break location into city and state
        $items = explode(',',$location);
        if (isset($items[0])) $city = trim($items[0]);
        else                  $city = '';
        if (isset($items[1])) $state = trim($items[1]);
        else                  $state = '';
        
        // Make sure have an area entry
        $em   = $this->getEntityManager();
        $repo = $em->getRepository('ZaysoCoreBundle:Org');
        
        $areaOrg = $repo->find($areaKey);
        if (!$areaOrg)
        {
            // Need a section
            $sectionKey = 'AYSOS' . substr($areaKey,5,2);
            $sectionOrg = $repo->find($sectionKey);
            if (!$sectionOrg)
            {
                die('*** Could not find Section for: ' . $regionDesc2);
            }
            // Make a new area
            $areaOrg = new Org();
            $areaOrg->setId($areaKey);
            $areaOrg->setParent($sectionOrg);
            $em->persist($areaOrg);
            
        }
       // Set the state if blank
       if (!$areaOrg->getDesc1()) $areaOrg->setDesc1('AYSO Area ' . substr($areaKey,5));
       if (!$areaOrg->getState()) $areaOrg->setState($state);
       
       // Make a region org entry
        $regionOrg = $repo->find($regionKey);
        if (!$regionOrg)
        {
            $regionOrg = new Org();
            $regionOrg->setId($regionKey);
            $em->persist($regionOrg);
        }
        // Update assorted info if missing
        if (!$regionOrg->getParent()) $regionOrg->setParent($areaOrg);
        if (!$regionOrg->getDesc1 ()) $regionOrg->setDesc1 ('AYSO Region ' . substr($regionKey,5));
        if (!$regionOrg->getDesc2 ()) $regionOrg->setDesc2 ($regionDesc2);
        if (!$regionOrg->getCity  ()) $regionOrg->setCity  ($city);
        if (!$regionOrg->getState ()) $regionOrg->setState ($state);
         
        
        // Done
        //$em->flush();
        //die('Area ' . $areaKey . ' ' . $regionKey . ' #' . $city . '# ' . $state);
    }
}

?>
