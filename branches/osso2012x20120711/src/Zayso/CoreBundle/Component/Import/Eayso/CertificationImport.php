<?php
namespace Zayso\CoreBundle\Component\Import\Eayso;

use Zayso\CoreBundle\Component\Debug;
use Zayso\CoreBundle\Component\Import\BaseImport;

class CertificationImport extends BaseImport
{
    protected $record = array
    (
      'region'     => array('cols' => 'RegionNumber',      'req' => true,  'default' => 0),
      'aysoid'     => array('cols' => 'AYSOID',            'req' => true,  'default' => ''),
      'firstName'  => array('cols' => 'FirstName',         'req' => true,  'default' => ''),
      'lastName'   => array('cols' => 'LastName',          'req' => true,  'default' => ''),
      'memYear'    => array('cols' => 'MembershipTermName','req' => true,  'default' => 'MY0000'),
    //'email'      => array('cols' => 'Email',             'req' => false, 'default' => ''),
    //'homePhone'  => array('cols' => 'Homephone',         'req' => false, 'default' => ''),
    //'workPhone'  => array('cols' => 'BusinessPhone',     'req' => false, 'default' => ''),
    //'gender'     => array('cols' => 'Gender',            'req' => false, 'default' => ''),
      'certDesc'   => array('cols' => 'CertificationDesc', 'req' => true,  'default' => ''),
      'certDate'   => array('cols' => 'CertDate',          'req' => true,  'default' => ''),
    );
    protected $manager;
    public function __construct($manager)
    {
        parent::__construct($manager->getEntityManager());
        $this->manager = $manager;
    }
    protected function getSafeHaven($cert)
    {   
        if (strpos($cert,'AYSOs Safe Haven')   !== false) return 'AYSO';
        if (strpos($cert,'Safe Haven Coach')   !== false) return 'Coach';
        if (strpos($cert,'Safe Haven Referee') !== false) return 'Referee';
        return null;
    }
    protected function getRefBadge($cert)
    {
        // Filter out online stuff
        if (strpos($cert,'Z-Online') !== false) return null;
        if (strpos($cert,'Webinar')  !== false) return null;

        if (strpos($cert,'National Referee')     !== false) return 'National';
        if (strpos($cert,'National 2 Referee')   !== false) return 'National 2';
        if (strpos($cert,'Advanced Referee')     !== false) return 'Advanced';
        if (strpos($cert,'Intermediate Referee') !== false) return 'Intermediate';
        if (strpos($cert,'Regional Referee')     !== false) return 'Regional';
        if (strpos($cert,'Assistant Referee')    !== false) return 'Assistant';
        if (strpos($cert,'U-8 Official')         !== false) return 'U8 Official';
        return null;
    }
    protected function getRefBadgeLevel($badge)
    {
        $badges = array(
            'National'     => 90,
            'National 2'   => 80,
            'Advanced'     => 70,
            'Intermediate' => 60,
            'Regional'     => 50,
            'Assistant'    => 40,
            'U8 Official'  => 30,
        );
        if (isset($badges[$badge])) return $badges[$badge];
        return 0;
    }
    protected function getSafeHavenLevel($badge)
    {
        $badges = array(
            'AYSO'    => 50,
            'Coach'   => 40,
            'Referee' => 30,
        );
        if (isset($badges[$badge])) return $badges[$badge];
        return 0;
    }
    public function processItem($item)
    {
        if (!$item->aysoid)   return;
        if (!$item->region)   return;
        if (!$item->certDesc) return;
        
        // Get the volunteer
        $aysoid = 'AYSOV' . $item->aysoid;
        
        $hasVol = $this->loadVol($aysoid); 
        if (!$hasVol) return;
        
        $vol = $this->manager->loadPersonForAysoid($aysoid);
        if (!$vol) return;
       
        $rp = $vol->getAysoRegisteredPerson();

        // Process the cert
        $certDesc = $item->certDesc;
        
        // Process Referee Badge
        $refBadge = $this->getRefBadge($certDesc);
        if ($refBadge) 
        {
            $refBadgeLevel = $this->getRefBadgeLevel($refBadge);
            if ($refBadgeLevel > $this->getRefBadgeLevel($rp->getRefBadge()))
            {
                $date = $this->processDate($item->certDate);
                $rp->setRefBadge($refBadge);
                $rp->setRefDate ($date);

                $this->total++;
            }
        }
        // Process Safe haven
        $badge = $this->getSafeHaven($certDesc);
        if ($badge) 
        {
            $badgeLevel = $this->getSafeHavenLevel($badge);
            if ($badgeLevel > $this->getSafeHavenLevel($rp->getSafeHaven()))
            {
                $rp->setSafeHaven($badge);
                $this->total++;
            }
        }
        
        // Maybe do some vol stuff? MY?
        $memYear = $this->processMemYear($item->memYear);
        if ($rp->getMemYear() < $memYear) 
        {
            $rp->setMemYear($memYear);
            $this->total++;
        }
        // Flush if needed
        if (($this->total % 100) == 0) 
        {
            $this->getEntityManager()->flush();
            $this->getEntityManager()->clear();
        }
    }
}
?>