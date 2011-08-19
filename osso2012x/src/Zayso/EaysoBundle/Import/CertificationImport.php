<?php
namespace Zayso\EaysoBundle\Import;

use Zayso\EaysoBundle\Entity\Volunteer;
use Zayso\EaysoBundle\Entity\Certification;
use Zayso\EaysoBundle\Repository\CertificationRepository as CertRepo;

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

    protected function init()
    {
        parent::init();
        $em = $this->getEntityManager();
        $this->volRepo  = $em->getRepository('EaysoBundle:Volunteer');
        $this->certRepo = $em->getRepository('EaysoBundle:Certification');
    }
    public function processVolunteer($item)
    {
        $aysoid = 'AYSOV' . $item->aysoid;
        
        $vol = $this->volRepo->find($aysoid);
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
    public function processItem($item)
    {
        if (!$item->aysoid)   return;
        if (!$item->region)   return;
        if (!$item->certDesc) return;

        $this->total++;

        // Make sure know about the cert
        $certDesc = $item->certDesc;

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
        $date = $item->certDate;
        if ($date)
        {
            $date = substr($date,6,4) . substr($date,0,2) . substr($date,3,2);
        }
    
        // Process each cert
        foreach($this->certs[$item->certDesc] as $cat => $type)
        {
            $this->processCert($vol,$cat,$type,$date);
        }
        $this->getEntityManager()->flush();
    }
    protected function processCert($vol,$cat,$type,$date)
    {
        $cert = $vol->getCertification($cat);
        if (!$cert)
        {
            $cert = new Certification();
            $cert->setVolunteer($vol);
            $cert->setCat($cat);
            $cert->setType($type);
            $cert->setDate($date);
            $this->getEntityManager()->persist($cert);
            return;
        }
        if ($type > $cert->getType()) $cert->setType($type);
        if ($date > $cert->getDate()) $cert->setDate($date);

        return;
    }
    // Translate report certification to types
    protected $certs = array
    (
        // Coaches
        'Safe Haven Coach'                         => array(CertRepo::TYPE_SAFE_HAVEN  => CertRepo::TYPE_SAFE_HAVEN_COACH,),
        'Z-Online Safe Haven Coach'                => array(CertRepo::TYPE_SAFE_HAVEN  => CertRepo::TYPE_SAFE_HAVEN_COACH,),
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
  );
}
?>