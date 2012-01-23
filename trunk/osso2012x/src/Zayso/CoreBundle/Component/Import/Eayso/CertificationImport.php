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
      'email'      => array('cols' => 'Email',             'req' => false, 'default' => ''),
      'homePhone'  => array('cols' => 'Homephone',         'req' => false, 'default' => ''),
      'workPhone'  => array('cols' => 'BusinessPhone',     'req' => false, 'default' => ''),
      'gender'     => array('cols' => 'Gender',            'req' => false, 'default' => ''),
      'certDesc'   => array('cols' => 'CertificationDesc', 'req' => true,  'default' => ''),
      'certDate'   => array('cols' => 'CertDate',          'req' => true,  'default' => ''),
    );
    protected $manager;
    public function __construct($manager)
    {
        parent::__construct($manager->getEntityManager());
        $this->manager = $manager;
    }
    /* ======================================================================
     * All this currently does is to make sure have a volunteer
     * Assume all volunteer contact info is done through volunteer import
     */
    public function processVolunteer($item)
    {
        // Ignore anyting before 2008
        $memYear = $this->processMemYear($item->memYear);
        if ($memYear < 2008) return null;
        
        $aysoid = 'AYSOV' . $item->aysoid;
        
        $vol = $this->volRepo->find($aysoid);
        
        return $vol;
        
        // For now, ignore cert of we have not volunteer
        if (!$vol) return;
        
        // And never do anything to the volunteer
        return;
        
        $memYear = $this->processMemYear($item->memYear);
        
        if ($vol)
        {
            // Update membership year if newer
            $memYear = $item->memYear;
            if (substr($memYear,0,2) == 'MY' && ($memYear < $vol->getMemYear())) $vol->setMemYear($memYear);

            return $vol;
        }
        $region = sprintf('AYSOR%04u',(int)$item->region);

        $homePhone = preg_replace('/\D/','',$item->homePhone);
        $workPhone = preg_replace('/\D/','',$item->workPhone);

        $firstName = ucfirst(strtolower($item->firstName));
        $lastName  = ucfirst(strtolower($item->lastName));
        $email     = strtolower($item->email);

        $vol = new Volunteer();
        $vol->setId($aysoid);
        $vol->setRegion($region);

        $vol->setMemYear   ($item->memYear);
        $vol->setFirstName ($firstName);
        $vol->setLastName  ($lastName);

        $vol->setEmail     ($item->email);
        $vol->setHomePhone ($homePhone);
        $vol->setWorkPhone ($workPhone);

        $vol->setGender    ($item->gender);

        $em = $this->getEntityManager();
        $em->persist($vol);

        return $vol;
    }
    protected function getSafeHaven($cert)
    {   
        if (strpos($cert,'AYSOs Safe Haven')   !== false) return 'AYSO';
        if (strpos($cert,'Safe Haven Coach')   !== false) return 'Coach';
        if (strpos($cert,'Safe Haven Referee') !== false) return 'Referee';
        return null;
        
        switch($cert)
        {
            case 'Webinar-AYSOs Safe Haven':
          //case 'Webinar-AYSOs Safe Haven, Z-Online AYSOs Safe Haven':
          //case 'Webinar-Safe Haven Update, Z-Online AYSOs Safe Haven':
            case 'Z-Online AYSOs Safe Haven':
            case 'AYSOs Safe Haven':
          //case 'AYSOs Safe Haven, Z-Online AYSOs Safe Haven':
                return 'AYSO';
                
            case 'Safe Haven Referee':
            case 'Z-Online Safe Haven Referee':
//            case 'U-8 Official & Safe Haven Referee':
//            case 'Assistant Referee & Safe Haven Referee':
//            case 'Regional Referee & Safe Haven Referee':
                return 'Referee';
                
           case 'Safe Haven Coach':
           case 'Z-Online Safe Haven Coach':
                return 'Referee';
        }
        return null;
    }
    protected function explodeCerts($cert)
    {
        $items = array();
        
        $cert = trim(str_replace('&amp;','&',$cert));
        
       // Take care of comma delimited nonsense
        $certs = explode(',',$cert);
        
        if (count($certs) > 1)
        {
            foreach($certs as $cert)
            {
                $itemsx = $this->explodeCerts($cert);
                $items = array_merge($items,$itemsx);
            }
            $cert = null;
        }
        else $cert = $certs[0];
        if (!$cert) return $items;

        // Same for the & stuff
        $certs = explode('&',$cert);
        
        if (count($certs) > 1)
        {
            foreach($certs as $cert)
            {
                $itemsx = $this->explodeCerts($cert);
                $items = array_merge($items,$itemsx);
            }
            $cert = null;
        }
        else $cert = $certs[0];
        if (!$cert) return $items;
        
        $items[] = $cert;
        
        return $items;
    }
    protected function getRefBadge($cert)
    {
        if (strpos($cert,'National Referee')     !== false) return 'National';
        if (strpos($cert,'National 2 Referee')   !== false) return 'National 2';
        if (strpos($cert,'Advanced Referee')     !== false) return 'Advanced';
        if (strpos($cert,'Intermediate Referee') !== false) return 'Intermediate';
        if (strpos($cert,'Regional Referee')     !== false) return 'Regional';
        if (strpos($cert,'Assistant Referee')    !== false) return 'Assistant';
        if (strpos($cert,'U-8 Official')         !== false) return 'U8 Official';
        return null;
    }
    protected function getHigherRefBadge($badge1, $badge2)
    {
        $badges = array(
            'National'     => 1,
            'National 2'   => 2,
            'Advanced'     => 3,
            'Intermediate' => 4,
            'Regional'     => 5,
            'Assistant'    => 6,
            'U8 Official'  => 7,
        );
        if (!$badge1) return $badge2;
        if (!$badge2) return $badge1;
        
        $i1 = $badges[$badge1];
        $i2 = $badges[$badge2];
        
        if ($i1 <= $i2) return $badge1;
        
        return $badge2;
    }
    protected function getHigherSafeHaven($haven1, $haven2)
    {
        $havens = array(
            'AYSO'    => 1,
            'Coach'   => 2,
            'Referee' => 3,
        );
        if (!$haven1) return $haven2;
        if (!$haven2) return $haven1;
        
        $i1 = $havens[$haven1];
        $i2 = $havens[$haven2];
        
        if ($i1 <= $i2) return $haven1;
        
        return $haven2;
    }
    public function processItem($item)
    {
        if (!$item->aysoid)   return;
        if (!$item->region)   return;
        if (!$item->certDesc) return;

        $this->total++;

        // Make sure know about the cert
        $certDesc = $item->certDesc;
        
        $refBadge = $this->getRefBadge($certDesc);
        if ($refBadge) return;
        die($certDesc);
        
        $safeHaven = $this->getSafeHaven($certDesc);
        if ($safeHaven) return;
        
        die($certDesc);
        
        $certs = $this->explodeCerts($certDesc);
        foreach($certs as $cert)
        {
            $safeHaven = $this->getSafeHaven($cert);
            if ($safeHaven) return;
        }
        Debug::dump($certs);
        die($certDesc);
        
        if (strpos($certDesc,'AYSOs Safe Haven') !== FALSE) $certDesc = 'Safe Haven AYSOs';
        if (strpos($certDesc,'Safe Haven Coach') !== FALSE) $certDesc = 'Safe Haven Coach';
        $certDesc = str_replace('&amp;','&',$certDesc);
        
        if (!isset($this->certs[$certDesc]))
        {
            $error = "{$item->aysoid} {$item->lastName} '{$certDesc}'";
            echo $error . "\n";
            $this->errors[] = $error;
            die();
            return;
        }

        // Update and possibly create volunteer record
        $vol = $this->processVolunteer($item);
        if (!$vol) return;

        // Process the date
        $date = $this->processDate($item->certDate);
    
        // Process each cert
        foreach($this->certs[$certDesc] as $cat => $type)
        {
            $this->processCert($vol,$cat,$type,$date);
        }
        $vol = null;
        if (($this->total % 100) == 0) 
        {
            $this->getEntityManager()->flush();
            $this->getEntityManager()->clear();
        }
    }
    protected function processCert($vol,$cat,$type,$date)
    {
        $cert = $vol->getCertification($cat);
        if (!$cert)
        {
          //echo "{$vol->getId()} $cat $type\n";
            $cert = new Certification();
            $cert->setVolunteer($vol);
            $cert->setCat($cat);
            $cert->setType($type);
            $cert->setDate($date);
            $this->getEntityManager()->persist($cert);
            return;
        }
        if ($type > $cert->getType()) 
        {
            $cert->setType($type);
            $cert->setDate($date);
        }
        if ($date > $cert->getDate()) $cert->setDate($date);

        return;
    }
    // Translate report certification to types
    /*
    protected $certs = array
    (
        // Coaches
        'Safe Haven AYSOs'                         => array(CertRepo::TYPE_SAFE_HAVEN  => CertRepo::TYPE_SAFE_HAVEN_AYSO,),
        'Safe Haven Coach'                         => array(CertRepo::TYPE_SAFE_HAVEN  => CertRepo::TYPE_SAFE_HAVEN_COACH,),
        'U-6 Coach'                                => array(CertRepo::TYPE_COACH_BADGE => CertRepo::TYPE_COACH_BADGE_U06,),
        'Z-Online U-6 Coach'                       => array(CertRepo::TYPE_COACH_BADGE => CertRepo::TYPE_COACH_BADGE_U06,),
        'U-8 Coach'                                => array(CertRepo::TYPE_COACH_BADGE => CertRepo::TYPE_COACH_BADGE_U08,),
        'Z-Online U-8 Coach'                       => array(CertRepo::TYPE_COACH_BADGE => CertRepo::TYPE_COACH_BADGE_U08,),
        'U-10 Coach'                               => array(CertRepo::TYPE_COACH_BADGE => CertRepo::TYPE_COACH_BADGE_U10,),
        'Z-Online U-10 Coach'                      => array(CertRepo::TYPE_COACH_BADGE => CertRepo::TYPE_COACH_BADGE_U10,),
        'U-12 Coach'                               => array(CertRepo::TYPE_COACH_BADGE => CertRepo::TYPE_COACH_BADGE_U12,),
        'Intermediate Coach'                       => array(CertRepo::TYPE_COACH_BADGE => CertRepo::TYPE_COACH_BADGE_INTERMEDIATE,),
        'Intermediate Coach - Cross Certification' => array(CertRepo::TYPE_COACH_BADGE => CertRepo::TYPE_COACH_BADGE_INTERMEDIATE,),
        'Advanced Coach'                           => array(CertRepo::TYPE_COACH_BADGE => CertRepo::TYPE_COACH_BADGE_ADVANCED,),
        'Advanced Coach - Cross Certification'     => array(CertRepo::TYPE_COACH_BADGE => CertRepo::TYPE_COACH_BADGE_ADVANCED,),
        'National Coach'                           => array(CertRepo::TYPE_COACH_BADGE => CertRepo::TYPE_COACH_BADGE_NATIONAL,),

        // Referees
        'Safe Haven Referee'          => array(CertRepo::TYPE_SAFE_HAVEN    => CertRepo::TYPE_SAFE_HAVEN_REFEREE,),
        'Z-Online Safe Haven Referee' => array(CertRepo::TYPE_SAFE_HAVEN    => CertRepo::TYPE_SAFE_HAVEN_REFEREE,),
        'U-8 Official'                => array(CertRepo::TYPE_REFEREE_BADGE => CertRepo::TYPE_REFEREE_BADGE_U08,),
        'Regional Referee'            => array(CertRepo::TYPE_REFEREE_BADGE => CertRepo::TYPE_REFEREE_BADGE_REGIONAL,),
        'Assistant Referee'           => array(CertRepo::TYPE_REFEREE_BADGE => CertRepo::TYPE_REFEREE_BADGE_ASSISTANT,),
        'Intermediate Referee'        => array(CertRepo::TYPE_REFEREE_BADGE => CertRepo::TYPE_REFEREE_BADGE_INTERMEDIATE,),
        'Advanced Referee'            => array(CertRepo::TYPE_REFEREE_BADGE => CertRepo::TYPE_REFEREE_BADGE_ADVANCED,),
        'National Referee'            => array(CertRepo::TYPE_REFEREE_BADGE => CertRepo::TYPE_REFEREE_BADGE_NATIONAL,),
        'National 2 Referee'          => array(CertRepo::TYPE_REFEREE_BADGE => CertRepo::TYPE_REFEREE_BADGE_NATIONAL_2,),

    'U-8 Official & Safe Haven Referee'      => array(
      CertRepo::TYPE_SAFE_HAVEN    => CertRepo::TYPE_SAFE_HAVEN_REFEREE,
      CertRepo::TYPE_REFEREE_BADGE => CertRepo::TYPE_REFEREE_BADGE_U08,
    ),
    'Assistant Referee & Safe Haven Referee' => array(
      CertRepo::TYPE_SAFE_HAVEN    => CertRepo::TYPE_SAFE_HAVEN_REFEREE,
      CertRepo::TYPE_REFEREE_BADGE => CertRepo::TYPE_REFEREE_BADGE_ASSISTANT,
    ),
    'Regional Referee & Safe Haven Referee'  => array(
      CertRepo::TYPE_SAFE_HAVEN    => CertRepo::TYPE_SAFE_HAVEN_REFEREE,
      CertRepo::TYPE_REFEREE_BADGE => CertRepo::TYPE_REFEREE_BADGE_REGIONAL,
    ),
    'Coach Administrator Training'         => array(),
    'VIP Buddy Training and Certification' => array(),
    'VIP Volunteer Training'               => array(),
    'Intermediate Referee Course'          => array(),
    'Advanced Referee Course'              => array(),
    'National Referee Course'              => array(),
    'Referee Administrator Training'       => array(),
    'Referee Assessor'                     => array(),
    'Referee Assessor Course'              => array(),
    'National Referee Assessor'            => array(),
    'National Referee Assessor Course'     => array(),
    'Referee Mentor'                       => array(),
    'B Coach'                              => array(),
    'National Coaching Course'             => array(),
    'VIP Coach/Referee'                    => array(),
    'Z-Online Regional Referee without Safe Haven' => array(),
  );*/
}
?>