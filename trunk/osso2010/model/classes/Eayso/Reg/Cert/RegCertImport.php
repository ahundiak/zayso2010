<?php
class Eayso_Reg_Cert_RegCertImport extends Eayso_Reg_Main_RegMainImport
{
  protected $readerClassName = 'Eayso_Reg_Cert_RegCertReader';

  protected function init()
  {
    parent::init();
    
    $this->certRepo = new Eayso_Reg_Cert_RegCertRepo();

    $this->directRegCert = new Eayso_Reg_Cert_RegCertDirect($this->context);

  }
  public function getResultMessage()
  {
    $file  = basename($this->innFileName);
    $count = $this->count;
    $class = get_class($this);

    $msg = sprintf("%s %s, Total: %u, Inserted: %u, Updated: %u",
      $class, $file,
      $count->total,$count->inserted,$count->updated);

    return $msg;
  }

  protected $personRegId = 0;

  public function processRowData($data)
  {
    // Validation
    $valid = TRUE;
    if (!$data['region'])   { $valid = FALSE; }
    if (!$data['reg_num'])  { $valid = FALSE; }
    if (!$data['certDesc']) { $valid = FALSE; }
    if (!$valid) { return; }

    $this->count->total++;

    // Make sure know about the cert
    $certDesc = $data['certDesc'];

    if (!isset($this->certs[$certDesc]))
    {
      $error = "{$data['reg_num']} {$data['lname']} '{$certDesc}'";
      echo $error . "\n";
      $this->errors[] = $error;
      die();
    }
    // Process the date
    $dates = explode('/',$data['datex']);
    if (count($dates) != 3) $datex = 'UNKNOWN';
    else
    {
      $year = (int)$dates[2];
      if ($year < 1900)
      {
        if ($year > 40) $year += 1900;
        else            $year += 2000;
      }
      $month = (int)$dates[0];
      $day   = (int)$dates[1];
      $datex = sprintf('%04u%02u%02u',$year,$month,$day);
    }
    
    // Process the year
    $data['reg_year'] = $this->processYear($data['reg_year']);
    
    // See if have a volunteer record for each aysoid
    $data['reg_type'] = $this->regTypeAyso;
    $this->processReg($data);

    // Process each cert
    foreach($this->certs[$certDesc] as $cat => $type)
    {
      $this->processCert($data,$cat,$type,$datex);
    }
    // die('cert');
  }
  protected function processCert($data,$certCat,$certType,$certDate)
  {
    $certDesc = $this->certRepo->getDesc($certType);
    $certYear = $data['reg_year'];

    // Gather the info
    $datax = array(
      'reg_type' => $data['reg_type'],
      'reg_num'  => $data['reg_num'],
      'catx'     => $certCat,
      'typex'    => $certType,
      'datex'    => $certDate,
      'yearx'    => $certYear,
    );
    // See if have a cat record
    $search = array(
      'reg_type' => $data['reg_type'],
      'reg_num'  => $data['reg_num'],
      'catx'     => $certCat,
    );
    $result = $this->directRegCert->fetchRow($search);
    $row = $result->row;
    if (!$row)
    {
      $this->directRegCert->insert($datax);
      $this->count->inserted++;
      return;
    }
    $datax['id'] = $row['id'];

    // Check for higher type
    if ($datax['typex'] > $row['typex'])
    {
      $this->directRegCert->update($datax);
      $this->count->updated++;
      return;
    }
    // Update the membership year if it is newer
    if ($datax['yearx'] > $row['yearx'])
    {
      $changes['id']    = $datax['id'];
      $changes['yearx'] = $datax['yearx'];

      $this->directRegCert->update($changes);
      $this->count->updated++;
    }
    // Ignore lower types
    if ($datax['typex'] < $row['typex']) return;

    // TODOx write test for UNKNOWN date comparisons
    // Oldest date or newest date?
    if ($datax['datex'] != 'UNKNOWN')
    {
      if (($datax['datex'] < $row['datex']) || ($row['datex'] == 'UNKNOWN'))
      {
        // Cerad_Debug::dump($datax); Cerad_Debug::dump($row); die();
        // echo "Datex {$datax['datex']} < {$row['datex']}\n";

        $changes['id']    = $datax['id'];
        $changes['datex'] = $datax['datex'];
        $this->directRegCert->update($changes);
        $this->count->updated++;
      }
    }
  }
   // Should get moved to classes
  protected $certs = array
  (
    // Coaches
    'Safe Haven Coach' => array(
      Eayso_VolCertRepo::TYPE_SAFE_HAVEN => Eayso_VolCertRepo::TYPE_SAFE_HAVEN_COACH,
    ),
    'Z-Online Safe Haven Coach' => array(
      Eayso_VolCertRepo::TYPE_SAFE_HAVEN => Eayso_VolCertRepo::TYPE_SAFE_HAVEN_COACH,
    ),
    'Z-Online AYSOs Safe Haven' => array(
      Eayso_VolCertRepo::TYPE_SAFE_HAVEN => Eayso_VolCertRepo::TYPE_SAFE_HAVEN_AYSO,
    ),
    'U-6 Coach'         => array(
      Eayso_VolCertRepo::TYPE_COACH_BADGE => Eayso_VolCertRepo::TYPE_COACH_BADGE_U06,
    ),
    'Z-Online U-6 Coach' => array(
      Eayso_VolCertRepo::TYPE_COACH_BADGE => Eayso_VolCertRepo::TYPE_COACH_BADGE_U06,
    ),
    'U-8 Coach'         => array(
      Eayso_VolCertRepo::TYPE_COACH_BADGE => Eayso_VolCertRepo::TYPE_COACH_BADGE_U08,
    ),
    'Z-Online U-8 Coach'         => array(
      Eayso_VolCertRepo::TYPE_COACH_BADGE => Eayso_VolCertRepo::TYPE_COACH_BADGE_U08,
    ),
      'U-10 Coach'         => array(
      Eayso_VolCertRepo::TYPE_COACH_BADGE => Eayso_VolCertRepo::TYPE_COACH_BADGE_U10,
    ),
    'Z-Online U-10 Coach'         => array(
      Eayso_VolCertRepo::TYPE_COACH_BADGE => Eayso_VolCertRepo::TYPE_COACH_BADGE_U10,
    ),
    'U-12 Coach'         => array(
      Eayso_VolCertRepo::TYPE_COACH_BADGE => Eayso_VolCertRepo::TYPE_COACH_BADGE_U12,
    ),
    'Intermediate Coach'         => array(
      Eayso_VolCertRepo::TYPE_COACH_BADGE => Eayso_VolCertRepo::TYPE_COACH_BADGE_INTERMEDIATE,
    ),
    'Intermediate Coach - Cross Certification' => array(
      Eayso_VolCertRepo::TYPE_COACH_BADGE => Eayso_VolCertRepo::TYPE_COACH_BADGE_INTERMEDIATE,
    ),
    'Advanced Coach' => array(
      Eayso_VolCertRepo::TYPE_COACH_BADGE => Eayso_VolCertRepo::TYPE_COACH_BADGE_ADVANCED,
    ),
    'Advanced Coach - Cross Certification' => array(
      Eayso_VolCertRepo::TYPE_COACH_BADGE => Eayso_VolCertRepo::TYPE_COACH_BADGE_ADVANCED,
    ),
    'National Coach' => array(
      Eayso_VolCertRepo::TYPE_COACH_BADGE => Eayso_VolCertRepo::TYPE_COACH_BADGE_NATIONAL,
    ),

    // Referees
    'Safe Haven Referee' => array(
      Eayso_VolCertRepo::TYPE_SAFE_HAVEN => Eayso_VolCertRepo::TYPE_SAFE_HAVEN_REFEREE,
    ),
    'Z-Online Safe Haven Referee' => array(
      Eayso_VolCertRepo::TYPE_SAFE_HAVEN => Eayso_VolCertRepo::TYPE_SAFE_HAVEN_REFEREE,
    ),
    'U-8 Official'         => array(
      Eayso_VolCertRepo::TYPE_REFEREE_BADGE => Eayso_VolCertRepo::TYPE_REFEREE_BADGE_U08,
    ),
    'Regional Referee'     => array(
      Eayso_VolCertRepo::TYPE_REFEREE_BADGE => Eayso_VolCertRepo::TYPE_REFEREE_BADGE_REGIONAL,
    ),
    'Assistant Referee'    => array(
      Eayso_VolCertRepo::TYPE_REFEREE_BADGE => Eayso_VolCertRepo::TYPE_REFEREE_BADGE_ASSISTANT,
    ),
    'Intermediate Referee' => array(
      Eayso_VolCertRepo::TYPE_REFEREE_BADGE => Eayso_VolCertRepo::TYPE_REFEREE_BADGE_INTERMEDIATE,
    ),
    'Advanced Referee'     => array(
      Eayso_VolCertRepo::TYPE_REFEREE_BADGE => Eayso_VolCertRepo::TYPE_REFEREE_BADGE_ADVANCED,
    ),
    'National Referee'     => array(
      Eayso_VolCertRepo::TYPE_REFEREE_BADGE => Eayso_VolCertRepo::TYPE_REFEREE_BADGE_NATIONAL,
    ),
    'National 2 Referee'   => array(
      Eayso_VolCertRepo::TYPE_REFEREE_BADGE => Eayso_VolCertRepo::TYPE_REFEREE_BADGE_NATIONAL_2,
    ),
    'U-8 Official & Safe Haven Referee'      => array(
      Eayso_VolCertRepo::TYPE_SAFE_HAVEN    => Eayso_VolCertRepo::TYPE_SAFE_HAVEN_REFEREE,
      Eayso_VolCertRepo::TYPE_REFEREE_BADGE => Eayso_VolCertRepo::TYPE_REFEREE_BADGE_U08,
    ),
    'U-8 Official &amp; Safe Haven Referee'      => array(
      Eayso_VolCertRepo::TYPE_SAFE_HAVEN    => Eayso_VolCertRepo::TYPE_SAFE_HAVEN_REFEREE,
      Eayso_VolCertRepo::TYPE_REFEREE_BADGE => Eayso_VolCertRepo::TYPE_REFEREE_BADGE_U08,
    ),
    'Assistant Referee & Safe Haven Referee' => array(
      Eayso_VolCertRepo::TYPE_SAFE_HAVEN    => Eayso_VolCertRepo::TYPE_SAFE_HAVEN_REFEREE,
      Eayso_VolCertRepo::TYPE_REFEREE_BADGE => Eayso_VolCertRepo::TYPE_REFEREE_BADGE_ASSISTANT,
    ),
    'Assistant Referee &amp; Safe Haven Referee' => array(
      Eayso_VolCertRepo::TYPE_SAFE_HAVEN    => Eayso_VolCertRepo::TYPE_SAFE_HAVEN_REFEREE,
      Eayso_VolCertRepo::TYPE_REFEREE_BADGE => Eayso_VolCertRepo::TYPE_REFEREE_BADGE_ASSISTANT,
    ),
    'Regional Referee & Safe Haven Referee'  => array(
      Eayso_VolCertRepo::TYPE_SAFE_HAVEN    => Eayso_VolCertRepo::TYPE_SAFE_HAVEN_REFEREE,
      Eayso_VolCertRepo::TYPE_REFEREE_BADGE => Eayso_VolCertRepo::TYPE_REFEREE_BADGE_REGIONAL,
    ),
    'Regional Referee &amp; Safe Haven Referee'  => array(
      Eayso_VolCertRepo::TYPE_SAFE_HAVEN    => Eayso_VolCertRepo::TYPE_SAFE_HAVEN_REFEREE,
      Eayso_VolCertRepo::TYPE_REFEREE_BADGE => Eayso_VolCertRepo::TYPE_REFEREE_BADGE_REGIONAL,
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