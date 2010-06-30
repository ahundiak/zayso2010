<?php
class Eayso_Import_VolCert extends Eayso_Import_Vol
{
  protected $readerClassName = 'Eayso_Import_VolCertReader';

  public $countInsertVol = 0;

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
    parent::init();
    $this->certRepo = new Eayso_VolCertRepo();
  }
  public function getResultMessage()
  {
    $file = basename($this->innFileName);
    $msg = sprintf("Eayso Vol Cert Import %s, Total: %u, Inserted: %u, Updated: %u, Vol Insert: %u",
      $file,
      $this->countTotal,$this->countInsert,$this->countUpdate,$this->countInsertVol);
    return $msg;
  }

  protected $personRegId = 0;

  public function processRowData($data)
  {
    // Validation
    $valid = TRUE;
    if (!$data['region'])         { $valid = FALSE; }
    if (!$data['person_reg_num']) { $valid = FALSE; }
    if (!$data['certDesc'])       { $valid = FALSE; }
    if (!$valid) { return; }

    $this->countTotal++;

    // Make sure know about the cert
    $certDesc = $data['certDesc'];

    if (!isset($this->certs[$certDesc]))
    {
      $error = "{$data['person_reg_num']} {$data['lname']} '{$certDesc}'";
      echo $error . "\n";
      $this->errors[] = $error;
      die();
    }
    // Process the date
    $dates = explode('/',$data['certDate']);
    if (count($dates) != 3) $certDate = ' UNKNOWN';
    else
    {
      $year = (int)$dates[2];
      if ($year > 40) $year += 1900;
      else            $year += 2000;
      $certDate = $year . $dates[0] . $dates[1];
    }
    // Process the year
    $data['person_reg_year'] = $this->processYear($data['person_reg_year']);

    // See if have a volunteer record for each aysoid
    $this->processCertVol($data);

    // Process each cert
    foreach($this->certs[$certDesc] as $cat => $type)
    {
      $this->processCert($data,$cat,$type,$certDate);
    }
  }
  protected function processCert($data,$certCat,$certType,$certDate)
  {
    $db = $this->db;
    
    $certDesc = $this->certRepo->getDesc($certType);

    // Look for existing record
    $search['person_reg_id'] = $this->personRegId;
    $search['cert_cat']      = $certCat;
    $sql = 'SELECT * FROM person_reg_cert WHERE person_reg_id = :person_reg_id AND cert_cat = :cert_cat';
    $row = $db->fetchRow($sql,$search);
    if ($row === FALSE)
    {
      $datax = array
      (
        'person_reg_id' => $this->personRegId,

        // Just for error checking
        'person_reg_num' => $data['person_reg_num'],
        'fname'          => $data['fname'],
        'lname'          => $data['lname'],

        'cert_cat'   => $certCat,
        'cert_type'  => $certType,
        'cert_desc'  => $certDesc,
        'cert_date'  => $certDate,

        'ts_created' => $this->ts,
        'ts_updated' => $this->ts,
      );
      $db->insert('person_reg_cert','id',$datax);
      $this->countInsert++;
      return;
    }
    // Cerad_Debug::dump($row); die();

    // Have a higher type?
    if ($certType > $row['cert_type'])
    {
      $datax = array
      (
        'id'         => $row['id'],
        'cert_type'  => $certType,
        'cert_desc'  => $certDesc, // Really should not need this
        'cert_date'  => $certDate,
        'ts_updated' => $this->ts,
      );
      $db->update('person_reg_cert','id',$datax);
      $this->countUpdate++;
      return;
    }
    if ($certType != $row['cert_type']) return;
        
    // Older data
    if ($certDate == $row['cert_date']) return;
    if ($certDate == ' UNKNOWN') return;
    if (($certDate < $row['cert_date']) || ($row['cert_date'] == ' UNKNOWN'))
    {
      $datax = array(
        'id'         => $row['id'],
        'cert_date'  => $certDate,
        'ts_updated' => $this->ts,
      );
      $db->update('person_reg_cert','id',$datax);
      $this->countUpdate++;
      return;
    }
  }
  protected function processCertVol($data)
  {
    $db = $this->db;

    // Look for existing record
    $search['type']   = 2;
    $search['aysoid'] = $data['person_reg_num'];
    $sql = 'SELECT id FROM person_reg WHERE person_reg_type_id = :type AND person_reg_num = :aysoid;';
    $datax = $db->fetchRow($sql,$search);

    if ($datax !== FALSE)
    {
      // Make sure the regions are updated
      $this->personRegId = $datax['id'];
      $this->processVolRegion($datax['id'],$data['region']);
      return;
    }

    // Insert vol record based on what we have
    $datax = array
    (
      'person_id'          => 0,
      'person_reg_num'     => $data['person_reg_num'],
      'person_reg_type_id' => 2,
      'person_reg_year'    => $data['person_reg_year'],

      'fname'    => $data['fname'],
      'lname'    => $data['lname'],

      'gender'   => $data['gender'],
      'email'    => $data['email'],

      'ts_created' => $this->ts,
      'ts_updated' => $this->ts,
    );
    $phone = $data['phone_home'];
    $phone = str_replace(array('-',' ','(',')'),array('','','',''),$phone);
    $datax['phone_home'] = $phone;

    $phone = $data['phone_work'];
    $phone = str_replace(array('-',' ','(',')'),array('','','',''),$phone);
    $datax['phone_work'] = $phone;

    // Trap dup exception just to be sure
    $db->insert('person_reg','id',$datax);
    $this->countInsertVol++;

    // Add any regions
    $this->personRegId = $id = $db->lastInsertId();
    $this->processVolRegion($id,$data['region']);

    return;
    
    Cerad_Debug::dump($data);
    Cerad_Debug::dump($datax);
    die();
  }
}
?>