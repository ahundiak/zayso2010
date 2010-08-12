<?php
class Eayso_Reg_Main_RegMainImport extends Cerad_Import
{
  protected $readerClassName = 'Eayso_Reg_Main_RegMainReader';
  protected $regions = array();

  protected $regTypeOsso = 101;
  protected $regTypeAyso = 102;

  protected function init()
  {
    parent::init();
    $this->db = $this->context->dbEayso;

    $this->directOrg     = new Eayso_Org_OrgDirect         ($this->context);
    $this->directRegOrg  = new Eayso_Reg_Org_RegOrgDirect  ($this->context);
    $this->directRegMain = new Eayso_Reg_Main_RegMainDirect($this->context);
    $this->directRegProp = new Eayso_Reg_Prop_RegPropDirect($this->context);
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
  protected function processRegMain($data)
  {
    // Pull out fields of interest
    $fields = array(
      'reg_type','reg_num','reg_year',
      'fname','lname','nname','mname','sname','dob','sex',
    );
    $datax = array();
    foreach($fields as $key)
    {
      if (isset($data[$key])) $datax[$key] = $data[$key];
    }

    // Look it up
    $search = array('reg_type' => $datax['reg_type'],'reg_num' => $datax['reg_num']);
    $result = $this->directRegMain->fetchRow($search);
    $row = $result->row;
    if (!$row)
    {
      $this->directRegMain->insert($datax);
      $this->count->inserted++;
      return;
    }
    // Ignore older records
    if ($datax['reg_year'] < $row['reg_year']) return;

    // Look for updates
    $changes = array();
    foreach($fields as $key)
    {
      if (isset($datax[$key]))
      {
        if ($row[$key] != $datax[$key])
        {
          $changes[$key] = $datax[$key];
        //printf("### Changes %s '%s' '%s'\n",$key,$row[$key],$datax[$key]);
        }
      }
    }
    if (count($changes) < 1) return;

    $changes['id'] = $row['id'];
    $this->directRegMain->update($changes);
    $this->count->updated++;
    return;
  }
  public function processRegOrg($data)
  {
    $region = $data['region'];
    if (!$region) return;

    if (!isset($this->regions[$region]))
    {
      $result = $this->directOrg->getOrgForKey($region);
      if (!$result->row)
      {
        echo "*** Missing region $region\n";
        $this->regions[$region] = 0;
        return;
      }
      $this->regions[$region] = $result->row['id'];
    }
    $orgId = $this->regions[$region];
    if (!$orgId) return;

    $datax = array(
      'reg_type' => $data['reg_type'],
      'reg_num'  => $data['reg_num'],
      'org_id'   => $orgId
    );
    $this->directRegOrg->insert($datax);

  }
  protected function processRegProp($data)
  {
    $propTypes = array(
      'phone_home' => 11,
      'phone_work' => 12,
      'phone_cell' => 13,
      'email'      => 21,
    );
    $datax = array(
      'reg_type' => $data['reg_type'],
      'reg_num'  => $data['reg_num'],
    );
    foreach($propTypes as $name => $typex)
    {
      if (isset($data[$name]) && $data[$name])
      {
        $valuex = $data[$name];

        switch($typex)
        {
          case 11: case 12: case 13:
              $valuex = preg_replace('/\D/','',$valuex);
            break;
        }
        $datax['typex']  = $typex;
        $datax['valuex'] = $valuex;
        $this->directRegProp->insert($datax); // TODOx DUP key should update valuex
      }
    }
  }
  protected function processReg($data)
  {
    // Different chunks of data
    $this->processRegMain($data);
    $this->processRegOrg ($data);
    $this->processRegProp($data);
  }
  public function processRowData($data)
  {   
    // Validation
    $valid = TRUE;
    if (!$data['region'])   { $valid = FALSE; }
    if (!$data['reg_num'])  { $valid = FALSE; }
    if (!$data['lname'])    { $valid = FALSE; }
    if (!$valid) { return; }

    $this->count->total++;

    // AYSO Vol
    $data['reg_type'] = $this->regTypeAyso;;

    // Clean up dob
    $dobs = explode('/',$data['dob']);
    if (count($dobs) == 3)
    {
      if (strlen($dobs[0]) == 1) $month = '0' . $dobs[0];
      else                       $month =       $dobs[0];
      if (strlen($dobs[1]) == 1) $day   = '0' . $dobs[1];
      else                       $day   =       $dobs[1];

      $data['dob'] = $dobs[2] . $month . $day;
    }
        
    // Clean up phone work extension
    $phoneWorkExt = trim($data['phone_work_ext']);
    if ($phoneWorkExt)
    {
      $data['phone_work'] = $data['phone_work'] . 'x' . $phoneWorkExt;
    }
    unset($data['phone_work_ext']);

    // Clean up registration year
    $data['reg_year'] = $this->processYear($data['reg_year']);

    // Different chunks of data
    $this->processReg($data);
  }
  protected function processYear($year)
  {
    // Clean up registration year
    $yearx = (int)(substr($year,-4));
    if (($yearx < 1990) || ($yearx > 2020)) die("Year: '$year'");
    return $yearx;
  }
}
?>
