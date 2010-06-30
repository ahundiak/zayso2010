<?php
class ImportEaysoVolCerts extends Cerad_Reader_CSV
{
  public $countInsert = 0;
  public $countUpdate = 0;

// Region	FirstName	LastName	Gender	AYSO ID
// Email	CertificationDesc	Certification Date

  protected $map = array
  (
    'Region'    => 'region',
    'AYSO ID'   => 'aysoid',
    'LastName'  => 'lname',
    'FirstName' => 'fname',
    'Gender'    => 'gender',
    'Email'     => 'email',

    'CertificationDesc'  => 'certDesc',
    'Certification Date' => 'certDate',
  );
    
  protected $certs = array
  (
    // Coaches
    'Safe Haven Coach' => array(
      Eayso_VolCertRepo::TYPE_SAFE_HAVEN => Eayso_VolCertRepo::TYPE_SAFE_HAVEN_COACH,
    ),
    'Z-Online Safe Haven Coach' => array(
      Eayso_VolCertRepo::TYPE_SAFE_HAVEN => Eayso_VolCertRepo::TYPE_SAFE_HAVEN_COACH,
    ),
    'U-6 Coach'         => array(
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
    'Assistant Referee & Safe Haven Referee' => array(
      Eayso_VolCertRepo::TYPE_SAFE_HAVEN    => Eayso_VolCertRepo::TYPE_SAFE_HAVEN_REFEREE,
      Eayso_VolCertRepo::TYPE_REFEREE_BADGE => Eayso_VolCertRepo::TYPE_REFEREE_BADGE_ASSISTANT,
    ),
    'Regional Referee & Safe Haven Referee'  => array(
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
  );
  protected $certRepo = NULL;
  protected function init()
  {
    $this->certRepo = new Eayso_VolCertRepo();
  }
  public function processRowData($datax)
  {
    // Convert to object
    $data = new Cerad_Data();
    foreach($datax as $name => $value) { $data->$name = $value; }

    // Make sure know about the cert
    if (!isset($this->certs[$data->certDesc]))
    {
      echo "{$data->aysoid} {$data->lname} '{$data->certDesc}'\n";
      die();
    }
    // Process the date
    $dates = explode('/',$data->certDate);
    if (count($dates) != 3) $certDate = ' UNKNOWN';
    else
    {
      $year = (int)$dates[2];
      if ($year > 40) $year += 1900;
      else            $year += 2000;
      $certDate = $year . $dates[0] . $dates[1];
    }
    // Process each cert
    foreach($this->certs[$data->certDesc] as $cat => $type)
    {
      $this->processCert($data,$cat,$type,$certDate);
    }
  }
  public function processCert($data,$certCat,$certType,$certDate)
  {
    $db = $this->context->getDb();

    $certDesc = $this->certRepo->getDesc($certType);

    // Look for existing record
    $search['aysoid'] = $data->aysoid;
    $search['cat']    = $certCat;
    $sql = 'SELECT * FROM eayso_vol_certs WHERE aysoid = :aysoid AND cert_cat = :cat';
    $row = $db->fetchRow($sql,$search);
    if ($row === FALSE)
    {
      $datax = array
      (
        'aysoid' => $data->aysoid,
      //'fname'  => $data->fname,
      //'lname'  => $data->lname,
      //'region' => (int)$data->region,

        'cert_cat'  => $certCat,
        'cert_type' => $certType,
        'cert_desc' => $certDesc,
        'cert_date' => $certDate,
      );
      // echo "Add Row\n";
      // echo "{$data->aysoid} {$data->lname} {$data->region} {$certCat} {$certType} '{$certDesc}' '{$certDate}\n";
      $db->insert('eayso_vol_certs','eayso_vol_cert_id',$datax);
      return;
    }
    // Cerad_Debug::dump($row); die();

    // Have a higher type?
    if ($certType > $row['cert_type'])
    {
      $datax = array
      (
        'eayso_vol_cert_id' => $row['eayso_vol_cert_id'],
        'cert_type' => $certType,
        'cert_desc' => $certDesc,
        'cert_date' => $certDate,
      );
      $db->update('eayso_vol_certs','eayso_vol_cert_id',$datax);
      return;
    }
    if ($certType != $row['cert_type']) return;
        
    // Older data
    if ($certDate == $row['cert_date']) return;
    if ($certDate == ' UNKNOWN') return;
    if (($certDate < $row['cert_date']) || ($row['cert_date'] == ' UNKNOWN'))
    {
      $datax = array(
        'eayso_vol_cert_id' => $row['eayso_vol_cert_id'],
        'cert_date'         => $certDate,
      );
      $db->update('eayso_vol_certs','eayso_vol_cert_id',$datax);
      return;
    }
  }
}
?>